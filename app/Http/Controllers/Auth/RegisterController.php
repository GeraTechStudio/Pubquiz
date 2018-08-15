<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PubQuizRegistration;
use App\Repositories\RegionRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->region = $regionModel;  
        $this->middleware('guest');
    }


     public function showRegistrationForm()
    {   
        $regions = $this->getRegion();
        $Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        return view('auth.register', ['region'=>$Rgns]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {   
        return Validator::make($data, [
            'region' => 'required|string|max:150',
            'login' => 'required|string|max:60|unique:users',
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users',
            'tel' => 'required|string|min:10|max:20|unique:users',
            'password' => 'required|string|min:6|alpha_dash|confirmed',
        ]);
    }


    public function confirmEmail(Request $request, $token)
    {
        User::whereToken($token)->firstOrFail()->confirmEmail();
        $request->session()->flash('message', 'Учетная запись подтверждена. Войдите под своим именем.');
        return redirect('login');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        Mail::to($user)->send(new PubQuizRegistration($user));
        $request->session()->flash('message', 'На ваш адрес было выслано письмо с подтверждением регистрации.');
        return back();
    } 


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \CopyShop\User
     */
    protected function create(array $data)
    {
        return User::create([
            'region' => $data['region'],
            'login' => $data['login'],
            'name' => $data['name'],
            'email' => $data['email'],
            'tel' => $data['tel'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
