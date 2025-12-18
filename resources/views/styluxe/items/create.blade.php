@extends('styluxe.layouts.dashboard')

@section('title', 'Add New Item - Styluxe')

@section('content')
<div class="form-container-padded">
    <div class="form-header">
        <h1 class="page-title">➕ Add New Item</h1>
    </div>
    
    <form action="{{ route('styluxe.items.store') }}" method="POST" enctype="multipart/form-data" class="styluxe-form">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label for="item_name">Item Name *</label>
                <input type="text" id="item_name" name="item_name" value="{{ old('item_name') }}" required>
                @error('item_name')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Tops" {{ old('category') == 'Tops' ? 'selected' : '' }}>👚 Tops</option>
                    <option value="Bottoms" {{ old('category') == 'Bottoms' ? 'selected' : '' }}>👖 Bottoms</option>
                    <option value="Dresses" {{ old('category') == 'Dresses' ? 'selected' : '' }}>👗 Dresses</option>
                    <option value="Outerwear" {{ old('category') == 'Outerwear' ? 'selected' : '' }}>🧥 Outerwear</option>
                    <option value="Jumpsuits" {{ old('category') == 'Jumpsuits' ? 'selected' : '' }}>🩱 Jumpsuits</option>
                    <option value="Shoes" {{ old('category') == 'Shoes' ? 'selected' : '' }}>👟 Shoes</option>
                    <option value="Accessories" {{ old('category') == 'Accessories' ? 'selected' : '' }}>👜 Accessories</option>
                </select>
                @error('category')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="size">Size *</label>
                <input type="text" id="size" name="size" value="{{ old('size') }}" placeholder="e.g., M, L, 8, 10" required>
                @error('size')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="color">Color</label>
                <input type="text" id="color" name="color" value="{{ old('color') }}" placeholder="e.g., Blue, Red">
                @error('color')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="condition">Condition *</label>
                <select id="condition" name="condition" required>
                    <option value="">Select Condition</option>
                    <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
                    <option value="Pre-Loved" {{ old('condition') == 'Pre-Loved' ? 'selected' : '' }}>Pre-Loved</option>
                    <option value="Vintage" {{ old('condition') == 'Vintage' ? 'selected' : '' }}>Vintage</option>
                    <option value="Branded" {{ old('condition') == 'Branded' ? 'selected' : '' }}>Branded</option>
                </select>
                @error('condition')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity *</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 0) }}" min="0" required>
                @error('quantity')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="price">Price (₱) *</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                @error('price')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Out-Of-Stock" {{ old('status') == 'Out-Of-Stock' ? 'selected' : '' }}>Out-Of-Stock</option>
                    <option value="Sold Out" {{ old('status') == 'Sold Out' ? 'selected' : '' }}>Sold Out</option>
                </select>
                @error('status')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Describe the item...">{{ old('description') }}</textarea>
            @error('description')
            <span class="error-text">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="image_path">Product Image</label>
            <input type="file" id="image_path" name="image_path" accept="image/*">
            <img id="imagePreview" style="display:none; max-width: 300px; margin-top: 10px; border-radius: 8px;">
            @error('image_path')
            <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="low_stock_threshold">Low Stock Threshold</label>
                <input type="number" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 10) }}" min="0">
                <small class="form-text">Mark as low stock at this level</small>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Save Item</button>
            <a href="{{ route('styluxe.items.index-public') }}" class="btn cancel-btn">Cancel</a>
        </div>
    </form>
</div>
@endsection