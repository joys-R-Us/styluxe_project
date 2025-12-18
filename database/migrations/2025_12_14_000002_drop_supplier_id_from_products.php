<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'supplier_id')) {
            Schema::table('products', function (Blueprint $table) {
                // Directly drop column; Laravel will handle constraints
                $table->dropColumn('supplier_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'supplier_id')) {
                    $table->unsignedBigInteger('supplier_id')->nullable()->after('barcode');
                    // Don't recreate the foreign key to avoid constraint issues in the future
                    // $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('set null');
                }
            });
        }
    }
};
