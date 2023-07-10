<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    
    protected $table= "payments";
    
    protected $guarded = ['id'];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::Class, 'payment_method_id');
    }

}
