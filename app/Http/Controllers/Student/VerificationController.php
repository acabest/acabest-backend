<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{

    
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only('resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

     /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
    
        Auth::guard('api')->login(User::findOrFail($request->route('id')));


        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            return response()->json([
                'message' => 'Signature expired'
            ], 403);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            return response()->json([
                'message' => 'Signature expired'
            ], 403);
        }

        if ($request->user()->hasVerifiedEmail()) {

            return response()->json([
                'message' => 'Student already verified'
            ]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'message' => 'Successfully verified'
        ]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        
        if ($request->user()->hasVerifiedEmail()) {
            
            return response()->json([
                'message' => 'Student already verified'
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        if ($request->wantsJson()) 
        {
            return response()->json([
                'message' => 'Email sent'
            ]);
        }

        return back()->with('resent', true);
    }
}
