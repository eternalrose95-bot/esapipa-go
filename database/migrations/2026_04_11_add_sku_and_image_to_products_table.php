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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->after('product_category_id');
            }
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });

        // Generate SKU berurutan untuk existing products
        $products = \App\Models\Product::orderBy('id')->get();
        $skuCounter = 1;
        foreach ($products as $product) {
            if (empty($product->sku)) {
                $sku = str_pad($skuCounter, 5, '0', STR_PAD_LEFT);
                $product->update(['sku' => $sku]);
                $skuCounter++;
            }
        }

        // Sekarang tambahkan unique constraint jika belum ada
        if (!$this->hasUniqueConstraint('products', 'sku')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unique('sku');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if ($this->hasUniqueConstraint('products', 'sku')) {
                $table->dropUnique('products_sku_unique');
            }
            if (Schema::hasColumn('products', 'sku')) {
                $table->dropColumn('sku');
            }
            if (Schema::hasColumn('products', 'image')) {
                $table->dropColumn('image');
            }
        });
    }

    private function hasUniqueConstraint($table, $column)
    {
        $indexes = \DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_NAME=? AND COLUMN_NAME=? AND SEQ_IN_INDEX=1 AND NON_UNIQUE=0", [$table, $column]);
        return count($indexes) > 0;
    }
};
