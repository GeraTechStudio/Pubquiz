<?php

namespace App;

use App\Notifications\PubQuizResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends \Eloquent implements Authenticatable, 
    CanResetPasswordContract {

    use CanResetPassword;
    use AuthenticableTrait;
    use Notifiable;

    protected $fillable = [
        'login','name', 'email', 'password', 'tel','verified', 'token', 'region',
    ];

    protected $hidden = [
        'password', 'remember_token', 'is_admin', 'region',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user){
            $user->token = str_random(30);
        });
    }


    public function confirmEmail()
    {
        $this->verified = true;
        $this->token = null;

        $this->save();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PubQuizResetPassword($token));
    }
}
