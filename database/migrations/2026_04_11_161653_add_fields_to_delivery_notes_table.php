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
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->string('driver_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('receiver_name')->nullable();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->dropColumn(['driver_name', 'vehicle_number', 'receiver_name']);
            $table->dropForeign(['sale_id']);
            $table->dropColumn('sale_id');
        });
    }
};
