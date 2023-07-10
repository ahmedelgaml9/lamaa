<?php

namespace App\Models;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    
    use HasTranslations;
    protected $table = "regions";
    protected $guarded = ['id'];
    public $translatable = ['name'];
    public $timestamps= false;

    public function rules()
    {
         return [

             'name.ar' => 'required|string',
         ];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug("sa-".$value);

    }

    public function city()
    {
        return $this->hasMany(City::class ,'region_id');
    }

    public function orders()
    {
         return $this->belongsToMany(Order::Class, 'order_products');
    }
    
}
