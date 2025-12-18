<nav class="topbar">
    <div class="container">
        <div class="topbar-links">
            {{-- Dashboard --}}
            @if (Auth::user()->isAdmin())
            <a href="{{ route('styluxe.dashboard') }}" class="topbar-link {{ request()->routeIs('styluxe.dashboard') ? 'active' : '' }}">
                📊 Dashboard
            </a>
            @endif

            {{-- Items (All roles) --}}
            <a href="{{ route('styluxe.items.index-public') }}" class="topbar-link {{ request()->routeIs('styluxe.items.*') ? 'active' : '' }}">
                👕 Items
            </a>

            {{-- Orders (Clients) --}}
            @if(Auth::user()->isClient())
            <a href="{{ route('styluxe.orders.index') }}" class="topbar-link {{ request()->routeIs('styluxe.orders.*') ? 'active' : '' }}">
                🛍️ My Orders
            </a>
            @endif

            {{-- Order Management - Admin --}}
            @if(Auth::user()->isAdmin())
            <a href="{{ route('styluxe.order-management.index') }}" class="topbar-link {{ request()->routeIs('styluxe.order-management.*') ? 'active' : '' }}">
                📦 Orders
            </a>
            @endif

            {{-- Settings --}}
            <a href="{{ route('styluxe.settings.profile') }}" class="topbar-link {{ request()->routeIs('styluxe.settings.*') ? 'active' : '' }}">
                ⚙️ Settings
            </a>
        </div>
    </div>
</nav>