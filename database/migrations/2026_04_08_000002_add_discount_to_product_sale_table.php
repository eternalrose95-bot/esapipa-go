<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_sale', function (Blueprint $table) {
            $table->float('discount_percentage')->default(0)->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('product_sale', function (Blueprint $table) {
            $table->dropColumn('discount_percentage');
        });
    }
};
