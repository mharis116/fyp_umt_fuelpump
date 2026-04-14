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
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('module_id');
            $table->foreignId('module_permission_type_id');
            $table->tinyInteger('is_permitted')->default(0);
            $table->timestamps();

            $table->index(['role_id', 'module_id', 'module_permission_type_id'], 'rhp_role_module_perm_idx');
            $table->index(['module_id', 'module_permission_type_id'], 'rhp_module_perm_idx');
            $table->index(['role_id', 'module_permission_type_id'], 'rhp_role_perm_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
    }
};
