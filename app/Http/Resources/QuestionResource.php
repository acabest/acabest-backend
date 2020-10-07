<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'question' => $this->question,
            'optionA' => $this->optionA,
            'optionB' => $this->optionB,
            'optionC' => $this->optionC,
            'optionD' => $this->optionD,
            'optionE' => $this->optionE,
            'answer' => $this->answer,
            'answer_explanation' => $this->answer_explanation,
            'image' => $this->image
        ];
    }
}
