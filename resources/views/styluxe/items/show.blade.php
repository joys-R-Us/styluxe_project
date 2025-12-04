@extends('styluxe.layouts.main')
@section('content')

<h1 class="page-title">👕 {{$item->item_name }}</h1>

<div class="item-show-wrapper">
    <div class="item-show-left">
        <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->item_name }}">
    </div>

    <div class="item-show-right">
        <p><strong>Barcode:</strong> {{ $item->barcode }}</p>
        <p><strong>Status:</strong> {{ $item->status }}</p>
        <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
        <p><strong>Price:</strong> ₱{{ number_format($item->price,2) }}</p>
        <p><strong>Category:</strong> {{ $item->category }}</p>
        <p><strong>Description:</strong> {{ $item->description }}</p>

        @if(in_array(auth()->user()->role,['admin','manager','staff','supplier']))
        <div class="item-action-btns">
            <a href="{{ route('styluxe.items.edit',$item->barcode) }}" class="btn">Edit</a>
            <form action="{{ route('styluxe.items.destroy',$item->barcode) }}" method="POST" >
                @csrf
                @method('DELETE')
                <button class="btn delete-btn">Delete</button>
            </form>
            <a href="{{ route('styluxe.items.index-public') }}" class="btn cancel-btn">Back to Items</a>
        </div>
        @endif
    </div>
</div>

@endsection
