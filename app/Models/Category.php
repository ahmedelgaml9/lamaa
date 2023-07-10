<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    protected $guarded = ['id'];
    
    public function setIconAttribute($image) {

        if ($image) {

            $dest = 'images/category/';
            $name = str_random(6) . '_' . $image->getClientOriginalName();
            $fileName = preg_replace('/\s+/', '',  $name);
            $image->move($dest,  $fileName);
            $this->attributes['icon'] =$fileName;
        }
    }
   
    public function rules()
    {
       
        return [
            
            'name' => 'required|string',
        ];
    }
}
