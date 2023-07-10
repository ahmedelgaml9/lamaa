<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OfferProduct extends Model
{
    
    protected $guarded = ['id']; 
    
    public function Offer(){

        return $this->belongsTo(Offer::class,'offer_id');
    }

}
