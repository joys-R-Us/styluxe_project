<?php

namespace App\Http\Service;
use Illuminate\Http\Request;
use App\Models\Products; // idk if sakto na models instead of service

class StockAlertService {
    public function checkLowStock() {
        return Products::whereColumn('quantity', '<=', 'low_stock_threshold')
        ->where('status', '!=', 'Sold Out')
        ->get();
    }

    public function notifyReorderLevel() {
        $products = Products::whereColumn('quantity', '<=', 'low_stock_threshold')->get();

        foreach($products as $product) {
            // notify admin
        }
    }

    public function batchUpload(Request $request) {
        $validated = $request->validated([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);
        //parse csv and create products
    }
}
?>