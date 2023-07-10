<?php

namespace App\Http\Controllers\Api\V3;

use App\Classes\Checkout;
use App\Classes\Operation;
use App\Http\Controllers\Controller;
use App\Http\Resources\V3\DeliverTimeResource;
use App\Http\Resources\V3\OrderDetailsResource;
use App\Http\Resources\V3\OrderResource;
use App\Models\Cart;
use App\Models\DeliveryDay;
use App\Models\Order;
use App\Models\OrderShippingAddress;
use App\Models\Payment;
use App\Classes\Payments;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Notification;
use App\Models\NotificationSubscription;
use App\Models\AdminNotification;
use App\User;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App;
use App\Traits\MailchampTrait;


class OrderController extends Controller
{

    use MailchampTrait;
    
    public function __construct(Request $request)
    {
        App::setLocale($request->header('lang'));
    }

    public function index(Request $request)
    {
       
         $orders = Order::where('user_id', auth()->id())->where('order_active',true);

         if($orderNumber = $request->order_number){
           
             $orderId = substr($orderNumber, 2);
             $orders->where('id', $orderId);
         }

         $orders = OrderResource::collection($orders->orderBy('id', 'desc')->paginate(10));

         return $this->sendResponse($orders->resource, trans('messages.get_data_success'));
    }

