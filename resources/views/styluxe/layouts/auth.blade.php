@extends('layouts.main')

@section('content')
<div class="auth-wrapper">

    <section class="auth-artwork">
        <img src="{{ asset('styluxe/images/auth-art.png') }}" alt="cartoon clothes" />
    </section>

    <section class="auth-form-panel">
        <div class="auth-card">
            <div class="auth-brand">
                <svg width="48" height="48" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="3" fill="var(--primary)"></rect></svg>
                <h1>Styluxe</h1>
            </div>

            @yield('content')
    
            <div class="auth-footer">
                <small>© {{ date('Y') }} Styluxe — Sustainable fashion inventory</small>
            </div>
        </div>
    </section>

</div>
@endsection
