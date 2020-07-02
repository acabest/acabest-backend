<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizPack extends Model
{
    //
    protected $fillable = ['title', 'short_description', 'detailed_description',
                            'time', 'course_id', 'thumbnail_image'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
