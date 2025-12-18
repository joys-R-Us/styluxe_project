@extends('styluxe.layouts.dashboard')

@section('title', 'Edit ' . $item->item_name . ' - Styluxe')

@section('content')
<div class="form-container-padded">
    <div class="form-header">
        <h1 class="page-title">✏️ Edit Item</h1>
        <a href="{{ route('styluxe.items.index-public') }}" class="btn cancel-btn">← Back to Items</a>
    </div>
    
    <form action="{{ route('styluxe.items.update', $item->barcode) }}" method="POST" enctype="multipart/form-data" class="styluxe-form">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label for="item_name">Item Name *</label>
                <input type="text" id="item_name" name="item_name" value="{{ old('item_name', $item->item_name) }}" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Category *</label>
                <select id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="size">Size *</label>
                <input type="text" id="size" name="size" value="{{ old('size', $item->size) }}" required>
            </div>
            
            <div class="form-group">
                <label for="color">Color</label>
                <input type="text" id="color" name="color" value="{{ old('color', $item->color) }}">
            </div>
            
            <div class="form-group">
                <label for="condition">Condition *</label>
                <select id="condition" name="condition" required>
                    <option value="New" {{ $item->condition == 'New' ? 'selected' : '' }}>New</option>
                    <option value="Pre-Loved" {{ $item->condition == 'Pre-Loved' ? 'selected' : '' }}>Pre-Loved</option>
                    <option value="Vintage" {{ $item->condition == 'Vintage' ? 'selected' : '' }}>Vintage</option>
                    <option value="Branded" {{ $item->condition == 'Branded' ? 'selected' : '' }}>Branded</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity *</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="price">Price (₱) *</label>
                <input type="number" id="price" name="price" value="{{ old('price', $item->price) }}" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="Available" {{ $item->status == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Out-Of-Stock" {{ $item->status == 'Out-Of-Stock' ? 'selected' : '' }}>Out-Of-Stock</option>
                    <option value="Sold Out" {{ $item->status == 'Sold Out' ? 'selected' : '' }}>Sold Out</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $item->description) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="image_path">Product Image</label>
            @if($item->image_path)
            <div class="current-image">
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->item_name }}" style="max-width: 200px; border-radius: 8px; margin-bottom: 10px;">
                <p><small>Current Image</small></p>
            </div>
            @endif
            <input type="file" id="image_path" name="image_path" accept="image/*">
            <img id="imagePreview" style="display:none; max-width: 300px; margin-top: 10px; border-radius: 8px;">
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="low_stock_threshold">Low Stock Threshold</label>
                <input type="number" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $item->low_stock_threshold) }}" min="0">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Update Item</button>
            <a href="{{ route('styluxe.items.show', $item->barcode) }}" class="btn cancel-btn">Cancel</a>
        </div>
    </form>
</div>
@endsection