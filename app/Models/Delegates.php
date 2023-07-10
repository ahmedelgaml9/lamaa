<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delegates extends Model
{
   
    protected $guarded = ['id'];
    public $timestamps=false;

    public function rules()
    {
       
        return [

            'name' => 'required|string',
            'phone' => 'required',
            'code' => 'required',
            
          ];
      }

}
