<?php

namespace App\Http\Resources\V3;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
  
    public function toArray($request)
    {
         
         $category =$this;

           return [
            
             'id' => (int) $category->id,
             'name' => (string) $category->name ,
             'icon' =>isset($category->icon)? asset('images/category/'.$category->icon): '',

         ];
    }
}
