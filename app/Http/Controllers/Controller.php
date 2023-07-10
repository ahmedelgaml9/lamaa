<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($data, ?string $message = 'success')
    {
       
        return Response::json([

            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
    }


    public function sendError($data, string $message = 'fail', int $code = 442)
    {
        
        return Response::json([

            'success' => false,
            'data' => is_object($data)?$data:(object) $data,
            'message' => $message,

        ], $code);

    }
}
