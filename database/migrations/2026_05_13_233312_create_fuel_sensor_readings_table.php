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
        Schema::create('fuel_sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->float('percentage_full');
            $table->float('distance_from_fuel_level');
            $table->float('quantity_in_ltrs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_sensor_readings');
    }
};
