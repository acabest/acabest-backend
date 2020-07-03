<?php

namespace App\Http\Resources;

use App\Course;
use App\Tutor;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizpackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tutor = Tutor::find($this->tutor_id);
        return [
            'id' => $this->id,
            'tutor' => $tutor->first_name . ' ' . $tutor->last_name,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'detailed_description' => $this->detailed_description,
            'course' => Course::find($this->course_id)->name,
            'time' =>  $this->time,
            'thumbnail_image' => $this->thumbnail_image,
            'created_at' => $this->created_at,
            'questions' => $this->questions
        ];
    }
}
