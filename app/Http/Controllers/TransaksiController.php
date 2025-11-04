<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Midtrans\Snap;
use Midtrans\Config;

class TransaksiController extends Controller
{
    // Tampilkan halaman konfirmasi pembelian
    public function create($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Produk::findOrFail($id);

            return view('transactions.create', compact('product', 'encryptedId'));
        } catch (\Exception $e) {
            abort(404, 'Produk tidak ditemukan.');
        }
    }

    // Proses pembayaran menggunakan Midtrans
    public function pay(Request $request)
    {
        $request->validate([
            'encryptedId' => 'required',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $id = Crypt::decrypt($request->encryptedId);
        $product = Produk::findOrFail($id);

        $totalAmount = $product->harga * $request->quantity;

        // Buat kode transaksi unik
        $transactionCode = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), 0, 8));

        // Simpan transaksi di database dengan status pending
        $transaction = Transaksi::create([
            'user_id' => Auth::id() ?? null,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'gross_amount' => $totalAmount,
            'status' => 'pending',
            'transaction_code' => $transactionCode,
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Pastikan order_id tidak null
        if (empty($transactionCode)) {
            return back()->with('error', 'Terjadi kesalahan membuat kode transaksi.');
        }

        // Data transaksi untuk Midtrans
        $midtransTransaction = [
            'transaction_details' => [
                'order_id' => $transactionCode,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $request->recipient_name,
                'phone' => $request->phone,
                'billing_address' => [
                    'address' => $request->shipping_address,
                ],
            ],
            'item_details' => [
                [
                    'id' => (string) $product->id, // harus string
                    'price' => (int) $product->harga,
                    'quantity' => (int) $request->quantity,
                    'name' => $product->nama_produk,
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($midtransTransaction);
        } catch (\Exception $e) {
            return back()->with('error', 'Midtrans Error: ' . $e->getMessage());
        }

        return view('transactions.midtrans', compact('snapToken', 'transaction'));
    }

    // Halaman selesai pembayaran
    public function finish(Request $request)
    {
        $status = $request->get('status') ?? 'unknown';
        return view('transactions.finish', compact('status'));
    }
}
