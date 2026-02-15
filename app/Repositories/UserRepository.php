<?php


namespace App\Repositories;

// use App\Models\SuperAdmin\Module;

use App\Role;
use App\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
// use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// htsaxon
class UserRepository
{
    public function __construct(private RolePermissionRepository  $rolePermissionRepository){

    }

    public function find($filters = []){
        $query = User::query();


        return $query;
    }



    // public function dropdown($location_id = null){

    //     // dd($client_id);
    //     return $this->find()->when($location_id != null, function($q) use($location_id){
    //         return $q->whereHas('locations', function($q) use($location_id){
    //             return $q->where('location_id', $location_id);
    //         });
    //     })
    //     ->whereStatus('active')->get();
    // }

    public function getAdminUser(){
        $user = $this->find()->where('isdeleted', 0)->where('is_system', 1)->first();
        return $user;
    }

    // public function createUser(array $payload){
    //     app(\App\Services\ClientDatabaseService::class)->verifyClientUserEmailDomain($payload['client_id'], $payload['email']);

    //     return DB::transaction(function () use($payload) {
    //         $user = User::create([
    //             'name' => $payload['name'], // net needed in client table , avoided redundancey
    //             'last_name' => $payload['last_name']??null,
    //             'email' => $payload['email'],
    //             'contact1' => $payload['contact1']??null,
    //             'contact2' => $payload['contact2']??null,
    //             'password' => Hash::make($payload['password']),

    //             'hierarchy_level_id' => $payload['hierarchy_level_id']??null,
    //             'is_hierarchy_end_level' => $payload['is_hierarchy_end_level']??0,
    //         ]);

    //         $user->roles()->sync($payload['role_ids']);
    //         $user->hierarchies()->sync($payload['hierarchy_ids']);
    //         return $user->load('roles');
    //     });
    // }

    // public function updateUser(User $user, array $payload)
    // {
    //     app(\App\Services\ClientDatabaseService::class)->verifyClientUserEmailDomain($payload['client_id'], $payload['email']);

    //     return DB::transaction(function () use ($user, $payload) {
    //         // Update role main fields
    //         $user->update([
    //             'name' => $payload['name'], // net needed in client table , avoided redundancey
    //             'last_name' => $payload['last_name']??null,
    //             'email' => $payload['email'],
    //             'contact1' => $payload['contact1']??null,
    //             'contact2' => $payload['contact2']??null,

    //             'hierarchy_level_id' => $payload['hierarchy_level_id']??null,
    //             'is_hierarchy_end_level' => $payload['is_hierarchy_end_level']??0,
    //         ]);

    //         $user->roles()->sync($payload['role_ids']);
    //         $user->hierarchies()->sync($payload['hierarchy_ids']);

    //         if(isset($payload['password'])){
    //             $user->update([
    //                 'password' => Hash::make($payload['password'])
    //             ]);
    //         }
    //         $this->clearCache($user);
    //         return $user->load('roles'); // return updated role with permissions
    //     });
    // }

    // public function assignPermissions(Role $role, array $permissions = []){
    //     foreach ($permissions as $permission) {
    //         $role->role_has_permissions()->create([
    //             'module_id' => $permission['module_id'],
    //             'module_permission_type_id' => $permission['module_permission_type_id'],
    //             'is_permitted' => $permission['is_permitted'],
    //         ]);
    //     }
    // }

    // public function get_role_details($role_id){
    //     $role = Role::with(['role_has_permissions'])->findOrFail($role_id);
    //     return $role;
    // }


    // public function clearCache(User $user){
    //     $this->rolePermissionRepository->clearRolePermissionsCache(user:$user);
    // }

}
