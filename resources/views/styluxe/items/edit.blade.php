@extends('styluxe.layouts.main')

@section('content')
<div class="dashboard-container">

    <h1 class="page-title">✏️ Edit Item</h1>

    <form action="{{ route('styluxe.items.update', $item->barcode) }}" method="POST" enctype="multipart/form-data" class="form-card mt-4">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('item_name', $item->item_name) }}" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3">{{ old('description', $item->description) }}</textarea>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="0" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="Available" {{ $item->status=='Available'?'selected':'' }}>Available</option>
                <option value="Sold Out" {{ $item->status=='Sold Out'?'selected':'' }}>Sold Out</option>
            </select>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" accept="image/*">
            @if($item->image_path)
                <img src="{{ $item->path }}" alt="{{ $item->item_name }}" class="preview-img mt-2">
            @endif
        </div>
        <button type="submit" class="btn mt-2">Update Item</button>
    </form>

</div>
@endsection
