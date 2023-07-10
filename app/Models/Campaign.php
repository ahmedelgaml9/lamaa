<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    
    protected $guarded = ['id'];
    
    public function rules()
    {
       
         return [
            
             'type' => 'required|string',
             'name' => 'required|string', 
             'offer_type' => 'required',
          ];
    }

}
