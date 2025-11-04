<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransServices
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Membuat transaksi dan mengembalikan Snap Token
     */
    public function createTransaction($paymentData)
    {
        try {
            $snapToken = Snap::getSnapToken($paymentData);
            return $snapToken;
        } catch (\Exception $e) {
            \Log::error('Midtrans error: ' . $e->getMessage());
            throw new \Exception('Gagal membuat transaksi Midtrans.');
        }
    }

    /**
     * Menangani notifikasi dari Midtrans
     */
    public function handleNotification($request)
    {
        try {
            return new Notification();
        } catch (\Exception $e) {
            \Log::error('Midtrans notification error: ' . $e->getMessage());
            throw new \Exception('Gagal memproses notifikasi Midtrans.');
        }
    }
}
