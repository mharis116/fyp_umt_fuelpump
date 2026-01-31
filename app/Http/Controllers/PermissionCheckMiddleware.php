<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repositories\RolePermissionRepository;

class PermissionCheckMiddleware
{

    // public function __construct(private RolePermissionRepository $rolePermissionRepository){

    // }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user(); // automatically uses guard (web/api)
        // dd((new RolePermissionRepository)->getPermittedRoutes(null, $user));
        
        return (new RolePermissionRepository)->handleMiddlewarePermissions($request, $next, $user);


        // $user = auth()->user(); // automatically uses guard (web/api)
        // $controller = $request->route()->controller;
        // $method = $request->route()->getActionMethod();



        // $ignored_permission_methods = property_exists($controller, 'ignored_permission_methods') ? $controller->ignored_permission_methods : [];
        // if (in_array($method, $ignored_permission_methods)) {
        //     return $next($request);
        // }

        // $permissions = $this->rolePermissionRepository->getRolePermissions($user?->roles()->pluck('role_id')->toArray()??[]);

        // $module_code = property_exists($controller, 'module_code') ? $controller->module_code : null;
        // $permission_methods = property_exists($controller, 'permission_methods') ? $controller->permission_methods : [];

        // $module_permission_type_code = $permission_methods[$method]['module_permission_type_code']??null;


        // if($module_code && $module_permission_type_code){
        //     $permission = $permissions
        //     ->where('module.code', $module_code)
        //     ->where('module_permission_type.code', $module_permission_type_code)
        //     ->first();
        //     // dd($permissions->toArray(), $module_permission_type_code, $module_code, $permission->toArray());
        //     // $permission = null;
        //     if(!$permission){
        //         abort(403,"No permissions found for this module's method in DB.");
        //     }
        //     if($permission->is_permitted == 1){
        //         return $next($request);
        //     }else{
        //         abort(403, "Unautharize Access");
        //     }


        //     // dd("no module code found");
        // } else {
        //     abort(403, "No permissions defined for this module");
        // }

        // dd('hello', $user, $controller, $method, $module_code,$permission_methods );
        // return $next($request);
    }
}
