<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    
    protected $table="user_notification";
    public $timestamps= false;
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at', 'seen_at'];

    public function user(){

    return $this->belongsTo('App\User','user_id');

   }

}
