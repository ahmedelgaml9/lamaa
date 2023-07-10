<?php

namespace App\Models;
use App\User;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;


class Conversation extends Model
{

    protected $guarded = ['id'];
    protected $table = "conversations";

    protected $appends = array('channel_id', 'unread_count');
    
    public function getChannelIdAttribute()
    {
        $talk__appKey = config('talk.broadcast.pusher.app_key');
        $talk__appName = config('talk.broadcast.app_name');
        $talk_conversation_channel = sha1($talk__appName.'-conversation-'.$this->id);
        return $talk_conversation_channel;
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    public function lastMessage() {

        return $this->hasOne(Message::class, 'conversation_id')->where('deleted_from_sender', 0)->where('deleted_from_receiver', 0)->latest();
    }

    public function getUnreadCountAttribute(){
        return (int) $this->messages()->where('user_id', '!=', auth()->id())->where('is_seen', 0)->count();
    }

    public function authEmployee(){

        return $this->belongsTo(User::class, 'user_two');
    }

    public function authCustomer(){
        
        return $this->belongsTo(User::class, 'user_one');
    }

}

