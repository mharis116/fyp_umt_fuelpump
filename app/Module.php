<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Module
 *
 * @property $id
 * @property $name
 * @property $code
 * @property $description
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Module extends Model
{


    // protected $connection = 'mysql';
    static $rules = [
        'name' => 'required|string',
        'code' => 'required|string',
        'description' => 'string',
        'status' => 'required',
    ];

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = ['name', 'code', 'description', 'status'];
    protected $guarded = [];


    /**
     * Get all of the comments for the Module
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permission_types()
    {
        return $this->hasMany(ModulePermissionType::class, 'module_id', 'id');
    }
}
