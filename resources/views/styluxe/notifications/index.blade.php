@extends('styluxe.layouts.dashboard')

@section('title', 'Notifications - Styluxe')

@section('content')
<div class="notifications-page-wrapper">
    <div class="page-header">
        <h1 class="page-title">🔔 Notifications</h1>
        @if($unreadCount > 0)
        <form action="{{ route('styluxe.notifications.read-all') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                Mark All as Read ({{ $unreadCount }})
            </button>
        </form>
        @endif
    </div>

    {{-- alerts displayed in layout --}}

    <div class="notifications-container">
        @forelse($notifications as $notification)
        <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}">
            <div class="notification-icon-large">
                {{ $notification->getIconClass() }}
            </div>
            
            <div class="notification-body">
                <div class="notification-header-inline">
                    <h4>{{ $notification->title }}</h4>
                    @if(!$notification->is_read)
                        <span class="badge bg-primary">New</span>
                    @endif
                </div>
                
                <p class="notification-message">{{ $notification->message }}</p>
                
                <div class="notification-meta">
                    <small class="text-muted">
                        <span class="notification-type">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</span>
                        <span class="notification-divider">•</span>
                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                    </small>
                </div>
            </div>

            <div class="notification-actions">
                @if($notification->action_url)
                    <a href="{{ route('styluxe.notifications.read', $notification->id) }}" 
                    class="btn btn-sm btn-primary">
                        View Details
                    </a>
                @endif
                
                @if(!$notification->is_read)
                    <a href="{{ route('styluxe.notifications.read', $notification->id) }}" 
                    class="btn btn-sm btn-outline-secondary">
                        Mark as Read
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div class="notifications-empty">
            <div class="empty-state">
                <div class="empty-icon">🔔</div>
                <h3>No Notifications</h3>
                <p>You're all caught up! Check back later for updates.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="notifications-pagination">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<style>
.notifications-page-wrapper {
    padding: var(--space-6);
    max-width: 900px;
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

.notifications-container {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

.notification-card {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: var(--space-4);
    padding: var(--space-5);
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 2px solid var(--bg-variant);
    transition: all 0.3s ease;
    align-items: start;
}

.notification-card:hover {
    border-color: var(--primary-violet);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.notification-card.unread {
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    border-color: var(--primary-violet);
    border-left-width: 4px;
}

.notification-card.read {
    opacity: 0.8;
}

.notification-icon-large {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-variant);
    border-radius: var(--radius-md);
}

.notification-card.unread .notification-icon-large {
    background: linear-gradient(135deg, var(--primary-violet) 0%, #9b59b6 100%);
    color: white;
}

.notification-body {
    flex: 1;
}

.notification-header-inline {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    margin-bottom: var(--space-2);
    flex-wrap: wrap;
}

.notification-header-inline h4 {
    margin: 0;
    font-size: var(--fs-lg);
    font-weight: 700;
    color: var(--text-dark);
}

.notification-message {
    margin: var(--space-2) 0;
    color: var(--text-body);
    line-height: 1.6;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    margin-top: var(--space-3);
}

.notification-type {
    font-weight: 600;
    color: var(--primary-violet);
}

.notification-divider {
    color: var(--text-muted);
}

.notification-time {
    color: var(--text-muted);
}

.notification-actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
    align-items: flex-end;
}

.notifications-empty {
    padding: var(--space-8) 0;
}

.empty-state {
    text-align: center;
    padding: var(--space-8);
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 2px dashed var(--bg-variant);
}

.empty-icon {
    font-size: 5rem;
    margin-bottom: var(--space-4);
    opacity: 0.3;
}

.empty-state h3 {
    font-size: var(--fs-2xl);
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: var(--space-2);
}

.empty-state p {
    color: var(--text-muted);
    font-size: var(--fs-base);
}

.notifications-pagination {
    margin-top: var(--space-6);
    display: flex;
    justify-content: center;
}

.badge {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 1rem;
}

.bg-primary {
    background-color: var(--primary-violet) !important;
    color: white;
}

.alert {
    padding: 1rem 1.5rem;
    margin-bottom: var(--space-4);
    border-radius: var(--radius-md);
    border: 2px solid;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.alert-success {
    background-color: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.btn-close {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    opacity: 0.5;
    padding: 0;
    width: 1.5rem;
    height: 1.5rem;
}

.btn-close:hover {
    opacity: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .notification-card {
        grid-template-columns: 1fr;
        gap: var(--space-3);
    }

    .notification-icon-large {
        width: 50px;
        height: 50px;
        font-size: 2rem;
        margin: 0 auto;
    }

    .notification-actions {
        flex-direction: row;
        justify-content: center;
        width: 100%;
    }

    .page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .page-header .btn {
        width: 100%;
    }
}

/* Animation for new notifications */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.notification-card.unread {
    animation: slideIn 0.3s ease-out;
}
</style>

@endsection

@push('scripts')
<script>
// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Confirm mark all as read
const markAllForms = document.querySelectorAll('form[action*="read-all"]');
markAllForms.forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!confirm('Mark all notifications as read?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush