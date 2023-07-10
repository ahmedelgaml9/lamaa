<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Bank extends Model implements HasMedia
{
   
    use HasTranslations, HasMediaTrait;

    protected $table = 'banks';
    protected $guarded = ['id'];
    public $translatable = ['name'];

    public $mediaLogoCollectionName = "bank_logo";
    public $mediaHintImageCollectionName = "bank_hint_image";

    public function rules()
    {
        return [
            
            'name.ar' => 'required|string',
            'account_number' => 'required',
        ];
    }

    public function getLogoAttribute()
    {
        
        $media = $this->getFirstMedia($this->mediaLogoCollectionName);

        if ($media) {

             return $media->getUrl();
             
        } else {

             return null;
        }
    }

    public function getHintImageAttribute()
    {
        
        $media = $this->getFirstMedia($this->mediaHintImageCollectionName);

        if ($media) {

            return $media->getUrl();

        } else {

            return null;
        }
    }

}
