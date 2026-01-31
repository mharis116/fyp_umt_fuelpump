<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\Controller;

use App\Role;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

use App\Repositories\RoleRepository;
use App\Repositories\RolePermissionRepository;

class RoleController extends Controller
{
    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(private RoleRepository $roleRepository, private RolePermissionRepository  $rolePermissionRepository){
        $this->module_code = 'roles-management';
        $this->ignored_permission_methods = [
            // 'create',
            // 'update',
            // 'destroy',
        ];
        $this->permission_methods = [
            'index' => [
                'module_permission_type_code' => 'read',
            ],
            'show' => [
                'module_permission_type_code' => 'read',
            ],
            'edit' => [
                'module_permission_type_code' => 'read',
            ],
            'update' => [
                'module_permission_type_code' => 'update',
            ],
            'create' => [
                'module_permission_type_code' => 'create',
            ],
            'store' => [
                'module_permission_type_code' => 'create',
            ],
            'destroy' => [
                'module_permission_type_code' => 'delete',
            ],
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>true,
            ]
        ];
        $roles = Role::where('is_system', 0)->paginate($_GET['perPage'] ?? 20)
        ->withQueryString();

        return view('role.index', compact('roles','breadcrumbs'))->with('i', ($request->input('page', 1) - 1) * $roles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("roles.create"),
                'active'=>true,
            ]
        ];
        $role = new Role();
        $modules = $this->rolePermissionRepository->get_client_active_modules();
        // dd($modules);
        return view('role.create', compact('role','breadcrumbs', 'modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'name' => ['required', Rule::unique('roles')],
			'landing_relative_url' => 'required',
			'description' => 'nullable',

			'permissions' => ['required', 'array'],
            'permissions.*.module_id' => ['required', Rule::exists('modules', 'id')],
            'permissions.*.module_permission_type_id' => ['required', Rule::exists('module_permission_types', 'id')],
            'permissions.*.is_permitted' => ['required', Rule::in(0, 1)]
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $payload = $validator->validated();
        // dd($payload);

        // $data['created_by'] = auth()->user()->id;

        // $data['business_id'] = auth()->user()->business_id;

        // Role::create($data);

        try{
            $this->roleRepository->createRole($payload);
        }catch(\Exception $e){
            throw $e;
        }


        return Redirect::route('roles.index')->with('success', 'Role created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("roles.show",$id),
                'active'=>true,
            ]
        ];
        $role = Role::findOrFail($id);

        return view('role.show', compact('role','breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("roles.edit", $id),
                'active'=>true,
            ]
        ];
        $role = $this->roleRepository->get_role_details($id, 0);
        $modules = $this->rolePermissionRepository->get_client_active_modules();


        return view('role.edit', compact('role','breadcrumbs', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $role_id)
    {
        $role = Role::findOrFail($role_id);
        if(!$role){
            abort(404);
        }
        $validator = Validator::make($request->all(), [
			'name' => ['required', Rule::unique('roles')->ignore($role_id)],
			'landing_relative_url' => 'required',
			'description' => 'nullable',

			'permissions' => ['required', 'array'],
            'permissions.*.module_id' => ['required', Rule::exists('modules', 'id')],
            'permissions.*.module_permission_type_id' => ['required', Rule::exists('module_permission_types', 'id')],
            'permissions.*.is_permitted' => ['required', Rule::in(0, 1)]
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $payload = $validator->validated();
        // dd($payload);
        // $data['updated_by'] = auth()->user()->id;

        // $data['business_id'] = auth()->user()->business_id;

        // $role->update($data);

        try{
            $this->roleRepository->updateRole($role, $payload);
        }catch(\Exception $e){
            throw $e;
        }


        return Redirect::back()->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        // if($role->users)
        $role->delete();
        return Redirect::route('roles.index')->with('success', 'Role deleted successfully');
    }
}


// manage is_system
