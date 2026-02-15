<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('hierarchies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('hierarchy_level_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('hierarchies')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('hierarchy_level_id')->references('id')->on('hierarchy_levels')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hierarchies');
    }
};
