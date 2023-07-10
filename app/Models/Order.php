<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'orders';
    protected $fillable = [
        
        'user_id',
        'status',
        'total',
        'services_total',
        'products_total',
        'vat',
        'vat_number',
        'delivery_date',
        'accountant_name',
        'payment_status',
        'driver_name',
        'delivery_start_time',
        'exchanged_points_count',
        'exchanged_points_value',
        'delivery_end_time',
        'driver_rating_stars',
        'cancelled_reason',
        'cancelled_by',
        'cancelled_at',
        'status_updates_count',
        'finished_from',
        'city_id',
        'driver_id',
        'order_number',
        'city_name',
        'last_payment_reference_id',
        'origin_payment_method',
        'coupon_discount'
    ];

    const STATUS_RESERVED = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SHIPPING = 3;
    const STATUS_CANCELED = 5;
    const STATUS_DELIVERED = 8;

    const ACTIVE_ORDER_STATUS_LIST = [

        self::STATUS_RESERVED,
        self::STATUS_PROCESSING,
//        self::STATUS_SCHEDULED,
        self::STATUS_SHIPPING,
//        self::STATUS_SHIPPED,
        self::STATUS_CANCELED,
//        self::STATUS_FAIL_PAYMENT,
//      self::STATUS_RESCHEDULED,
        self::STATUS_DELIVERED,
    
    ];

    const ON_PROCESS_ORDER_STATUS_LIST = [
        self::STATUS_RESERVED,
        self::STATUS_SHIPPING,
        //self::STATUS_RESCHEDULED,

    ];

    protected $dates = ['created_at', 'updated_at', 'cancelled_at'];

    public function getOrderNumberAttribute()
    {
        return (int) '99'.$this->id;
    }

    public function getProductsRatingAttribute()
    {
        
        $ratingData = $this->ratings->where('type', 'products')->first();
        
        return $ratingData?(float) $ratingData->rating:0;
    }

    public function scopeNotPending($query)
    {
        return $query->where('payment_status',true);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'referenceable_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')
            ->withPivot(['quantity', 'amount', 'city_id'])->where('type', '=', 'product');
    }

    public function services()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')
        
            ->withPivot(['quantity', 'amount', 'city_id','size','mattress','car_type'])->where('type', '=', 'service');
    }

    public function servicesandproducts()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')
            ->withPivot(['quantity', 'amount', 'city_id','size','mattress']);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

    public function getPaymentMethodAttribute()
    {
        
        $payment = $this->payment;
        $paymentMethod = null;
        if($payment){
            $paymentMethod = $payment->paymentMethod;
        }

        return $paymentMethod?$paymentMethod->gateway:null;
    }

    public function getIsRatedAttribute()
    {
        return $this->ratings()->count() > 0;
    }

    public function getProductRatingAttribute()
    {
        $rating = $this->ratings()->where('type', 'products')->orderBy('ratings.id', 'DESC')->first();

        return $rating?$rating->rating: 0;
    }

    public function getDriverRatingAttribute()
    {
        $rating = $this->ratings()->where('type', 'driver')->orderBy('ratings.id', 'DESC')->first();
        return $rating?$rating->rating: 0;
    }

    public function address()
    {
       
        return $this->hasOne(OrderShippingAddress::class)->withDefault(function ($address, $parent) {

            $address->username = $parent->user?$parent->user->name:null;
            $address->mobile = $parent->user?$parent->user->mobile:null;
            $address->address = $parent->user?$parent->user->address:null;

            return $address;
        });
    }

    public function ratings()
    {
         return $this->morphMany(Rating::class, 'ratingable');
    }

    public function promotions()
    {
        return $this->belongsToMany(PromoCode::class, 'promocode_user', 'promocode_id', 'referenceable_id')->where('promocode_user.referenceable_type', 'orders');
    }

    public function receipt()
    {
        return $this->hasOne(OrderReceipt::class, 'order_id');
    }

    public function additions()
    {
        return $this->hasMany(OrderAddition::class, 'order_id')->select(['addition_id','name','price']);
    }
}
