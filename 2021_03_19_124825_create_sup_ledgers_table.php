<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sup_ledgers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->double('dr',255,2)->nullable();
            $table->double('cr',255,2)->nullable();
            $table->double('adjustment',255,2)->nullable();
            $table->text('desc')->nullable();
            $table->foreignId('pur_id')->nullable();
            $table->foreign('pur_id')->references('id')->on('purchases');
            $table->foreignId('sup_id');
            $table->foreign('sup_id')->references('id')->on('suppliers');
            $table->enum('type',['purchase','payment','opbl'])->default('purchase');
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
        Schema::dropIfExists('sup_ledgers');
    }
}
