@extends('styluxe.layouts.main')

@section('content')
<div class="auth-page cartoon-auth">

    <!-- HERO ART -->
    <section class="auth-hero">
        <img src="{{ asset('storage/images/explore_icon.gif') }}" alt="cartoon fashion art">
        <h1>🌈 Join Styluxe!</h1>
        <p>Create your account and start managing your sustainable fashion items today! 🛍️</p>
    </section>

    <!-- FORM CARD -->
    <section class="auth-panel">
        <div class="auth-card glass">
            <h2>Register</h2>
            <form method="POST" action="{{ route('styluxe.register.submit') }}">
                @csrf
                <input type="text" name="name" placeholder="Full Name" value="{{ old('item_name') }}" required>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <button type="submit" class="form-btn mt-2">Register</button>
            </form>

            <p class="mt-4 text-center">
                Already have an account? 
                <a href="{{ route('styluxe.login') }}" class="link-button">Login</a>
            </p>
        </div>
    </section>

</div>
@endsection
