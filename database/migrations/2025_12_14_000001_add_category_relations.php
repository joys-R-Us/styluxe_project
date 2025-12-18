<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('item_name');
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
                    $table->index('category_id');
                }
            });
        }

        if (Schema::hasTable('stock_logs')) {
            Schema::table('stock_logs', function (Blueprint $table) {
                if (! Schema::hasColumn('stock_logs', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('product_barcode');
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
                    $table->index('category_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stock_logs')) {
            Schema::table('stock_logs', function (Blueprint $table) {
                if (Schema::hasColumn('stock_logs', 'category_id')) {
                    $table->dropForeign(['category_id']);
                    $table->dropIndex(['category_id']);
                    $table->dropColumn('category_id');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'category_id')) {
                    $table->dropForeign(['category_id']);
                    $table->dropIndex(['category_id']);
                    $table->dropColumn('category_id');
                }
            });
        }
    }
};
