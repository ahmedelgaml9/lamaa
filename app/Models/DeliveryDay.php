<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class DeliveryDay extends Model
{

     public function times()
     {

          return $this->hasMany(DeliveryTime::class,'delivery_day_id');
     }

     public function countdeliverytime()
     {
         return $this->times()->count();
     }

    public function getDayNameAttribute($value)
    {
        return Arr::get(Carbon::getWeekDays(), $this->day_of_week, trans('common.n/a'));
    }
}
