@extends('styluxe.layouts.dashboard')

@section('title', 'Order Management - Styluxe')

@section('content')
<div class="order-management-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">📦 Order Management</h1>
            <p class="text-muted">View and manage all customer orders</p>
        </div>
    </div>

    {{-- alerts displayed in layout --}}

    <!-- Order Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <h3>{{ $orders->total() }}</h3>
                <p>Total Orders</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-info">
                <h3>{{ $orders->where('order_status', 'pending')->count() }}</h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔄</div>
            <div class="stat-info">
                <h3>{{ $orders->where('order_status', 'processing')->count() }}</h3>
                <p>Processing</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3>{{ $orders->where('order_status', 'completed')->count() }}</h3>
                <p>Completed</p>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    @if($orders->count() > 0)
    <div class="items-table-wrapper">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Client</th>
                    <th>Items</th>
                    <th>Total Amount</th>
                    <th>Order Status</th>
                    <th>Payment Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <strong>{{ $order->order_number }}</strong>
                    </td>
                    <td>
                        <div class="client-cell">
                            @if($order->client && $order->client->avatar)
                                <img src="{{ Storage::url($order->client->avatar) }}" 
                                     alt="{{ $order->client->name }}"
                                     class="client-avatar-sm">
                            @else
                                <div class="client-avatar-sm-placeholder">
                                    {{ $order->client ? strtoupper(substr($order->client->name, 0, 2)) : '?' }}
                                </div>
                            @endif
                            <span>{{ $order->client ? $order->client->name : 'Unknown' }}</span>
                        </div>
                    </td>
                    <td>{{ $order->items->count() }} item(s)</td>
                    <td><strong>{{ $order->formattedTotal() }}</strong></td>
                    <td>
                        <span class="role-badge {{ $order->getStatusBadgeClass() }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="role-badge {{ $order->getPaymentStatusBadgeClass() }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        {{ $order->created_at->format('M d, Y') }}
                        <br>
                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                    </td>
                    <td class="actions-td">
                        <a href="{{ route('styluxe.order-management.show', $order->id) }}" 
                           class="btn btn-sm btn-primary"
                           title="View Details">
                            👁️ View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <p class="empty-icon">📦</p>
        <h3>No Orders Yet</h3>
        <p>No customer orders have been placed yet.</p>
    </div>
    @endif
</div>

<style>
.order-management-wrapper {
    padding: var(--space-6);
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: var(--space-6);
}

.page-title {
    font-size: var(--fs-3xl);
    font-weight: 800;
    color: var(--primary-violet);
    margin: 0 0 var(--space-2);
    text-shadow: 2px 2px 0px rgba(253, 141, 164, 0.3);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-4);
    margin-bottom: var(--space-6);
}

.stat-card {
    display: flex;
    align-items: center;
    gap: var(--space-6);
    padding: var(--space-6) var(--space-8);
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 3px solid var(--primary-violet);
    box-shadow: var(--shadow-cartoonish);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    font-size: 3rem;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-coral) 100%);
    border-radius: var(--radius-md);
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}

.stat-info {
    flex: 1;
}

.stat-info h3 {
    font-size: var(--fs-4xl);
    font-weight: 800;
    color: var(--text-dark);
    margin: 0 0 var(--space-2);
    line-height: 1;
}

.stat-info p {
    color: var(--text-muted);
    margin: 0;
    font-size: var(--fs-base);
    font-weight: 600;
}

.items-table-wrapper {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 3px solid var(--primary-violet);
    box-shadow: var(--shadow-cartoonish);
    overflow: hidden;
    margin: var(--space-6) 0;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
}

.items-table th {
    padding: var(--space-6) var(--space-4);
    text-align: left;
    font-weight: 700;
    font-size: var(--fs-sm);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.items-table td {
    padding: var(--space-6) var(--space-4);
    vertical-align: middle;
    border-bottom: 2px solid var(--bg-variant);
}

.items-table tbody tr:last-child td {
    border-bottom: none;
}

.items-table tbody tr:hover {
    background-color: var(--bg-variant);
}

.client-cell {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.client-avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--primary-violet);
    flex-shrink: 0;
}

.client-avatar-sm-placeholder {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-coral) 100%);
    color: var(--text-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.7rem;
    flex-shrink: 0;
}



.actions-td {
    white-space: nowrap;
}

.empty-state {
    text-align: center;
    padding: var(--space-8) var(--space-6);
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 3px dashed var(--bg-variant);
    margin: var(--space-6) 0;
}

.empty-icon {
    font-size: 6rem;
    margin-bottom: var(--space-6);
    opacity: 0.3;
}

.empty-state h3 {
    font-size: var(--fs-2xl);
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: var(--space-3);
}

.empty-state p {
    color: var(--text-muted);
    font-size: var(--fs-lg);
    margin-bottom: var(--space-6);
}

.pagination-wrapper {
    margin-top: var(--space-6);
    display: flex;
    justify-content: center;
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
    float: right;
    background: transparent;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    opacity: 0.5;
}

.btn-close:hover {
    opacity: 1;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .items-table {
        font-size: var(--fs-sm);
    }

    .items-table th,
    .items-table td {
        padding: var(--space-3) var(--space-2);
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