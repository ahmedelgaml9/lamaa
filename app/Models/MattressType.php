<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MattressType extends Model
{
    
  
    protected $guarded = ['id'];
    
    public function rules()
    {
        
        return [

            'name' => 'required|string',
            'price' => 'required',
        ];
    }
}
