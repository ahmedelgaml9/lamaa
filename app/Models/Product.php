<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model 
{

    protected $table = 'products';
    protected $guarded = ['id'];

    public function rules()
    {
          return [

             'title' => 'required|string',
             'price' => 'required',
         ];
    }

    public function setImgAttribute($image) {

        if ($image) {
            $dest = 'images/products/';
            $name = str_random(6) . '_' . $image->getClientOriginalName();
            $fileName = preg_replace('/\s+/', '',  $name);
            $image->move($dest,  $fileName);
            $this->attributes['img']= $fileName;
        }
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    public function orders()
    {

        return $this->belongsToMany(Order::Class, 'order_products');
    }

    public function cities()
    {
        return $this->belongsToMany(City::Class, 'product_cities');
    }

}
