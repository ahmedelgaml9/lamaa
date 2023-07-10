<?php

namespace App\Http\Resources\V3;

use App\Classes\Operation;
use App\Models\Order;
use App\Models\OrderReceipt;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;


class OrderDetailsResource extends JsonResource
{

    public function toArray($request)
    {
        
        $order = $this;
        $statusText = trans('admin.orders_status_options.'.$order->status);
        $driverData = (object) [];
        
        if($driver = $order->driver){

            $driverData = [

                'id' => $driver->id,
                'name' => $driver->name,
                'mobile' => $driver->mobile
                 
              ];
          }

          $payment = $order->payment;

            return [

                    'id' => (int) $order->id ,
                    'formated_number' => (int) $order->order_number,
                    'payment_status' => (boolean) $order->payment_status,
                    'is_rated' => (boolean) $order->is_rated,
                // 'driver_rating_stars' => (double)round($order->driver_rating),
                    'new_updates' => (boolean) $order->status_updates_count,
                    'show_rating' => ($order->is_rated > 0 && $order->status == Order::STATUS_DELIVERED)?true:false,
                // 'rating_stars' => (double)round($order->product_rating),
                    'statusLabel' => $statusText,
                    'status' =>  $statusText,
                    'customer_name' => $order->address->username,
                    'customer_phone' => $order->address->mobile,
                    'delivery_date' => $order->delivery_date,
                    'delivery_time' => $order->delivery_start_time."-".$order->delivery_end_time,
                    'payment_method' => $order->origin_payment_method,
                    'address' => $order->address->address,
                    'city_id' => $order->city_id ,
                    'products' => $this->loadProducts($order->products),
                    'services' => $this->loadServices($order->services),
                    'pricing' => $this->loadPriceses($order),
                    'can_cancel' =>  Operation::canCancelOrder($order),
                    'driver' => $driverData,
                    'created_at' => (string) $order->created_at->format('d/m/Y h:i A'),
                
             ];
        }

        public function loadProducts($products)
        {
          
              return collect($products)->map(function ($product) {
 
                return [
  
                    'title' => (string) $product->title,
                    'price' => (double) $product->price,
                    'quantity' => (int) $product->pivot->quantity,
                    'total' => (double) $product->pivot->amount,
                    'thumbnail' => isset($product->img)?asset('images/products/'.$product->img):''
                ];
             });
        }

        public function loadServices($services)
        {
        
              return collect($services)->map(function ($service) {

                 return [

                        'title' => (string) $service->title,
                        'price' => (double) $service->price,
                        'total' => (double) $service->pivot->amount,
                        'thumbnail' => isset($service->img)?asset('images/products/'.$service->img):'',
                        'size'=>$service->pivot->size ,
                        'mattress'=> $service->pivot->mattress,
                        'car_type'=> $service->pivot->car_type,
                        'additions'=> $service->additions
                   ];
              });
         }

         public function loadPriceses($order)
         {  
            
             return collect([

                 'total' => round((double) $order->total,2),
                 'services_total' => round((double) $order->services_total ,2),
                 'products_total' => round((double) $order->products_total ,2),
                 'copoun_amount' => (double) $order->coupon_discount,
              ]);
         }
}
