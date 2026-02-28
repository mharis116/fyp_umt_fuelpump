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
            $table->foreignId('sup_id');
            $table->foreign('sup_id')->references('id')->on('suppliers');

        });
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
        Schema::table('stocks', function (Blueprint $table) {
            $table->foreignId('dip_id')->nullable();
            $table->foreign('dip_id')->references('id')->on('dips');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('op_bal_id')->nullable();
            $table->foreign('op_bal_id')->references('id')->on('cust_ledgers');
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignId('op_bal_id')->nullable();
            $table->foreign('op_bal_id')->references('id')->on('sup_ledgers');
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('exp_type_id');
            $table->foreign('exp_type_id')->references('id')->on('exp_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
