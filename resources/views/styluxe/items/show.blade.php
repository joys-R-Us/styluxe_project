@extends('styluxe.layouts.dashboard')

@section('title', $item->item_name . ' - Styluxe')

@section('content')
<div class="item-show-wrapper">
    <div class="item-show-header">
        <h1 class="page-title">{{ $item->item_name }}</h1>

        @auth
            @if(auth()->user()->role === 'client')
                @if($item->status === 'Available' && $item->quantity > 0)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addToOrderModal">
                        ➕ Add to Order
                    </button>
                @else
                    <button type="button" class="btn btn-secondary" disabled>
                        Out of Stock
                    </button>
                @endif
            @endif
        @endauth
    </div>

    <div class="item-show-grid">
        {{-- Image --}}
        <div class="item-show-image">
            @if($item->image_path)
            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->item_name }}">
            @else
            <div class="no-image-large">📷 No Image</div>
            @endif
        </div>

        {{-- Details --}}
        <div class="item-show-details">
            <div class="detail-group">
                <label>Barcode</label>
                <p><code>{{ $item->barcode }}</code></p>
            </div>

            <div class="detail-group">
                <label>Status</label>
                <p>
                    <span class="role-badge {{ $item->getStockStatusClass() }}">
                        {{ $item->status }}
                    </span>
                </p>
            </div>

            <div class="detail-group">
                <label>Category</label>
                <p>{{ $item->category->name ?? 'Uncategorized' }}</p>
            </div>

            <div class="detail-group">
                <label>Size / Color</label>
                <p>{{ $item->size }} 
                    @if($item->color)/ {{ $item->color }}
                    @endif</p>
            </div>

            <div class="detail-group">
                <label>Condition</label>
                <p>{{ $item->condition }}</p>
            </div>

            <div class="detail-group">
                <label>Price</label>
                <p class="item-price-large">{{ $item->formattedPrice() }}</p>
            </div>

            <div class="detail-group">
                <label>Stock Quantity</label>
                <p><strong>{{ $item->quantity }}</strong> units</p>
            </div>

            <div class="detail-group">
                <label>Low Stock Threshold</label>
                <p>{{ $item->low_stock_threshold }} units</p>
            </div>

            {{-- reorder_level removed --}}

            @if($item->description)
            <div class="detail-group">
                <label>Description</label>
                <p>{{ $item->description }}</p>
            </div>
            @endif

            @if($item->addedBy)
            <div class="detail-group">
                <label>Added By</label>
                <p>{{ $item->addedBy->name }} ({{ ucfirst($item->addedBy->role) }})</p>
            </div>
            @endif

            <div class="detail-group">
                <label>Last Updated</label>
                <p>{{ $item->updated_at->format('M d, Y H:i') }}</p>
            </div>

            {{-- Actions --}}
            @if(Auth::user()->canManageInventory())
            <div class="item-actions">
                @if(!empty($item->barcode))
                <a href="{{ route('styluxe.items.edit', $item->barcode) }}" class="btn btn-primary">✏️ Edit</a>
                @endif
                
                @if(Auth::user()->isAdmin())
                <form action="{{ route('styluxe.items.destroy', $item->barcode) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                </form>
                @endif

                <a href="{{ route('styluxe.items.index-public') }}" class="btn back-btn">← Back to Items</a>
            </div>
            @endif
        </div>
    </div>

    {{-- Stock History --}}
    @if($stockHistory && $stockHistory->count() > 0)
    <section class="mt-6">
        <h2 class="section-title">📜 Stock History</h2>
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Change</th>
                        <th>Previous</th>
                        <th>New</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockHistory as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i') }}</td>
                        <td>
                            <span class="role-badge">{{ ucfirst($log->change_type) }}</span>
                        </td>
                        <td>{{ $log->quantity_change }}</td>
                        <td>{{ $log->previous_quantity }}</td>
                        <td>{{ $log->new_quantity }}</td>
                        <td>{{ $log->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif
</div>
<!-- Add to Order Modal -->
<div class="modal fade" id="addToOrderModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Add to Order</h5>
                <button type="button" class="btn-close" onclick="closeOrderModal()" aria-label="Close"></button>
            </div>
            <form action="{{ route('styluxe.orders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="product-summary mb-4">
                        <div class="d-flex align-items-center gap-3">
                            @if($item->image_path)
                            <img src="{{ Storage::url($item->image_path) }}" 
                                 alt="{{ $item->item_name }}"
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $item->item_name }}</h6>
                                <p class="text-muted mb-0">{{ $item->formattedPrice() }}</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="items[0][barcode]" value="{{ $item->barcode }}">
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" 
                               class="form-control" 
                               id="quantity" 
                               name="items[0][quantity]" 
                               min="1" 
                               max="{{ $item->quantity }}" 
                               value="1" 
                               required
                               onchange="updateTotal()">
                        <small class="text-muted">Available: {{ $item->quantity }} units</small>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-select">
                            <option value="">Select payment method</option>
                            <option value="Cash">Cash</option>
                            <option value="GCash">GCash</option>
                            <option value="">No other options available yet. 🙇🏽‍♀️</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" 
                                  id="notes" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="Add any special instructions..."></textarea>
                    </div>

                    <div class="order-total-preview p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Total Amount:</strong>
                            <span class="fs-4 text-primary" id="totalAmount">{{ $item->formattedPrice() }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeOrderModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Backdrop -->
<div class="modal-backdrop fade" id="modalBackdrop" style="display: none;"></div>

<style>
    /* Order Cards */
.order-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-left-color: var(--primary-color, #ff69b4);
}

/* Timeline Styles */
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    padding-bottom: 30px;
    color: #999;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 30px;
    bottom: -10px;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #e0e0e0;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e0e0e0;
}

