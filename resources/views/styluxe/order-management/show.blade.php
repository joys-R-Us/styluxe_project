@extends('styluxe.layouts.dashboard')

@section('title', 'Order Details - Styluxe')

@section('content')
<div class="order-detail-wrapper">
    <div class="page-header">
        <div>
            <a href="{{ route('styluxe.order-management.index') }}" class="btn btn-outline-secondary mb-3">
                ← Back to Orders
            </a>
            <h1 class="page-title">📦 Order #{{ $order->order_number }}</h1>
        </div>
        
        @if($order->canBeCancelled())
        <div class="action-buttons">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                ✏️ Update Status
            </button>
        </div>
        @endif
    </div>

    {{-- alerts displayed in layout --}}

    <div class="order-detail-grid">
        <!-- Order Information -->
        <div class="detail-card">
            <div class="card-header">
                <h3>📄 Order Information</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Order Number:</span>
                    <span class="info-value">
                        <strong>{{ $order->order_number }}</strong>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Order Status:</span>
                    <span class="info-value">
                        <span class="role-badge {{ $order->getStatusBadgeClass() }}" style="color: #000;">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Payment Status:</span>
                    <span class="info-value">
                        <span class="role-badge {{ $order->getPaymentStatusBadgeClass() }}" style="color: #000;">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Order Date:</span>
                    <span class="info-value">{{ $order->created_at->format('F d, Y h:i A') }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">{{ $order->updated_at->diffForHumans() }}</span>
                </div>

                @if($order->payment_method)
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">{{ $order->payment_method }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Client Information -->
        <div class="detail-card">
            <div class="card-header">
                <h3>👤 Client Information</h3>
            </div>
            <div class="card-body">
                <div class="client-info">
                    @if($order->client)
                        @if($order->client->avatar)
                            <img src="{{ Storage::url($order->client->avatar) }}" 
                                 alt="{{ $order->client->name }}"
                                 class="client-avatar">
                        @else
                            <div class="client-avatar-placeholder">
                                {{ strtoupper(substr($order->client->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <h4>{{ $order->client->name }}</h4>
                            <p class="text-muted">{{ $order->client->email }}</p>
                            @if($order->client->phone)
                                <p class="text-muted">📞 {{ $order->client->phone }}</p>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">Client information not available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="detail-card full-width">
            <div class="card-header">
                <h3>🛍️ Order Items</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="order-items-table">
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
                                    <div class="item-cell">
                                        @if($item->product && $item->product->image_path)
                                            <img src="{{ Storage::url($item->product->image_path) }}" 
                                                 alt="{{ $item->product->item_name }}"
                                                 class="item-thumbnail">
                                        @endif
                                        <div>
                                            <strong>{{ $item->product ? $item->product->item_name : 'Product unavailable' }}</strong>
                                            @if($item->product)
                                                <br>
                                                <small class="text-muted">Barcode: {{ $item->product_barcode }}</small>
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
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                <td><strong class="total-amount">{{ $order->formattedTotal() }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
        <div class="detail-card full-width">
            <div class="card-header">
                <h3>📝 Order Notes</h3>
            </div>
            <div class="card-body">
                <div class="order-notes">
                    {{ $order->notes }}
                </div>
            </div>
        </div>
        @endif

        <!-- Order Timeline -->
        <div class="detail-card full-width">
            <div class="card-header">
                <h3>📊 Order Status Timeline</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ in_array($order->order_status, ['pending', 'processing', 'completed']) ? 'completed' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Order Placed</strong>
                            <br><small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($order->order_status, ['processing', 'completed']) ? 'completed' : ($order->order_status == 'pending' ? '' : '') }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Processing</strong>
                            <br><small class="text-muted">Preparing items</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ $order->order_status == 'completed' ? 'completed' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Completed</strong>
                            <br><small class="text-muted">Order delivered</small>
                        </div>
                    </div>
                    @if($order->order_status == 'cancelled')
                    <div class="timeline-item cancelled">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Cancelled</strong>
                            <br><small>Order was cancelled</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✏️ Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('styluxe.order-management.update-status', $order->id) }}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="order_status" class="form-label">Order Status</label>
                        <select name="order_status" id="order_status" class="form-select" required>
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="form-label">Admin Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" 
                                  placeholder="Add any notes about this status update...">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.order-detail-wrapper {
    padding: var(--space-6);
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--space-6);
    gap: var(--space-4);
    flex-wrap: wrap;
}

.page-title {
    font-size: var(--fs-3xl);
    font-weight: 800;
    color: var(--primary-violet);
    margin: 0;
    text-shadow: 2px 2px 0px rgba(253, 141, 164, 0.3);
}

.action-buttons {
    display: flex;
    gap: var(--space-3);
}

.order-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-6);
}

.detail-card {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 3px solid var(--primary-violet);
    overflow: hidden;
    box-shadow: var(--shadow-cartoonish);
    transition: all 0.3s ease;
}

.detail-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.detail-card.full-width {
    grid-column: 1 / -1;
}

