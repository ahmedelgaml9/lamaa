<?php

namespace App\Http\Resources\V3;

use App\Classes\Operation;
use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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

        return [
            
            'id' => (int) $order->id ,
            'formated_number' => (string) $order->order_number,
            'route_key' => (int) $order->route_key,
            'payment_status' => (boolean) $order->payment_status,
            'is_rated' => (boolean) $order->is_rated,
            'driver_rating_stars' => (double)round($order->driver_rating),
            'new_updates' => (boolean) $order->status_updates_count,
            'show_rating' => ($order->is_rated > 0 && $order->status == Order::STATUS_DELIVERED)?true:false,
            'rating_stars' => (double)round($order->product_rating),
            'statusLabel' => $statusText,
            'status' => $statusText,
            'delivery_date' => $order->delivery_date,
            'total' => (double) $order->total,
            'driver' => $driverData,
            'created_at' => (string) $order->created_at->format('d/m/Y h:i A')
            
        ];
    }
}
