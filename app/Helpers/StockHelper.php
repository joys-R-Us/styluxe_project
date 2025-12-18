<?php
namespace App\Helpers;

use App\Models\Products;
use App\Models\Notification;
use App\Models\User;

class StockHelper
{
    /**
     * Check and notify for low stock items
     */
    public static function checkLowStockAlerts()
    {
        $lowStockItems = Products::lowStock()->get();
        
        if ($lowStockItems->count() > 0) {
            self::notifyLowStock($lowStockItems);
        }

        return $lowStockItems;
    }

    /**
     * Check items that need reorder
     */
    public static function checkReorderNeeded()
    {
        $reorderItems = Products::where('quantity', '<=', Products::raw('low_stock_threshold'))->get();
        
        if ($reorderItems->count() > 0) {
            self::notifyReorderNeeded($reorderItems);
        }

        return $reorderItems;
    }

    /**
     * Update product status based on quantity
     */
    public static function updateProductStatus(Products $product)
    {
        if ($product->quantity <= 0) {
            $product->status = 'Sold Out';
        } elseif ($product->quantity <= $product->low_stock_threshold) {
            $product->status = 'Out-Of-Stock';
        } else {
            $product->status = 'Available';
        }

        $product->save();
    }

    /**
     * Notify admins and suppliers about low stock
     */
    private static function notifyLowStock($items)
    {
        $users = User::where('role', 'admin')->get();

        foreach ($items as $item) {
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'low_stock',
                    'title' => 'Low Stock Alert',
                    'message' => "{$item->item_name} is running low (Stock: {$item->quantity})",
                    'action_url' => route('styluxe.items.show', $item->barcode),
                    'data' => [
                        'product_barcode' => $item->barcode,
                        'current_stock' => $item->quantity,
                        'threshold' => $item->low_stock_threshold,
                    ],
                ]);
            }

            // Supplier role removed; notifications target admins only
        }
    }

    /**
     * Notify about reorder needed
     */
    private static function notifyReorderNeeded($items)
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($items as $item) {
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'restock_needed',
                    'title' => 'Reorder Alert',
                    'message' => "{$item->item_name} has reached reorder level ({$item->quantity} units)",
                    'action_url' => route('styluxe.items.show', $item->barcode),
                    'data' => [
                        'product_barcode' => $item->barcode,
                        'current_stock' => $item->quantity,
                        'threshold' => $item->low_stock_threshold,
                    ],
                ]);
            }
        }
    }

    /**
     * Get stock statistics
     */
    public static function getStockStatistics()
    {
        return [
            'total_items' => Products::count(),
            'available_items' => Products::where('status', 'Available')->count(),
            'low_stock_items' => Products::lowStock()->count(),
            'sold_out_items' => Products::where('status', 'Sold Out')->count(),
            'out_of_stock_items' => Products::where('status', 'Out-Of-Stock')->count(),
            'total_quantity' => Products::sum('quantity'),
            'total_value' => Products::selectRaw('SUM(quantity * price) as total')->value('total'),
        ];
    }
}
?>