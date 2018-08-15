<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RegionRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Config;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RegionRepository $regionModel)
    {   
        $this->middleware('guest')->except('logout');
        $this->region = $regionModel;  
    }

    public function showLoginForm()
    {   
        $regions = $this->getRegion();
        $Rgns = view(config('EnvSettings.THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        return view('auth.login', ['region'=>$Rgns]);
    }

    protected function attemptLogin(Request $request)
    {   
        return $this->guard()->attempt(
            $this->credentials($request), ($request->remember == 'on')
        );
    }

    protected function credentials(Request $request)
    {   
        $login = $this->username();
        $password = $request->password;

        $user = User::where($login, "=", $request->login )->first(); /*Если не существует логина*/
        if($user == NULL){
          return $array = array($login => $request->login, 'password'=> $password, 'verified' => true, 'region' => env('REGION'));
        }
            if($user->is_admin == "main_admin"){
                $array = array($login => $request->login, 'password'=> $password, 'verified' => true, 'region' => "full");
            }
            else{
                $array = array($login => $request->login, 'password'=> $password, 'verified' => true, 'region' => env('REGION'));
            }    

        return $array;
    } 
}
    
