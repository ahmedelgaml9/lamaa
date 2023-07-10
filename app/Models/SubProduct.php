<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class SubProduct extends Model implements HasMedia
{

    use HasMediaTrait;
    protected $table = 'sub_products';
    protected $guarded =['id'];

}
