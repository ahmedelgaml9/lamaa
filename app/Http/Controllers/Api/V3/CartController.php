<?php

namespace App\Http\Controllers\Api\V3;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CartAddition;
use App\Models\Additions;
use App;


class CartController extends Controller
{
    
    public function __construct(Request $request)
    {
        App::setLocale($request->header('lang'));
    }

    public function addToCart(Request $request)
    {
        
            if(!Product::find($request->product_id)){
                
                return response()->json([

                    'status' => 422,
                    'success' => false,
                    'message' => trans('هذا المنتج غير متواجد'),
                 ]);
              }

              $hasUserId = User::find($request->user_id)?true:false;
              $cartData = [];

             if($hasUserId){
    
                 $cart = Cart::updateOrCreate(['user_id' => $request->user_id, 'status' => 1],['user_id' => $request->user_id]);
            
              }else{

             $device_id = $request->device_id;

              if(!$device_id){

                  $device_id = uniqid().'_'.rand(100, 100000);
              }

             if($request->cart_id){

                $cart = Cart::where('id',$request->cart_id)->where('status', 1)->first();

                $device_id = $cart->device_id;

                }else{

                     $cart = Cart::where('status', 1)->where('device_id', $device_id)->orderBy('created_at', 'desc')->first();
                }

                 if(!$cart){

                        $cart = Cart::create(['device_id' => $device_id, 'status' => 1]);
                    }
                } 

                $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $request->product_id)->orderBy('created_at', 'desc')->first();

               if($cartItem){

                if($request->amount <= 0){

                    $cartItem->delete();

                   }else{

                     $modAmount = 0;
                     $newAmount = (int)$request->amount > 0?(int)$request->amount:1;

                     if($request->math_type == 'minus'){
                          $modAmount = (int)$cartItem->amount - $newAmount;
                          if($modAmount <= 0) {
                             $modAmount = 0;
                         }
 
                       }elseif ($request->math_type == 'balance'){

                           $modAmount = $newAmount;

                       }else{

                          $modAmount = (int)$cartItem->amount + $newAmount;
                     }

                         $city_id = $request->city_id?:$cartItem->city_id;
                         $cartItem->update(['amount' => $modAmount, 'city_id' => $city_id]);
                     }
                 }
                else{

                     $cartItem = CartItem::create(['cart_id' => $cart->id,  'product_id' => $request->product_id, 'city_id' => $request->city_id, 'amount' => ((int)$request->amount) > 0?(int)$request->amount:1]);
                }

                $items = $cart->items()->with('serviceProduct')->get();
                $itemsCount = $items->sum('amount');
            
                $sumPricesArray = [];
                $productPrices= [];
                $servicePrices =[];
        
                if($itemsCount){

                   foreach ($items as $item){

                        $amount = (int)$item->amount;
                        $itemAll = $item->serviceProduct;
                        $itemService = $item->service;
                        $itemProduct = $item->product;

                        $itemSize= $item->size;
                        $itemMatterss= $item->Mattresstype;
                        $total_additions = $item->additions->sum('price');
                        $productPrice = $itemProduct?(float)$itemProduct->price:0;
                        $servicePrice = $itemService?(float)$itemService->price:0;
                        $allPrice = $itemAll?(float)$itemAll->price:0;

                        $productsizePrice = $itemSize?(float)$itemSize->size_price:0;
                        $productMatterssPrice = $itemMatterss?(float)$itemMatterss->price:0;
                        $sumPricesArray[] = $allPrice * $amount + $productsizePrice + $productMatterssPrice + $total_additions;
                        $productPrices[] = $productPrice * $amount;
                        $servicePrices[]= $servicePrice  + $productsizePrice + $productMatterssPrice + $total_additions;

                    }
                 }
 
                    $sumPrices = array_sum($sumPricesArray);
                    $sumproducts = array_sum($productPrices);
                    $sumservices = array_sum($servicePrices);

                    $productItemArray= [];
                    $serviceItemArray= [];
    
