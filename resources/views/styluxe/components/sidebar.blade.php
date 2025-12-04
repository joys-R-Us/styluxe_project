<aside class="styluxe-sidebar">
    <ul class="sidebar-menu">
        @if(Auth::check())
            @php $role = Auth::user()->role; @endphp
            @if($role === 'admin' || $role === 'staff')
                <li><a href="{{ route('styluxe.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('styluxe.items.index-public') }}">Items</a></li>
                <li><a href="#">Orders</a></li>
                <li><a href="#">Sales</a></li>
                <li><a href="#">Users</a></li>
                <li><a href="#">Settings</a></li>
            
            @elseif($role === 'supplier')
                <li><a href="{{ route('styluxe.items.index-public') }}">My Items</a></li>
                <li><a href="{{ route('styluxe.dashboard') }}">Analytics</a></li>
                <li><a href="#">Settings</a></li>
            @endif
        @endif

        
    </ul>

</aside>
