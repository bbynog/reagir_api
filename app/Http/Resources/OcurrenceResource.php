<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OcurrenceResource extends JsonResource
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
            'id' => $this->id,
            'violence_type' => $this->violence_type,
            'what_to_do' => $this->what_to_do,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at            
        ];
    }
}
