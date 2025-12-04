@extends('styluxe.layouts.main')

@section('content')
<div class="unauth-container">
    <div class="unauth-card">
        <h1>🚫Unauthorized Access</h1>
        <p>You do not have the right credentials to access this page.</p>

        <a href="{{ route('styluxe.homepage') }}" class="return-btn">
            ← Return to Home
        </a>
    </div>
</div>

@endsection