<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    
    protected $table ="carsizes";
    protected $guarded = ['id'];

    public function rules()
    {
          return [
            'size' => 'required|string',
         ];
    }
}
