<?php
namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Order;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $query = Products::query();

        // Overall stats
        $stats = [
            'total_products' => Products::count(),
            'available_products' => Products::where('status', 'Available')->count(),
            'low_stock_count' => Products::whereColumn('quantity', '<=', 'low_stock_threshold')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        // Category breakdown
        $categoryStats = (clone $query)
            ->selectRaw('category_id, COUNT(*) as count, SUM(quantity) as total_qty')
            ->groupBy('category_id')
            ->get();


        // Sales by category
        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_barcode', '=', 'products.barcode')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->select('products.category_id', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('products.category_id')
            ->orderByDesc('total_sold')
            ->get();

        // Recent stock activity
        $recentActivity = StockLog::with('product', 'user')
            ->latest()
            ->take(20)
            ->get();

        // Monthly trends (last 6 months)
        $monthlyTrends = Order::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent items (for dashboard)
        $recentItems = Products::latest('updated_at')->take(8)->get();

        // Low stock alerts
        $lowStockAlerts = Products::whereColumn('quantity', '<=', 'low_stock_threshold')
            ->where('status', '!=', 'Sold Out')
            ->get();

        return view('styluxe.dashboard', compact(
            'stats',
            'categoryStats',
            'salesByCategory',
            'recentActivity',
            'monthlyTrends',
            'recentItems',
            'lowStockAlerts'
        ));
    }
}