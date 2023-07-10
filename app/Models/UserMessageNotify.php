<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMessageNotify extends Model
{
  
    protected $table="notifications_messages_users";
    protected $guarded = ['id'];
    public $timestamps= false;

}
