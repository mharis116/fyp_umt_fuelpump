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
        Schema::table('dips', function (Blueprint $table) {
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->float('total_sales_qty')->nullable();
            $table->integer('total_sales_count')->nullable();
            $table->float('total_purchase_qty')->nullable();
            $table->integer('total_purchase_count')->nullable();
            $table->float('last_dip_qty')->nullable();
            $table->float('variance')->nullable();
            $table->float('abs_variance')->nullable();
            $table->string('true_label')->nullable();
            $table->string('predicted_label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dips', function (Blueprint $table) {
            $table->dropColumn(['temperature', 'humidity', 'total_sales_qty', 'total_sales_count', 'total_purchase_qty', 'total_purchase_count', 'last_dip_qty', 'variance', 'abs_variance', 'true_label', 'predicted_label']);
        });
    }
};
