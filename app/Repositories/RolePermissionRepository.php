<?php


namespace App\Repositories;

use App\Module;
use App\Role;
use App\RoleHasPermission;
use App\User;
// use App\Models\SuperAdmin\ClientHasModule;
use Illuminate\Support\Str;
// use App\Models\Role;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Routing\Route;


class RolePermissionRepository
{
    private $use_cache;
    private $subKeys;
    public $rolePermissionSubKeys;

    public function __construct(private $client_id = null){
        $this->use_cache = false;
        $this->rolePermissionSubKeys = [
            'role_permissions' => 'role_permissions',
            'user_roles' => 'user_roles',
        ];
        $this->subKeys = [
            ...$this->rolePermissionSubKeys,

        ];

        // if(!$client_id){
        //     $this->client_id = get_client_id();
        // }
    }

    //region gettor settor
    public static function get_active_modules(){
        $modules = Module::where('status', 'active')->with(['permission_types'])->get();
        return $modules;
    }


    // public function get_client_modules(){
    //     return ClientHasModule::where('client_id', $this->client_id)->get();
    // }


    public function get_client_active_modules(){
        // $client_module_ids = $this->get_client_modules()?->pluck('module_id')->toArray();
        $modules = Module::where('status', 'active')->with(['permission_types'])->get();
        return $modules;
    }

    public function test(){
        $role = $this->getRoleWithPermissions();
    }


    //region cache
    public function setCache($subKey, $value, User $user, $ttl = null)
    {
        // default ttl = 10 minutes (600 seconds)
        if(!$ttl){
            $ttl = now()->addMinutes(10);
        }
        $key = "client_{$this->client_id}_user_{$user->id}__{$subKey}";
        Cache::put($key, $value, $ttl);
    }

    public function getCache($subKey, User $user)
    {
        $key = "client_{$this->client_id}_user_{$user->id}__{$subKey}";
        // $key = "client_{$this->client_id}_permissions_user_{$user->id}";
        return Cache::get($key);
    }

    public function forgetCache($subKey, User $user)
    {
        $key = "client_{$this->client_id}_user_{$user->id}__{$subKey}";
        // $key = "client_{$this->client_id}_permissions_user_{$user->id}";
        Cache::forget($key);
    }

    public function clearRolePermissionsCache(User $user){
        foreach($this->rolePermissionSubKeys as $subKey){
            $this->forgetCache(subKey:$subKey, user:$user);
        }
    }


    //region helpers
    public function getRolePermissions($user, $role_ids)
    {
        // $use_session = true;
        $permissions = $this->getCache(subKey:$this->subKeys["role_permissions"], user:$user);
        if(!$permissions || !$this->use_cache){
            $client_module_ids = $this->get_client_modules()?->pluck('module_id')->toArray();
            // dd($client_module_ids);
            $permissions = RoleHasPermission::whereIn('role_id', $role_ids)
            ->with(['module', 'module_permission_type'])
            ->whereIn('module_id', $client_module_ids)
            ->select('module_id', 'module_permission_type_id')
            ->selectRaw('MAX(is_permitted) as is_permitted') // 1 wins over 0
            ->groupBy('module_id', 'module_permission_type_id')
            ->get();
            // dd($permissions);
            // session(['role_permissions' => $permissions]);
            $this->setCache(subKey:$this->subKeys["role_permissions"], value:$permissions, user:$user);
        }
        return $permissions;
    }

    public function getMethodPermission($permissions, $module_code,  $module_permission_type_code){
        return $permissions
        ->where('module.code', $module_code)
        ->where('module_permission_type.code', $module_permission_type_code)
        ->first();
    }

    public function getUserRoles(User $user){
        $user_roles = $this->getCache(subKey:$this->subKeys["user_roles"], user:$user);
        if(!$user_roles || !$this->use_cache){
            $user_roles = $user?->roles()?->get()?->toArray();
            $this->setCache(subKey:$this->subKeys["user_roles"], value:$user_roles, user:$user);
        }
        return $user_roles;
    }

    public function getUserRoleIds(User $user){
        $roles = $this->getUserRoles($user);
        $role_ids = collect($roles)?->pluck('id')->toArray()??[];
        // dd($role_ids,$roles);
        return $role_ids;
    }


    public function hasPermission(User $user, string $module_code, string $permssion_type_code){
        $roleIds = $this->getUserRoleIds($user);
        $permissions = $this->getRolePermissions($user, $roleIds);
        return $this->getMethodPermission($permissions, $module_code,  $permssion_type_code)?->is_permitted == 1;
    }



    public function readController($controller){
        $details = [];
        $details['module_code'] = property_exists($controller, 'module_code') ? $controller->module_code : null;
        $details['permission_methods'] = property_exists($controller, 'permission_methods') ? $controller->permission_methods : [];
        $details['ignored_permission_methods'] = property_exists($controller, 'ignored_permission_methods') ? $controller->ignored_permission_methods : [];

        return $details;
    }

    public function readRoute(Route $route){
        $details = [];
        $details['middlewares'] = $route->gatherMiddleware();
        $details['controller'] = $route->getController();
        $details['method'] = $route->getActionMethod();
        return $details;

    }


