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
            [
                'module_id' => 4,
                'name' => "Products",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 14,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 15,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 16,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 17,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 18,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 5,
                'name' => "Suppliers",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 19,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 20,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 21,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 22,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 23,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 6,
                'name' => "Customers",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 24,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 25,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 26,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 27,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 28,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 7,
                'name' => "Sales",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 29,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 30,
                        'name' => 'Read',
                    ],
                    // [
                    //     'id' => 31,
                    //     'name' => 'Update',
                    // ],
                    [
                        'id' => 32,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 33,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 8,
                'name' => "Purchases",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 34,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 35,
                        'name' => 'Read',
                    ],
                    // [
                    //     'id' => 36,
                    //     'name' => 'Update',
                    // ],
                    [
                        'id' => 37,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 38,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 9,
                'name' => "Supplier Ledgers",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 40,
                        'name' => 'Read',
                    ],
                ]
            ],
            [
                'module_id' => 10,
                'name' => "Expenses",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 41,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 42,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 43,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 44,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 45,
                        'name' => 'Global Access',
                    ],
                    [
                        'id' => 46,
                        'name' => 'Manage Expense Types',
                    ],
                ]
            ],
            [
                'module_id' => 11,
                'name' => "Fuel Dips",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 47,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 48,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 49,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 50,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 51,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 12,
                'name' => "Stock",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 52,
                        'name' => 'Read',
                    ],
                ]
            ],
            [
                'module_id' => 13,
                'name' => "Supplier Payments",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 53,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 54,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 55,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 56,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 57,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 14,
                'name' => "Customer Payments",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 58,
                        'name' => 'Create',
                    ],
                    [
                        'id' => 59,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 60,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 61,
                        'name' => 'Delete',
                    ],
                    [
                        'id' => 62,
                        'name' => 'Global Access',
                    ],
                ]
            ],
            [
                'module_id' => 15,
                'name' => "Fuel Backups",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 63,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 64,
                        'name' => 'Transfer',
                    ]
                ]
            ],
            [
                'module_id' => 16,
                'name' => "Reports",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 65,
                        'name' => 'Credit Report',
                    ],
                    [
                        'id' => 66,
                        'name' => 'Daily Sales Report',
                    ],
                    [
                        'id' => 67,
                        'name' => 'Profit Loss Report',
                    ],
                    [
                        'id' => 68,
                        'name' => 'Expense Report',
                    ],
                    [
                        'id' => 69,
                        'name' => 'Fuel Price Report',
                    ],
                ]
            ],
            [
                'module_id' => 17,
                'name' => "Customer Ledgers",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 39,
                        'name' => 'Read',
                    ],
                ]
            ],
            [
                'module_id' => 18,
                'name' => "Hierarchy Management",
                'status' => "active",
                'module_permission_types' => [
                    [
                        'id' => 70,
                        'name' => 'Upload',
                    ],
                    [
                        'id' => 71,
                        'name' => 'Read',
                    ],
                    [
                        'id' => 72,
                        'name' => 'Update',
                    ],
                    [
                        'id' => 73,
                        'name' => 'Delete',
                    ],
                ]
            ],
        ];
        return collect($modules);
    }
}


