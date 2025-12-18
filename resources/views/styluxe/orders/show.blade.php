@extends('styluxe.layouts.dashboard')

@section('title', 'Order Details')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('styluxe.orders.index') }}" class="btn btn-outline-secondary">
            ← Back to My Orders
        </a>
    </div>

    {{-- alerts displayed in layout --}}

    <div class="row">
        <!-- Order Info -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header text-white">
                    <h4 class="mb-0">📦 Order {{ $order->order_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Date:</strong><br>
                            {{ $order->created_at->format('F d, Y h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            <span class="badge {{ $order->getStatusBadgeClass() }}" style="color: #3a3a3aff;">
                                {{ ucfirst($order->order_status) }}
                            </span>
                            <span class="badge {{ $order->getPaymentStatusBadgeClass() }}" style="color: #3a3a3aff;">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    @if($order->payment_method)
                    <div class="mb-3">
                        <strong>💳 Payment Method:</strong><br>
                        {{ $order->payment_method }}
                    </div>
                    @endif

                    @if($order->notes)
                    <div class="mb-3">
                        <strong>📝 Notes:</strong><br>
                        <div class="p-3 bg-light rounded">
                            {{ $order->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🛍️ Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image_path)
                                            <img src="{{ Storage::url($item->product->image_path) }}" 
                                                 alt="{{ $item->product->item_name }}" 
                                                 class="rounded me-3"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->item_name ?? 'Product unavailable' }}</strong>
                                                @if($item->product)
                                                    <br>
                                                    <small class="text-muted">{{ $item->product->barcode }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>₱{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>₱{{ number_format($item->subtotal, 2) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong class="fs-5 text-primary">{{ $order->formattedTotal() }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Order Timeline -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">📊 Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $order->order_status == 'pending' ? 'active' : 'completed' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Pending</strong>
                                <br><small class="text-muted">Order placed</small>
                            </div>
                        </div>
                        <div class="timeline-item {{ $order->order_status == 'processing' ? 'active' : ($order->order_status == 'completed' ? 'completed' : '') }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Processing</strong>
                                <br><small class="text-muted">Preparing items</small>
                            </div>
                        </div>
                        <div class="timeline-item {{ $order->order_status == 'completed' ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Completed</strong>
                                <br><small class="text-muted">Order delivered</small>
                            </div>
                        </div>
                        @if($order->order_status == 'cancelled')
                        <div class="timeline-item active text-danger">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <strong>Cancelled</strong>
                                <br><small>Order was cancelled</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">💰 Payment Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Payment Status:</strong><br>
                        <span class="badge {{ $order->getPaymentStatusBadgeClass() }}" style="color: #3a3a3aff;">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Total Amount:</strong><br>
                        <span class="fs-4 text-primary">{{ $order->formattedTotal() }}</span>
                    </div>
                    @if($order->payment_method)
                    <div>
                        <strong>Payment Method:</strong><br>
                        {{ $order->payment_method }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection