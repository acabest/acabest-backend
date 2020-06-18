<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    //
    protected $table = 'user_social';
    protected $guarded = [];
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
