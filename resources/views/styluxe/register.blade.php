@extends('styluxe.layouts.auth')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1>🎀 Create Styluxe Account</h1>

        <form action="{{ route('styluxe.register.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
                @error('password')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn">Register</button>
        </form>

        <p class="mt-6">
            Already have an account?
            <a href="{{ route('styluxe.login') }}">Login</a>
        </p>
    </div>
</div>
@endsection
