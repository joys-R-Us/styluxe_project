@extends('styluxe.layouts.main')

@section('content')
<div class="auth-page cartoon-auth">

    <!-- HERO ART -->
    <section class="auth-hero">
        <img src="{{ asset('storage/images/explore_icon.gif') }}" alt="cartoon fashion art">
        <h1>🎀 Welcome Back!</h1>
        <p>Log in to manage your Styluxe inventory and stay stylish! ✨</p>
    </section>

    <!-- FORM CARD -->
    <section class="auth-panel">
        <div class="auth-card glass">
            <h2>Login</h2>
            <form method="POST" action="{{ route('styluxe.login.submit') }}">
                @csrf
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" placeholder="Password" required>
                
                <!-- Remember Me -->
                <div class="remember_label">
                    <input id="rememberMe" type="checkbox" class="checkbox" name="remember">
                    <label for="rememberMe">Remember me</label>
                </div>

                <button type="submit" class="form-btn mt-2" >Login</button>
            </form>

            <p class="text-center" style="margin-top: 10px;">
                Don't have an account? 
                <a href="{{ route('styluxe.register') }}" class="link-button">Register</a>
            </p> 
        </div>
    </section>

</div>
@endsection