.card-header {
    background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-coral) 100%);
    padding: var(--space-6) var(--space-8);
    color: white;
    border-bottom: 3px solid rgba(255, 255, 255, 0.2);
}

.card-header h3 {
    margin: 0;
    font-size: var(--fs-xl);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.card-body {
    padding: var(--space-8);
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-4) var(--space-3);
    border-bottom: 2px solid var(--bg-variant);
    gap: var(--space-4);
}

.info-row:first-child {
    padding-top: 0;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    font-weight: 600;
    color: var(--text-muted);
}

.info-value {
    font-weight: 500;
    color: var(--text-dark);
    text-align: right;
}

.client-info {
    display: flex;
    align-items: center;
    gap: var(--space-6);
    padding: var(--space-4);
    background: var(--bg-variant);
    border-radius: var(--radius-md);
}

.client-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--primary-violet);
    flex-shrink: 0;
}

.client-avatar-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-coral) 100%);
    color: var(--text-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: var(--fs-2xl);
    border: 4px solid var(--primary-violet);
    flex-shrink: 0;
}

.client-info h4 {
    margin: 0 0 var(--space-2);
    font-size: var(--fs-xl);
}

.client-info p {
    margin: var(--space-1) 0;
}

/* Order Items Table */
.table-responsive {
    overflow-x: auto;
}

.order-items-table {
    width: 100%;
    border-collapse: collapse;
}

.order-items-table thead {
    background: var(--bg-variant);
}

.order-items-table th {
    padding: var(--space-4);
    text-align: left;
    font-weight: 700;
    color: var(--text-dark);
    border-bottom: 2px solid var(--primary-violet);
}

.order-items-table td {
    padding: var(--space-4);
    border-bottom: 1px solid var(--bg-variant);
}

.order-items-table tbody tr:last-child td {
    border-bottom: none;
}

.item-cell {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.item-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    border: 2px solid var(--primary-violet);
    flex-shrink: 0;
}

.total-row {
    background: var(--bg-variant);
    font-weight: 700;
}

.total-amount {
    font-size: var(--fs-2xl);
    color: var(--primary-violet);
}

.order-notes {
    padding: var(--space-6);
    background: #fff8e6;
    border-left: 5px solid var(--warning);
    border-radius: var(--radius-md);
    color: #856404;
    font-size: var(--fs-base);
    line-height: 1.6;
}

/* Timeline */
.timeline {
    position: relative;
    padding: var(--space-4) 0;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    padding-bottom: var(--space-6);
    color: var(--text-muted);
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 30px;
    bottom: -10px;
    width: 3px;
    background: var(--bg-variant);
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--bg-variant);
    border: 4px solid var(--bg-white);
    box-shadow: 0 0 0 2px var(--bg-variant);
}

.timeline-item.completed {
    color: var(--success);
}

.timeline-item.completed .timeline-marker {
    background: var(--success);
    box-shadow: 0 0 0 2px var(--success);
}

.timeline-item.completed::before {
    background: var(--success);
}

.timeline-item.cancelled {
    color: var(--danger);
}

.timeline-item.cancelled .timeline-marker {
    background: var(--danger);
    box-shadow: 0 0 0 2px var(--danger);
}

.timeline-content strong {
    font-size: var(--fs-lg);
}

.role-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: var(--fs-sm);
    color: var(--text-light);
}

.badge-warning { background: var(--warning); color: var(--text-dark); }
.badge-success { background: var(--success); }
.badge-danger { background: var(--danger); }
.badge-info { background: var(--info); }

/* Modal */
.modal-content {
    border-radius: var(--radius-lg);
    border: 3px solid var(--primary-violet);
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-coral) 100%);
    color: white;
    border-bottom: 3px solid rgba(255, 255, 255, 0.2);
}

.modal-title {
    font-weight: 700;
}

.form-label {
    font-weight: 600;
    margin-bottom: var(--space-2);
}

.form-control,
.form-select {
    border: 2px solid var(--bg-variant);
    border-radius: var(--radius-md);
    padding: var(--space-3);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-violet);
    box-shadow: 0 0 0 3px rgba(164, 160, 255, 0.2);
}

.alert {
    padding: var(--space-4);
    border-radius: var(--radius-md);
    margin-bottom: var(--space-4);
    border: 2px solid;
}

.alert-success {
    background-color: #e6f9f7;
    border-color: var(--success);
    color: #0e5e57;
}

.alert-danger {
    background-color: #ffe6e6;
    border-color: var(--danger);
    color: #8b2e2e;
}

.btn-close {
    opacity: 0.5;
}

.btn-close:hover {
    opacity: 1;
}

@media (max-width: 1024px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
    }

    .action-buttons {
        width: 100%;
        flex-direction: column;
    }

    .action-buttons .btn {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .client-info {
        flex-direction: column;
        text-align: center;
    }

    .item-cell {
        flex-direction: column;
        text-align: center;
    }
}
</style>

@endsection

@push('scripts')
<script>
// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s';
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);
</script>
@endpush