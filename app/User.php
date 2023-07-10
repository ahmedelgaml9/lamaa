<?php

namespace App;
use App\Models\City;
use App\Models\Conversation;
use App\Models\CustomerShippingAddress;
use App\Models\Order;
use App\Models\UserBalance;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


class User extends Authenticatable implements HasMedia
{
    
    use HasApiTokens, Notifiable, HasMediaTrait;

    const CUSTOMER_STATUSES_LIST = [

        'active' => 'active',
        'inactive' => 'inactive',
        'pending' => 'pending',
        'before_pending' => 'before_pending',
        'special_active' => 'special_active',
        'special_pending' => 'special_pending'
    ];
  
    protected $guarded = ['id'];

    public $mediaCollectionName = 'user-avatar';

   
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [

        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute()
    {
        $media = $this->getFirstMedia($this->mediaCollectionName);

        if ($media) {
            return $media->getUrl();
        } else {
            return null;
        }
    }

    public function getOrdersCountAttribute()
    {

       return $this->orders()->count();
    }

    public function getOrdersproductsCountAttribute()
    {
        return $this->orders()->products()->count();
    }

    public function getDefaultAddressAttribute()
    {
        
        $defaultAddress = $this->addresses()->orderBy('customer_shipping_addresses.id', 'desc')->first();

        if($defaultAddress){

            return $defaultAddress->address;
        }

        return null;
    }

    public function getUserBalanceAttribute()
    {
        return (float) $this->balance;
    }

    public function getCityNameAttribute()
    {
        $city = $this->city;
        return $city ? $city->name:null;
    }

    public function addresses()
    {
        return $this->hasMany(CustomerShippingAddress::Class, 'user_id');
    }

    public function balances()
    {
        return $this->hasMany(UserBalance::Class, 'user_id');
    }


    public function staff()
    {

        return $this->hasOne('App\Models\Staff');
    }


    public function orders()
    {
        return $this->hasMany(Order::Class, 'user_id');
    }

    public function driverOrders()
    {

        return $this->hasMany(Order::Class, 'driver_id');
    }

    public function customerConversations()
    {
        return $this->hasMany(Conversation::Class, 'user_one');
    }

    public function staffConversations()
    {
        return $this->hasMany(Conversation::Class, 'user_two');
    }

    public function city()
    {
        return $this->belongsTo(City::Class, 'city_id');
    }

    public function rules($update = false)
    {
       
        $rules = [
           
           'name' => 'required|string',
           'email' => 'unique:users',
//          'password' => 'required|min:6|confirmed',
       ];

       if($update){

           $rules['email'] = "unique:users,email,$this->id,id";
           $rules['password'] = 'nullable';
       }

          return $rules;
    }


}
