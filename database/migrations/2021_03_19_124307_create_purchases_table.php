<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->bigInteger('inv_no');
            $table->text('desc')->nullable();
            $table->string('sup_bill_no')->nullable();
            // $table->foreignId('pur_type_id');
            // $table->foreign('pur_type_id')->references('id')->on('pur_types');
            // $table->foreignId('sup_id');
            // $table->foreign('sup_id')->references('id')->on('suppliers');
            $table->bigInteger('total_qty');
            $table->bigInteger('retail_amount')->nullable();
            $table->bigInteger('cost_amount');
            $table->bigInteger('adjustment')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
