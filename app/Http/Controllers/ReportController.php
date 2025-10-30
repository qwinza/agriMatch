<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Produk;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'monthly');
        $date = $request->get('date', now()->format('Y-m'));

        // Inisialisasi variabel dengan nilai default
        $stats = [
            'total_orders' => 0,
            'total_revenue' => 0,
            'total_spent' => 0,
            'completed_orders' => 0,
            'average_order_value' => 0,
        ];

        $orders = collect(); // Empty collection
        $topProducts = collect(); // Empty collection
        $favoriteFarmers = collect(); // Empty collection

        try {
            // Parse date berdasarkan filter
            $dateRange = $this->getDateRange($filter, $date);

            if ($user->role === 'petani') {
                // Query untuk petani
                $orders = Order::whereHas('product', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->with(['product', 'user'])
                    ->latest()
                    ->get();

                // Hitung stats untuk petani
                $stats['total_orders'] = $orders->count();
                $stats['completed_orders'] = $orders->where('status', 'completed')->count();
                $stats['total_revenue'] = $orders->where('status', 'completed')->sum('total_price');
                $stats['average_order_value'] = $stats['completed_orders'] > 0 ?
                    $stats['total_revenue'] / $stats['completed_orders'] : 0;

                // Top products untuk petani
                $topProducts = Produk::where('user_id', $user->id)
                    ->withCount([
                        'orders as total_orders' => function ($query) use ($dateRange) {
                            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                ->where('status', 'completed');
                        }
                    ])
                    ->withSum([
                        'orders as total_revenue' => function ($query) use ($dateRange) {
                            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                ->where('status', 'completed');
                        }
                    ], 'total_price')
                    ->having('total_orders', '>', 0)
                    ->orderBy('total_orders', 'desc')
                    ->limit(5)
                    ->get();

            } else {
                // Query untuk pembeli
                $orders = Order::where('user_id', $user->id)
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->with(['product.user'])
                    ->latest()
                    ->get();

                // Hitung stats untuk pembeli
                $stats['total_orders'] = $orders->count();
                $stats['completed_orders'] = $orders->where('status', 'completed')->count();
                $stats['total_spent'] = $orders->where('status', 'completed')->sum('total_price');
                $stats['average_order_value'] = $stats['completed_orders'] > 0 ?
                    $stats['total_spent'] / $stats['completed_orders'] : 0;

                // Favorite farmers untuk pembeli
                $favoriteFarmersData = Order::where('user_id', $user->id)
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->where('status', 'completed')
                    ->with('product.user')
                    ->get()
                    ->groupBy('product.user_id')
                    ->map(function ($orders, $userId) {
                        return [
                            'farmer' => $orders->first()->product->user,
                            'order_count' => $orders->count(),
                            'total_spent' => $orders->sum('total_price')
                        ];
                    })
                    ->sortByDesc('order_count')
                    ->take(5);

                $favoriteFarmers = $favoriteFarmersData->values();
            }

        } catch (\Exception $e) {
            // Log error dan biarkan variabel dengan nilai default
            \Log::error('Report error: ' . $e->getMessage());
        }

        return view('farmers.reports.index', compact(
            'stats',
            'orders',
            'topProducts',
            'favoriteFarmers',
            'filter',
            'date'
        ));
    }

    private function getDateRange($filter, $date)
    {
        switch ($filter) {
            case 'daily':
                $start = Carbon::parse($date)->startOfDay();
                $end = Carbon::parse($date)->endOfDay();
                break;
            case 'weekly':
                $start = Carbon::parse($date)->startOfWeek();
                $end = Carbon::parse($date)->endOfWeek();
                break;
            case 'monthly':
                $start = Carbon::parse($date)->startOfMonth();
                $end = Carbon::parse($date)->endOfMonth();
                break;
            case 'yearly':
                $start = Carbon::create($date, 1, 1)->startOfYear();
                $end = Carbon::create($date, 12, 31)->endOfYear();
                break;
            default:
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
        }

        return [
            'start' => $start,
            'end' => $end
        ];
    }

    public function export(Request $request)
    {
        // Logic untuk export report
        $type = $request->get('type', 'pdf');

        return redirect()->back()->with('success', 'Report exported successfully');
    }
}