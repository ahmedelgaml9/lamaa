<?php

namespace App\Http\Controllers\Api\V3;

use App\Classes\Polygon;
use App\Http\Controllers\Controller;
use App\Http\Resources\V3\AddressResource;
use App\Models\Cart;
use App\Models\City;
use App\Models\CustomerShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App;

class AddressController extends Controller
{

    public function __construct(Request $request)
    {

        App::setLocale($request->header('lang'));
    }
   
    public function index()
    {
        
        $addresses = AddressResource::collection(CustomerShippingAddress::where('user_id', auth()->id())->where('status', 1)->orderBy('id', 'desc')->paginate(20));
        
        return $this->sendResponse($addresses->resource, trans('messages.get_data_success'));
    }

    public function getDefaultAddress(Request $request)
    {

        $address = CustomerShippingAddress::where('user_id', auth()->id())->where('status', 1)->where('set_default', 1)->first();
        
        if(!$address){
            
            $address = CustomerShippingAddress::where('user_id', auth()->id())->where('status', 1)->orderBy('id', 'desc')->first();
        }
        
        return $this->sendResponse($address, trans('messages.get_data_success'));
    }

    public function setDefaultAddress(Request $request)
    {
       
        $id = $request->id;
        $address = CustomerShippingAddress::where('user_id', auth()->id())->where('id', $id)->first();
        if(!$address){

            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        CustomerShippingAddress::where('user_id', auth()->id())->where('status', 1)->where('set_default', 1)->update(['set_default' => false]);
        $address->update(['set_default' => true]);
        $userCart = Cart::where('user_id', auth()->id())->where('status', 1)->orderBy('id', 'desc')->first();
        if($userCart && $address->city_id != $userCart->city_id){

            $userCart->items()->delete();
        }

        return $this->sendResponse([], trans('messages.update_data_success'));
    }

    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [

            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {

             return $this->sendError(error_processor($validator), trans('messages.some_fields_are_missing'), 404);
        }

        $city = Polygon::getCityByCoordinates($request->lat, $request->lng);
        
        if(!$city){

             return $this->sendError([],trans('messages.region_not_support'), 442);
        }

        $data = $request->all();
        $data['city_id'] = $city->id;
        $data['user_id'] = auth()->id();
        $data['username'] = $request->get('username', auth()->user()->name);
        if($data['set_default']){
            
            CustomerShippingAddress::where('user_id', auth()->id())->where('status', 1)->where('set_default', 1)->update(['set_default' => false]);
        }

        $newAddress =  CustomerShippingAddress::create($data);
        $userCart = Cart::where('user_id', auth()->id())->where('status', 1)->orderBy('id', 'desc')->first();
        if($userCart && $newAddress->city_id != $userCart->city_id){
            $userCart->items()->delete();
        }

        $addresses = AddressResource::collection(CustomerShippingAddress::where('status', 1)->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(20));

        return $this->sendResponse($addresses->resource, trans('messages.data_stored_success'));
    }

    public function update(Request $request, $id)
    {
        
        $address = CustomerShippingAddress::where('user_id', auth()->id())->where('id', $id)->first();

        if(!$address){
            
            return $this->sendError([], trans('messages.not_found_data'), 442);
        }
        
        $city = Polygon::getCityByCoordinates($address->lat, $address->lng);

        if(!$city){

            return $this->sendError([], trans('messages.region_not_support'), 442);
        }

        $data = $request->all();
        
        if($data['set_default']){

            CustomerShippingAddress::where('user_id', auth()->id())->where('status', 1)->where('set_default', 1)->update(['set_default' => false]);
        }

        $data['city_id'] = $city->id;
        $data['username'] = $request->get('username', auth()->user()->name);
        $address->update($data);
        $userCart = Cart::where('user_id', auth()->id())->where('status', 1)->orderBy('id', 'desc')->first();
        if($userCart && $address->city_id != $userCart->city_id){
            $userCart->items()->delete();
        }

        return $this->sendResponse([], trans('messages.update_data_success'));
    }

    public function delete(Request $request, $id)
    {
       
        $address = CustomerShippingAddress::where('user_id', auth()->id())->where('id', $id)->first();
       
        if(!$address){

            return $this->sendError([], trans('messages.not_found_data'), 442);
        }

        $address->update(['status' => 0]);
        $addresses = AddressResource::collection(CustomerShippingAddress::where('status', 1)->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(20));
        return $this->sendResponse($addresses->resource, trans('messages.update_data_success'));
    }

    public function getCityByCoordinates(Request $request)
    {
        
        $city = Polygon::getCityByCoordinates($request->lat, $request->long);
        
        if(!$city){

            return $this->sendError([], trans('messages.region_not_support'), 442);
        }
        
          return $this->sendResponse(['city_id' => (int) $city->id], trans('messages.get_data_success'));
    }
}
