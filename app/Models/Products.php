<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    /** @use HasFactory<\Database\Factories\ClothingInventoryFactory> */
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'barcode',
        'item_name',
        'category_id',
        'size',
        'color',
        'condition',
        'description',
        'quantity',
        'price',
        'status',
        'image_path',
        'reorder_level',
        'low_stock_threshold',
        'added_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'reorder_level' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->barcode)) {
                $item->barcode = self::generateBarcode();
            }
        });

        static::updating(function ($item) {
            if ($item->quantity <= 0) {
                $item->status = 'Sold Out';
            } elseif ($item->quantity <= $item->low_stock_threshold) {
                $item->status = 'Out-Of-Stock';
            } else {
                $item->status = 'Available';
            }
        });
    }

    // Relationships
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'product_barcode', 'barcode');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_barcode', 'barcode');
    }

    // Helper methods
    public static function generateBarcode()
    {
        do {
            $barcode = 'STX' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (self::where('barcode', $barcode)->exists());

        return $barcode;
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function needsReorder()
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function formattedPrice()
    {
        return '₱' . number_format($this->price, 2);
    }

    public function isAvailable()
    {
        return $this->status === 'Available' && $this->quantity > 0;
    }

    public function getStockStatusClass()
    {
        if ($this->status === 'Sold Out') {
            return 'status-sold-out';
        } elseif ($this->isLowStock()) {
            return 'status-low-stock';
        } elseif ($this->status === 'Out-Of-Stock') {
            return 'status-outOfStock';
        }
        return 'status-available';
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available')->where('quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'low_stock_threshold')
                     ->where('status', '!=', 'Sold Out');
    }

    public function scopeNeedsReorder($query)
    {
        return $query->whereColumn('quantity', '<=', 'low_stock_threshold');
    }

    public function scopeByCategory($query, $category)
    {
        if (is_numeric($category)) {
            return $query->where('category_id', (int) $category);
        }

        return $query->whereHas('category', function ($q) use ($category) {
            $q->where('slug', $category)->orWhere('name', $category);
        });
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('item_name', 'LIKE', "%{$keyword}%")
              ->orWhere('barcode', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%");
        });
    }
}