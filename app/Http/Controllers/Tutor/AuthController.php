<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Resources\TutorInfoResource;
use App\Tutor;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'category_id' => 'required',
            'position' => 'required',
            'institution' => 'required',
            'short_description' => 'required',
            'detailed_description' => 'required',
            'mobile_number' => 'required',
            'email' => 'required|unique:tutors',
            'password' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg'
        ]);


//        $imageName = time() . '.' . $request->image->getClientOriginalExtension();
        $path = Storage::disk(getenv('STORAGE'))->putFile('/images/profiles', new File($request->image));

//        $path = getenv('APP_ENV') == 'local' ? $path : getenv('AWS_BUCKET_URI') . $path;
        $path = getenv('AWS_BUCKET_URI') . $path;

        $tutor = Tutor::create($request->all());

        $tutor->update([
            'password' => Hash::make($request->password),
            'image' => $path
        ]);

//        $request->image->move(public_path('images/profiles'), $imageName);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (! $token = auth('tutor')->attempt($credentials))
        {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 422);
        }

        return $this->respondWithToken(new TutorInfoResource($tutor), $token);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth('tutor')->attempt($credentials))
        {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 422);
        }
        $tutor = Tutor::where(['email' => $request->email])->first();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('tutor')->factory()->getTTL() * 60,
            'tutor' => new TutorInfoResource($tutor)
        ]);
    }

     /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($tutor, $token)
    {
        return response()->json([
            'tutor' => $tutor,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('tutor')->factory()->getTTL() * 60
        ]);
    }
}
