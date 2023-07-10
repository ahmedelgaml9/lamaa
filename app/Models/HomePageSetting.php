<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class HomePageSetting extends Model implements HasMedia
{
    
    use  HasMediaTrait ;
    protected $guarded = ['id'];
    public $slidersMediaCollection = 'homepage-sliders';


  public function getSlidersAttribute()
  {

       $images = [];

       $gallery = $this->getMedia($this->slidersMediaCollection);

        foreach ($gallery as $image){

          $images[] = ['id' => $image->id,'url' => (string)$image->getFullUrl()];
    
        }
        
          return $images;
      }

  }
