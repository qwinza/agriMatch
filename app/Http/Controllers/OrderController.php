<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Produk::findOrFail($productId);

        // Cek stok
        if ($request->quantity > $product->stock) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        // Cek apakah produk masih aktif
        if (!$product->is_active) {
            return redirect()->back()->with('error', 'Produk tidak tersedia untuk dipesan.');
        }

        $totalPrice = $product->price * $request->quantity;

        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $totalPrice,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'status' => 'pending',
                'order_code' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
            ]);

            // Reduce product stock
            $product->decrement('stock', $request->quantity);

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan.');
        }
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'petani') {
            // Untuk petani: tampilkan pesanan untuk produk mereka
            $orders = Order::whereHas('product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['product', 'user'])->latest()->paginate(10);

            return view('farmers.orders.index', compact('orders'));
        } else {
            // Untuk pembeli: tampilkan pesanan mereka - GUNAKAN PAGINATE
            $orders = Order::where('user_id', $user->id)
                ->with(['product.user'])
                ->latest()
                ->paginate(10); // ← PERBAIKI: ganti get() dengan paginate()

            return view('farmers.orders.index', compact('orders'));
        }
    }

    public function show($id)
    {
        $order = Order::with(['product', 'user'])->findOrFail($id);

        // Authorization check
        $user = Auth::user();
        if ($user->role === 'pembeli' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === 'petani' && $order->product->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('farmers.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $user = Auth::user();

        // Hanya petani pemilik produk yang bisa update status
        if ($user->role !== 'petani' || $order->product->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->update([
            'status' => $request->status,
            'status_updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        // Hanya pembeli yang membuat pesanan yang bisa cancel
        if ($user->role !== 'pembeli' || $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa cancel jika status masih pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        // Kembalikan stok
        $order->product->increment('stock', $order->quantity);

        $order->update([
            'status' => 'cancelled',
            'status_updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}