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
        Schema::table('product_purchase', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('discount_percentage');
        });

        Schema::table('product_sale', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('unit_price');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('unit_price');
        });

        Schema::table('product_quotation', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_purchase', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('product_sale', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('product_quotation', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
