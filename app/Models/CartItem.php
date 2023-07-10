<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    
    protected $guarded = ['id'];
    protected $table = "cart_items";

    public function product()
    {
        
        return $this->belongsTo(Product::class, 'product_id')->where('type','=','product');
    }
    
    public function service()
    {
        return $this->belongsTo(Product::class, 'product_id')->where('type','=','service');
    }
    
    public function  serviceProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Size()
    {
        return $this->belongsTo(CartSizes::class, 'size_id');
    }

    public function Mattresstype()
    {
        return $this->belongsTo(MattressType::class , 'mattress_type_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function additions()
    {
        
        return $this->hasMany(CartAddition::class , 'cartitem_id')->select(['addition_id','addition','price','service_id']);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }


}
