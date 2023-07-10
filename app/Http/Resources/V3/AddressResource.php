<?php

namespace App\Http\Resources\V3;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            
            'id' => $this->id,
            'username' => $this->username,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'user_id'=>$this->user_id,
            'city_id'=>$this->city_id,
            'status'=>$this->status,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'set_default' => $this->set_default,

        ];
    }
}
