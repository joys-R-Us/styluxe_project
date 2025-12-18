@extends('styluxe.layouts.main')

@section('title', 'Welcome to Styluxe')

@section('content')

<div class="homepage-container">

    <!-- HERO SECTION -->
    <section class="hero cartoon-hero">
        <div class="hero-bg-wrapper">
            @foreach(range(1, 25) as $i)
                <img class="silhouette pattern-img" 
                    src="{{ asset('storage/images/bg_silhouettes_' . rand(1,7) . '.png') }}" 
                    alt="bg pattern" />
            @endforeach
        </div>
        
        <h1>💌 Welcome to Styluxe!</h1>
        <p>Your sustainable fashion adventure starts here. Browse thousands of curated, vintage, artsy & 
            <br> pre-loved clothing items to flaunt your clothes! 🎀</p>
        <a href="#explore" class="btn explore-btn mt-4">
            Explore Now  
            <img class="explore-icon" src="{{ asset('storage/images/explore_icon.gif') }}" alt="explore icon" />
        </a>
    </section>

    <!-- BORDER DECORATION -->
    <div class="border-decor">
        <img class="border-decor-icon" src="{{ asset('storage/images/border_decor.gif') }}" alt="border decoration" />
    </div>

    <!-- Categories -->
    <div class="home-categories">
        <a href="{{ route('styluxe.items.index-public', ['category' => 'Tops']) }}">👚 Tops</a>
        <a href="{{ route('styluxe.items.index-public', ['category' => 'Bottoms']) }}">👖 Bottoms</a>
        <a href="{{ route('styluxe.items.index-public', ['category' => 'Shoes']) }}">👟 Shoes</a>
        <a href="{{ route('styluxe.items.index-public', ['category' => 'Accessories']) }}">👜 Accessories</a>
    </div>

    <!-- New Arrivals -->
    <section id="explore" class="arrivals">
        <h2 class="section-title">✨ New Arrivals</h2>
        <div class="clothes-grid">
            @forelse($items as $item)
            <div class="clothes-card">
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->item_name }}">
                <h4 class="name">{{ $item->item_name }}</h4>
                <p class="status">{{ $item->status }}</p>
                @if($item->barcode)
                    <a href="{{ route('styluxe.items.show', $item->barcode) }}" class="btn btn-sm">View Details</a>
                @endif
            </div>
            @empty
                <p>We're sorry, no items yet! 🛍️</p>
            @endforelse
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.pattern-img').forEach(img => {
            img.style.setProperty('--top', Math.random());
            img.style.setProperty('--left', Math.random());
        });
    });
</script>
@endpush