@extends('styluxe.layouts.dashboard')

@section('title', 'Create User - Styluxe')

@section('content')
<div class="user-create-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">👤 Create New User</h1>
            <p class="text-muted">Add a new admin or client account</p>
        </div>
        <div class="action-buttons" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('styluxe.settings.users') }}" class="btn btn-outline-secondary">
                ← Back to Users
            </a>
        </div>
    </div>

    {{-- alerts displayed in layout --}}

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <form action="{{ route('styluxe.settings.users.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Full Name *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">Password *</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">Role *</label>
                            <select 
                                id="role" 
                                name="role" 
                                class="form-select @error('role') is-invalid @enderror"
                                required>
                                <option value="">Select a role</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <strong>Admin:</strong> Full system access, manage items, orders, users<br>
                                <strong>Client:</strong> Browse items, place orders, manage profile
                            </small>
                        </div>

                        <div class="mb-4 form-check">
                            <input 
                                type="checkbox" 
                                id="is_active" 
                                name="is_active" 
                                class="form-check-input" 
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Account Active
                            </label>
                            <small class="text-muted d-block">Inactive accounts cannot log in</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                ✅ Create User
                            </button>
                            <a href="{{ route('styluxe.settings.users') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-create-wrapper {
    padding: 2rem 0;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}
</style>
@endsection
