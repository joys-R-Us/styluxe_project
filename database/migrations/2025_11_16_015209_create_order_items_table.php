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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id');

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->decimal('price', 11, 2);
            $table->string('size', 15)->nullable();
            $table->string('color', 30)->nullable();
            
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('item_id')->references('product_id')->on('products')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_tables');
    }
};
