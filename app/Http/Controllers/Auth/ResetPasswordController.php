<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\RegionRepository;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RegionRepository $regionModel)
    {
        $this->middleware('guest');
        $this->region = $regionModel;
    }

    public function showResetForm(Request $request, $token = null)
    {
        $regions = $this->getRegion();
        $Rgns = view(config('EnvSettings.THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(env('THEME').'.layouts.navigation')->render();
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email, 'region'=>$Rgns, 'navigation'=>$NVG]
        );
    }

    
}
