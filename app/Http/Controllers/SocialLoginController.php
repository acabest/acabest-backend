<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Student;
use App\UserSocial;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Tymon\JWTAuth\JWTAuth;

class SocialLoginController extends Controller
{
    //
    
    protected $auth;
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
        $this->middleware('social');
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
            return redirect(env('CLIENT_BASE_URL'). '?error=Unable to Login using' . $service);
        }
       

        $email = $serviceUser->getEmail();
        
        if ($service != 'google')
        {
            $email = $serviceUser->getId() . '@'. $service . 'local';
        }

        $user = $this->getExistingUser($serviceUser, $email, $service);
        if (!$user) {
            $user = Student::create([
                'first_name' => explode(' ', $serviceUser->getName())[0],
                'last_name' => explode(' ', $serviceUser->getName())[1],
                'email' => $email,
                'password' => ''
            ]);
        }

        if ($this->needsToCreateSocial($user, $service)) {
            UserSocial::create([
                'student_id' => $user->id,
                'social_id' => $serviceUser->getId(),
                'service' => $service
            ]);
        };

        // $token = auth('student')->attempt(['email' => $user->email, 'password'=>$user->password]);
        
        return redirect(env('CLIENT_BASE_URL'). '?token='. $this->auth->fromUser($user));
        // dd($serviceUser);
    }

    public function needsToCreateSocial(Student $student, $service)
    {
        return !$student->hasSocialLinked($service);
    }

    public function getExistingUser($serviceUser, $email , $service)
    {
        if ($service == 'google') {

            return Student::where('email', $email)->orWhereHas('social', function($q) use ($serviceUser, $service) {
                $q->where('social_id', $serviceUser->getId())->where('service', $service);
            })->first();

        }else {
            $userSocial = UserSocial::where('social_id', $serviceUser->getId())->first();


            return $userSocial ? $userSocial->user : null;
        }

        
    }
}
