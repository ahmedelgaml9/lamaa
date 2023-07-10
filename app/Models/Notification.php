<?php

namespace App\Models;
use App\User;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;


class Notification extends Model implements HasMedia
{

    use  HasMediaTrait;

    protected $table="notifications";
    protected $guarded = ['id'];
    public $timestamps= false;
    public $translatable = ['title'];
    public $mediaImageCollectionName = "notification_image";
    protected $dates = ['updated_at', 'send_at'];

    public function rules()
    {
        
        return [

            'title' => 'required|string',
        ];
    }

    
    public function getImageAttribute()
    {
        
        $media = $this->getFirstMedia($this->mediaImageCollectionName);

        if ($media) {
            return $media->getUrl();
        } else {
            return null;
        }
    }

    public function users(){

        return $this->belongsToMany(User::class,'user_notification', 'notification_id', 'user_id')->withPivot(['status', 'seen_at']);
    }

}
