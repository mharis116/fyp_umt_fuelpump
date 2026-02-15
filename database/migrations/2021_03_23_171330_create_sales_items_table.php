<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_items', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->foreignId('sale_id');
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreignId('pro_id');
            $table->foreign('pro_id')->references('id')->on('products');
            $table->string('sku');
            $table->float('qty');
            $table->float('cost_price')->nullable();
            $table->bigInteger('subtotal');
            $table->float('retail_price');
            $table->text('desc')->nullable();
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
        Schema::dropIfExists('sales_items');
    }
}
