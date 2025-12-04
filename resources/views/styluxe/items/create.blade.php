@extends('styluxe.layouts.main')

@section('content')
<div class="dashboard-container">

    <h1 class="page-title">➕ Add New Item</h1>

    <form action="{{ route('styluxe.items.store') }}" method="POST" enctype="multipart/form-data" class="form-card mt-4">
        @csrf
        
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image_path" accept="image/*" required>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="item_name" value="{{ old('item_name') }}" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" required>
                <option value="Top">Top</option>
                <option value="Bottom">Bottom</option>
                <option value="Outerwear">Outerwear</option>
                <option value="Dress">Dress</option>
                <option value="Jumpsuits">Jumpsuits</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Size</label>
            <select name="size" required>
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <!-- option mag add ug OTHER -->
            </select>
        </div>

        <div class="form-group">
            <label>Color</label>
            <input type="text" name="color" value="{{ old('color') }}" required>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="{{ old('quantity',1) }}" min="0" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="Available">Available</option>
                <option value="Sold Out">Sold Out</option>
                <option value="Out-Of-Stock">Out-Of-Stock</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn mt-2">Save Item</button>
    </form>

</div>
@endsection
