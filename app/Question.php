<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //

    protected $fillable = ['question', 'optionA', 'optionB', 'optionC', 'optionD',
                            'optionE', 'answer', 'answer_explanation', 'image' ];

    public function quizpack()
    {
        return $this->belongsTo(QuizPack::class);
    }
}
