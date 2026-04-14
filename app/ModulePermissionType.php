<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModulePermissionType extends Model
{
    protected $guarded = [];

    // protected $connection = 'mysql';

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
