<!-- <aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('storage/images/styluxe_logo.png') }}" alt="Styluxe Logo">
        <span>Styluxe</span>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('styluxe.dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('styluxe.items.index') }}" class="nav-link">Inventory</a>
        <a href="#" class="nav-link">Analytics</a>
        <a href="#" class="nav-link">Sales</a>
        <a href="#" class="nav-link">Settings</a>
    </nav>
</aside>

<header class="site-header">
  <div class="container">
    <a class="logo" href="{{ route('styluxe.homepage') }}">{{ config('app.name','Styluxe') }}</a>

    <nav class="site-nav">
      <a href="{{ route('styluxe.homepage') }}">Home</a>
      <a href="{{ route('styluxe.items.index') }}">Items</a>
      @guest
        <a href="{{ route('styluxe.login') }}" class="btn">Login</a>
        <a href="{{ route('styluxe.register') }}" class="btn ghost">Register</a>
      @else
        <a href="{{ route('styluxe.dashboard') }}" class="btn">Dashboard</a>
      @endguest
    </nav>
  </div>
</header>
--> 