<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('operational_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('operational_expenses', 'product_name')) {
                $table->dropColumn(['product_name', 'quantity', 'unit_price']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('operational_expenses', function (Blueprint $table) {
            $table->string('product_name')->after('store_name');
            $table->integer('quantity')->after('product_name');
            $table->decimal('unit_price', 15, 2)->after('quantity');
        });
    }
};
