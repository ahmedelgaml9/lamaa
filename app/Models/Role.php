<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    
    protected $table="roles";
    protected $guarded = ['id'];
    
    public function setNameAttribute($value)
    {

        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
        
    }

}
