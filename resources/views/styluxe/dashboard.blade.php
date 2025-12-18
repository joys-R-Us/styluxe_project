{{-- resources/views/styluxe/dashboard.blade.php --}}
@extends('styluxe.layouts.dashboard')

@section('title', 'Dashboard - Styluxe')

@section('content')
<div class="dashboard-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h1 class="page-title" style="margin: 0;">📊 Dashboard Overview</h1>
        <div class="quick-actions" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ route('styluxe.items.create') }}" class="btn" style="background-color: #10b981;">➕ Add Item</a>
            <a href="{{ route('styluxe.batch-upload') }}" class="btn" style="background-color: #10b981;">➕ Batch Upload</a>
            <a href="{{ route('styluxe.settings.users.create') }}" class="btn" style="background-color: #10b981;">👤 User</a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="bubble-grid">
        <div class="stat-card">
            <span class="stat-emoji">📦</span>
            <h3 class="stat-title">Total Products</h3>
            <p class="stat-value">{{ $stats['total_products'] }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">✨</span>
            <h3 class="stat-title">Available</h3>
            <p class="stat-value">{{ $stats['available_products'] }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">⚠️</span>
            <h3 class="stat-title">Low Stock</h3>
            <p class="stat-value">{{ $stats['low_stock_count'] }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">🛍️</span>
            <h3 class="stat-title">Total Orders</h3>
            <p class="stat-value">{{ $stats['total_orders'] }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">⏳</span>
            <h3 class="stat-title">Pending Orders</h3>
            <p class="stat-value">{{ $stats['pending_orders'] }}</p>
        </div>

        <div class="stat-card">
            <span class="stat-emoji">💰</span>
            <h3 class="stat-title">Total Revenue</h3>
            <p class="stat-value">₱{{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
    </div>

    <div class="border-divider"></div>

    {{-- Category Breakdown --}}
    @if($categoryStats->count() > 0)
    <section class="mt-6">
        <h2 class="section-title">📂 Category Breakdown</h2>
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
    @else
    <section class="mt-6">
        <h2 class="section-title">📂 Category Breakdown</h2>
        <h3>No items at the moment. 😭🫙</h3>
        <div class="category-grid">
            @foreach($categoryStats as $cat)
            <div class="category-card">
                <h4>{{ $cat->category->name ?? 'Uncategorized' }}</h4>
                <p><strong>{{ $cat->count }}</strong> items</p>
                <p>Total Qty: <strong>{{ $cat->total_qty }}</strong></p>
            </div>
            @endforeach
        </div>
    @endif
    
    <div class="border-divider"></div>

    {{-- Low Stock Alerts --}}
    @if($lowStockAlerts->count() > 0)
    <section class="mt-6">
        <h2 class="section-title">⚠️ Low Stock Alerts</h2>
        <div class="alert-list">
            @foreach($lowStockAlerts->take(5) as $item)
            <div class="alert-item">
                @if($item->image_path)
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->item_name }}">
                @else
                    <div class="no-image-small">📷 No Image</div>
                @endif
                <div class="alert-info">
                    <h4>{{ $item->item_name }}</h4>
                    <p>Current Stock: <strong class="text-danger">{{ $item->quantity }}</strong></p>
                    <p>Threshold: {{ $item->low_stock_threshold }}</p>
                </div>
                @if(!empty($item->barcode))
                <a href="{{ route('styluxe.items.edit', $item->barcode) }}" class="btn btn-sm">Restock</a>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @else
    <section class="mt-6">
        <h2 class="section-title">⚠️ Low Stock Alerts</h2>
        <h3>No items at the moment. 😭🫙</h3>
    @endif

    <div class="border-divider"></div>

    {{-- Recent Items --}}
    @if($recentItems->count() > 0)
    <section class="mt-6">
        <h2 class="section-title">✨ Recent Items</h2>
        <div class="items-grid">
            @foreach($recentItems as $item)
            <div class="item-card">
                @if($item->image_path)
                <div class="item-thumb">
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->item_name }}">
                </div>
                @else
                    <div class="item-thumb no-image-card">📷 No Image</div>
                @endif
                <div class="item-info">
                    <h3>{{ $item->item_name }}</h3>
                    <p class="item-status {{ $item->getStockStatusClass() }}">{{ $item->status }}</p>
                    <p class="item-stock">Stock: {{ $item->quantity }}</p>
                    <p class="item-price">{{ $item->formattedPrice() }}</p>
                    @if(!empty($item->barcode))
                        <a href="{{ route('styluxe.items.show', $item->barcode) }}" class="view-btn">View</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @else
    <section class="mt-6">
        <h2 class="section-title">✨ Recent Items</h2>
        <h3>No recent items. List might be empty. 🤔🫙</h3>
    @endif

    <div class="border-divider"></div>

    {{-- Sales by Category --}}
    @if($salesByCategory->count() > 0)
    <section class="mt-6">
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
                        <td><strong>{{ $sale->category->name ?? 'Uncategorized' }}</strong></td>
                        <td>{{ $sale->total_sold }}</td>
                        <td>₱{{ number_format($sale->revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @else
    <section class="mt-6">
        <h2 class="section-title">💰 Sales by Category</h2>
        <h3>No recent activity.🥹</h3>
    @endif

    <div class="border-divider"></div>

    {{-- Recent Stock Activity --}}
    @if($recentActivity->count() > 0)
    <section class="mt-6">
        <h2 class="section-title">📜 Recent Stock Activity</h2>
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Change</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity->take(10) as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, H:i') }}</td>
                        <td>{{ $log->product ? $log->product->item_name : 'N/A' }}</td>
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
    @else
    <section class="mt-6">
        <h2 class="section-title">📜 Recent Stock Activity</h2>
        <h3>No recent stock activity. 📊</h3>
    @endif
</div>

<style>
.alert-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

.alert-item {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    background: var(--bg-white);
    padding: var(--space-4);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    border-left: 4px solid var(--warning);
}

.alert-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    border: 2px solid var(--warning);
}

.alert-info {
    flex: 1;
}

.alert-info h4 {
    margin: 0 0 var(--space-2);
    color: var(--primary-violet);
}

.alert-info p {
    margin: var(--space-1) 0;
    color: var(--text-muted);
}
</style>
@endsection