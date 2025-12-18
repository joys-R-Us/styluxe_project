<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_barcode',
        'category_id',
        'user_id',
        'change_type',
        'quantity_change',
        'previous_quantity',
        'new_quantity',
        'notes',
    ];

    protected $casts = [
        'quantity_change' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
    ];

    public $timestamps = false;
    protected $dates = ['created_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            $log->created_at = now();
            $log->updated_at = now();
        });
    }

    // Relationships
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_barcode', 'barcode');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getChangeTypeLabel()
    {
        $labels = [
            'added' => '➕ Added',
            'removed' => '➖ Removed',
            'adjusted' => '🔄 Adjusted',
            'sold' => '💰 Sold',
        ];

        return $labels[$this->change_type] ?? $this->change_type;
    }

    public function getChangeClass()
    {
        $classes = [
            'added' => 'text-success',
            'removed' => 'text-danger',
            'adjusted' => 'text-info',
            'sold' => 'text-warning',
        ];

        return $classes[$this->change_type] ?? '';
    }
}