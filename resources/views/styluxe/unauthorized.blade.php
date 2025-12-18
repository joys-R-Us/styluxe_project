@extends('styluxe.layouts.main')

@section('title', 'Unauthorized Access')

@section('content')
<div class="unauth-container">
    <div class="unauth-card">
        <h1>🚫 Unauthorized Access</h1>

        <p>
            You do not have the right credentials to access this page.
        </p>

        @guest
            <a href="{{ route('styluxe.login') }}" class="btn-primary">
                🔐 Go to Login
            </a>
        @else
            <a href="{{ route('styluxe.homepage') }}" class="btn-secondary">
                ← Return to Home
            </a>
        @endguest
    </div>
</div>
@endsection
