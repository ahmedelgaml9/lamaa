<?php

namespace App\Models;
use App\User;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;


class NotificationSubscription extends Model implements HasMedia
{
    
    use HasTranslations, HasMediaTrait;

    protected $table="notification_subscriptions";
    protected $guarded = ['id'];
    public $timestamps= false;
    public $translatable = ['title'];

    public function rules()
    {
        
        return [
            
            'player_id' => 'required|string',
        ];
    }

}
