<!-- 
resources/views/styluxe/
│
├── layouts/
│     ├── main.blade.php        ← PUBLIC layout (Homepage, Login, Register)
│     ├── dashboard.blade.php   ← DASHBOARD layout (uses sidebar + topbar)
│     └── auth.blade.php        ← keep if needed for auth-only pages
│
├── components/
│     ├── sidebar.blade.php     ← FINAL sidebar (combined version)
│     └── dash-header.blade.php ← FINAL topbar
├── items/
│     ├── create.blade.php
│     ├── edit.blade.php
│     ├── show.blade.php
│     ├── index.blade.php        ← homepage
│     ├── index-public.blade.php ← list of item in table format
├── homepage.blade.php
├── login.blade.php
├── register.blade.php
├── unauthorized.blade.php
└── dashboard.blade.php         ← Page using dashboard layout

-->

<!Doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Styluxe Dashboard')</title>

    <link rel="stylesheet" href="{{ asset('css/styluxe/styluxe.css') }}">
    @stack('head')
</head>

<body class="styluxe-dashboard-bg">

    <div class="dashboard-wrapper">

        <!-- SIDEBAR -->
        @include('styluxe.components.sidebar')

        <div class="dashboard-main-area">

            <!-- DASHBOARD TOP HEADER -->
            @include('styluxe.components.dash-header')

            <!-- CONTENT -->
            <main class="dashboard-content">
                @yield('content')
            </main>
        </div>
    </div>

@stack('scripts')
</body>
</html>
