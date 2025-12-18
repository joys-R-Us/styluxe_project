<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->string('product_barcode', 20);
            $table->unsignedBigInteger('user_id');
            $table->enum('change_type', ['added', 'removed', 'adjusted', 'sold']);
            $table->integer('quantity_change');
            $table->integer('previous_quantity');
            $table->integer('new_quantity');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('product_barcode')->references('barcode')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['product_barcode', 'created_at']);
            $table->index('change_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};