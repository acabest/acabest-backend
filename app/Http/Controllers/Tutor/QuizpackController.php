<?php

namespace App\Http\Controllers\Tutor;

use App\QuizPack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuizpackResource;
use App\Question;
use Illuminate\Support\Facades\Storage;

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
            'time' => 'required|integer',
            'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $alreadyAvailable = QuizPack::where([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'detailed_description' => $request->detailed_description,
            'course_id' => $request->course_id,
            'time' => $request->time
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

        // $request->thumbnail_image->move(public_path('images/quizpack-thumbnails'), $imageName);
        $imageName = time() . '.' . $request->thumbnail_image->getClientOriginalExtension();
    
        $request->thumbnail_image->move(storage_path('app/public/images/quizpack-thumbnails/'), $imageName);
        
        return response()->json([
            'message' => 'Quizpack created',
            'quizpack' => $quizpack
        ], 201);

    }

    public function addQuestion(QuizPack $quizpack, Request $request)
    {
        // return $request->all();
        $request->validate([
            'question' => 'required',
            'optionA' => 'required',
            'optionB' => 'required',
            'answer' => 'required',
            'answer_explanation' => 'required',
        ]);

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image' => 'image|mimes:jpg,png,jpeg'
            ]);
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();

            $question = $quizpack->questions()->create($request->all());

            $question->update([
                'image' => $imageName
            ]);
            // $request->image->move(public_path('images/question-images'), $imageName);
            $imageName = time() . '.' . $request->thumbnail_image->getClientOriginalExtension();
    
            $request->thumbnail_image->move(storage_path('app/public/images/quizpack-thumbnails/'), $imageName);
        
        } else {
            $question = $quizpack->questions()->create($request->all());
        }
       

        return response()->json([
            'message' => 'question created',
            'question' => $question
        ], 201);
    }

    public function tutorQuizpacks()
    {
        return response()->json([
            'quizpacks' => QuizpackResource::collection(auth('tutor')->user()->quizpacks)
        ]);
    }

    public function updateQuizpack(QuizPack $quizpack, Request $request)
    {
        if (auth('tutor')->user()->id != $quizpack->tutor_id)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        // return $request->all();
        //  return public_path('images/quizpack-thumbnails/' . $quizpack->thumbnail_image);
        // dd(url() . 'images/quizpack-thumbnails/' . $quizpack->thumbnail_image);
        if ($request->hasFile('thumbnail_image'))
        {
            $imageName = time() . '.' . $request->thumbnail_image->getClientOriginalExtension();
    
            $request->thumbnail_image->move(storage_path('app/public/images/quizpack-thumbnails/'), $imageName);

            Storage::delete('/images/quizpack-thumbnails/' . $quizpack->thumbnail_image);

            $quizpack->update($request->all());
            $quizpack->update([
                'thumbnail_image' => $imageName
            ]);
        } else 
        {
            $quizpack->update($request->all()); 
        }

        return response()->json([
            'message' => 'Quizpack Updated',
            'quizpack' => $quizpack
        ]);
    }

    public function updateQuestion(Request $request, QuizPack $quizpack, Question $question)
    {
        if (auth('tutor')->user()->id != $quizpack->tutor_id)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json(request()->all());
    }
}
