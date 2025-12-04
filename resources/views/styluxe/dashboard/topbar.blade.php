<!-- @if(Auth::check())
    @if(Auth::user()->role == 'admin')
        {{-- Admin full menu --}}
    @elseif(Auth::user()->role == 'manager')
        {{-- Manager limited menu --}}
    @elseif(Auth::user()->role == 'staff')
        {{-- Staff buttons --}}
    @elseif(Auth::user()->role == 'supplier')
        {{-- Supplier menu --}}
    @else
        {{-- Client --}}
    @endif
@else
    {{--Guest menu --}}
@endif -->

<header class="dashboard-topbar">
    <div class="topbar-left">
        <h2>@yield('title')</h2>
    </div>

    <div class="topbar-right">
        <div class="user-info">
            <span>{{ auth()->user()->name }}</span>
            <img src="{{ asset('storage/images/default_user.png') }}" class="user-avatar" alt="">
        </div>
        <form method="POST" action="{{ route('styluxe.logout') }}">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>
</header>
