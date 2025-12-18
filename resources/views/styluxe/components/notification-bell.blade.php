@php
    $unreadCount = Auth::check() 
        ? \App\Models\Notification::where('user_id', Auth::id())->unread()->count() 
        : 0;
    $recentNotifications = Auth::check() 
        ? \App\Models\Notification::where('user_id', Auth::id())->latest()->take(5)->get() 
        : collect();
@endphp

<div class="notification-wrapper">
    <button class="notification-bell" id="notificationBell">
        <svg class="notification-bell" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5S10.5 3.17 10.5 4v.68C7.63 5.36 6 7.93 6 11v5l-2 2v1h16v-1l-2-2z"/>
        </svg>
        @if($unreadCount > 0)
        <span style="position: absolute; top: -0.5em; right: -0.5em; background-color: red; color: white; border-radius: 50%; padding: 0.2em 0.5em; font-size: 0.8em;" class="notification-badge">{{ $unreadCount }}</span>
        @endif
    </button>

    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notification-header">
            <h4>Notifications</h4>
            @if($unreadCount > 0)
            <form action="{{ route('styluxe.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn-link">Mark all as read</button>
            </form>
            @endif
        </div>

        <div class="notification-list">
            @forelse($recentNotifications as $notification)
            <a href="{{ route('styluxe.notifications.read', $notification->id) }}" 
               class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}">
                <span class="notification-icon">{{ $notification->getIconClass() }}</span>
                <div class="notification-content">
                    <h5>{{ $notification->title }}</h5>
                    <p>{{ Str::limit($notification->message, 60) }}</p>
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </a>
            @empty
            <div class="notification-empty">
                <p>No notifications yet 🎉</p>
            </div>
            @endforelse
        </div>

        <a href="{{ route('styluxe.notifications.index') }}" class="notification-footer">
            View All Notifications
        </a>
    </div>
</div>