@extends('styluxe.layouts.dashboard')

@section('title', 'New Order')

@section('content')
<div class="container py-5">
    <div class="page-header mb-4">
        <h1>🛒 Create New Order</h1>
        <p class="text-muted">Select items and place your order</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('styluxe.orders.store') }}" method="POST" id="orderForm">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Items Selection -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Select Items</h5>
                    </div>
                    <div class="card-body">
                        <div id="orderItems">
                            <!-- Items will be added here -->
                        </div>
                        <button type="button" class="btn btn-outline-primary" onclick="addOrderItem()">
                            ➕ Add Item
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Total Items:</strong>
                            <span id="totalItems" class="float-end">0</span>
                        </div>
                        <div class="mb-3">
                            <strong>Total Amount:</strong>
                            <span id="totalAmount" class="float-end text-primary fs-5">₱0.00</span>
                        </div>
                        <hr>
                        
                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select">
                                <option value="">Select payment method</option>
                                <option value="Cash">Cash</option>
                                <option value="GCash">GCash</option>
                                <option value="">No other options available yet. 🙇🏽‍♀️</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="Add any special instructions...">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="submitBtn" disabled>
                            Place Order
                        </button>
                        <a href="{{ route('styluxe.orders.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemCounter = 0;
const products = @json($items);

function addOrderItem() {
    itemCounter++;
    const itemHtml = `
        <div class="order-item mb-3 p-3 border rounded" id="item-${itemCounter}">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Product</label>
                    <select name="items[${itemCounter}][barcode]" class="form-select product-select" 
                            onchange="updateItemPrice(${itemCounter})" required>
                        <option value="">Select product...</option>
                        ${products.map(p => `
                            <option value="${p.barcode}" 
                                    data-price="${p.price}" 
                                    data-stock="${p.quantity}">
                                ${p.item_name} - ₱${parseFloat(p.price).toFixed(2)} (Stock: ${p.quantity})
                            </option>
                        `).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="items[${itemCounter}][quantity]" 
                           class="form-control quantity-input" 
                           min="1" value="1" 
                           onchange="updateItemPrice(${itemCounter})" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Subtotal</label>
                    <div class="fw-bold" id="subtotal-${itemCounter}">₱0.00</div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${itemCounter})">
                        🗑️
                    </button>
                </div>
            </div>
        </div>
    `;
    document.getElementById('orderItems').insertAdjacentHTML('beforeend', itemHtml);
    updateOrderSummary();
}

function removeItem(id) {
    document.getElementById(`item-${id}`).remove();
    updateOrderSummary();
}

function updateItemPrice(id) {
    const item = document.getElementById(`item-${id}`);
    const select = item.querySelector('.product-select');
    const quantityInput = item.querySelector('.quantity-input');
    const subtotalDiv = document.getElementById(`subtotal-${id}`);
    
    const selectedOption = select.options[select.selectedIndex];
    const price = parseFloat(selectedOption.dataset.price || 0);
    const stock = parseInt(selectedOption.dataset.stock || 0);
    const quantity = parseInt(quantityInput.value || 0);
    
    // Update max quantity
    quantityInput.max = stock;
    if (quantity > stock) {
        quantityInput.value = stock;
    }
    
    const subtotal = price * quantity;
    subtotalDiv.textContent = `₱${subtotal.toFixed(2)}`;
    
    updateOrderSummary();
}

function updateOrderSummary() {
    const items = document.querySelectorAll('.order-item');
    let totalItems = 0;
    let totalAmount = 0;
    
    items.forEach(item => {
        const select = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        
        if (select.value && quantityInput.value) {
            const price = parseFloat(select.options[select.selectedIndex].dataset.price || 0);
            const quantity = parseInt(quantityInput.value || 0);
            totalItems += quantity;
            totalAmount += price * quantity;
        }
    });
    
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalAmount').textContent = `₱${totalAmount.toFixed(2)}`;
    document.getElementById('submitBtn').disabled = totalItems === 0;
}

// Add first item on load
addOrderItem();
</script>
@endpush
@endsection