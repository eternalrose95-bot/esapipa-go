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
        if (!Schema::hasColumn('purchases', 'supplier_id')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->foreignId('supplier_id')->nullable()->after('supplier_name')->constrained('suppliers');
            });
        }

        DB::table('purchases')
            ->whereNull('supplier_id')
            ->orderBy('id')
            ->chunkById(100, function ($purchases) {
                foreach ($purchases as $purchase) {
                    if (!isset($purchase->supplier_name) || trim($purchase->supplier_name) === '') {
                        continue;
                    }

                    $supplier = DB::table('suppliers')
                        ->where('name', $purchase->supplier_name)
                        ->first();

                    if ($supplier) {
                        DB::table('purchases')
                            ->where('id', $purchase->id)
                            ->update(['supplier_id' => $supplier->id]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('purchases', 'supplier_id')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn('supplier_id');
            });
        }
    }
};
