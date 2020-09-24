<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TutorInfoResource extends JsonResource
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
          'first_name' => $this->first_name,
           'last_name' => $this->last_name,
           'program' => $this->program,
            'institution' => $this->institution,
            'short_description' => $this->short_description,
            'detailed_description' => $this->detailed_description,
            'mobile_number' => $this->mobile_number,
            'email' => $this->mobile_number,
            'position' => $this->position,
            'image' => $this->image,
            'quizpacks' => $this->quizpacks
        ];
    }
}
