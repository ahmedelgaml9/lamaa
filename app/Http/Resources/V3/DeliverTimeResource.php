<?php

namespace App\Http\Resources\V3;

use App\Classes\Operation;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliverTimeResource extends JsonResource
{
   
    public function toArray($request)
    {
        
        $delivery_time = $this;
        $countOrders = Operation::countOrdersByDeliveryShifts($request->delivery_date, $delivery_time->start_time, $delivery_time->end_time);
        $diff = max((int) $delivery_time->max_orders_to_accept - $countOrders, 0);
            return [
                'name' => implode(' - ', [$delivery_time->start_time, $delivery_time->end_time]),
                'is_available' => $delivery_time->max_orders_to_accept > 0? ($diff > 0): true,
                'id'   => $delivery_time->getRouteKey()
            ];

    }

}
