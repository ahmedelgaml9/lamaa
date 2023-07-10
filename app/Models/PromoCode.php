<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{

    protected $table = 'promocodes';
    public $timestamps= false;

    public function users(){

         return $this->hasMany('App\Models\PromoCodeuser','promocode_id');
    }

    public function products()
    {
        
        return $this->belongsToMany(Product::class, 'promo_code_products', 'promocode_id', 'product_id');
    }

}


