<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pro_id');
            $table->foreign('pro_id')->references('id')->on('products');
            $table->double('qty',255,2);
            $table->string('sighn')->nullable();
            $table->string('desc')->nullable();
            $table->double('change_in_qty',255,2)->nullable();
            // $table->boolean('isdeleted')->nullable()->default(0);
            $table->timestamp('date');
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
        Schema::dropIfExists('dips');
    }
}
