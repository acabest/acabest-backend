<?php

namespace App\Http\Resources;

use App\Program;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $program = Program::findOrFail($this->program_id);
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'program' => $program->name,
            'email_verified_at' => $this->email_verified_at,
            'mobile_number' => $this->mobile_number
        ];
    }
}
