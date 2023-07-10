<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasTranslations;

    protected $table = "cities";
    protected $guarded = ['id'];
    public $timestamps = false;
    public $translatable = ['name'];

     public function rules()
     {
         return [
            
             'name.ar' => 'required|string',
             'region_id' => 'required',
         ];
     }

    public function region()
    {
        return $this->belongsTo(Area::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] =str_slug($value);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::Class, 'order_products');
    }

}
