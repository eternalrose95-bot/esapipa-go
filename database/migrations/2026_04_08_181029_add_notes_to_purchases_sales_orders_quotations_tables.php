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
        Schema::table('purchases', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('grand_total');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('sale_date');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('supplier_id');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
