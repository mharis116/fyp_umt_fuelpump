<?php


namespace App\Repositories;

use App\Module;
use App\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
// use App\Models\Role;

// htsaxon
class RoleRepository
{
    private $rolePermissionRepository;
    public function __construct(private $client_id = null){
        // if(!$client_id){
        //     $this->client_id = get_client_id();
        // }
        $this->rolePermissionRepository = app(RolePermissionRepository::class, ['client_id', $client_id]);
    }

    public function createRole(array $payload, $is_system = 0){
        return DB::transaction(function () use($payload, $is_system) {
            $role = Role::create([
                'name' => $payload['name'],
                'code' => Str::slug($payload['name']),
                'landing_relative_url' => $payload['landing_relative_url'],
                'is_system' => $is_system,
                'description' => $payload['description'],
            ]);


            $this->assignPermissions($role, $payload['permissions']??[]);

            return $role->load('role_has_permissions'); // eager load permissions
        });
    }

    public function updateRole(Role $role, array $payload)
    {
        return DB::transaction(function () use ($role, $payload) {

            // Update role main fields
            $role->update([
                'name' => $payload['name'],
                'code' => Str::slug($payload['name']),
                'landing_relative_url' => $payload['landing_relative_url'] ?? null,
                'description' => $payload['description'] ?? null,
            ]);

            // Reset permissions (simplest approach)
            $role->role_has_permissions()->delete();

            $this->assignPermissions($role, $payload['permissions']??[]);
            $this->clearCache($role);

            return $role->load('role_has_permissions'); // return updated role with permissions
        });
    }

    public function assignPermissions(Role $role, array $permissions = []){
        foreach ($permissions as $permission) {
            // dd($role->toArray(), $permission);
            $role->role_has_permissions()->create([
                'module_id' => $permission['module_id'],
                'module_permission_type_id' => $permission['module_permission_type_id'],
                'is_permitted' => $permission['is_permitted'],
            ]);
        }
    }

    public function clearCache(Role $role){
        foreach($role->users()->get() as $user){
            $this->rolePermissionRepository->clearRolePermissionsCache(user:$user);
        }
    }

    public function get_role_details($role_id, $is_system = 0){
        $role = Role::where('is_system', $is_system)->with(['role_has_permissions'])->findOrFail($role_id);
        return $role;
    }

    public function getAdminRolePayload($rolename){
        // $modules = $this->rolePermissionRepository->get_client_active_modules();
        $modules = $this->rolePermissionRepository->get_active_modules();
        $permissions = [];
        foreach($modules as $module){
            foreach($module['permission_types'] as $permission_type){
                $permissions[] = [
                    'module_id' => $permission_type->module_id,
                    'module_permission_type_id' => $permission_type->id,
                    'is_permitted' => 1,
                ];
            }
        }

        $payload = [
            'name' => $rolename,
            'code' => Str::slug($rolename),
            'landing_relative_url' => '/dashboard',
            'description' => 'Client Admin Role',

            'permissions' => $permissions,
        ];

        return $payload;
    }

    public function createClientAdminUserRole($user){
        // $rolename = 'Admin';
        // $payload = $this->getAdminRolePayload($rolename);
        // $role = Role::where('code', Str::slug($rolename))->with(['role_has_permissions'])->first();
        // if(!$role){

        //     $role = $this->createRole($payload, 1);
        // }else{
        //     $role->role_has_permissions()->delete();
        //     $this->assignPermissions($role, $payload['permissions']??[]);
        // }
        $role = $this->resetAdminRolePermissions();
        $user->roles()->sync([$role->id]);
        // $this->clearCache($role);

        return $role;
    }

    public function resetAdminRolePermissions(){
        $rolename = 'Admin';
        $payload = $this->getAdminRolePayload($rolename);
        $role = Role::where('code', Str::slug($rolename))->with(['role_has_permissions'])->first();
        if(!$role){
            $role = $this->createRole($payload, 1);
        }else{
            $role?->role_has_permissions()->delete();
            $this->assignPermissions($role, $payload['permissions']??[]);
        }
        $this->clearCache($role);


        return $role;
    }

    // public function


}
