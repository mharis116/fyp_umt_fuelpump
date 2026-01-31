<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Module;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

// use App\Repositories\ClientRepository;
use App\Repositories\RoleRepository;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // Get all defined modules as a collection
        $modules = $this->modules();
        $max_module_id = 0;
        $max_permisssion_type_id = 0;


        Module::whereNotIn(
            'id', $modules->pluck('module_id')->toArray()
        )->delete();

        // Loop through modules to create/update
        // Module::where('id','>','0')->delete();
        foreach($modules as $moduleData){

            $module = Module::updateOrCreate([
                'id'   => $moduleData['module_id'],
            ],[
                'code' => Str::slug($moduleData['name']),
                'name'   => $moduleData['name'],
                'status' => $moduleData['status'],
            ]);

            $max_module_id = $moduleData['module_id'] > $max_module_id?$moduleData['module_id']:$max_module_id;

            // Permission type codes defined for this module
            $definedPermissions = collect($moduleData['module_permission_types'] ?? [])
            ->pluck('name')
            ->map(fn($name) => Str::slug($name))
            ->toArray();

            // Delete old permission types not in definition
            $module->permission_types()->whereNotIn('code', $definedPermissions)->delete();

            $module->permission_types()->delete();
            // Update or create permission types
            foreach($moduleData['module_permission_types'] ?? [] as $permissionData){
            // collect($moduleData['module_permission_types'] ?? [])->each(function ($permissionData) use ($module) {
                $module->permission_types()->updateOrCreate([
                    'id' => $permissionData['id'],
                ],[
                    'code' => Str::slug($permissionData['name']),
                    'name' => $permissionData['name']
                ]);


                // dd($module->id);
                // break;

                $max_permisssion_type_id = $permissionData['id'] > $max_permisssion_type_id?$permissionData['id']:$max_permisssion_type_id;

            }
        }

        // $this->syncClientAdminRolePermissions();

        $this->command->info('max_module_id = '.$max_module_id.' && max_permisssion_type_id = '.$max_permisssion_type_id);

    }

    public function syncClientAdminRolePermissions(){
        // $clientRepo = app(ClientRepository::class);

        // foreach($clientRepo->getClients()??[] as $client){
            // $this->command->info('Client: '.$client->company_name.' , Id: '.$client->id);
            // set_client_database($client->id);
            $roleRepo = app(RoleRepository::class);
            $roleRepo->resetAdminRolePermissions();
        // }
    }

    public function modules(): object
    {
        // active, inactive
        $modules = [
            [
                'module_id' => 1,
                'name' => "Roles Management",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 1,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 3,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 4,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 5,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 2,
                'name' => "Users Management",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 6,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 7,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 8,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 9,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 10,
                        'name' => 'Global Access',
                    ],
                    [
                        'id' => 11,
                        'name' => 'Change Password'
                    ],
                ]
            ],

            [
                'module_id' => 3,
                'name' => "Main Dashboard",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 12,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 13,
                        'name' => 'Global Access',
                    ],
                ]
            ],

        ];
        return collect($modules);
    }
}

