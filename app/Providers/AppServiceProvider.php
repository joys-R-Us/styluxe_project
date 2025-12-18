<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('styluxe.components.notification-bell', function ($view) {
            if (Auth::check()) {
                $unreadCount = Notification::where('user_id', Auth::id())->unread()->count();
                $recentNotifications = Notification::where('user_id', Auth::id())->recent()->get();
            } else {
                $unreadCount = 0;
                $recentNotifications = collect();
            }

            $view->with(compact('unreadCount', 'recentNotifications'));
        });
    }
    public const HOME = '/styluxe/homepage'; // Redirect to dashboard after login
    // new code added*/
}
