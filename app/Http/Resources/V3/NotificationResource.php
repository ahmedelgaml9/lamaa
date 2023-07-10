<?php

namespace App\Http\Resources\V3;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
  
    public function toArray($request)
    {
        $notification = $this;
        
        return [
            
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'photo' => $notification->photo?url($notification->photo):null,
            'options' => $notification->options?json_decode($notification->options):(object) [],
            'created_at' => $notification->created_at
        ];
    }
}
