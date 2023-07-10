<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductNotification extends Model
{
    
    protected $table = 'product_notification';
    
    protected $guarded = ['id'];

    public function product()
    {

        return $this->belongsTo(Product::Class, 'product_id');
    }
}
