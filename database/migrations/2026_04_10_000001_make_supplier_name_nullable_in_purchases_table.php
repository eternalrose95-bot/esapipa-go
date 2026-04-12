<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('purchases', 'supplier_name')) {
            DB::statement('ALTER TABLE purchases MODIFY supplier_name VARCHAR(255) NULL;');

            DB::table('purchases')
                ->whereNull('supplier_name')
                ->whereNotNull('supplier_id')
                ->chunkById(100, function ($purchases) {
                    foreach ($purchases as $purchase) {
                        $supplier = DB::table('suppliers')->find($purchase->supplier_id);
                        if ($supplier) {
                            DB::table('purchases')
                                ->where('id', $purchase->id)
                                ->update(['supplier_name' => $supplier->name]);
                        }
                    }
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('purchases', 'supplier_name')) {
            DB::statement('ALTER TABLE purchases MODIFY supplier_name VARCHAR(255) NOT NULL;');
        }
    }
};
