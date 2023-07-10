<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;

class Appnotification extends Model implements HasMedia
{
    
    use HasTranslations, HasMediaTrait;
    protected $table="notifications";
    protected $guarded = ['id'];
    public $translatable = ['title'];

    public function rules()
    {   
           
         return [
               
             'title.ar' => 'required|string',
         ];
    }

     public function users(){

         return $this->hasMany(UserNotification::class ,'notification_id');
     }

}
