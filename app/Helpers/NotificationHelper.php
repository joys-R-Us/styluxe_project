<?php
namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Notify specific user
     */
    public static function notify($userId, $type, $title, $message, $actionUrl = null, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'data' => $data,
        ]);
    }

    /**
     * Notify all admins
     */
    public static function notifyAdmins($type, $title, $message, $actionUrl = null, $data = null)
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            self::notify($admin->id, $type, $title, $message, $actionUrl, $data);
        }
    }

    // Legacy staff/supplier notification helpers removed — use notifyAdmins() or notify() instead.

    /**
     * Get unread count for user
     */
    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)->unread()->count();
    }

    /**
     * Mark all as read for user
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)->unread()->update(['is_read' => true]);
    }
}
?>