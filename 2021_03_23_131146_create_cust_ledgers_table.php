<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cust_ledgers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->double('dr',255,2)->nullable();
            $table->double('cr',255,2)->nullable();
            $table->text('desc')->nullable();
            $table->double('adjustment',255,2)->nullable();
            $table->foreignId('sale_id')->nullable();
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreignId('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->enum('type',['sale','payment','opbl'])->default('sale');
            $table->boolean('isdeleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cust_ledgers');
    }
}
