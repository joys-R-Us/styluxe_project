@extends('styluxe.layouts.dashboard')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>📦 My Orders</h1>
                <p class="text-muted">Track and manage your orders</p>
            </div>
            <a href="{{ route('styluxe.orders.create') }}" class="btn btn-primary">
                ➕ New Order
            </a>
        </div>
    </div>

    {{-- alerts displayed in layout --}}

    <!-- Orders List -->
    <div class="orders-list">
        @forelse($orders as $order)
        <div class="card mb-3 order-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h5 class="mb-1">{{ $order->order_number }}</h5>
                        <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                    </div>
                    <div class="col-md-2">
                        <span class="badge {{ $order->getStatusBadgeClass() }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                    <div class="col-md-2">
                        <span class="badge {{ $order->getPaymentStatusBadgeClass() }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="col-md-2">
                        <strong>{{ $order->formattedTotal() }}</strong>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('styluxe.orders.show', $order->id) }}" 
                           class="btn btn-sm btn-outline-primary">
                            👁️ View Details
                        </a>
                    </div>
                </div>
                
                <!-- Order Items Preview -->
                <div class="mt-3">
                    <small class="text-muted">
                        {{ $order->items->count() }} item(s): 
                        {{ $order->items->pluck('product.item_name')->take(3)->join(', ') }}
                        @if($order->items->count() > 3)
                            and {{ $order->items->count() - 3 }} more...
                        @endif
                    </small>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <div class="mb-4">
                <img src="{{ asset('storage/images/empty_orders.png') }}" 
                     alt="No orders" 
                     style="max-width: 200px; opacity: 0.5;">
            </div>
            <h4>No orders yet! 🛍️</h4>
            <p class="text-muted">Start shopping and place your first order!</p>
            <a href="{{ route('styluxe.orders.create') }}" class="btn btn-primary mt-3">
                Place Your First Order
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection