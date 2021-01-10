<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UniversityResource extends JsonResource
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
            'country' => $this->country,
            'name' => $this->name,
            'domains' => $this->domains,
            'ttl' => $this->ttl
        ];
    }
}
