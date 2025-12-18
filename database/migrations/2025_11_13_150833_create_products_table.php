<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key 'id'
            $table->string('barcode')->unique();
            $table->string('item_name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('size');
            $table->string('color')->nullable();
            $table->enum('condition', ['New', 'Pre-Loved', 'Vintage', 'Branded']);
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10, 2);
            $table->enum('status', ['Available', 'Out-Of-Stock', 'Sold Out'])->default('Available');
            $table->string('image_path')->nullable();
            $table->integer('reorder_level')->default(5);
            $table->integer('low_stock_threshold')->default(10);
            $table->unsignedBigInteger('added_by')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['status', 'quantity']);
            $table->index('category_id');
            $table->index('barcode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
