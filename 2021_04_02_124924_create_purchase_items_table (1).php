<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->foreignId('pro_id');
            $table->foreign('pro_id')->references('id')->on('products');
            $table->foreignId('pur_id');
            $table->foreign('pur_id')->references('id')->on('purchases');
            $table->string('sku');
            $table->string('pur_type')->nullable();
            $table->bigInteger('qty');
            $table->bigInteger('cost_price');
            $table->bigInteger('retail_price');
            $table->bigInteger('sub_total');
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
        Schema::dropIfExists('purchase_items');
    }
}
