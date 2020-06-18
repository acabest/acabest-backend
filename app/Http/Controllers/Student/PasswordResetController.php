<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Notifications\Student\PasswordResetRequest;
use App\Notifications\Student\PasswordResetSuccess;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    //
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);

        $student = User::where([
            'email' => $request->email,
            'role' => 'student'
        ])->first();

        if (!$student) 
        {
            return response()->json([
                'message' => 'Student Email Not Found'
            ], 404);
        }
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $student->email,
            'token' => Str::random(60)
        ]);

        if ($student && $passwordReset) 
        {
            $student->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        }

        return response()->json([
            'message' => 'We have emailed your password reset link'
        ], 200);
    }

    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset)
        {
            return response()->json([
                'message' => 'The password reset token is invalid'
            ], 404);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast())
        {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid'
            ], 404);
        }

        return response()->json([
            'status' => 'OK' 
        ], 200);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if (!$passwordReset)
        {
            return response()->json([
                'message' => 'The password reset token is invalid'
            ], 404);
        }

        $student = User::where('email', $passwordReset->email)->first();

        if (!$student)
        {
            return response()->json([
                'message' => 'No student with that email'
            ], 404);
        }

        $student->password = Hash::make($request->password);

        $student->notify(
            new PasswordResetSuccess($passwordReset)
        );

        return response()->json([
            'message' => 'Password updated',
            'student' => $student
        ]);
    }
}

