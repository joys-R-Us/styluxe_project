<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'action_url',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function getIconClass()
    {
        $icons = [
            'low_stock' => '⚠️',
            'order_placed' => '🛍️',
            'order_completed' => '✅',
            'request_approved' => '👍',
            'request_rejected' => '❌',
            'restock_needed' => '📦',
        ];

        return $icons[$this->type] ?? '🔔';
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecent($query)
    {
        return $query->latest()->take(10);
    }
}
?>