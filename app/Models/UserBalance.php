<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{

    protected $guarded = ['id'];
    
    protected $table = "user_balances";

    protected $dates = ['expiry_date'];


    public function createdByData(){

        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
