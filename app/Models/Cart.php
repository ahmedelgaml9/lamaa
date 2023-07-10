<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    protected $guarded = ['id'];
    protected $table = "carts";

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function amount()
    {
       
        $amount = $this->hasMany(CartItem::class, 'cart_id')->sum('amount');

      if (empty($amount)) {

          return 0;
          
       } else {

            return $amount;
       }
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

}
