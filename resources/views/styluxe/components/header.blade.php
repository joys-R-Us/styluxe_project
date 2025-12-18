<header class="styluxe-header">
    <div class="container">
        <div class="header-grid">
            {{-- Logo + Brand --}}
            <a class="logo" href="{{ route('styluxe.homepage') }}">
                <img class="logo-icon" src="{{ asset('storage/images/styluxe_logo.gif') }}" alt="Styluxe">
                <span class="brand">Styluxe</span>
            </a>

            {{-- Actions (Right) --}}
            <div class="header-actions">

                {{-- Notifications --}}
                @auth
                    @include('styluxe.components.notification-bell')
                @endauth

                {{-- Profile (Authenticated User) --}}
                @auth
                    <div class="header-profile-dropdown">
                        <button class="header-profile" id="profileDropdownBtn">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" 
                                     class="profile-pic" >
                            @else
                                <div class="profile-pic-placeholder">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif

                            <div class="profile-info">
                                <span class="profile-name">{{ Auth::user()->name }}</span>
                                <span class="role-badge {{ Auth::user()->getRoleBadgeClass() }}">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>
                            <span class="dropdown-arrow">▼</span>
                        </button>

                        {{-- Profile Dropdown Menu --}}
                        <div class="profile-dropdown-menu" id="profileDropdownMenu">
                            <div class="dropdown-header">
                                <strong>{{ Auth::user()->name }}</strong>
                                <small>{{ Auth::user()->email }}</small>
                            </div>
                            <div class="dropdown-divider"></div>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('styluxe.dashboard') }}" class="dropdown-item">
                                    📊 Dashboard
                                </a>
                            @endif
                            <a href="{{ route('styluxe.settings.profile') }}" class="dropdown-item">
                                ⚙️ Profile Settings
                            </a>
                            @if(Auth::user()->role === 'client')
                                <a href="{{ route('styluxe.orders.index') }}" class="dropdown-item">
                                    📦 My Orders
                                </a>
                            @endif
                            <a href="{{ route('styluxe.notifications.index') }}" class="dropdown-item">
                                🔔 Notifications
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('styluxe.logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-item">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                {{-- Guest View --}}
                @guest
                    <div class="header-profile">
                        <div class="profile-pic-placeholder">
                            👤
                        </div>
                        <div class="profile-info">
                            <span class="role-badge badge-client">Visitor</span>
                        </div>
                    </div>

                    <a href="{{ route('styluxe.login') }}" class="btn login-btn">
                        🔐 Login
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileBtn = document.getElementById('profileDropdownBtn');
    const profileMenu = document.getElementById('profileDropdownMenu');
    
    if (profileBtn && profileMenu) {
        // Toggle dropdown on button click
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileBtn.classList.toggle('active');
            profileMenu.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                profileBtn.classList.remove('active');
                profileMenu.classList.remove('show');
            }
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                profileBtn.classList.remove('active');
                profileMenu.classList.remove('show');
            }
        });
    }
});
</script>
