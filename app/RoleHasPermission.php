<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    // protected $connection = 'client';
    protected $guarded = [];


    public function module()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Module::class, 'module_id', 'id');
    }

    public function module_permission_type()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\ModulePermissionType::class, 'module_permission_type_id', 'id');
    }
}
