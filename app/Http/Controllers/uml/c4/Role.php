<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @property $id
 * @property $name
 * @property $landing_relative_url
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property RoleHasPermission[] $roleHasPermissions
 * @property UserHasRole[] $userHasRoles
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Role extends Model
{

    // protected $connection = 'client';

    static $rules = [
			'name' => 'required|string',
			'landing_relative_url' => 'required|string',
			'description' => 'string',
    ];

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = ['name', 'landing_relative_url', 'description'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function role_has_permissions()
    {
        return $this->hasMany(RoleHasPermission::class, 'role_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userHasRoles()
    {
        return $this->hasMany(UserHasRole::class, 'role_id', 'id');
    }



    public function users()
    {
        return $this->belongsToMany(
            User::class,        // Related model
            'user_has_roles',       // Pivot table
            'role_id',           // Foreign key on pivot for Location
            'user_id',              // Foreign key on pivot for User
            'id',                   // Local key on User table
            'id'                    // Local key on Location table
        );
    }

    public static function dropdown(){
        return Role::where('is_system', 0)->get();
    }

}
