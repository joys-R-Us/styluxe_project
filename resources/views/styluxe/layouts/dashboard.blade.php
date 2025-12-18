<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Styluxe Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/styluxe/styluxe.css') }}">
    @stack('head')
</head>
<body class="styluxe-bg">

    {{-- Header --}}
    @include('styluxe.components.header')

    {{-- Topbar Navigation --}}
    @include('styluxe.components.topbar')

    {{-- Main Content --}}
    <main class="main-content">
        <div class="container">
            {{-- Alerts (flash / validation) handled centrally --}}
            @include('styluxe.components.alerts')

            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('styluxe.components.footer')

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/styluxe/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>