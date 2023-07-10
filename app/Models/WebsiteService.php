<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


class WebsiteService extends Model implements HasMedia
{
   
    use HasMediaTrait;
   
    protected $table ="website_services";
    protected $guarded = ['id'];
    public $mediaPhotoCollectionName ="WebsiteServices-images";

    public function getImgAttribute()
    {
       
        $media = $this->getFirstMedia($this->mediaPhotoCollectionName);

        if ($media) {

            return $media->getUrl();
        }
    }

    public function rules()
    {
        
        return [

            'title' => 'required|string',
            'description' => 'required|string',

        ];
    }


}
