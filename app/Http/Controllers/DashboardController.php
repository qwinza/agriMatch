<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'petani') {
            return $this->petani();
        } elseif ($user->role === 'pembeli') {
            return $this->pembeli();
        }

        abort(403, 'Peran pengguna tidak dikenali.');
    }

    public function petani()
    {
        $user = Auth::user();

        // Hitung total produk milik petani yang login
        $totalProducts = Produk::where('user_id', $user->id)->count();

        // Hitung produk baru minggu ini
        $newProductsThisWeek = Produk::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->count();

        // PERBAIKAN: Gunakan whereHas('product') bukan whereHas('products')
        $pendingOrders = Order::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->count();

        $currentMonthRevenue = Order::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');

        $totalSales = Order::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'completed')->count();

        // Hitung revenue increase
        $lastMonthRevenue = Order::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total_price');

        $revenueIncrease = 0;
        if ($lastMonthRevenue > 0 && $currentMonthRevenue > 0) {
            $revenueIncrease = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }

        return view('farmers.dashboard', compact(
            'user',
            'totalProducts',
            'newProductsThisWeek',
            'pendingOrders',
            'currentMonthRevenue',
            'totalSales',
            'revenueIncrease'
        ));
    }

    public function pembeli()
    {
        $user = Auth::user();
        return view('buyers.dashboard', compact('user'));
    }
}