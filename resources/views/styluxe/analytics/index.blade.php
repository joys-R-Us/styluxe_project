@extends('styluxe.layouts.dashboard')

@section('title', 'Analytics - Styluxe')

@section('content')
<div class="analytics-container">
    <h1 class="page-title">📈 Analytics Dashboard</h1>

    {{-- Overall Stats --}}
    <div class="bubble-grid">
        <div class="stat-card">
            <span class="stat-emoji">📦</span>
            <h3 class="stat-title">Total Products</h3>
            <p class="stat-value">{{ $stats['total_products'] ?? 0 }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">✨</span>
            <h3 class="stat-title">Available</h3>
            <p class="stat-value">{{ $stats['available_products'] ?? 0 }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">⚠️</span>
            <h3 class="stat-title">Low Stock</h3>
            <p class="stat-value">{{ $stats['low_stock_count'] ?? 0 }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">🛍️</span>
            <h3 class="stat-title">Total Orders</h3>
            <p class="stat-value">{{ $stats['total_orders'] ?? 0 }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">⏳</span>
            <h3 class="stat-title">Pending Orders</h3>
            <p class="stat-value">{{ $stats['pending_orders'] ?? 0 }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">💰</span>
            <h3 class="stat-title">Total Revenue</h3>
            <p class="stat-value">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
        </div>
    </div>

    {{-- Category Breakdown --}}
    <section class="analytics-section">
        <h2 class="section-title">📂 Inventory by Category</h2>
        <div class="category-grid">
            @foreach($categoryStats as $cat)
            <div class="category-card">
                <h4>{{ $cat->category->name ?? 'Uncategorized' }}</h4>
                <p><strong>{{ $cat->count }}</strong> items</p>
                <p>Total Qty: <strong>{{ $cat->total_qty }}</strong></p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Sales by Category --}}
    @if($salesByCategory->count() > 0)
    <section class="analytics-section">
        <h2 class="section-title">💰 Sales by Category</h2>
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Units Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesByCategory as $sale)
                    <tr>
                        <td><strong>{{ $sale->category->name }}</strong></td>
                        <td>{{ $sale->total_sold }}</td>
                        <td>₱{{ number_format($sale->revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

    {{-- Monthly Trends --}}
    @if($monthlyTrends->count() > 0)
    <section class="analytics-section">
        <h2 class="section-title">📊 Monthly Trends</h2>
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyTrends as $trend)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::parse($trend->month . '-01')->format('F Y') }}</strong></td>
                        <td>{{ $trend->order_count }}</td>
                        <td>₱{{ number_format($trend->revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

    {{-- Recent Stock Activity --}}
    @if($recentActivity->count() > 0)
    <section class="analytics-section">
        <h2 class="section-title">📜 Recent Stock Activity</h2>
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Quantity Change</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity as $log)
                    <tr>
                        <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $log->product->category->name ? $log->product->category->name->item_name : 'N/A' }}</td>
                        <td>
                            <span class="role-badge {{ $log->getChangeClass() }}">
                                {{ $log->getChangeTypeLabel() }}
                            </span>
                        </td>
                        <td>{{ $log->quantity_change }}</td>
                        <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif
</div>

<style>
.analytics-container {
    max-width: 1400px;
    margin: 0 auto;
}

.analytics-section {
    margin: var(--space-8) 0;
}

.category-card {
    background: var(--bg-white);
    padding: var(--space-4);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    text-align: center;
    border: 2px solid var(--bg-variant);
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary-violet);
    box-shadow: var(--shadow-md);
}

.category-card h4 {
    margin: 0 0 var(--space-2);
    color: var(--primary-violet);
}

.category-card p {
    margin: var(--space-1) 0;
    color: var(--text-muted);
}
</style>
@endsection