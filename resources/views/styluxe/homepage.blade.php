@extends('styluxe.layouts.main')

@section('content')

<div class="homepage-container">

    <!-- HERO SECTION -->
    <section class="hero cartoon-hero">
        <div class="hero-bg-wrapper">
            @foreach(range(1, 25) as $i)
                <img class="silhouette pattern-img" 
                    src="{{ Storage::url('images/bg_silhouettes_' . rand(1,7) . '.png') }}" 
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
    <div class="border-decor mt-6 mb-6">
        <img class="border-decor-icon" src="{{ asset('storage/images/border_decor.gif') }}" alt="border decoration" />
    </div>

    <!-- Search -->
    <div class="home-search">
        <form action="{{ route('styluxe.items.index-public') }}" method="GET">
            <input type="text" name="search" placeholder="Search for clothes...">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Categories -->
    <div class="home-categories">
        <a href="#">👚 Tops</a>
        <a href="#">👖 Bottoms</a>
        <a href="#">👟 Shoes</a>
        <a href="#">👜 Accessories</a>
    </div>

    <!-- New Arrivals -->
    <h2 class="section-title">✨ New Arrivals</h2>
    <div class="clothes-grid">
        @forelse($items as $item)
        <div class="clothes-card">
            <img src="{{ asset('storage/'.$item->image_path) }}" alt="">
            <h4 class="name">{{ $item->item_name }}</h4>
            <p class="status">{{ $item->status }}</p>
        </div>
        @empty
            <p>We're sorry, no items yet! 🛍️</p>
        @endforelse
    </div>

    <!-- FEATURED / TRENDING ITEMS -->
    <section id="explore" class="trending mt-6">
        <h2 class="section-title">❤️‍🔥 Trending Items</h2>
        <div class="clothes-grid">
            @forelse($items as $item)
            <div class="clothes-card">
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->item_name }}">
                <div class="info">
                    <div class="name">{{ $item->item_name }}</div>
                    <div class="status">{{ $item->status }} | Qty: {{ $item->quantity }}</div>
                    <div class="mt-2 flex-gap">
                        <a href="{{ route('styluxe.items.show', $item->barcode) }}" class="btn">View</a>
                    </div>
                </div>
            </div>
            @empty
            <p>No items yet! Start adding your fashionable pieces. 🛍️</p>
            @endforelse
        </div>
    </section>

    <!-- CUTE BANNER -->
    <section class="cute-banner mt-6">
        <img class="cute-banner-icon" src="{{ asset('storage/images/clothes_banner.png') }}" alt="clothes art">
        <p>Keep your inventory stylish and up-to-date! 🎀🛍️</p>
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