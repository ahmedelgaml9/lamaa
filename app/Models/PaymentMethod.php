<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class PaymentMethod extends Model
{
    
    use HasMediaTrait;

    protected $table= "payment_methods";
    public $translatable = ['name'];
    protected $guarded = ['id'];


}
