<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pro_id');
            $table->foreign('pro_id')->references('id')->on('products');
            // $table->foreignId('dip_id')->nullable();
            // $table->foreign('dip_id')->references('id')->on('dips');
            $table->string('sku');
            $table->text('desc')->nullable();
            $table->double('qty',255,2);
            $table->double('stock_capacity',255,2);
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
        Schema::dropIfExists('stocks');
    }
}
