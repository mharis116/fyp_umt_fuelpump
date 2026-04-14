<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pro_id');
            $table->foreign('pro_id')->references('id')->on('products');
            $table->foreignId('pur_id');
            $table->foreign('pur_id')->references('id')->on('purchases');
            $table->string('sku');            
            $table->double('qty',255,2)->default(0);            
            $table->double('fqty',255,2)->default(0);            
            $table->double('stock_capacity',255,2);            
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
        Schema::dropIfExists('fuel_backups');
    }
}
