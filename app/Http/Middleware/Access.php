<?php

namespace App\Http\Middleware;
use Config;
use Closure;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
      switch($role){
        case 'admin':
        if(!\Auth::user()){
          if($request->ajax())
            return response('Access Denied')->setStatusCode(403);
          return redirect()->route('home');
        }else
            if($request->user()->is_admin == "main_admin"){
                break;
            }
            else{
                if($request->user()->is_admin != config('EnvSettings.REGION')."_admin"){
                    return redirect()->route('home');
                }
            }
        break;
        default:
        return redirect()->route('home');
        break;
      }

      return $next($request);
    }
}
