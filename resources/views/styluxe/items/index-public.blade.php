@extends('styluxe.layouts.dashboard')
@section('title', 'Browse Items - Styluxe')
@section('content')

<div class="items-list-container">

    <h1 class="page-title">🛍️ Browse Items</h1>

    <!-- FILTERS -->
    <div class="item-search-wrapper">
        <form action="{{ route('styluxe.items.index-public') }}" method="GET" class="search-form">
            <input type="text" name="search" placeholder="🔍 Search items..." value="{{ request('search') }}">
            <button class="btn" type="submit">Search</button>
        </form>

        {{-- Add Item Button (for authorized users) --}}
        @if (Auth::check() && Auth::user()->canManageInventory())
        
        <div class="quick-actions" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ route('styluxe.items.create') }}" class="btn mt-2 mb-4" style="background-color: #10b981;">➕ Add Item</a>
            <a href="{{ route('styluxe.batch-upload') }}" class="btn mt-2 mb-4" style="background-color: #10b981;">➕ Batch Upload</a>
        </div>
        @endif
    </div>

    <div class="filter-buttons">
        <a href="{{ route('styluxe.items.index-public') }}" class="filter-btn {{ !request('filter') ? 'active' : '' }}">All</a>
        <a href="{{ route('styluxe.items.index-public', ['filter'=>'available']) }}" class="filter-btn {{ request('filter') == 'available' ? 'active' : '' }}">✨ Available</a>
        <a href="{{ route('styluxe.items.index-public', ['filter'=>'low stock']) }}" class="filter-btn {{ request('filter') == 'low stock' ? 'active' : '' }}">⚠️ Low Stock</a>
        <a href="{{ route('styluxe.items.index-public', ['filter'=>'sold out']) }}" class="filter-btn {{ request('filter') == 'sold out' ? 'active' : '' }}">❌ Sold Out</a>
    </div>

    <!-- ITEMS TABLE -->
    <div class="items-table-wrapper">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Barcode</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        @if($item->image_path)
                        <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->item_name }}" class="table-img">
                        @else
                        <div class="no-image">📷</div>
                        @endif
                    </td>
                    <td><strong>{{ $item->item_name }}</strong></td>
                    <td>{{ $item->barcode }}</td>
                    <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                    <td>
                        <span class="role-badge {{ $item->getStockStatusClass() }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->formattedPrice() }}</td>
                    <td class="actions-td">
                        @if($item->barcode)
                            <a href="{{ route('styluxe.items.show', $item->barcode) }}" class="btn-sm btn-primary" title="View">
                                👁️
                            </a>
                        @else
                            <span class="text-muted">No barcode</span>
                        @endif

                        @if (Auth::check() && Auth::user()->canManageInventory())
                            @if(!empty($item->barcode))
                            <a href="{{ route('styluxe.items.edit', $item->barcode) }}" class="btn-sm btn-secondry" title="Edit">
                            @endif
                                ✏️
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                            @if(!empty($item->barcode))
                                <form action="{{ route('styluxe.items.destroy', $item->barcode) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-cancel" title="Delete">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px;">
                        <p class="empty-icon">📦</p>
                        <p>No items found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        {{ $items->links() }}
    </div>
</div>

<style>
    .items-list-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--space-6);
}

.items-search-wrapper {
    display: flex;
    gap: var(--space-4);
    align-items: center;
    margin-bottom: var(--space-6);
}

.items-search-form {
    display: flex;
    gap: var(--space-2);
    flex: 0 0 50%;
    max-width: 600px;
}

.items-search-form input {
    flex: 1;
    padding: var(--space-3) var(--space-4);
    border: 2px solid var(--primary-violet);
    border-radius: var(--radius-full);
    font-size: var(--fs-base);
    outline: none;
}

.items-search-form input:focus {
    border-color: var(--secondary-coral);
    box-shadow: 0 0 0 3px rgba(255, 101, 132, 0.1);
}

.filter-buttons {
    display: flex;
    gap: var(--space-2);
    margin-bottom: var(--space-4);
    flex-wrap: wrap;
}

.filter-btn {
    padding: var(--space-2) var(--space-4);
    background: var(--bg-variant);
    border-radius: var(--radius-full);
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 600;
    font-size: var(--fs-sm);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.filter-btn:hover {
    background: var(--primary-violet);
    color: var(--text-light);
}

.filter-btn.active {
    background: var(--primary-violet);
    color: var(--text-light);
    border-color: var(--secondary-coral);
}

.no-image {
    width: 60px;
    height: 60px;
    background: var(--bg-variant);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-sm);
    font-size: 24px;
}

.actions-td {
    display: flex;
    gap: var(--space-2);
    flex-wrap: wrap;
}
</style>

@endsection