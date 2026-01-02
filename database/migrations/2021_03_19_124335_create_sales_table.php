<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->bigInteger('invoice_no');
            $table->float('cost_amount',255);
            $table->float('retail_amount',255);
            $table->text('desc')->nullable();
            $table->float('total_qty',255);
            $table->float('adjustment',255)->nullable();
            // $table->foreignId('customer_id');
            // $table->foreign('customer_id')->references('id')->on('customers');
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
        Schema::dropIfExists('sales');
    }
}
