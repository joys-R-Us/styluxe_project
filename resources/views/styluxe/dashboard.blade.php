@extends('styluxe.layouts.main')

@section('content')

<div class="main-content">

    <!-- FILTER + SEARCH + ADD ITEM -->
    <div class="top-controls">
        <!-- Dashboard Title -->
        <div class="dashboard-title">
            @auth
            <h2>Dashboard</h2> 
            @endauth
        </div>
        
        <!-- Search -->
        <form action="{{ route('styluxe.items.index-public') }}" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search items..." value="{{ request('search') }}">
            <button class="btn" type="submit">Search</button>
        </form>

        <!-- Filters 
        <div class="filter-buttons">
            <a href="{{ route('styluxe.items.index-public', ['filter' => 'available']) }}" class="filter-btn">Available</a>
            <a href="{{ route('styluxe.items.index-public', ['filter' => 'low stock']) }}" class="filter-btn">Low Stock</a>
            <a href="{{ route('styluxe.items.index-public') }}" class="filter-btn">All</a>
            <a href="{{ route('styluxe.items.index-public', ['filter' => 'sold out']) }}" class="filter-btn">Sold Out</a>
        </div> -->

        <!-- Add Item Button -->
        <div class="add-item-section">
            @if(in_array(auth()->user()->role,['admin','manager','staff','supplier']))
            <a href="{{ route('styluxe.items.create') }}" class="add-item-btn">➕ Add Item</a>
            @endif
        </div>
    </div>

    <!-- Bubble Stats -->
    <div href="{{ route('styluxe.items.index-public') }}" class="bubble-grid">
        <div class="bubble-card">
            <span class="emoji">🧺</span>
            <h3>Total Items</h3>
            <p>{{ $items->count() }}</p>
        </div>

        <div href="{{ route('styluxe.items.index-public', ['filter' => 'available']) }}" class="bubble-card">
            <span class="emoji">✨</span>
            <h3>Available</h3>
            <p>{{ $items->where('status','Available')->count() }}</p>
        </div>

        <div href="{{ route('styluxe.items.index-public', ['filter' => 'low stock']) }}" class="bubble-card">
            <span class="emoji">⚠️</span>
            <h3>Low Stock</h3>
            <p>{{ $items->where('quantity','<=',5)->count() }}</p>
        </div>

        <div href="{{ route('styluxe.items.index-public', ['filter' => 'sold out']) }}" class="bubble-card">
            <span class="emoji">❌</span>
            <h3>Sold Out</h3>
            <p>{{ $items->where('status','Sold Out')->count() }}</p>
        </div>
    </div>

    <!-- ITEMS GRID -->
    <h2 class="section-title">Your Items</h2>

    <div class="items-grid">
        @foreach($items as $item)
        <a href="{{ route('styluxe.items.show', $item->barcode) }}" class="item-card">
            <div class="item-thumb">
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="">
            </div>
            <div class="item-info">
                <h3>{{ $item->item_name }}</h3>
                <p class="item-status">Status: {{ $item->status}}</p>
                <p class="item-stock">Stock: {{ $item->quantity }}</p>
                <p class="item-price">₱{{ number_format($item->price, 2) }}</p>
            </div>

            <div class="view-btn">View</div>
        </a>
        @endforeach
    </div>

    
</div>

<!-- Cute Banner -->
    <div class="cute-banner">
        <img src="storage/app/public/images/clothes_banner.png" alt="clothes art">
        <p>Keep your inventory stylish and up-to-date! 🎀🛍️</p>
    </div>

@endsection
