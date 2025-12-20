<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Route;

class adminmanager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::User()->account_type == 'admin' or Auth::User()->account_type == 'manager'){
            return $next($request);
        }else{
            Session::flash('error', 'You are not Eligible !');
            return redirect()->back();
        }
    }
}