    //region middleware func
    public function handleMiddlewarePermissions(Request $request, Closure $next, User $user){

        // return $next($request);

        $route = $request->route();
        $routeDetails = $this->readRoute($route);

        $controller = $routeDetails['controller'];
        $method = $routeDetails['method'];


        $controllerDetails = $this->readController($controller);

        if (in_array($method, $controllerDetails['ignored_permission_methods'])) {
            return $next($request);
        }



        $module_permission_type_code = $controllerDetails['permission_methods'][$method]['module_permission_type_code']??null;


        if($controllerDetails['module_code'] && $module_permission_type_code){
            $permission = $this->hasPermission($user,  $controllerDetails['module_code'], $module_permission_type_code);
            if(!$permission){
                if ($request->expectsJson()) {
                    // abort(403, "Unauthorized access");
                    return response()->json([
                        'message' => 'Unauthorized access',
                        'details' => 'target_method:'.$method,
                        'code' => 403
                    ], 403);
                } else {
                    return redirect()->route('client.losted')->with('error', 'No permissions found for this module request');
                }


                abort(403,"No permissions found for this module request");
            }
            if($permission){
                return $next($request);
            }else{


                if ($request->expectsJson()) {
                    abort(403, "Unauthorized access");
                } else {
                    return redirect()->route('client.losted')->with('error', 'Unauthorized access');
                }

                // abort(403, "Unautharize Access");

                return response()->json([
                    'message' => 'Unauthorized access',
                    'details' => 'target_method:'.$method,
                    'code' => 403
                ], 403);
            }
        } else {
            if ($request->expectsJson()) {
                // abort(403, "Unauthorized access");

                return response()->json([
                    'message' => 'Unauthorized access',
                    'details' => 'target_method:'.$method,
                    'code' => 403
                ], 403);
            } else {
                return redirect()->route('client.losted')->with('error', 'No permissions defined for this module');
            }
            abort(403, "No permissions defined for this module");
        }
    }

    //region get auth routes
    public function getPermittedRoutes(string $type = null, User $user = null)
    {
        if(!$user){
            $user = auth()->user();
        }

        $allowedRoutes = [
            'web' => [],
            'api' => [],
        ];

        foreach (\Route::getRoutes() as $route)
        {

            $routeDetails = $this->readRoute($route);
            $controller = $routeDetails['controller'];
            $method = $routeDetails['method'];
            $middlewares = $routeDetails['middlewares'];

            // Pre-filter by type if provided
            if ($type === 'api' && !in_array('api', $middlewares)) {
                continue;
            }
            if ($type === 'web' && in_array('api', $middlewares)) {
                continue;
            }

            if (!$controller) {
                continue;
            }

            $controllerDetails = $this->readController($controller);

            $module_permission_type_code = $controllerDetails['permission_methods'][$method]['module_permission_type_code'] ?? null;

            $isAllowed = false;

            if (in_array($method, $controllerDetails['ignored_permission_methods'])) {
                // Case 1: ignored → always allowed
                $isAllowed = true;
            } elseif ($controllerDetails['module_code'] && $module_permission_type_code) {
                // Case 2: check permissions
                $isAllowed = $this->hasPermission($user,  $controllerDetails['module_code'], $module_permission_type_code);
            }

            if ($isAllowed) {
                $routeInfo = [
                    'name'   => $route->getName(),
                    'uri'    => $route->uri(),
                    'method' => $method,
                    'http'   => array_diff($route->methods(), ['HEAD']), // filter HEAD out
                ];

                if (in_array('api', $middlewares)) {
                    $allowedRoutes['api'][] = $routeInfo;
                } else {
                    $allowedRoutes['web'][] = $routeInfo;
                }
            }
        }

        // If type is given, return only that group
        return $type ? $allowedRoutes[$type] : $allowedRoutes;
    }

    //region check route auth
    public function getRoutePermission(string $routeName, User $user = null): bool
    {
        if(!$user){
            $user = auth()->user();
        }

        // Find route by name
        $route = \Route::getRoutes()->getByName($routeName);
        if (!$route) {
            return false; // no such route
        }
        $routeDetails = $this->readRoute($route);
        $controller = $routeDetails['controller'];
        $method = $routeDetails['method'];
        // $middlewares = $routeDetails['middlewares'];

        if (!$controller) {
            return false;
        }

        $controllerDetails = $this->readController($controller);

        $module_permission_type_code = $controllerDetails['permission_methods'][$method]['module_permission_type_code'] ?? null;

        // Case 1: ignored → always allowed
        if (in_array($method, $controllerDetails['ignored_permission_methods'])) {
            return true;
        }

        // Case 2: check role permission
        if ($controllerDetails['module_code'] && $module_permission_type_code) {
            return $this->hasPermission($user,  $controllerDetails['module_code'], $module_permission_type_code);
        }

        return false;
    }




    //region fixed permissions
    public function hasGlobalAccess(User $user, string $module_code){
        return $this->hasPermission( $user,  $module_code, 'global-access');
    }

    public function hasReadAccess(User $user, string $module_code){
        return $this->hasPermission( $user,  $module_code, 'read');
    }

}
