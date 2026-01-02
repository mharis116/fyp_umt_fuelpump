<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->string('company')->nullable();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phon1')->unique();
            $table->string('phon2')->nullable();
            $table->string('city');
            $table->string('address')->nullable();
            $table->integer('opening_bal')->nullable();
            // $table->foreignId('op_bal_id')->nullable();
            // $table->foreign('op_bal_id')->references('id')->on('sup_ledgers');
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
        Schema::dropIfExists('suppliers');
    }
}
