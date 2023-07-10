<?php

namespace App\Http\Controllers\Api\V3;
use App\Classes\Helper;
use App\Classes\Operation;
use App\Http\Controllers\Controller;
use App\Http\Resources\V3\UserResource;
use App\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\carbon;


class AuthController extends Controller
{
     
     public function otp_login(Request $request)
     {
            
          
               $validator = Validator::make($request->all(), [

                    'mobile' => 'required',
               ]);

               if($validator->fails()) {

                     return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
                }

                if (substr($request->mobile, 0, 1) == '0') {

                      return response()->json([

                       'status' => 422,
                       'success' => false,
                       'message' => trans('you must enter number donot begin with zero'),

                   ]);
                }
                
                 $digits = 4;
                 $otpCode = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
                 $user = User::where("mobile", $request->mobile)->where('deleted_at',null)->first();

                 if($user){
 
                      $user->update(['mobile' => $request->mobile, "otp_code" => $otpCode]);

                  }else{

                     User::create(['mobile' => $request->mobile, 'status' => 0,'email' => hexdec(uniqid()).'_customer@lamaa.com', 'password' => Hash::make(uniqid()), "otp_code" => $otpCode]);
                }
 
                $masdgat = array(

                    "userName"=> "Lamaa.carwash",
                    "numbers"=> $request->mobile ,
                    "userSender"=> "OTP", 
                    "apiKey"=>"74be46bdc89bc697f4f4757fe3823ee0",
                    "msg"=> " رمز التحقق: $otpCode",
                    "msgEncoding "=> "UTF8"
                 );

                 $d =$masdgat;
                 $data = json_encode($d);
                 $client = new Client([

                      'headers' => ['Content-Type' =>'application/json']
                 ]);

                 $response = $client->post('https://www.msegat.com/gw/sendsms.php',

                       ['body' =>$data]
                  ); 

                 return $this->sendResponse(['otp_code' => $otpCode, 'show_otp' => true], trans('messages.added_success'));
          } 

          public function otp_verify(Request $request)
          {
               
               $validator = Validator::make($request->all(), [
  
                    'mobile' => 'required',
                    'otp_code' => 'required|integer'
               ]); 

              if($validator->fails()) {

                   return $this->sendError(error_processor($validator), trans('messages.some_fields_are_missing'), 442);
              }
 
              $user = User::where("mobile", $request->mobile)->where("otp_code", $request->otp_code)->first();

              if(!$user){

                   return $this->sendError([], trans('messages.otp_code_is_not_correct'), 442);
              }

              $cart_id = Cart::where('user_id',$user->id)->first();
              $user->update(['otp_code' => null, "is_mobile_verified" => 1]);
              $accessToken = $user->createToken('Personal Access Token')->accessToken;
 
               return $this->sendResponse(['new_user' => ($user->status == 0), "access_token" => $accessToken, 'cart_id'=>$cart_id , "user" => new UserResource($user)], trans('messages.added_success'));
        
         }

         public function otp_register(Request $request)
         {
               
              $validator = Validator::make($request->all(),[

                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,'.auth()->id(),
                    'city_id' => 'required',
               ]);

               if($validator->fails()) {

                    return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
                }

                $user = auth()->user()->update([

                    'name' => $request->name,
                    'email' => $request->email,
                    'city_id' => $request->city_id,
                    'status' => 1,
               ]);
    
               return $this->sendResponse(new UserResource(auth()->user()), trans('messages.updated_success'));
         }

        public function logout(Request $request)
        { 
             
            $request->user()->token()->revoke();

            return $this->sendResponse([], trans('messages.updated_success'));
        
        }

       public function deleteUser(Request $request)
       { 
          
            $user= auth()->user();
            
            $delete =  User::where('id',$user->id)->update(['deleted_at'=> Carbon::now()]);

            return $this->sendResponse([], trans('messages.delete_user_succes'));
       }

}
