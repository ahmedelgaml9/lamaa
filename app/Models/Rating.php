<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    
    protected $table="ratings";
    protected $guarded = ['id'];
    
}
