<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class other
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
        if(Auth::user()->account_type == 'staff' or Auth::user()->account_type == 'customer' or Auth::user()->account_type == 'supplier'){
            Session::flash('error','You are not Autherized!');
            return redirect()->back();
        }else{
            return $next($request);
        }
        
    }
}
