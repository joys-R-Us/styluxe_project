<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $user = Auth::user();

        // Only admin and client roles supported
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        if ($user->role === 'client') {
            return $this->clientDashboard();
        }

        return redirect()->route('styluxe.homepage');
    }

    private function adminDashboard()
    {
        $stats = [
            'total_products' => Products::count(),
            'low_stock' => Products::whereColumn('quantity', '<=', 'low_stock_threshold')->count(),
            'total_orders' => Order::count(),
        ];

        $topCategories = Products::selectRaw('category')
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupby('category_id')
            ->get();

        return view('styluxe.layouts.dashboard', compact('stats', 'topCategories'));
    }

    // Only admin and client dashboards supported

    private function clientDashboard()
    {
        $myOrders = Order::where('client_id', Auth::id())->latest()->take(5)->get();
        
        return view('styluxe.dashboard.client', compact('myOrders'));
    }
}
