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
        Schema::create('supplier_requests', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('request_type', 50); // ADD ITEM, BATCH UPLOAD, RESTOCK, EDIT ITEM

            $table->string('request_status', 100)->nullable(); // PENDING, APPROVED, REJECTED, SORTED 

            $table->unsignedBigInteger('staff_id')->nullable();      
                  
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->nullOnDelete();                         
            $table->foreign('item_id')->references('product_id')->on('products')->nullOnDelete();              
            $table->foreign('staff_id')->references('user_id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
