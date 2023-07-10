<?php

namespace App\Http\Resources\V3;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    
    public function toArray($request)
    {
        $product = $this;

        return [
            
            'id' => (int) $product->id,
            'type' => $product->type,
            'title' => (string) $product->title,
            'description' => (string) $product->description,
            'img' =>isset($product->img) ? url('images/products'.$product->img): '',
            'price' => (float) $product->price, 
            'available'=>(boolean) $product->available,
        ];
    }
}
