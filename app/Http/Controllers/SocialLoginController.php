<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Student;
use App\User;
use App\UserSocial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Tymon\JWTAuth\JWTAuth;

class SocialLoginController extends Controller
{
    //
    
    protected $auth;
    protected $new;
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
        $this->middleware('social')->except('update');
        $this->middleware('auth:api')->only('update');
    }
    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();
    }

    public function callback($service)
    {

        try {
            $serviceUser = Socialite::driver($service)->stateless()->user();

        } catch(InvalidStateException $e) {
            return redirect(env('CLIENT_BASE_URL'). '?error=Unable to Login using ' . $service);
        }
       

        $email = $serviceUser->getEmail();
        
        if ($service != 'google')
        {
            $email = $serviceUser->getId() . '@'. $service . 'local';
        }

        $user = $this->getExistingUser($serviceUser, $email, $service);
        
        if (!$user) {
            $this->new = true;
            $user = User::create([
                'first_name' => explode(' ', $serviceUser->getName())[0],
                'last_name' => explode(' ', $serviceUser->getName())[1],
                'email' => $email,
                'email_verified_at' => Carbon::now(),
                'password' => ''
            ]);
            
        }

        
        if ($this->needsToCreateSocial($user, $service)) {
            UserSocial::create([
                'user_id' => $user->id,
                'social_id' => $serviceUser->getId(),
                'service' => $service
            ]);
        };

        // $token = auth('student')->attempt(['email' => $user->email, 'password'=>$user->password]);
        if ($this->new) {
            return redirect(env('CLIENT_BASE_URL'). '?token='. $this->auth->fromUser($user)  . '&new=true'); 
           
        }else {
          return redirect(env('CLIENT_BASE_URL'). '?token='. $this->auth->fromUser($user));
        }
        // dd($serviceUser);
    }

    public function needsToCreateSocial(User $user, $service)
    {
        return !$user->hasSocialLinked($service);
    }

    public function getExistingUser($serviceUser, $email , $service)
    {
        if ($service == 'google') {

            return User::where('email', $email)->orWhereHas('social', function($q) use ($serviceUser, $service) {
                $q->where('social_id', $serviceUser->getId())->where('service', $service);
            })->first();

        }else {
            $userSocial = UserSocial::where('social_id', $serviceUser->getId())->first();


            return $userSocial ? $userSocial->user : null;
        }

        
    }

    public function update(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'mobile_number' => 'required'
        ]);

        $user = auth('api')->user();

        $user->update([
            'role' => $request->role,
            'mobile_number' => $request->mobile_number
        ]);

        return response()->json([
            'message' => 'Profile Updated',
            'user' => $user
        ]);
    }
}