.timeline-item.active {
    color: #28a745;
}

.timeline-item.active .timeline-marker {
    background: #28a745;
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-item.completed {
    color: #666;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    box-shadow: 0 0 0 2px #28a745;
}

/* Order Form */
.order-item {
    background: #f8f9fa;
    transition: all 0.2s ease;
}

.order-item:hover {
    background: #e9ecef;
}

/* Badges */
.badge {
    padding: 0.5em 1em;
    font-weight: 600;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

.badge-info {
    background-color: #17a2b8;
    color: #fff;
}

.badge-success {
    background-color: #28a745;
    color: #fff;
}

.badge-danger {
    background-color: #dc3545;
    color: #fff;
}
</style>

@push('scripts')
<script>
const itemPrice = {{ floatval($item->price) }};

function updateTotal() {
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    const total = quantity * itemPrice;
    
    document.getElementById('totalAmount').textContent = '₱' + total.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function openOrderModal() {
    const modal = document.getElementById('addToOrderModal');
    const backdrop = document.getElementById('modalBackdrop');
    
    if (!modal || !backdrop) return;
    
    modal.style.display = 'block';
    backdrop.style.display = 'block';
    
    // Trigger animations
    setTimeout(() => {
        modal.classList.add('show');
        backdrop.classList.add('show');
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }, 10);
    
    updateTotal();
}

function closeOrderModal() {
    const modal = document.getElementById('addToOrderModal');
    const backdrop = document.getElementById('modalBackdrop');
    
    if (!modal || !backdrop) return;
    
    modal.classList.remove('show');
    backdrop.classList.remove('show');
    
    setTimeout(() => {
        modal.style.display = 'none';
        backdrop.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }, 300);
}

// Close modal when clicking backdrop
const backdrop = document.getElementById('modalBackdrop');
if (backdrop) {
    backdrop.addEventListener('click', closeOrderModal);
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeOrderModal();
    }
});

// Initialize total on page load
document.addEventListener('DOMContentLoaded', function() {
    updateTotal();
});
</script>
@endpush
@endsection