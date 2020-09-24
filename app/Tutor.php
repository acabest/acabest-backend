<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Tutor extends Authenticatable implements JWTSubject
{
    //
    protected $guarded = [];

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

    protected $hidden = [
        'password'
    ];

    public function quizpacks()
    {
        return $this->hasMany(QuizPack::class);
    }

    public function program()
    {
        return $this->belongsTo(Category::class);
    }
}
