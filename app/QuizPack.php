<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizPack extends Model
{
    //
    protected $fillable = ['title', 'short_description', 'detailed_description',
                            'topic', 'course_id', 'thumbnail_image'];
}
