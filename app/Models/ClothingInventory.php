<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothingInventory extends Model
{
    /** @use HasFactory<\Database\Factories\ClothingInventoryFactory> */
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'barcode';
    public $incrementing = false; // because barcode is not an integer
    protected $keyType = 'string';

    protected $fillable = [
        'barcode',             // Unique barcode for the item
        'item_name',           // Item name
        'category',            // Item category (e.g., tops, bottoms, outerwear, dresses, jumpsuits)
        'size',                // Size of the clothing item
        'color',               // Color of the clothing item
        'condition',           // Condition (New, Pre-loved, Vintage, Branded)
        'description',         // item details
        'quantity',            // Quantity in stock
        'price',               // Price
        'status',              // Available / Out-Of-Stock / Sold-Out
        'image_path',               // Image path
        'reorder_level',        // Automated low stock alerts
        'low_stock_threshold', // threshold for low stock alerts
    ];

    public static function boot()
    {
        parent::boot();

        // Automatically set default values on creating
        static::creating(function ($item) {
            if (empty($item->barcode)) {
                $item->barcode = self::generateBarcode();
            }
        });
    }

    public static function generateBarcode()
    {
        do {
            $barcode = 'STX' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (self::where('barcode', $barcode)->exists());

        return $barcode;
    }

    // check STOCK
    public function isLowStock()
    {
        return $this->quantity <= $this-> low_stock_threshold;
    }

    public function formattedPrice()
    {

        return '₱'. number_format($this->price, 2);
    }


    public function isAvailable()
    {
        return $this->status === 'Available';
    }
    /*    
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available');
    }

    public function scopeLowStock($query) {
        return $query->whereColumn('quantity', '<=', 'low_stock_threshold');
    }

    public function scopeSearch($query, $keyword) 
    {
        return $query->where('item_name', 'LIKE', "%$keyword%")
                    -> orWhere('barcode', 'LIKE', "%$keyword%");

    } */
}