<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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


    public function showLinkRequestForm()
    {
        $regions = $this->getRegion();
        $Rgns = view(config('EnvSettings.THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(env('THEME').'.layouts.navigation')->render();
        return view('auth.passwords.email')->with(['region'=>$Rgns, 'navigation'=>$NVG]);
    }

}