    public function show(Request $request, $orderId)
    {
        
        $orderId = substr($orderId, 2);
        $order = Order::where('id',$orderId)->first();

        if(!$order){

            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

         return $this->sendResponse(new OrderDetailsResource($order), trans('messages.get_data_success'));
    }

    public function rate(Request $request, $orderId)
    {
        
        $orderId = substr($orderId, 2);
        
        $order = Order::where('id',$orderId)->where('user_id', auth()->id())->first();

        if(!$order){

            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        foreach (['driver', 'products'] as $type){

          Rating::updateOrCreate(['type' => $type, 'user_type' => User::class, 'user_id' => auth()->id(), 'ratingable_type' => Order::class, 'ratingable_id' => $orderId],
            [
                
                'type' => $type,
                'user_id' => auth()->id(),
                'user_type' => User::class,
                'ratingable_type' => Order::class,
                'ratingable_id' => $orderId,
                'rating' => $type == 'driver'?$request->driver_rating:$request->products_rating,
                'comment' => $request->comment
            ]);
        }

         return $this->sendResponse([], trans('messages.data_updated_success'));
    }

    public function cancel(Request $request, $orderId)
    {
       
        $orderId = substr($orderId, 2);
        $order = Order::where('id',$orderId)
            ->where('user_id', auth()->id())
            ->where('status', '!=', Order::STATUS_DELIVERED)->first();

        if(!$order){
            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        $order->update(['status' => Order::STATUS_CANCELED, 'cancelled_by' => auth()->id(), 'cancelled_at' => Carbon::now()]);

        return $this->sendResponse(new OrderDetailsResource($order), trans('messages.data_updated_success'));
    }

    public function returnOrder(Request $request, $orderId)
    {
        
        $orderId = substr($orderId, 2);
        $order = Order::where('id',$orderId)
            ->where('user_id', auth()->id())
            ->where('status',  Order::STATUS_DELIVERED)->first();

        if(!$order){
            
            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        $order->update(['status' => Order::STATUS_RETURNED]);

        return $this->sendResponse(new OrderDetailsResource($order), trans('messages.data_updated_success'));
    }

    protected function initStcPay($initRequest = [])
    {
       
        $url = "B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentAuthorize";
        $stcCurlData = Checkout::callStcPay($url, $initRequest);
        dd($stcCurlData);

        try {

        } catch (\Exception $e){
            
            return $apiOperations->getErrorJsonResponse($e->getMessage());
        }

        return new JsonResponse(json_decode($stcCurlData['response']), $stcCurlData['code']);
    }

    public function create(Request $request)
    {

            $validator = Validator::make($request->all(), [

                'address.delivery_type' => 'required|in:personal,gift',
                'username'            => 'required_if:delivery_type, gift',
                'mobile'              => 'required_if:delivery_type,gift',
                'delivery_date'       => 'required|date_format:Y-m-d|after_or_equal:' . Carbon::now()->toDateString(),
                'delivery_start_time' => 'required',
                'delivery_end_time'   => 'required',
                'address.address'             => 'required',
                'address.lat'                 => 'required_with:address.address',
                'address.lng'                 => 'required_with:address.address',
                'payment_method'      => 'bail|required|in:visa,qasthaa,cash,bank,mada,tabby,paylink',
                'bank_transfer_no'    => 'bail|required_if:payment_method,bank',
                'bank_id'             => 'bail|required_if:payment_method,bank',

            ]);

             if ($validator->fails()) {

                   return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
             }

             $authUser = auth()->user();
             $cityId = $request->header('city_id');
             $cart = Cart::where('id', $request->cart_id)->where('status', 1)->first();
              if(!$cart){
             
                  return response()->json([

                        'status' => 422,
                        'success' => false,
                        'message' => trans('عربة التسوق فارغة.'),
                 ]);
               }
        
                $items = $cart->items()->with('serviceProduct')->get();
                $itemsCount = $items->sum('amount');
                $sumPricesArray = [];
                $productPrices=[];
                $servicePrices=[]; 
    
                if($itemsCount){

                    foreach($items as $item){

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
                        $productPrices[]= $productPrice * $amount;
                        $servicePrices[] = $servicePrice  + $productsizePrice + $productMatterssPrice + $total_additions;

                    }
               }  

               $coupon_discount = 0;
               $total = array_sum($sumPricesArray);
               $productstotal = array_sum($productPrices);
               $servicestotal = array_sum($servicePrices);
 
             if($request->promocode){

                $response = Checkout::checkCoupon($request->promocode, $total);

               if($response['success']){

                    $total = $response['new_price'];
                    $coupon_discount = $response['discount_value'];
                }
             }
        
             $paymentMethod = PaymentMethod::where('gateway', $request->payment_method)->where('status', 1)->first();

            if(!$paymentMethod){

                return $this->sendError([], __('messages.payment_method_not_supported'), 442);
            }

            $data = $request->all();
            
            if(in_array($paymentMethod->gateway, ['tabby','applepay','paylink'])){

                $data['payment_status'] = 0;
                $data['order_active'] = 0;

             }
             else{
 
                $data['order_active'] = 1;

            }

            $data['user_id'] = $authUser->id;
            $data['city_id'] = $cityId;
            $data['total']= $total;
            $data['services_total']= $servicestotal;
            $data['products_total']= $productstotal;
            $data['coupon_discount'] = $coupon_discount;
            $data['status'] = Order::STATUS_RESERVED;
            $data['last_payment_reference_id'] = hexdec(uniqid());
            $data['origin_payment_method'] = $request->payment_method;
            //$data['order_number'] = date('Ymd-his') ;

           try {
  
                DB::beginTransaction();
                $order = Order::create($data);
                Checkout::saveProducts($order,  $items);
                $addressRequest = $request->get('address',[]);
                $addressRequest['order_id'] = $order->id;
                $addressRequest['mobile'] = $request->mobile?:$authUser->mobile;
                $addressRequest['username'] = $request->username?:$authUser->name;
                OrderShippingAddress::create($addressRequest);
        
                Payment::create([

                    'payment_method_id' => $paymentMethod->id,
                    'status' => in_array($paymentMethod->gateway, ['cash', 'bank'])?1:0,
                    'payment_reference_id' => $data['last_payment_reference_id'],
                    'referenceable_id' => $order->id,
                    'referenceable_type' => 'orders',

                 ]);

                 DB::commit();

                // $this->sendMail($order->id);
                 $notification = new AdminNotification();
                 $notification->title ="تم استقبال طلب جديد"." ".$order->order_number;
                 $notification->content ="طلب جديد"." ".$order->order_number.""."from user".$order->user_id;
                 $notification->type="order";
                 $notification->save();

              if($paymentMethod->gateway =='cash'){

                 $playerId = NotificationSubscription::whereIn('user_id',[$authUser->id])->pluck('player_id')->toArray();

                 if(!empty($playerId))
                 {
                      $message = __('لقد استلمت طلب جديد', ['order_id'=> $order->id,'user_id'=> $order->user_id ,'date' =>  $order->created_at]);
                
                      sendNewNotification($message, $playerId);
                
                  } 
              }

             if(in_array($paymentMethod->gateway, ['mada','applepay','paylink'])){

                    return Payments::create($request, [
                    
                         'user' => $authUser,
                         'invoice' => $order,
                         'invoice_number' =>$order->id,
                         'amount' => $order->total
                   ]);
               }

                if($paymentMethod->gateway =="tabby"){

                    return $this->tabby($order->id);
                }
         
                 Checkout::emptyAllCarts($authUser->id);
 
                  return $this->sendResponse(new OrderDetailsResource($order), trans('messages.order_sent_successfully'));
      
               }catch (\Exception $e){

                 DB::rollback();

                 return $this->sendError([], $e->getMessage().'-'.$e->getFile().'- line'.$e->getLine(), 442);
            }
       }

       public function reorder(Request $request)
       {
       
               $validator = Validator::make($request->all(), [

                   'order_id'            => 'required',
                   'delivery_date'       => 'required|date_format:Y-m-d|after_or_equal:' . Carbon::now()->toDateString(),
                   'delivery_start_time' => 'required',
                   'delivery_end_time'   => 'required',
                   'payment_method'      => 'bail|required|in:visa,qasthaa,cash,bank,mada,applepay,paylink',

               ]);

               if($validator->fails()) {
 
                    return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
                }

                $order = Order::find($request->order_id);
                $paymentMethod = PaymentMethod::where('gateway', $request->payment_method)->where('status', 1)->first();
       
                if(!$paymentMethod){

                     return $this->sendError([], __('messages.payment_method_not_supported'), 442);
                 }

                 $authUser = auth()->user();
                 $data =$request->all();

                if(in_array($paymentMethod->gateway, ['tabby','applepay','paylink'])){

                      $data['payment_status'] = 0;
                      $data['order_active'] = 0;
    
                 }
                 else{
     
                    $data['order_active'] = 1;
                    
                 }
                 
                $data['user_id'] = $authUser->id;
                $data['city_id'] =$order->city_id;
                $data['total']= $order->total;
                $data['services_total']= $order->services_total;
                $data['products_total'] = $order->products_total;
                $data['coupon_discount'] = $order->coupon_discount;
                $data['status'] = Order::STATUS_RESERVED;
                $data['last_payment_reference_id'] = hexdec(uniqid());
                $data['origin_payment_method'] =$order->origin_payment_method ;
                //$data['order_number'] = date('Ymd-his');

             try {

                DB::beginTransaction();

                $newOrder = Order::create($data);
                Checkout::getProducts($order, $newOrder);
                $address = OrderShippingAddress::where('order_id',$request->order_id)->first();
                $addressRequest['delivery_type'] =$address->delivery_type;
                $addressRequest['address'] = $address->address;
                $addressRequest['lat'] = $address->lat;
                $addressRequest['lng'] = $address->lng;
                $addressRequest['order_id'] = $newOrder->id;
                $addressRequest['mobile'] = $request->mobile?:$authUser->mobile;
                $addressRequest['username'] = $request->username?:$authUser->name;
                OrderShippingAddress::create($addressRequest);
            
                Payment::create([

                    'payment_method_id' => $paymentMethod->id,
                    'status' => in_array($paymentMethod->gateway, ['cash', 'bank'])?1:0,
                    'payment_reference_id' => $data['last_payment_reference_id'],
                    'referenceable_id' => $newOrder->id,
                    'referenceable_type' => 'orders',
                ]);
            
                DB::commit();
                
               // $this->sendMail($newOrder->id);
                $notification = new AdminNotification();
                $notification->title ="تم استقبال طلب جديد"." ". $newOrder->order_number;
                $notification->content ="طلب جديد برقم "." ".$newOrder->order_number.""."from user".$order->user_id;
                $notification->type="order";
                $notification->save();
            
              if($paymentMethod->gateway =='cash'){

                $playerId = NotificationSubscription::whereIn('user_id',[$authUser->id])->pluck('player_id')->toArray();

                if(!empty($playerId))
                {
                      $message = __('لقد استلمت طلب جديد', ['order_id'=>$newOrder->id,'user'=>$newOrder->user_id ,'date' => $newOrder->created_at]);
                  
                     sendNewNotification($message, $playerId);
                 }
              }

              if(in_array($paymentMethod->gateway, ['mada','applepay','paylink'])){
  
                    return Payments::create($request, [

                        'user' => $authUser,
                        'invoice' =>$newOrder ,
                        'invoice_number' =>$newOrder->id,
                        'amount' => $newOrder->total
                    ]);
                }

               if($paymentMethod->gateway =="tabby"){

                    return $this->tabby($newOrder->id);
                }

                return $this->sendResponse(new OrderDetailsResource($newOrder), trans('messages.order_sent_successfully'));

            }catch (\Exception $e){

               DB::rollback();

               return $this->sendError([], $e->getMessage().'-'.$e->getFile().'- line'.$e->getLine(), 442);
          }
      }

      public function applyCoupon(Request $request)
      {
           
           $response = Checkout::checkCoupon($request->promo_code, $request->price);

           if(!$response['success']){

               return $this->sendError([], $response['message'], 442);
           }

            return $this->sendResponse(['new_price' => $response['new_price'], 'discount_value' => $response['discount_value']], trans('messages.coupon_applied_success'));
      }

      public function getAvailableShifts(Request $request)
      {

          $validator = Validator::make($request->all(), [

              'delivery_date' => 'required|date',
          ]);

        if($validator->fails()) {

            return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
        }

        $deliveryDate = Carbon::parse($request->get('delivery_date'));

        $day = DeliveryDay::whereDayOfWeek($deliveryDate->dayOfWeek)->first();

        if(!$day){

            return $this->sendError(error_processor($validator), trans('غير متاح التوصيل في هذا التاريخ'), 442);
        }

        if(!$day->status){

            return $this->sendError(error_processor($validator), trans('messages.delivery_on_x_day_not_available', ['day' => trans('admin.week_days_list.'.$day->day_of_week)]), 442);
        }

         $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d',  $request->get('delivery_date'));

        if($carbonDate->isToday()){
            $nowTime = \Carbon\Carbon::now()->format('H:i:s');
            $times = $day->times()->where('start_time','>',$nowTime);

            }else{

                $times = $day->times();
            }
    
            $timesList = $times->where('delivery_times.city_id', (int) $request->header('city_id'))->get();
            $timesCollection = [];
                foreach ($timesList as $delivery_time){

                  $countOrders = Operation::countOrdersByDeliveryShifts($request->delivery_date, $delivery_time->start_time, $delivery_time->end_time);
                  $diff = max((int) $delivery_time->max_orders_to_accept - $countOrders, 0);

                  if($delivery_time->max_orders_to_accept > 0 && $diff > 0){
                       $timesCollection[] = [

                            'name' => implode(' - ', [$delivery_time->start_time, $delivery_time->end_time]),
                            'is_available' => $delivery_time->max_orders_to_accept > 0? ($diff > 0): true,
                            'id'   => $delivery_time->getRouteKey()
                        ];
                   }
              }

          return $this->sendResponse($timesCollection, trans('messages.get_data_success'));
    }

    public function hasNewUpdates(Request $request)
    {
    
        $user = auth()->user();
        $notificationsCount = Operation::userNotificationsCount($user, true);
        return $this->sendResponse(['has_new_updates' => (boolean) $notificationsCount['ordersCount'], 'count' => (int) $notificationsCount['ordersCount']], trans('messages.get_data_success'));

    }

    public function updatePaymentMethod(Request $request, $orderId)
    {
       
         $validator = Validator::make($request->all(), [
            
             'payment_method' => 'bail|required|in:mastercard,visa,sadad,cash,bank,apple-pay,stc-pay',
         ]);

        if ($validator->fails()) {
            
             return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
        }

        $orderId = substr($orderId, 2);
        
        $order = Order::find($orderId);

        if(!$order){
            
             return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        if(!in_array($order->payment_method, ['cash'])){
            
             return $this->sendError([], trans('messages.you_cannot_update_this_order_payment_method'), 404);
        }

        if(in_array($order->status, [Order::STATUS_DELIVERED, Order::STATUS_CANCELED])){

             return $this->sendError([], trans('messages.you_cannot_update_this_order_payment_method'), 404);
        }

        if($request->payment_method == 'apple-pay'){

            return $this->sendResponse(['order_id' => (int) $order->id,'payment_reference_id' => (string) $order->last_payment_reference_id], trans('messages.get_data_success'));
        }

        return $this->sendResponse(['view' => Checkout::payWithPayfort($order)], trans('messages.get_data_success'));
    }

    public function skipPayment(Request $request, $orderId)
    {
       
        $orderId = (int) substr($orderId, 2);
        $order = Order::find($orderId);

        if(!$order){

            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        if($order->payment_status || in_array($order->payment_method, ['cash', 'bank'])){

            return $this->sendError([], trans('messages.something_went_wrong'), 404);
        }

        $order->status = Order::STATUS_RESERVED;
        $order->payment_status = false;
        $order->save();

         Checkout::emptyAllCarts($order->user);

         return $this->sendResponse(new OrderDetailsResource($order), trans('messages.get_data_success'));
    }


    public function paymentStatus(Request $request, $status)
    {
        
        if($status == 'success'){

            return $this->sendResponse([], trans('messages.get_data_success'));
        
        }

         return $this->sendError([], trans('messages.something_went_wrong'), 442);
    }

    public function getReceipt(Request $request)
    {

        $cart = Cart::where('id', $request->cart_id)->first();
       
        if(!$cart){
            
             return $this->sendError(['error_type' => 'cart_empty'], trans('عربة التسوق فارغة'), 404);
        }

        $cityId = $request->header('city_id');
        $items = $cart->items()->get();
        $productsIdsArray = [];
        $totalPrice = 0;
        if($items->count()){
            foreach($items as $item){
                if($item->amount <= 0){
                    $item->delete();
                    continue;
                }

                $product = $item->product;
                if($product){

                    $totalPrice += Checkout::getProductSaleDetails($product, ['price' => $item->price,'quantity' => (int)$item->amount],$cityId)['salePrice'];
                    $productsIdsArray[$item->product_id] = ['price' => $item->price,'quantity' => (int)$item->amount];
                }
            }
        }

         $balanceValue = 0;

         if($request->pay_with_balance){

             $walletDetails = Checkout::useWallet($totalPrice);

             if(!$walletDetails['success']){
                
                  return $this->sendError(['error_type' => 'cannot_use_wallet'], trans('لا يوجد رصيد كافي'), 442);
            }  

              $balanceValue = $walletDetails['paid_with_balance'];
          }

            $promoCodeValue = 0;
  
           if($request->promo_code){

               $promoCodeDetails = Checkout::checkCoupon($request->promo_code, $totalPrice);

              if(!$promoCodeDetails['success']){

                    return $this->sendError(['error_type' => 'cannot_use_coupon'], $promoCodeDetails['message'], 442);
               }
                  $promoCodeValue = $promoCodeDetails['discount_value'];
             }

             $response = Checkout::sumCurrentProductsPriceWithIds($productsIdsArray, $cart->city_id, null, $promoCodeValue, $balanceValue);
       
             return $this->sendResponse([
            
                 'productsPrice' => (float) $response['productsPrice'],
                 'promoCodeDiscount' => (float) $response['promoCodeDiscount'],
                 'offerDiscountValue' => (float) $response['discountValue'],
                 'deliveryFees' => (float) $response['deliveryFees'],
                 'taxValue' => (float) $response['taxValue'],
                 'paid_with_balance' => (float) $response['balanceValue'],
                 'finalPrice' => (float) $response['finalPrice'],

               ], trans('messages.get_data_object'));
         }

        public function return(Request $request)
        {
     
              $orderNumber = $request->orderNumber;
              $transactionNo = $request->transactionNo;

              if(!$request->transactionNo){

                   return response()->json(['success' => false,'status_code'=> 422 ,'message'=> 'Transaction Not Found','data' => []]);
              }

              $token = Payments::paylinkAuth();
            
              if(!$token){

                  return response()->json(['success' => false,'status_code'=> 422, 'message' => 'Auth Error','data' => []]);
              }

              $payload = [];

              $payUrl = 'https://restapi.paylink.sa/api/getInvoice/'.$transactionNo;
                
              $ch = curl_init( $payUrl );
              curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
              curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
              curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
              curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
              $result = curl_exec($ch);
              curl_close($ch);
              $response = json_decode($result);

             if(isset($response->orderStatus) && $response->orderStatus == 'Paid'){
               
                $order = Order::where('id',$orderNumber)->first();
                $paymentupdate= Payment::where('referenceable_id',$orderNumber)->where('referenceable_type', 'orders')->update(['status' => 1]);
                $order_updates= Order::where('id',$orderNumber)->update(['payment_status' => true ,'order_active'=>true]);
               
                $playerId = NotificationSubscription::whereIn('user_id',[$order->user_id])->pluck('player_id')->toArray();

                if(!empty($playerId))
                {
    
                    $message = __('you received new order', ['order_id'=>$order->id,'user'=>$order->user_id ,'date' => $order->created_at]);
                    $notification = Notification::create(['group' => 'select_user', 'title' => $message, 'content' => $message]);
                    $notification->users()->sync([$order->user_id]);
                    sendNewNotification($message, $playerId);
    
                }

                Checkout::emptyAllCarts($order->user_id);

               return response()->json(['success' => true,'status_code'=> 200, 'message' => 'Paid','data' => []]);
           }
              return response()->json(['success' => false,'status_code'=> 422 ,'message'=> 'Not Paid','data' => ['transaction_number' => $orderNumber]]);
        } 

        public function feedback(Request $request)
        {
              $client = new Client([

                   'headers' => ['Content-Type' =>'application/json','Authorization'=>"Bearer sk_test_f9ad5b7b-d210-444c-aac8-81f80a8f7489"]
              ]);

                  $orderResponse = $client->get('https://api.tabby.ai/api/v2/payments/'.$request->payment_id,
             
              );

              $response = json_decode($orderResponse->getBody(), true);

              $orderId = $response['order']['reference_id'];

            if(isset($response['status']) && $response['status'] == 'AUTHORIZED'){
               
                 $order = Order::where('id', $orderId)->first();
                 $paymentupdate= Payment::where('referenceable_id', $orderId)->where('referenceable_type', 'orders')->update(['status' => 1]);
                 $order_updates= Order::where('id', $orderId)->update(['payment_status' => true ,'transaction_id'=>$request->payment_id ,'order_active'=>true]);
               
                 $playerId = NotificationSubscription::whereIn('user_id',[$order->user_id])->pluck('player_id')->toArray();

                if(!empty($playerId))
                {
    
                     $message = __('you received new order', ['order_id'=>$order->id,'user'=>$order->user_id ,'date' => $order->created_at]);
                     $notification = Notification::create(['group' => 'select_user', 'title' => $message, 'content' => $message]);
                     $notification->users()->sync([$order->user_id]);
                     sendNewNotification($message, $playerId);
    
                }

                 Checkout::emptyAllCarts($order->user_id);

                 return response()->json(['success' => true,'status_code'=> 200, 'message' => 'Paid','data' => ['transaction_number' => $request->payment_id]]);
             }

              return response()->json(['success' => false,'status_code'=> 422 ,'message'=> 'Not Paid','data' => []]);
        }

        protected function tabby($id)
        {
        
                $order = Order::find($id);
                $address = OrderShippingAddress::where('order_id',$id)->first();
                $orders = array(

                   'payment'=>array(

                       "amount"=> $order->total,
                       "currency"=> "SAR",
                       "description"=> "test",

                     'buyer'=>array(

                         "phone"=>"500000001",
                         "email"=> "card.success@tabby.ai",
                         "name"=> "ahmed",
                         "dob"=> "2022-08-24"

                      ),

                      'shipping_address'=>array(

                          "city"=>$order->city->name,
                          "address"=> $address->address,
                          "zip"=> "8765"
                       ),
        
                        'order'=>array(

                           "tax_amount"=> "0.00",
                            "shipping_amount"=> "0.00",
                            "discount_amount"=> "0.00",
                            "updated_at"=> "2019-08-24T14:15:22Z",
                            "reference_id"=> "$order->id",
                        ),

                         'meta'=>array(

                             "order_id"=> "$order->id",
                             "customer"=> "#customer-id" 
                         ),

                         'attachment'=>array(

                            "body"=> "{\"flight_reservation_details\": {\"pnr\": \"TR9088999\",\"itinerary\": [...],\"insurance\": [...],\"passengers\": [...],\"affiliate_name\": \"some affiliate\"}}",
                            "content_type"=> "application/vnd.tabby.v1+json"
                        ),
                    ),
        
                     "lang"=> "ar",
                     "merchant_code"=> "Lamaasau",
                     "merchant_urls"=>array(
                     "success"=> "http://127.0.0.1:8000/api/v3/success",
                     "cancel"=> "http://127.0.0.1:8000/api/v3/cancel",
                     "failure"=> "http://127.0.0.1:8000/api/v3/failure"
                 )
       
            );

            $orders_new = $orders;
            $data = json_encode($orders_new);
            $client = new Client([

                'headers' => ['Content-Type' =>'application/json','Authorization'=>"Bearer pk_test_a157dffd-3834-46af-9370-03f4e01ffdbb"]

            ]);

            $orderResponse = $client->post('https://api.tabby.ai/api/v2/checkout',

                 ['body' => $data]
            );  

            $order_results = json_decode($orderResponse->getBody(), true);

            return response()->json(['success' => true,'status_code'=> 200, 'data' => ['payment_url' => $order_results['configuration']['available_products']['installments'][0]['web_url']]]);

       }

        
}
