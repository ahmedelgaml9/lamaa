<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Slide extends Model implements HasMedia
{
   
    use HasMediaTrait;

    public $mediaImageCollectionName = "slide_image";
    protected $table="slides";
    protected $guarded = ['id'];

   
    public function getImageAttribute()
    {
        $media = $this->getFirstMedia($this->mediaImageCollectionName);

         if ($media) {

              return $media->getUrl();
         }
     }

    public function rules()
    {
     
        return [
            
            'image' => 'required|image', 
        ];
    }

}
