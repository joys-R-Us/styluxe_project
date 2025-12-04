<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('barcode')->unique();            // Unique barcode for the item
            $table->string('item_name', 100);                      // Item name
            $table->string('category', 50);                       // Item category
            $table->string('size');                           // Size of the clothing item
            $table->string('color',30);                           // Color of the clothing item
            $table->string('condition');                      // Condition (New, Pre-loved, Vintage)
            $table->text('description', 500)->nullable();          // item details
            $table->integer('quantity')->nullable();                      // Quantity in stock
            $table->decimal('price', 11, 2);                  // Price per unit
            $table->string('status')->default('Available');                         // Available / Out-Of-Stock / Sold Out
            $table->string('image_path')->nullable();    // Image path
            $table->unsignedBigInteger('supplier_id')->nullable();
            
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->nullOnDelete();
            // $table->integer('reorder_level')->default(5);     // Automated low stock alerts
            // $table->integer('low_stock_threshold')->default(5);           // threshold for low stock alerts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
