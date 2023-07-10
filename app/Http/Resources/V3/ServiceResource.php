<?php

namespace App\Http\Resources\V3;

use App\Models\Product;
use App\Models\Size;
use App\Models\MattressType;
use App\Models\Additions;
use App\Models\CartSizes;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        
        $service = $this;
        $cartypes = MattressType::get(['id','name','price']);
        $carsizes =   CartSizes::where('service_id' , $service->id)->get(['id','size','size_price']);
        $additions =  Additions::where('service_id' , $service->id)->get(['id','addition','addition_price']);
        
        return [

            'id' => (int) $service->id,
            'type' => $service->type,
            'title' => (string)  $service->title,
            'description' => (string) $service->description,
            'img' =>isset($service->img) ? url('images/products/'.$service->img): '',
            'price' => (float) $service->price ,
            'available'=>(boolean) $service->available,
            'cartypes' => $cartypes,
            'carsizes' => $carsizes,
            'additions'=> $additions
            
        ];
    }
}
