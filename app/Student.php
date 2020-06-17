<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Student extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;
    //
    protected $fillable = [
        'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'program_id',
        'mobile_number'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function program()
    {
        return $this->hasOne(Program::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendEmailVerificationNotification()
    {
       $this->notify(new Notifications\StudentVerifyEmailNotification);
    }
}
