<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{

    protected $guarded = ['id'];
    protected $table = "audits";

    public function user()
    {
        
        return $this->belongsTo(User::Class, 'user_id');
    }

}
