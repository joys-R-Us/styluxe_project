@extends('styluxe.layouts.main')

@section('content')
<div class="dashboard-container">

    <h1 class="page-title">🛍️ Manage Items</h1>
    <p class="dash-subtitle">All your clothes at a glance!</p>

    <div class="action-buttons mt-4" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('styluxe.items.create') }}" class="btn">➕ Add New Item</a>
    </div>

    <div class="clothes-grid mt-6">
        @forelse($items as $item)
        <div class="clothes-card">
            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->item_name }}">
            <div class="info">
                <div class="name">{{ $item->item_name }}</div>
                <div class="status">{{ $item->status }} | Qty: {{ $item->quantity }}</div>
                <div class="mt-2 flex-gap">
                    <a href="{{ route('styluxe.items.show', $item->barcode) }}" class="btn">View</a>
                    @if(!empty($item->barcode))
                    <a href="{{ route('styluxe.items.edit', $item->barcode) }}" class="btn btn-secondary">Edit</a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p>No items found! Add some clothes to your inventory.</p>
        @endforelse
    </div>

</div>
@endsection
