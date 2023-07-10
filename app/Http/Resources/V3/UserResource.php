<?php

namespace App\Http\Resources\V3;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    
    public function toArray($request)
    {
        $customer = $this;

        return [

            'id' => $customer->id,
            'name' => $customer->name,
            'mobile' => $customer->mobile,
            'email' => $customer->email,
            'photo'=>isset($customer->avatar) ? url($customer->avatar):'',
            'city_name' => $customer->city?$customer->city->name:'--',
            'country_code' => $customer->country_code,
            'balance' => $customer->balance,
        ];
    }

}
