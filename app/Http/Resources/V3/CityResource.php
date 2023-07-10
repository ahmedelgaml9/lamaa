<?php

namespace App\Http\Resources\V3;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
