<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'client_id',
        'total_amount',
        'order_status',
        'payment_status',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    // Relationships
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper methods
    public static function generateOrderNumber()
    {
        do {
            $number = 'ORD-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    public function formattedTotal()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    public function getStatusBadgeClass()
    {
        $badges = [
            'pending' => 'badge-warning',
            'processing' => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
        ];

        return $badges[$this->order_status] ?? 'badge-warning';
    }

    public function getPaymentStatusBadgeClass()
    {
        $badges = [
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'failed' => 'badge-danger',
        ];

        return $badges[$this->payment_status] ?? 'badge-warning';
    }

    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'processing']);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'completed');
    }
}