                 if($items->count()){
                     foreach($items as $item){
                       $product = $item->product;
                       if($product){
                          $productItemArray[] = [

                            'in_cart_quantity' => (int) $item->amount,
                            'cart_item_id' => (int) $item->id,
                            'id' => (int) $product->id,
                            'title' => (string) $product->title,
                            'quantity' => (float) $product->quantity,
                            'price' => (double) $product->price,
                            'img' => $product->img?(string) asset('images/products/'.$product->img):null,
                            'available'=>(int) $product->available,
                       ];
                    }
                 }
              }

                if($items->count()){

                    foreach($items as $item){
 
                       $service = $item->service;
                       $size = $item->size;
                       $Matterss = $item->Mattresstype;
                       $Additions = $item->additions;
                       $carType =$item->car_type;

                     if($service){

                           $serviceItemArray[] = [
 
                                'cart_item_id' => (int) $item->id,
                                'id' => (int)$service->id,
                                'title' => (string) $service->title,
                                'price' => (double) $service->price,
                                'img' => $service->img?(string) asset('images/products/'.$service->img):null,
                                'available'=>(int) $service->available,
                                'size_title' => (string)  isset($size)? $size->size: null,
                                'size_price' => (double) isset($size) ? $size->size_price: null,
                                'mattress_type_title' => (string) isset($Matterss) ? $Matterss->name: null,
                                'mattress_type_price' => (double) isset($Matterss) ? $Matterss->price: null,
                                'car_type'=>$item->car_type,
                                'additions' => $Additions
                         ];
                     }
                  }
             }

