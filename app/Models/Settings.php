<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Settings extends Model implements HasMedia
{
    
    use HasMediaTrait; 

    protected $table="settings";
    protected $guarded = ['id'];

}
