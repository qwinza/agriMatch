<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NgrokHelper
{
    public static function getNgrokUrl()
    {
        // Cek cache dulu untuk menghindari request berulang
        return Cache::remember('ngrok_url', 60, function () {
            try {
                // Cek ngrok API untuk mendapatkan URL
                $response = Http::timeout(3)->get('http://127.0.0.1:4040/api/tunnels');

                if ($response->successful()) {
                    $data = $response->json();

                    foreach ($data['tunnels'] as $tunnel) {
                        if ($tunnel['proto'] === 'https') {
                            $url = $tunnel['public_url'];
                            Log::info('Ngrok URL detected: ' . $url);
                            return $url;
                        }
                    }
                }

                return null;
            } catch (\Exception $e) {
                Log::debug('Ngrok not running or not accessible: ' . $e->getMessage());
                return null;
            }
        });
    }

    public static function isNgrokRunning()
    {
        return !is_null(self::getNgrokUrl());
    }

    public static function getBaseUrl()
    {
        if (self::isNgrokRunning() && app()->environment('local')) {
            return self::getNgrokUrl();
        }

        return config('app.url');
    }

    public static function getCallbackUrl()
    {
        return self::getBaseUrl() . '/payment/callback';
    }

    public static function getFinishUrl()
    {
        return self::getBaseUrl() . '/transactions/finish';
    }
}