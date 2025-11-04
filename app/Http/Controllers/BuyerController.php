<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Order;
use App\Models\Cart;
use App\Services\MidtransServices;
use Carbon\Carbon;

class BuyerController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransServices $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    // Marketplace - semua produk aktif
    public function marketplace(Request $request)
    {
        $query = Produk::with('user')
            ->where('status', 'active')
            ->where('stok', '>', 0);

        // Filtering
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('harga', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Produk::where('status', 'active')->distinct()->pluck('kategori');

        return view('buyers.marketplace', compact('products', 'categories'));
    }

    // Detail produk
    public function productDetail($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Produk::with('user')
                ->where('status', 'active')
                ->findOrFail($id);

            // Related products
            $relatedProducts = Produk::with('user')
                ->where('kategori', $product->kategori)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->where('stok', '>', 0)
                ->inRandomOrder()
                ->limit(4)
                ->get();

            return view('buyers.products.show', compact('product', 'relatedProducts', 'encryptedId'));

        } catch (\Exception $e) {
            abort(404, 'Produk tidak ditemukan.');
        }
    }

    // Cart System
    public function cart()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->harga;
        });

        return view('buyers.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produks,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $product = Produk::findOrFail($request->product_id);

            // Check stock
            if ($product->stok < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stok
                ], 400);
            }

            // Check if product already in cart
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($existingCart) {
                $existingCart->increment('quantity', $request->quantity);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $request->quantity
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => Cart::where('user_id', Auth::id())->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ke keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    // Checkout Process
    public function checkout(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('buyer.cart')->with('error', 'Keranjang belanja kosong.');
            }

            // Validate stock and calculate total
            $totalAmount = 0;
            $orderItems = [];

            foreach ($cartItems as $cartItem) {
                if ($cartItem->product->stok < $cartItem->quantity) {
                    throw new \Exception("Stok {$cartItem->product->nama_produk} tidak mencukupi.");
                }

                $itemTotal = $cartItem->quantity * $cartItem->product->harga;
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->harga,
                    'total_price' => $itemTotal
                ];
            }

            // Create order
            $order = Order::create([
                'order_code' => 'ORD-' . time() . '-' . rand(1000, 9999),
                'user_id' => $user->id,
                'total_price' => $totalAmount,
                'status' => 'pending',
                'shipping_address' => $user->alamat, // Pastikan field alamat ada di users table
                'notes' => $request->notes
            ]);

            // Create order items (jika menggunakan pivot table)
            foreach ($orderItems as $item) {
                $order->products()->attach($item['product_id'], [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['total_price']
                ]);
            }

            // Create Midtrans transaction
            $paymentData = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone, // Pastikan field phone ada
                ],
                'item_details' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->product_id,
                        'price' => $item->product->harga,
                        'quantity' => $item->quantity,
                        'name' => $item->product->nama_produk,
                    ];
                })->toArray(),
            ];

            $snapToken = $this->midtransService->createTransaction($paymentData);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return view('buyers.checkout', compact('order', 'snapToken'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('buyer.cart')->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    // Midtrans Payment Notification (Webhook)
    public function paymentNotification(Request $request)
    {
        try {
            $notification = $this->midtransService->handleNotification($request);

            $order = Order::where('order_code', $notification->order_id)->firstOrFail();

            if (
                $notification->transaction_status == 'capture' ||
                $notification->transaction_status == 'settlement'
            ) {

                $order->update(['status' => 'completed']);

                // Update product stock
                foreach ($order->products as $product) {
                    $product->decrement('stok', $product->pivot->quantity);
                }

            } elseif (
                $notification->transaction_status == 'deny' ||
                $notification->transaction_status == 'cancel' ||
                $notification->transaction_status == 'expire'
            ) {

                $order->update(['status' => 'cancelled']);

            } elseif ($notification->transaction_status == 'pending') {
                $order->update(['status' => 'pending']);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            \Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    // Payment Finish Page
    public function paymentFinish(Request $request)
    {
        $orderCode = $request->order_id;
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return redirect()->route('buyer.marketplace')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('buyers.payment.finish', compact('order'));
    }

    public function paymentUnfinish(Request $request)
    {
        $orderCode = $request->order_id;
        return view('buyers.payment.unfinish', compact('orderCode'));
    }

    public function paymentError(Request $request)
    {
        $orderCode = $request->order_id;
        return view('buyers.payment.error', compact('orderCode'));
    }
}