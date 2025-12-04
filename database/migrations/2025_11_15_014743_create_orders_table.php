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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('client_id');
            $table->decimal('total_price', 11, 2)->default(0);

            $table->string('payment_status', 20)->default('PENDING'); // PENDING, PAID, CANCALLED, FAILED

            $table->string('order_status', 20)->default('PENDING'); // PENDING, PROCESSING, SHIPPED, DELIVERED, CANCELLED

            $table->foreign('client_id')->references('user_id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
