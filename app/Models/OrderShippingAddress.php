<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShippingAddress extends Model
{
    
    protected $table = 'order_shipping_addresses';
    protected $guarded = ['id'];
    
}
