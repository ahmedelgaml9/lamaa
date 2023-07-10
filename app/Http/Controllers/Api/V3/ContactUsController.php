<?php

namespace App\Http\Controllers\Api\V3;
use App\Http\Controllers\Controller;
use App\Http\Resources\V3\AddressResource;
use App\Models\Contactus;
use App\Models\AdminNotification;
use App\Models\CustomerShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App;


class ContactUsController extends Controller
{

    public function __construct(Request $request)
    {
        App::setLocale($request->header('lang'));
    }
    
    public function send(Request $request)
    { 
         
          $validator = Validator::make($request->all(), [

              'title' => 'required',
              'info' => 'required',
              'username' => 'required',
              'mobile' => 'required'
         ]);

        if ($validator->fails()) {
            
             return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
        }

        $data = $request->all();
        $data['user_id'] = auth('api')->id();
        $contact = Contactus::create($data);
        $notification = new AdminNotification();
        $notification->title= "اتصال جديد تم استقباله"." ". $contact->title;
        $notification->content ="اتصال جديد تم استقباله"." ".$contact->title.""."from user".$contact->user_id;
        $notification->type ="contactus";
        $notification->save();

        return $this->sendResponse([], trans('messages.send_data_success'));
    } 
}
