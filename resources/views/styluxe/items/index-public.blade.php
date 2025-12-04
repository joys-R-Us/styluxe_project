@extends('styluxe.layouts.main')
@section('content')

<div class="public-items-wrapper">

    <h1 class="page-title">🛍️ Browse Items</h1>

    <!-- SEARCH + FILTERS -->
    <div class="top-controls">
        <form action="{{ route('styluxe.items.index-public') }}" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search items..." value="{{ request('search') }}">
            <button type="submit">Search</button>
        </form>

        <div class="filter-buttons">
            <a href="{{ route('styluxe.items.index-public', ['filter'=>'available']) }}" class="filter-btn">Available</a>
            <a href="{{ route('styluxe.items.index-public', ['filter'=>'low stock']) }}" class="filter-btn">Low Stock</a>
            <a href="{{ route('styluxe.items.index-public', ['filter'=>'sold out']) }}" class="filter-btn">Sold Out</a>
            <a href="{{ route('styluxe.items.index-public') }}" class="filter-btn">All</a>
        </div>
    </div>

    <!-- ITEMS TABLE -->
    <div class="items-table-wrapper">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Barcode</th>
                    <th>Status</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td><img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->item_name }}" class="table-img"></td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->barcode }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->price,2) }}</td>
                    <td>{{ $item->category }}</td>
                    <td class="actions-td">
                        <a href="{{ route('styluxe.items.show',$item->barcode) }}" title="View">
                            <img src="{{ asset('storage/icons/view.png') }}" class="action-icon">
                        </a>

                        @if(auth()->check() && in_array(auth()->user()->role,['admin','manager','staff']))
                            <a href="{{ route('styluxe.items.edit',$item->barcode) }}" title="Edit">
                                <img src="{{ asset('storage/icons/edit.png') }}" class="action-icon">
                            </a>
                            <form action="{{ route('styluxe.items.destroy',$item->barcode) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="icon-btn" title="Delete">
                                    <img src="{{ asset('storage/icons/delete.png') }}" class="action-icon">
                                </button>
                            </form>
                        @elseif(auth()->check() && auth()->user()->role == 'client')
                            <!-- Client actions -->
                            <form action="{{ route('cart.add',$item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="icon-btn" title="Add to Cart">
                                    <img src="{{ asset('storage/icons/cart.png') }}" class="action-icon">
                                </button>
                            </form>
                            <form action="{{ route('wishlist.add',$item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="icon-btn" title="Add to Wishlist">
                                    <img src="{{ asset('storage/icons/wishlist.png') }}" class="action-icon">
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">No items found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
