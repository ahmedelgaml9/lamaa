<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{

    protected $guarded = ['id'];
    public $timestamps=false;

    public function rules()
    {
        return [

            'name' => 'required|string',
            'type' => 'required',
            'start_date' => 'required',
            'expiry_date' => 'required',
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_products', 'offer_id', 'product_id');
    }

    public function offerProduct()
    {
        return $this->belongsTo(Product::class, 'other_product_id');
    }

    public function cities()
    {
        return $this->belongsToMany(City::Class, 'offer_cities');
    }


}
