@extends('styluxe.layouts.dashboard')

@section('title', 'Profile Settings - Styluxe')

@section('content')
<div class="settings-wrapper">
    <div class="page-header">
        <h1 class="page-title">⚙️ Profile Settings</h1>
        <p class="text-muted">Manage your account information and preferences</p>
    </div>

    {{-- alerts displayed in layout --}}

    <form action="{{ route('styluxe.settings.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="settings-grid">
            <!-- Profile Picture Section -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>👤 Profile Picture</h3>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-preview">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" 
                                 alt="{{ $user->name }}" 
                                 id="avatarPreview">
                        @else
                            <div class="avatar-placeholder" id="avatarPreview">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        <label for="avatar" class="btn btn-outline-primary">
                            📷 Choose New Picture
                        </label>
                        <input type="file" 
                               id="avatar" 
                               name="avatar" 
                               accept="image/*" 
                               style="display: none;"
                               onchange="previewAvatar(this)">
                        <p class="text-muted mt-2 mb-0">
                            <small>JPG, JPEG or PNG. Max 2MB.</small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>📝 Personal Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label" novalidate>Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="+63 XXX XXX XXXX">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3" 
                                  placeholder="Enter your full address">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Account Role</label>
                        <div class="role-display">
                            <span class="role-badge role-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>🔒 Change Password</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Leave blank if you don't want to change your password
                    </p>

                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" 
                               class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" 
                               name="new_password"
                               minlength="6">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" 
                               class="form-control" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation"
                               minlength="6">
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>ℹ️ Account Information</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Account Status:</span>
                        <span class="info-value">
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Member Since:</span>
                        <span class="info-value">{{ $user->created_at->format('F d, Y') }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                💾 Save Changes
            </button>
            <a href="{{ route('styluxe.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
.settings-wrapper {
    padding: var(--space-6);
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: var(--space-6);
}

.page-title {
    font-size: var(--fs-3xl);
    font-weight: 800;
    color: var(--primary-violet);
    margin-bottom: var(--space-2);
    text-shadow: 2px 2px 0px rgba(253, 141, 164, 0.3);
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: var(--space-6);
    margin-bottom: var(--space-6);
}

.settings-card {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 3px solid var(--primary-violet);
    overflow: hidden;
    box-shadow: var(--shadow-cartoonish);
    transition: all 0.3s ease;
    margin-bottom: var(--space-4);
}

.settings-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-4px);
}

.card-header {
    background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-coral) 100%);
    padding: var(--space-6);
    color: var(--text-light);
    border-bottom: 3px solid rgba(255, 255, 255, 0.2);
}

.card-header h3 {
    margin: 0;
    font-size: var(--fs-xl);
    font-weight: 700;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.card-body {
    padding: var(--space-8);
    background: var(--bg-white);
}

/* Avatar Section - Extra padding */
.settings-card .card-body.text-center {
    padding: var(--space-8) var(--space-6);
}

.avatar-preview {
    width: 150px;
    height: 150px;
    margin: var(--space-6) auto var(--space-6);
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid var(--primary-violet);
    box-shadow: var(--shadow-md);
    position: relative;
    background: var(--bg-variant);
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-coral) 100%);
    color: var(--text-light);
    font-size: var(--fs-3xl);
    font-weight: 800;
}

/* Form elements spacing */
.form-group {
    margin-bottom: var(--space-6);
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: var(--space-3);
    font-size: var(--fs-base);
}

.form-control {
    width: 100%;
    padding: var(--space-4);
    border: 2px solid var(--bg-variant);
    border-radius: var(--radius-md);
    font-size: var(--fs-base);
    transition: all 0.3s ease;
    background: var(--bg-white);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-violet);
    box-shadow: 0 0 0 4px rgba(164, 160, 255, 0.15);
    transform: translateY(-1px);
}

.form-control.is-invalid {
    border-color: var(--danger);
}

.invalid-feedback {
    color: var(--danger);
    font-size: var(--fs-sm);
    margin-top: var(--space-1);
}

/* Info rows spacing */
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-4) var(--space-3);
    border-bottom: 2px solid var(--bg-variant);
    gap: var(--space-4);
}

.info-row:first-child {
    padding-top: 0;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    font-weight: 600;
    color: var(--text-muted);
}

.info-value {
    font-weight: 500;
    color: var(--text-dark);
}

.btn-close {
    float: right;
    background: transparent;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    opacity: 0.5;
}

.btn-close:hover {
    opacity: 1;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .form-actions .btn {
        width: 100%;
    }
}
</style>

@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            return;
        }
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            
            if (preview.tagName === 'IMG') {
                // Update existing image
                preview.src = e.target.result;
                preview.style.objectFit = 'cover';
                preview.style.objectPosition = 'center';
            } else {
                // Replace placeholder with image
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Avatar Preview';
                img.id = 'avatarPreview';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.objectPosition = 'center';
                img.style.display = 'block';
                
                preview.parentNode.replaceChild(img, preview);
            }
        }
        
        reader.readAsDataURL(file);
    }
}

// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s';
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);
</script>
@endpush