<?php

namespace App\Http\Controllers;

use App\QuizPack;
use Illuminate\Http\Request;

class QuizpackController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth:tutor');
    }
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'short_description' => 'required',
            'detailed_description' => 'required',
            'course_id' => 'required',
            'topic' => 'required',
            'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $alreadyAvailable = QuizPack::where([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'detailed_description' => $request->detailed_description,
            'course_id' => $request->course_id,
            'topic' => $request->topic
        ])->get()->first();

        if ($alreadyAvailable) {
            return response()->json([
                'message' => 'Quick Pack already exists'
            ]);
        }
        $imageName = time() . '.' . $request->thumbnail_image->getClientOriginalExtension();

        $quizpack = auth('tutor')->user()->quizpacks()->create($request->all());

        $quizpack->update([
            'thumbnail_image' => $imageName
        ]);

        $request->thumbnail_image->move(public_path('images/thumbnails'), $imageName);

        return response()->json([
            'message' => 'Quizpack created',
            'quizpack' => $quizpack
        ], 201);

    }
}
