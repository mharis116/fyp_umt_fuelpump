<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Route;
// use Illuminate\Contracts\Routing\Middleware;
class admin


{
    public function __construct(Route $route)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $d = $request->route()->getActionName();
        if(Auth::User()->account_type == 'admin' or strrchr($d,"@") == '@index' or strrchr($d,"@") == '@create' or strrchr($d,"@") == '@show' or strrchr($d,"@") == '@store'){
            return $next($request);
        }else{
            Session::flash('error', 'You are not Eligible !');
            return redirect()->back();
        }
    }
}
