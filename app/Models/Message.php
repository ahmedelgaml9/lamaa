<?php

namespace App\Models;
use App\User;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use Nahid\Talk\Messages\Message as TalkMessage;


class Message extends TalkMessage implements HasMedia
{
    
    use HasMediaTrait;

    protected $guarded = ['id'];
    protected $table = "messages";
    public $galleryMediaCollection = 'chat_message_images';

    public function getImagesAttribute()
    {
        $images = [];
        $gallery = $this->getMedia($this->galleryMediaCollection);
        foreach ($gallery as $image){
            $images[] = (string)$image->getFullUrl();
        }
        return $images;

    }



}
