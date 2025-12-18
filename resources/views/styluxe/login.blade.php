@extends('styluxe.layouts.auth')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 >🎀 Login to Styluxe</h1>
        
        <form action="{{ route('styluxe.login.submit') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <!-- Remember Me -->
            <div class="remember_label">
                <input id="rememberMe" type="checkbox" class="checkbox" name="remember">
                <label for="rememberMe">Remember me</label>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p class="mt-6">Don't have an account? <a href="{{ route('styluxe.register') }}">Register</a></p>

        <!-- forgot password mag add -->
    </div>
</div>
@endsection