              return response()->json([
                
                  'status' => 200,
                  'success' => true,
                  'message' => trans('messages.item_add_to_cart'),
                  'data' => ['cart_id' => (int) $cart->id, 'items_count' => (int) $itemsCount, 'items_sum_prices' => (float) $sumPrices, 'products_prices'=>$sumproducts ,'services_prices'=>$sumservices ,'products' => $productItemArray , 'services'=>$serviceItemArray]
            ]);
       }

      public function addserviceToCart(Request $request)
      {
           
            if(!Product::find($request->service_id)){

                return response()->json([

                    'status' => 422,
                    'success' => false,
                    'message' => trans('هذه الخدمة غير موجودة'),
                ]);
            }

             $hasUserId = User::find($request->user_id)?true:false;
             $cartData = [];
             if($hasUserId){
 
                 $cart = Cart::updateOrCreate(['user_id' => $request->user_id, 'status' => 1],['user_id' => $request->user_id]);
             
              }else{

              $device_id = $request->device_id;

              if(!$device_id){

                  $device_id = uniqid().'_'.rand(100, 100000);
              }

             if($request->cart_id){

                 $cart = Cart::where('id',$request->cart_id)->where('status', 1)->first();

                 $device_id = $cart->device_id;

             }else{

                  $cart = Cart::where('status', 1)->where('device_id', $device_id)->orderBy('created_at', 'desc')->first();
             }

             if(!$cart){

                     $cart = Cart::create(['device_id' => $device_id, 'status' => 1]);
                }
             }

              $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $request->service_id)->orderBy('created_at', 'desc')->first();
        
              if($cartItem){
            
                
              }
              else{

                $cartItem = CartItem::create([

                    'cart_id' => $cart->id, 
                    'product_id' => $request->service_id,
                    'size_id'=> $request->size_id , 
                    'car_type'=> $request->car_type,
                    'mattress_type_id'=> $request->mattress_id,
                    'city_id' => $request->city_id, 
                    'amount' => 1

                ]);
            }
         
            $additions = Additions::whereIn('id',$request->get('additions_ids',[]))->get();
         
            foreach($additions as $addition)
            {
                
               $getaddition = CartAddition::where(['addition_id'=> $addition->id , 'service_id'=> $request->service_id ,'cartitem_id'=> $cartItem->id])->get();

              if(count($getaddition) < 1)
               {
                    
                    $insert = new CartAddition;
                    $insert->cartitem_id = $cartItem->id;
                    $insert->addition_id = $addition->id;
                    $insert->addition = $addition->addition;
                    $insert->price  = $addition->addition_price;
                    $insert->service_id = $addition->service_id ;
                    $insert->save();
                    
                }
              }
  
               $items = $cart->items()->with('serviceProduct')->get();
               $itemsCount = $items->sum('amount');
     
               $sumPricesArray = [];
               $productPrices = [];
               $servicePrices = [] ;

             if($itemsCount){

                 foreach ($items as $item){

                    $amount = (int)$item->amount;
                    $itemAll = $item->serviceProduct;
                    $itemService = $item->service;
                    $itemProduct = $item->product;

                    $itemSize= $item->size;
                    $itemMatterss= $item->Mattresstype;
                    $total_additions = $item->additions->sum('price');
                    $productPrice = $itemProduct?(float)$itemProduct->price:0;
                    $servicePrice = $itemService?(float)$itemService->price:0;
                    $allPrice = $itemAll?(float)$itemAll->price:0;

                    $productsizePrice = $itemSize?(float)$itemSize->size_price:0;
                    $productMatterssPrice = $itemMatterss?(float)$itemMatterss->price:0;
                    $sumPricesArray[] = $allPrice * $amount + $productsizePrice + $productMatterssPrice + $total_additions;
                    $productPrices[] = $productPrice * $amount;
                    $servicePrices[] = $servicePrice + $productsizePrice + $productMatterssPrice + $total_additions;

                 }
             }
         
            $sumPrices = array_sum($sumPricesArray);
            $sumproducts = array_sum($productPrices);
            $sumservices = array_sum($servicePrices);

            $productItemArray= [];
            $serviceItemArray= [];
 
            if($items->count()){
                 foreach($items as $item){
                     $product = $item->product;
                      if($product){
                           $productItemArray[] = [

                                'in_cart_quantity' => (int) $item->amount,
                                'cart_item_id' => (int) $item->id,
                                'id' => (int) $product->id,
                                'title' => (string) $product->title,
                                'quantity' => (float) $product->quantity,
                                'price' => (double) $product->price,
                                'img' => $product->img?(string) asset('images/products/'.$product->img):null,
                                'available'=>(int) $product->available,
                           ];
                        }
                    }
                }
 
               if($items->count()){
                
                foreach($items as $item){

                    $service = $item->service;
                    $size = $item->size;
                    $Matterss = $item->Mattresstype;
                    $Additions = $item->additions;
                    $carType =$item->car_type;

                   if($service){

                       $serviceItemArray[] = [

                            'cart_item_id' => (int) $item->id,
                            'id' => (int)  $service->id,
                            'title' => (string)   $service->title,
                            'price' => (double)   $service->price,
                            'img' =>   $service->img?(string) asset('images/products/'.$service->img):null,
                            'available'=>(int) $service->available,
                            'size_title' => (string)  isset($size)? $size->size: null,
                            'size_price' => (double) isset($size) ?$size->size_price: null,
                            'mattress_type_title' => (string) isset($Matterss) ? $Matterss->name: null,
                            'mattress_type_price' => (double) isset($Matterss) ? $Matterss->price:null,
                            'car_type'=> $item->car_type,
                            'additions' => $Additions
                       
                        ];
                    }
                }
            }
 
            return response()->json([

                'status' => 200,
                'success' => true,
                'message' => trans('messages.item_add_to_cart'),
                'data' => ['cart_id' => (int) $cart->id, 'items_count' => (int) $itemsCount, 'items_sum_prices' => (float) $sumPrices ,'products_prices'=>$sumproducts ,'services_prices'=>$sumservices ,'products' => $productItemArray , 'services'=>$serviceItemArray]
          ]);
    }

    public function removeFromCart(Request $request, $cart_id, $product_id)
    {
        
         $cart = Cart::where('id',$cart_id)->where('status', 1)->first();

         if(!$cart){

             return response()->json([
                
                'status' => 422,
                'success' => false,
                'message' => trans('messages.cart_empty'),

            ]);
        }

        $device_id = $cart->device_id;
        $item = CartItem::where('cart_id', $cart_id)->where('product_id', $product_id)->orderBy('created_at', 'desc')->first();
        
        if(!$item){

            return response()->json([
                
                'status' => 422,
                'success' => false,
                'message' => trans('هذا العنصر غير متواجد'),
            ]);
        }

        $item->delete();
        $hasUserId = User::find($request->user_id)?true:false;
        $items = $cart->items()->with('serviceProduct')->get();
        $itemsCount = $items->sum('amount');
        $sumFinalPricesArray = [];
        $sumPricesArray = [];
        $productPrices = [];
        $servicePrices =[];

        if($itemsCount){

            foreach ($items as $item){
                
                $amount = (int)$item->amount;
                $itemAll = $item->serviceProduct;
                $itemService = $item->service;
                $itemProduct = $item->product;

                $itemSize= $item->size;
                $itemMatterss= $item->Mattresstype;
                $total_additions = $item->additions->sum('price');
                $productPrice = $itemProduct?(float)$itemProduct->price:0;
                $servicePrice = $itemService?(float)$itemService->price:0;
                $allPrice = $itemAll?(float)$itemAll->price:0;

                $productsizePrice = $itemSize?(float)$itemSize->size_price:0;
                $productMatterssPrice = $itemMatterss?(float)$itemMatterss->price:0;
                $sumPricesArray[] = $allPrice * $amount + $productsizePrice + $productMatterssPrice + $total_additions;
                $productPrices[] = $productPrice * $amount;
                $servicePrices[] = $servicePrice  + $productsizePrice + $productMatterssPrice + $total_additions;
              }
           }

            $sumPrices = array_sum($sumPricesArray);
            $sumproducts = array_sum($productPrices);
            $sumservices = array_sum($servicePrices);

             return response()->json([

                 'status' => 200,
                 'success' => true,
                 'message' => trans('messages.get_data_success'),
                 'data' => ['cart_id' => (int) $cart->id, 'items_count' => (int) $itemsCount, 'items_sum_prices' => (float) $sumPrices,'products_prices'=> $sumproducts,'services_prices'=> $sumservices , 'isRegistered' => $hasUserId, 'message' => trans('products::alert.remove_from_cart_successfully')]
           ]);
      }

     public function emptyCart(Request $request, $cart_id)
     {
       
         $cart = Cart::where('id',$cart_id)->where('status', 1)->first();
         if(!$cart){

          return response()->json([

              'status' => 422,
              'success' => false,
              'message' => trans('messages.cart_empty'),
   
           ]);
        }

          $cart->items()->delete();

          return response()->json([

              'status' => 200,
              'success' => true,
              'message' => trans('messages.cart_empty'),
        ]);
    }

    public function userCart(Request $request)
    {
       
        $cart = Cart::where('user_id', auth('api')->id())->where('status', 1)->orderBy('created_at', 'desc')->first();
        
        return response()->json([

            'status' => 200,
            'success' => true,
            'message' => trans('messages.cart_empty'),
            'data' => ['cart_id' => $cart?$cart->id:null,'has_cart' => $cart?true:false]

        ]);
    }

    public function cartItems(Request $request, $cart_id)
    {

        $cart = Cart::where('id', $cart_id)->where('status', 1)->first();

        if(!$cart){

            return response()->json([

                'status' => 422,
                'success' => false,
                'message' => trans('messages.cart_empty'),
                
            ]);
        }

        $items = $cart->items()->with('serviceProduct')->get();
        $itemsCount = $items->sum('amount');
    
        $sumPricesArray = [];
        $productPrices =[];
        $servicePrices =[];

         if($itemsCount){
             foreach ($items as $item){
                $amount = (int)$item->amount;
                $itemAll = $item->serviceProduct;
                $itemService = $item->service;
                $itemProduct = $item->product;

                $itemSize= $item->size;
                $itemMatterss= $item->Mattresstype;
                $total_additions = $item->additions->sum('price');
                $productPrice = $itemProduct?(float)$itemProduct->price:0;
                $servicePrice = $itemService?(float)$itemService->price:0;
                $allPrice = $itemAll?(float)$itemAll->price:0;

                $productsizePrice = $itemSize?(float)$itemSize->size_price:0;
                $productMatterssPrice = $itemMatterss?(float)$itemMatterss->price:0;
                $sumPricesArray[] = $allPrice * $amount + $productsizePrice + $productMatterssPrice + $total_additions;
                $productPrices[] = $productPrice * $amount;
                $servicePrices[] = $servicePrice  + $productsizePrice + $productMatterssPrice + $total_additions;
            }
        }

        $sumPrices = array_sum($sumPricesArray);
        $sumproducts = array_sum($productPrices);
        $sumservices = array_sum($servicePrices);

        $productItemArray= [];
        $serviceItemArray= [];

        if($items->count()){
            foreach($items as $item){
                $product = $item->product;
                   if($product){
                        $productItemArray[] = [

                            'in_cart_quantity' => (int) $item->amount,
                            'cart_item_id' => (int) $item->id,
                            'id' => (int) $product->id,
                            'title' => (string) $product->title,
                            'quantity' => (float) $product->quantity,
                            'price' => (double) $product->price,
                            'img' => $product->img?(string) asset('images/products/'.$product->img):null,
                            'available'=>(int) $product->available, 
                      ];
                   }
                }
            }

           if($items->count()){
               
              foreach($items as $item){

                 $service = $item->service;
                 $size = $item->size;
                 $Matterss = $item->Mattresstype;
                 $Additions = $item->additions;

                 if($service){
                     $serviceItemArray[]= [

                        'cart_item_id' => (int) $item->id,
                        'id' => (int)  $service->id,
                        'title' => (string)   $service->title,
                        'price' => (double)  $service->price,
                        'img' =>  $service->img?(string) asset('images/products/'.$service->img):null,
                        'available'=>(int)  $service->available,
                        'size_title' => (string)  isset($size)? $size->size: null,
                        'size_price' => (double) isset($size) ?$size->size_price: null,
                        'mattress_type_title' => (string) isset($Matterss) ? $Matterss->name: null,
                        'mattress_type_price' => (double) isset($Matterss) ? $Matterss->price: null,
                        'car_type'=>$item->car_type,
                        'additions' => $Additions
                   ];
                }
              }
           }

          return response()->json([
            
             'status' => 200,
             'success' => true,
             'message' => trans('messages.get_data_success'),
             'data' => ['cart_id' => (int) $cart->id, 'items_count' => (int) $itemsCount, 'items_sum_prices' => (float) $sumPrices , 'products_prices'=>$sumproducts , 'services_prices'=>$sumservices  , 'products' => $productItemArray , 'services'=>$serviceItemArray]
         ]);
    }
    
    public function updateCartUser(Request $request, $cart_id)
    {
        
          $cart = Cart::where('id', $cart_id)->where('status', 1)->first();
          
          if(!$cart){

             return response()->json([

                 'status' => 422,
                 'success' => false,
                 'message' => trans('messages.cart_empty'),

              ]);
          }

          $user = auth('api')->user();
          $userCart = Cart::where('status', 1)->where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
         
          if($userCart){
            Cart::where('status', 1)->where('user_id', $user->id)->whereNotIn('id', [$userCart->id])->delete();
            $newCartItems = CartItem::where('cart_id', $cart->id)->update(['cart_id' => $userCart->id]);
          }
          else{
             
               $cart->update(['user_id' => $user->id]);
               $userCart = $cart;
         
            }
  
            return response()->json([

               'status' => 200,
               'success' => true,
               'message' => trans('messages.cart_empty'),
               'data' => ['cart_id' => $userCart->id,'message' => __('products::alert.added_user_to_cart_successfully')]
          ]);
      }
  }
