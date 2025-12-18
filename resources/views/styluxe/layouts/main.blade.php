<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Styluxe')</title>

    <!-- Base & App CSS -->
    <link rel="stylesheet" href="{{ asset('css/styluxe/styluxe.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    @stack('head')
</head>
<body class="styluxe-bg">

    <!-- PUBLIC HEADER -->
    @include('styluxe.components.header')

    {{-- Alerts (flash / validation) --}}
    @include('styluxe.components.alerts')

    <!-- main wrapper -->
    <div class="dashboard-wrapper">
        <!-- Main Content -->
        <main class="dashboard-main">
            @yield('content')
        </main>
    </div>
    
    <!-- Footer -->
    <footer class="styluxe-footer">
        <div class="container">
            <div>© {{ date('Y') }} Styluxe</div>
            <div>Made with ♥ — Rizza hehe🤓😛</div>
        </div>
    </footer>

    <script src="{{ asset('styluxe/js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
