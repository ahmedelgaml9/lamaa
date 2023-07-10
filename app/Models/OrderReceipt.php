<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReceipt extends Model
{
    
    protected $table = 'order_receipt';
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
