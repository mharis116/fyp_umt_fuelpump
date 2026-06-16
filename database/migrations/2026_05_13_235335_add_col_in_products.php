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
        Schema::table('stocks', function (Blueprint $table) {
            $table->float('distance_at_0_liters')->default(0);
            $table->float('distance_at_n_liters')->default(0);
            // $table->float('max_water_volume_liters')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('distance_at_0_liters');
            $table->dropColumn('distance_at_n_liters');
            // $table->dropColumn('max_water_volume_liters');
        });
    }
};
