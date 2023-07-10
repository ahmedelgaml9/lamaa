<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class  Staff extends Model
{
    
    protected $table= "role_users";
    protected $guarded = ['id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
