<header class="styluxe-header">
    <div class="container header-flex">
        <!-- logo + brand name -->
        <a class="logo" href="{{ route('styluxe.dashboard') }}">
            <img class="logo-icon" src="{{ asset('storage/images/styluxe_logo.gif') }}">
            <span class="brand">Styluxe</span>
        </a>  

        @auth
        <div class="header-actions">
            <div class="header-profile">
                <img src="{{ asset('storage/images/profile.png') }}" class="profile-pic">
                <span>{{ Auth::user()->name }}</span>
            </div>

            <form action="{{ route('styluxe.logout') }}" method="POST">
                @csrf
                <button class="btn logout-btn">Logout</button>
            </form>
        </div>
        @endauth
    </div>
</header>
