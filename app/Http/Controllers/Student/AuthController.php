<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStudentRequest;
use App\Http\Resources\StudentResource;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
// use Illuminate\Foundation\Auth\ResetsPasswords;

class AuthController extends Controller
{
  
    // use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('auth:api')->only(['studentDetails']);
    }
    public function register(RegisterStudentRequest $request)
    {
        $student = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_number' => $request->mobile_number,
            'program_id' => $request->program_id,
            'role' => 'student'
        ]);
        
        $student = User::findOrFail($student->id);

        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials))
        {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return $this->respondWithToken($student, $token);
        
    }


    public function login(Request $request)
    {   
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials))
        {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 422);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($student, $token)
    {
        return response()->json([
            'student' => $student,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function studentDetails()
    {
        return new StudentResource(auth('api')->user());
    }

}

