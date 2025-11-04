<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function show(Order $order)
    {
        // Pastikan hanya pembeli yang bisa checkout order mereka
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Data transaksi
        $transaction = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($transaction);

        return view('checkout.payment', compact('order', 'snapToken'));
    }

    // Webhook callback dari Midtrans
    public function callback(Request $request)
    {
        $payload = $request->all();

        $order = Order::where('order_code', $payload['order_id'])->firstOrFail();

        // Update status berdasarkan payment_type dan transaction_status
        $status = $payload['transaction_status'] ?? 'pending';

        if ($status == 'capture' || $status == 'settlement') {
            $order->update([
                'status' => 'completed',
                'status_updated_at' => now(),
            ]);
        } elseif ($status == 'pending') {
            $order->update([
                'status' => 'pending',
                'status_updated_at' => now(),
            ]);
        } elseif ($status == 'deny' || $status == 'cancel' || $status == 'expire') {
            $order->update([
                'status' => 'cancelled',
                'status_updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
