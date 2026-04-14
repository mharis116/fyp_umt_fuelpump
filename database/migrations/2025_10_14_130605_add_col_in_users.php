<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('hierarchy_level_id')->nullable()->constrained("hierarchy_levels")->onDelete("set null");
            $table->tinyInteger('is_hierarchy_end_level')->default(0); // false = not end, true = end
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['hierarchy_level_id']);
            $table->dropColumn(['hierarchy_level_id', 'is_hierarchy_end_level']);
        });
    }
};
