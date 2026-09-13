<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard analytics.
     */
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Summary Counters
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $todayTransactions = Order::whereDate('order_date', $today)
            ->where('order_status', 'completed')
            ->count();
        $todaySales = Order::whereDate('order_date', $today)
            ->where('order_status', 'completed')
            ->sum('order_amount');
        $monthSales = Order::where('order_date', '>=', $startOfMonth)
            ->where('order_status', 'completed')
            ->sum('order_amount');
        $lowStockProducts = Product::where('product_stock', '<=', 5)->count();

        // 1. Sales last 7 days
        $last7Days = collect();
        $last7DaysSales = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $label = $date->translatedFormat('d M');

            $amount = Order::whereDate('order_date', $dateStr)
                ->where('order_status', 'completed')
                ->sum('order_amount');

            $last7Days->push($label);
            $last7DaysSales->push((float) $amount);
        }

        // 2. Sales per month for current year
        $currentYear = Carbon::now()->year;
        $monthsLabels = [];
        $monthlySales = [];
        for ($m = 1; $m <= 12; $m++) {
            $carbonMonth = Carbon::create($currentYear, $m, 1);
            $monthsLabels[] = $carbonMonth->translatedFormat('M');

            $total = Order::whereYear('order_date', $currentYear)
                ->whereMonth('order_date', $m)
                ->where('order_status', 'completed')
                ->sum('order_amount');

            $monthlySales[] = (float) $total;
        }

        // 3. Top 5 Best Selling Products
        $topProducts = OrderDetail::select(
            'products.product_name',
            DB::raw('SUM(order_details.order_quantity) as total_sold')
        )
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->groupBy('order_details.product_id', 'products.product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $topProductNames = $topProducts->pluck('product_name')->toArray();
        $topProductQuantities = $topProducts->pluck('total_sold')->map(fn($q) => (int)$q)->toArray();

        // Recent Orders
        $recentOrders = Order::with(['user'])
            ->latest('order_date')
            ->take(8)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'todayTransactions',
            'todaySales',
            'monthSales',
            'lowStockProducts',
            'last7Days',
            'last7DaysSales',
            'monthsLabels',
            'monthlySales',
            'topProductNames',
            'topProductQuantities',
            'recentOrders'
        ));
    }
}
