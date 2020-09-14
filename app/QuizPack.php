<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizPack extends Model
{
    //
    protected $fillable = ['title', 'short_description', 'detailed_description',
                            'time', 'category_id', 'subcategory_id',
        'subsubcategory_id', 'thumbnail_image'];
    /**
     * @var mixed
     */


    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
