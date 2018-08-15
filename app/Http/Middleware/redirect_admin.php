<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class redirect_admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->is_admin == "main_admin" || Auth::user()->is_admin == config('EnvSettings.REGION')."_admin"){
            return redirect('/admin');
        }
        return $next($request);
    }
}
