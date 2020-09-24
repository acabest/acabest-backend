<?php

namespace App\Http\Controllers\Tutor;

use App\QuizPack;
use Illuminate\Http\File;
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
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'subsubcategory_id' => 'required',
            'time' => 'required|integer',
            'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $alreadyAvailable = QuizPack::where([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'detailed_description' => $request->detailed_description,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'subsubcategory_id' => $request->subsubcategory_id,
            'time' => $request->time
        ])->get()->first();

        if ($alreadyAvailable) {
            return response()->json([
                'message' => 'Quick Pack already exists'
            ]);
        }
        $imageName = time() . '.' . $request->thumbnail_image->getClientOriginalExtension();

        $path = Storage::disk(getenv('STORAGE'))->putFile('/images/quizpack-thumbnails', new File($request->thumbnail_image));

//        $path = getenv('APP_ENV') == 'local' ? $path : getenv('AWS_BUCKET_URI') . $path;
        $path = getenv('AWS_BUCKET_URI') . $path;

        $quizpack = auth('tutor')->user()->quizpacks()->create($request->all());

        $quizpack->update([
            'thumbnail_image' => $path
        ]);


        return response()->json([
            'message' => 'Quizpack created',
            'quizpack' => new QuizpackResource($quizpack)
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

        $request->validate([
            'title' => 'required',
            'short_description' => 'required',
            'detailed_description' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'subsubcategory_id' => 'required',
            'time' => 'required|integer',
            'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $path = Storage::disk(getenv('STORAGE'))->putFile('/images/quizpack-thumbnails', new File($request->thumbnail_image));

        $path = getenv('AWS_BUCKET_URI') . $path;


        Storage::disk(getenv('STORAGE'))->delete('/images/quizpack-thumbnails/' . $quizpack->thumbnail_image);

        $quizpack->update($request->all('title', 'short_description', 'detailed_description',
                                        'category_id', 'subcategory_id', 'subsubcategory_id',  'time') + ['thumbnail_image' => $path]);

        return response()->json([
            'message' => 'Quizpack Updated',
            'quizpack' => new QuizpackResource($quizpack)
        ]);
    }

    public function deleteQuizpack(QuizPack $quizpack)
    {

        if (auth('tutor')->user()->id != $quizpack->tutor_id)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        Storage::disk(getenv('STORAGE'))->delete('/images/quizpack-thumbnails/' . $quizpack->thumbnail_image);

        $quizpack->delete();

        return response()->json(['success' => true, 'message' => 'Quizpack deleted']);
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

            $path = Storage::disk(getenv('STORAGE'))->putFile('/images/answer-images/' . $request->image);

            $path =  getenv('AWS_BUCKET_URI') . $path;
            $question = $quizpack->questions()->create($request->all());

            $question->update([
                'image' => $path
            ]);


        } else {
            $question = $quizpack->questions()->create($request->all());
        }


        return response()->json([
            'message' => 'question created',
            'question' => $question
        ], 201);
    }

    public function updateQuestion(Request $request, QuizPack $quizpack, Question $question)
    {
        if (auth('tutor')->user()->id != $quizpack->tutor_id)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

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
            $imageToDelete = $question->image;

            $path = Storage::disk(getenv('STORAGE'))->putFile('/images/answer-images/' . $request->image);

            $path = getenv('AWS_BUCKET_URI') . $path;

            $question->update($request->all('question', 'optionA', 'optionB', 'optionC', 'optionD', 'optionE') +
                                                        ['image' => $path]);


            Storage::disk(getenv('STORAGE'))->delete('/images/answer-images/' .$imageToDelete);
        } else {
            $question = $question->update($request->all());
        }

        return response()->json(['success' => true, 'question' => $question]);
    }

    public function deleteQuestion(Request $request, QuizPack $quizpack, Question $question)
    {
        Storage::disk(getenv('STORAGE'))->delete('/images/answer-images/' .$question->image);

        $question->delete();

        return response()->json(['success' => true, 'message' => 'Question deleted']);
    }
}
