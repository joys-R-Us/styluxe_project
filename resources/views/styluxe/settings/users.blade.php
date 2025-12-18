@extends('styluxe.layouts.dashboard')

@section('title', 'User Management - Styluxe')

@section('content')
<div class="user-management-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">👥 User Management</h1>
            <p class="text-muted">Manage system users and permissions</p>
        </div>
        <div class="action-buttons" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('styluxe.settings.users.create') }}" class="btn btn-primary">
                ➕ Add New User
            </a>
        </div>
    </div>

    {{-- alerts displayed in layout --}}

    <!-- User Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👤</div>
            <div class="stat-info">
                <h3>{{ $users->total() }}</h3>
                <p>Total Users</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3>{{ $users->where('is_active', true)->count() }}</h3>
                <p>Active Users</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⚠️</div>
            <div class="stat-info">
                <h3>{{ $users->where('is_active', false)->count() }}</h3>
                <p>Inactive Users</p>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-card">
        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-cell">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" 
                                         alt="{{ $user->name }}" 
                                         class="user-avatar">
                                @else
                                    <div class="user-avatar-placeholder">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="user-info">
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->id === Auth::id())
                                        <span class="badge badge-sm bg-info">You</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                @if($user->id !== Auth::id())
                                    <!-- Toggle Status -->
                                    <form action="{{ route('styluxe.users.toggle-status', $user->id) }}" 
                                          method="POST" 
                                          style="display: inline;">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            {{ $user->is_active ? '⏸️' : '▶️' }}
                                        </button>
                                    </form>

                                    <!-- Delete User -->
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('styluxe.users.delete', $user->id) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Delete this user? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                🗑️
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="empty-state">
                                <p>No users found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
    @endif
</div>

<style>
.user-management-wrapper {
    padding: var(--space-6);
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-6);
    flex-wrap: wrap;
    gap: var(--space-3);
}

.page-title {
    font-size: var(--fs-3xl);
    font-weight: 800;
    color: var(--text-dark);
    margin: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--space-4);
    margin-bottom: var(--space-6);
}

.stat-card {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    padding: var(--space-5);
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 2px solid var(--bg-variant);
    box-shadow: var(--shadow-sm);
}

.stat-icon {
    font-size: 3rem;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-violet) 0%, #9b59b6 100%);
    border-radius: var(--radius-md);
}

.stat-info h3 {
    font-size: var(--fs-3xl);
    font-weight: 800;
    color: var(--text-dark);
    margin: 0;
}

.stat-info p {
    color: var(--text-muted);
    margin: 0;
}

.users-table-card {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 2px solid var(--bg-variant);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table thead {
    background: linear-gradient(135deg, var(--primary-violet) 0%, #9b59b6 100%);
    color: white;
}

.users-table th {
    padding: var(--space-4);
    text-align: left;
    font-weight: 700;
    font-size: var(--fs-sm);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.users-table tbody tr {
    border-bottom: 1px solid var(--bg-variant);
    transition: background-color 0.2s ease;
}

.users-table tbody tr:hover {
    background-color: #f8f9fa;
}

.users-table td {
    padding: var(--space-4);
    vertical-align: middle;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--primary-violet);
}

.user-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-violet) 0%, #9b59b6 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: var(--fs-sm);
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.role-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: var(--fs-sm);
}

.role-admin { background: #8a2be2; color: white; }
.role-client { background: #95a5a6; color: white; }

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: var(--fs-sm);
}

.badge-sm {
    padding: 0.125rem 0.5rem;
    font-size: 0.7rem;
}

.bg-success { background-color: #28a745; color: white; }
.bg-danger { background-color: #dc3545; color: white; }
.bg-info { background-color: #17a2b8; color: white; }
.bg-warning { background-color: #ffc107; color: #000; }

.action-buttons {
    display: flex;
    gap: var(--space-2);
}

.empty-state {
    padding: var(--space-8);
    text-align: center;
    color: var(--text-muted);
}

.pagination-wrapper {
    margin-top: var(--space-6);
    display: flex;
    justify-content: center;
}

.alert {
    padding: var(--space-4);
    border-radius: var(--radius-md);
    margin-bottom: var(--space-4);
    border: 2px solid;
}

.alert-success {
    background-color: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .users-table {
        font-size: var(--fs-sm);
    }

    .user-avatar,
    .user-avatar-placeholder {
        width: 32px;
        height: 32px;
    }
}
</style>

@endsection

@push('scripts')
<script>
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