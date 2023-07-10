<?php

namespace App\Http\Resources\V3;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SliderCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [

            'data' => $this->collection->map(function($data) {
                
                return [

                    'id' => $data->id,
                    'image' => isset($data->image) ? url($data->image): ''
                ];
            })
        ];
    }

    public function with($request)
    {
        return [

            'message' => trans('messages.get_data_success'),
            'success' => true,
            'status' => 200
        ];
    }
}
