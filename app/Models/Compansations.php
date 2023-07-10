<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Compansations extends Model
{
    
    protected $guarded = ['id'];

    public function rules()
    {
         return [
            
             'order_id' => 'required',
             'action' => 'required',
             'type' => 'required',
         ];
    }

    public function user()
    {
        return $this->belongsTo('App\User','created_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class ,'order_id');
    }
    
}
