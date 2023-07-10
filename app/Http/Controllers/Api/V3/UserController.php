<?php

namespace App\Http\Controllers\Api\V3;
use App\Classes\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\V3\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App;


class UserController extends Controller
{
   
    public function __construct(Request $request)
    {
        App::setLocale($request->header('lang'));
    }

    public function profile()
    {
       
        return $this->sendResponse(new UserResource(Auth()->user()), trans('messages.get_data_success'));
    }

    public function updateProfile(Request $request)
    {
         
         $validator = Validator::make($request->all(), [

              'name' => 'required',
              'mobile' => 'required',
              'email' => 'required',

           ]);

          if ($validator->fails()) {

                return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
           }

             $user = auth()->user();
             $user->update([

              'name'=>$request->name,
              'email'=>$request->email,
              'mobile'=>$request->mobile,

           ]);

              return $this->sendResponse(new UserResource(Auth()->user()), trans('messages.updated_success'));
       }

       public function updatePhoto(Request $request)
       {
           
            $validator = Validator::make($request->all(), [

               'avatar' => 'required|image',

            ]);

           if ($validator->fails()) {

               return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
           
           }

            $user = User::find(Auth::user()->id);

           if ($request->hasFile('avatar')) {

               $user->clearMediaCollection($user->mediaCollectionName);
               $user->addMedia($request->file('avatar'))
                   ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                   ->toMediaCollection($user->mediaCollectionName);
           }
               
            return $this->sendResponse(new UserResource(Auth()->user()), trans('messages.updated_success'));
     }

     public function wallet()
     {
        
          return $this->sendResponse(['balance' => auth()->user()->balance], trans('messages.get_data_success'));

     }


   

 }
