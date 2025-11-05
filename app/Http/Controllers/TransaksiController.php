<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;
use App\Helpers\NgrokHelper;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    public function create($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Produk::findOrFail($id);

            // Pass client key ke view untuk Midtrans
            $clientKey = config('midtrans.client_key');

            return view('transactions.create', compact('product', 'encryptedId', 'clientKey'));
        } catch (\Exception $e) {
            abort(404, 'Produk tidak ditemukan.');
        }
    }

    public function pay(Request $request)
    {
        $request->validate([
            'encryptedId' => 'required',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
            'payment_mode' => 'nullable|string|in:auto_success,real'
        ]);

        DB::beginTransaction();

        try {
            $id = Crypt::decrypt($request->encryptedId);
            $product = Produk::findOrFail($id);

            // Cek stok
            if ($request->quantity > $product->stok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stok
                ], 400);
            }

            $totalAmount = $product->harga * $request->quantity;

            // Buat kode yang UNIK untuk setiap transaksi
            $timestamp = time();
            $transactionCode = 'TRX-' . $timestamp . '-' . strtoupper(substr(uniqid(), -6));
            $orderCode = 'ORD-' . $timestamp . '-' . strtoupper(substr(uniqid(), -6));

            // 1. BUAT ORDER - STATUS PENDING
            $order = Order::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $totalAmount,
                'shipping_address' => $request->shipping_address,
                'notes' => "Penerima: " . $request->recipient_name . " | Telp: " . $request->phone . " | " . ($request->notes ?? ''),
                'status' => 'pending', // TETAP PENDING
                'order_code' => $orderCode,
                'payment_status' => 'pending', // TETAP PENDING
                'midtrans_order_id' => $transactionCode,
                'midtrans_gross_amount' => $totalAmount,
            ]);

            // 2. BUAT TRANSAKSI - STATUS PENDING
            $transaction = Transaksi::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'order_id' => $order->id,
                'quantity' => $request->quantity,
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'gross_amount' => $totalAmount,
                'status' => 'pending', // TETAP PENDING
                'transaction_code' => $transactionCode,
            ]);

            // 3. KURANGI STOK
            $product->decrement('stok', $request->quantity);

            // TENTUKAN PAYMENT MODE
            $paymentMode = $request->payment_mode ?? 'real';

            \Log::info('Payment Process Started', [
                'payment_mode' => $paymentMode,
                'order_id' => $order->id,
                'transaction_code' => $transactionCode,
                'amount' => $totalAmount
            ]);

            if ($paymentMode === 'auto_success') {
                // AUTO SUCCESS MODE
                \Log::info('🔧 AUTO SUCCESS MODE - Processing immediate payment');

                try {
                    $this->processAutoSuccess($order, $transaction, $transactionCode);
                    DB::commit();

                    \Log::info('🎉 AUTO SUCCESS - Payment completed successfully');

                    return response()->json([
                        'success' => true,
                        'auto_success' => true,
                        'order_id' => $order->id,
                        'transaction_code' => $transactionCode,
                        'redirect_url' => route('transactions.finish') . '?order_id=' . $transactionCode . '&transaction_status=settlement&status_code=200'
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    $product->increment('stok', $request->quantity);

                    \Log::error('💥 Auto Success Error: ' . $e->getMessage());

                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memproses auto success: ' . $e->getMessage()
                    ], 500);
                }

            } else {
                // REAL PAYMENT MODE - Menggunakan Midtrans
                \Log::info('🌐 REAL PAYMENT MODE - Using Midtrans');

                try {
                    // SETUP MIDTRANS
                    Config::$serverKey = config('midtrans.server_key');
                    Config::$isProduction = config('midtrans.is_production');
                    Config::$isSanitized = true;
                    Config::$is3ds = true;

                    $baseUrl = config('app.url', 'https://0073f355d7ae.ngrok-free.app');

                    \Log::info('🔗 Midtrans Configuration', [
                        'is_production' => Config::$isProduction,
                        'base_url' => $baseUrl
                    ]);

                    $midtransTransaction = [
                        'transaction_details' => [
                            'order_id' => $transactionCode,
                            'gross_amount' => (int) $totalAmount,
                        ],
                        'customer_details' => [
                            'first_name' => $request->recipient_name,
                            'phone' => $request->phone,
                            'shipping_address' => [
                                'first_name' => $request->recipient_name,
                                'phone' => $request->phone,
                                'address' => $request->shipping_address,
                            ],
                        ],
                        'item_details' => [
                            [
                                'id' => (string) $product->id,
                                'price' => (int) $product->harga,
                                'quantity' => (int) $request->quantity,
                                'name' => $product->nama_produk,
                            ]
                        ],
                        'callbacks' => [
                            'finish' => $baseUrl . '/transactions/finish',
                        ]
                    ];

                    \Log::info('📦 Sending request to Midtrans');

                    // Generate Snap Token
                    $snapToken = Snap::getSnapToken($midtransTransaction);

                    \Log::info('✅ Snap token generated successfully', [
                        'token_length' => strlen($snapToken),
                        'transaction_code' => $transactionCode
                    ]);

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'snapToken' => $snapToken,
                        'order_id' => $order->id,
                        'transaction_code' => $transactionCode,
                        'auto_success' => false
                    ]);

                } catch (\Exception $midtransError) {
                    DB::rollBack();
                    $product->increment('stok', $request->quantity);

                    \Log::error('💥 Midtrans Error: ' . $midtransError->getMessage());
                    \Log::error('Midtrans trace: ' . $midtransError->getTraceAsString());

                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memproses pembayaran Midtrans: ' . $midtransError->getMessage()
                    ], 500);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('💥 Payment Process Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process auto success payment without Midtrans
     */
    private function processAutoSuccess($order, $transaction, $transactionCode)
    {
        // Update order status - PASTIKAN menggunakan status yang sesuai dengan ENUM
        $order->update([
            'status' => 'confirmed',
            'payment_status' => 'settlement',
            'payment_method' => 'auto_success',
            'midtrans_transaction_id' => 'AUTO-' . $transactionCode,
            'midtrans_response' => json_encode([
                'auto_success' => true,
                'transaction_status' => 'settlement',
                'status_code' => '200',
                'payment_type' => 'auto_success'
            ]),
        ]);

        // Update transaction status - GUNAKAN STATUS YANG DITERIMA OLEH DATABASE
        $transaction->update([
            'status' => 'success', // Gunakan 'success' bukan 'settlement'
            'payment_method' => 'auto_success',
            'midtrans_transaction_id' => 'AUTO-' . $transactionCode,
            'midtrans_response' => json_encode([
                'auto_success' => true,
                'transaction_status' => 'settlement'
            ]),
        ]);

        return true;
    }

    /**
     * Handle Midtrans payment notification (callback) - IMPROVED VERSION
     */
    public function callback(Request $request)
    {
        \Log::info('🔄 ===== MIDTRANS CALLBACK START =====');
        \Log::info('📦 Callback Source: ' . $request->ip());
        \Log::info('📦 Callback Data:', $request->all());

        $serverKey = config('midtrans.server_key');

        // Validasi signature Midtrans
        $hashed = hash(
            "sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        \Log::info('🔐 Signature Verification:', [
            'order_id' => $request->order_id,
            'received_signature' => $request->signature_key,
            'calculated_signature' => $hashed,
            'is_valid' => $hashed === $request->signature_key
        ]);

        if ($hashed !== $request->signature_key) {
            \Log::error('❌ Signature verification FAILED');
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        \Log::info('✅ Signature VALID - Processing callback');

        DB::beginTransaction();
        try {
            // Cari transaksi berdasarkan transaction_code (order_id dari Midtrans)
            $transaction = Transaksi::where('transaction_code', $request->order_id)->first();

            if (!$transaction) {
                \Log::warning('❌ Transaction not found for order_id: ' . $request->order_id);
                DB::rollBack();
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Cari order yang terkait
            $order = Order::where('midtrans_order_id', $request->order_id)->first();

            if (!$order) {
                \Log::warning('❌ Order not found for midtrans_order_id: ' . $request->order_id);
                DB::rollBack();
                return response()->json(['message' => 'Order not found'], 404);
            }

            \Log::info('📋 Found transaction and order:', [
                'transaction_id' => $transaction->id,
                'order_id' => $order->id,
                'current_order_status' => $order->status,
                'current_payment_status' => $order->payment_status
            ]);

            // Update berdasarkan status dari Midtrans
            $this->updatePaymentStatus($order, $transaction, $request);

            DB::commit();
            \Log::info('💾 Database updated successfully');

            return response()->json(['message' => 'Callback processed successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('💥 Error processing callback: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['message' => 'Error processing callback'], 500);
        }
    }

    /**
     * Update payment status based on Midtrans response - IMPROVED VERSION
     */
    private function updatePaymentStatus($order, $transaction, $request)
    {
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        \Log::info('🔄 Updating payment status:', [
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $request->payment_type
        ]);

        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'challenge') {
                    // Pembayaran ditantang oleh FDS
                    $this->setPaymentStatus($order, $transaction, 'challenge', 'pending', $request);
                    \Log::info('⚠️ Payment challenged by FDS');
                } else if ($fraudStatus == 'accept') {
                    // Pembayaran berhasil
                    $this->setPaymentStatus($order, $transaction, 'success', 'confirmed', $request);
                    \Log::info('🎉 Payment captured successfully');
                }
                break;

            case 'settlement':
                // Pembayaran berhasil diselesaikan - AUTO SETTLEMENT DARI MIDTRANS
                $this->setPaymentStatus($order, $transaction, 'settlement', 'confirmed', $request);
                \Log::info('🎉 Payment settled successfully');
                break;

            case 'pending':
                // Menunggu pembayaran
                $this->setPaymentStatus($order, $transaction, 'pending', 'pending', $request);
                \Log::info('⏳ Payment pending');
                break;

            case 'deny':
                // Pembayaran ditolak
                $this->setPaymentStatus($order, $transaction, 'deny', 'cancelled', $request);
                $this->restoreStock($order);
                \Log::info('❌ Payment denied');
                break;

            case 'expire':
                // Pembayaran kadaluarsa
                $this->setPaymentStatus($order, $transaction, 'expire', 'cancelled', $request);
                $this->restoreStock($order);
                \Log::info('⏰ Payment expired');
                break;

            case 'cancel':
                // Pembayaran dibatalkan
                $this->setPaymentStatus($order, $transaction, 'cancel', 'cancelled', $request);
                $this->restoreStock($order);
                \Log::info('🚫 Payment cancelled');
                break;

            default:
                \Log::warning('🤔 Unknown transaction status: ' . $transactionStatus);
                break;
        }
    }

    /**
     * Set payment and order status
     */
    private function setPaymentStatus($order, $transaction, $paymentStatus, $orderStatus, $request)
    {
        // Update order
        $order->update([
            'status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'payment_method' => $request->payment_type,
            'midtrans_transaction_id' => $request->transaction_id,
            'midtrans_gross_amount' => $request->gross_amount,
            'midtrans_response' => json_encode($request->all()),
        ]);

        // Update transaction - GUNAKAN STATUS YANG SESUAI DENGAN DATABASE
        $transactionStatus = $this->mapToDatabaseStatus($paymentStatus);

        $transaction->update([
            'status' => $transactionStatus,
            'payment_method' => $request->payment_type,
            'midtrans_transaction_id' => $request->transaction_id,
            'midtrans_response' => json_encode($request->all()),
        ]);

        \Log::info('📝 Status updated:', [
            'order_status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'transaction_status' => $transactionStatus
        ]);
    }

    /**
     * Map Midtrans status to database-safe status
     */
    private function mapToDatabaseStatus($midtransStatus)
    {
        $statusMap = [
            'pending' => 'pending',
            'capture' => 'success',
            'settlement' => 'success', // Map settlement to success
            'success' => 'success',
            'challenge' => 'pending',
            'deny' => 'failed',
            'cancel' => 'cancelled',
            'expire' => 'failed',
            'failure' => 'failed'
        ];

        return $statusMap[$midtransStatus] ?? 'pending';
    }

    /**
     * Restore product stock when payment fails
     */
    private function restoreStock($order)
    {
        try {
            $order->product->increment('stok', $order->quantity);
            \Log::info('📦 Stock restored:', [
                'product_id' => $order->product_id,
                'quantity' => $order->quantity
            ]);
        } catch (\Exception $e) {
            \Log::error('💥 Error restoring stock: ' . $e->getMessage());
        }
    }

    /**
     * Method khusus untuk testing - auto success payment
     */
    public function payAutoSuccess(Request $request)
    {
        $request->merge(['payment_mode' => 'auto_success']);
        return $this->pay($request);
    }

    public function finish(Request $request)
    {
        // Debug: Lihat semua parameter yang dikirim Midtrans
        \Log::info('🎯 FINISH PAGE ACCESSED', $request->all());

        // Ambil status dari berbagai kemungkinan parameter Midtrans
        $status = $request->get('transaction_status')
            ?? $request->get('status_code')
            ?? $request->get('status')
            ?? 'unknown';

        $orderId = $request->get('order_id'); // transaction_code dari Midtrans

        $order = null;
        $transaction = null;
        $product = null;

        if ($orderId) {
            // Cari transaksi berdasarkan transaction_code
            $transaction = Transaksi::where('transaction_code', $orderId)->first();

            // Cari order berdasarkan midtrans_order_id
            $order = Order::where('midtrans_order_id', $orderId)
                ->with(['product'])
                ->first();

            // Jika tidak ketemu dengan midtrans_order_id, coba cari dengan order_id dari transaksi
            if (!$order && $transaction) {
                $order = Order::where('id', $transaction->order_id ?? null)
                    ->with(['product'])
                    ->first();
            }

            if ($order && $order->product) {
                $product = $order->product;
            }
        }

        \Log::info('🔍 Finish Page Data:', [
            'order_id' => $orderId,
            'status' => $status,
            'order_found' => !is_null($order),
            'transaction_found' => !is_null($transaction)
        ]);

        // Normalisasi status untuk tampilan
        $displayStatus = 'pending';
        $isSuccess = false;
        $message = '';
        $icon = '⏳';
        $bgColor = 'bg-yellow-50';
        $textColor = 'text-yellow-800';
        $borderColor = 'border-yellow-200';

        if (in_array($status, ['settlement', 'capture', 'success'])) {
            $displayStatus = 'success';
            $isSuccess = true;
            $message = 'Pembayaran Berhasil! 🎉';
            $icon = '✅';
            $bgColor = 'bg-green-50';
            $textColor = 'text-green-800';
            $borderColor = 'border-green-200';
        } elseif (in_array($status, ['pending', 'chargeback', 'review'])) {
            $displayStatus = 'pending';
            $message = 'Menunggu Pembayaran...';
            $icon = '⏳';
            $bgColor = 'bg-yellow-50';
            $textColor = 'text-yellow-800';
            $borderColor = 'border-yellow-200';
        } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failure', 'error'])) {
            $displayStatus = 'failure';
            $message = 'Pembayaran Gagal atau Dibatalkan';
            $icon = '❌';
            $bgColor = 'bg-red-50';
            $textColor = 'text-red-800';
            $borderColor = 'border-red-200';
        }

        return view('transactions.finish', compact(
            'displayStatus',
            'isSuccess',
            'order',
            'transaction',
            'product',
            'status',
            'orderId',
            'message',
            'icon',
            'bgColor',
            'textColor',
            'borderColor'
        ));
    }

    // Method untuk melihat pesanan saya
    public function myOrders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['product.user'])
            ->latest()
            ->paginate(10);

        return view('farmers.orders.index', compact('orders'));
    }

    // Method untuk melihat detail order
    // Di TransaksiController - method orderDetail
    public function orderDetail($id)
    {
        // Gunakan eager loading yang benar
        $order = Order::with(['product.user'])
            ->findOrFail($id);

        // Cari transaction secara manual jika relasi tidak bekerja
        $transaction = Transaksi::where('order_id', $order->id)->first();

        // Authorization check
        if ($order->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('orders.show', compact('order', 'transaction'));
    }
    /**
     * Method untuk cancel order yang pending - FIXED VERSION
     */
    public function cancelOrder($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::with(['product'])->findOrFail($id);

            // Authorization check - FIXED: gunakan != bukan !==
            if ($order->user_id != Auth::id()) {
                \Log::warning('Unauthorized cancel attempt', [
                    'order_user_id' => $order->user_id,
                    'current_user_id' => Auth::id(),
                    'order_id' => $id
                ]);
                abort(403, 'Unauthorized action.');
            }

            // Hanya bisa cancel order yang masih pending
            if ($order->status !== 'pending') {
                return redirect()->back()->with('error', 'Tidak dapat membatalkan order yang sudah diproses.');
            }

            // Kembalikan stok
            if ($order->product) {
                $order->product->increment('stok', $order->quantity);
                \Log::info('Stock restored for cancelled order', [
                    'order_id' => $order->id,
                    'product_id' => $order->product_id,
                    'quantity' => $order->quantity
                ]);
            }

            // Update status order
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            // Update transaksi jika ada
            $transaction = Transaksi::where('order_id', $order->id)->first();
            if ($transaction) {
                $transaction->update([
                    'status' => 'cancelled'
                ]);
            }

            DB::commit();

            \Log::info('Order cancelled successfully', ['order_id' => $order->id]);

            return redirect()->route('transactions.my-orders')->with('success', 'Order berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error cancelling order: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk check status payment
     */
    public function checkPaymentStatus($orderCode)
    {
        try {
            $order = Order::where('order_code', $orderCode)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $transaction = Transaksi::where('order_id', $order->id)->first();

            return response()->json([
                'success' => true,
                'order_status' => $order->status,
                'payment_status' => $order->payment_status,
                'transaction_status' => $transaction->status ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Method untuk manual sync status dari Midtrans
     */
    public function syncPaymentStatus($orderCode)
    {
        try {
            $order = Order::where('order_code', $orderCode)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Logic untuk sync status dari Midtrans bisa ditambahkan di sini
            // Untuk sekarang return current status

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil di-sync',
                'order_status' => $order->status,
                'payment_status' => $order->payment_status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync status'
            ], 500);
        }
    }

    /**
     * Method untuk debug authorization
     */
    public function debugOrder($id)
    {
        $order = Order::find($id);
        $user = Auth::user();

        \Log::info('Debug Order Access', [
            'order_id' => $id,
            'order_user_id' => $order ? $order->user_id : 'not found',
            'current_user_id' => $user ? $user->id : 'not authenticated',
            'is_same_user' => $order && $user ? ($order->user_id == $user->id) : false
        ]);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        return response()->json([
            'order_user_id' => $order->user_id,
            'current_user_id' => $user->id,
            'is_owner' => $order->user_id == $user->id,
            'order_status' => $order->status
        ]);
    }
}