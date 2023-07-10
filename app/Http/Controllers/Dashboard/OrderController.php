<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Checkout;
use App\Classes\Notification;
use App\Classes\Operation;
use App\Exports\CommonExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\V3\DeliverTimeResource;
use App\Imports\OrdersImport;
use App\Models\Audit;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Compansations;
use App\Models\CustomerShippingAddress;
use App\Models\DeliveryDay;
use App\Models\Order;
use App\Models\OrderReceipt;
use App\Models\OrderShippingAddress;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\HomePageSetting ;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Redirect;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class OrderController extends Controller
{

    public function index(Request $request, $template = 'orders')
    {
        
        $data['breadcrumb'] = [
            
            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.'.$template), 'url' => null],
        ];

        $data['page_title'] = trans('admin.'.$template.'_list');
        $data['orders'] = $this->searchResult($request, Order::query())->orderBy('id', 'desc')->paginate($request->get('show_result_count', 15))->withQueryString();
        $data['template'] = $template;
        $request->request->add(['template' => $template]);

        if ($request->ajax()) {
            $listView = view('dashboard.orders.partials.orders_list', $data)->render();
            return response()->json(['listView' => $listView]);
        }

        return view('dashboard.orders.index', $data);
    }

    protected function searchResult($request, $orders){

        if($searchWord = $request->get('search_word')){
            $orders = $orders->where(function ($q) use ($searchWord) {
                  $q->whereHas('user', function ($q) use ($searchWord) {
                      $q->where('name','like','%'.$searchWord.'%')
                        ->orWhere('mobile','like', '%'.$searchWord.'%');
                  });
                 })->orWhere('id', substr($searchWord, 2));
         }
        
         if($orderNumber = $request->order_number){

             $orders = $orders->where('id', substr($orderNumber, 2));
         }

        if(!is_null($request->status)){

            $orders = $orders->where('status', $request->status);
        }

        if(is_null($request->status) && $request->template == 'downloads'){
            
            $orders = $orders->whereIn('status', Order::ON_PROCESS_ORDER_STATUS_LIST);
        }

        if($createdAt= $request->created_at){
            
            $array = explode(' >> ', $createdAt);
            $orders = $orders->where('created_at', '>=', $array[0])
            ->where('created_at', '<=', $array[1]);
        }

        if($delivery_date= $request->delivery_date){
            $array = explode(' >> ', $createdAt);
            $orders = $orders->where('delivery_date', '>=', $array[0])
                ->where('delivery_date', '<=', $array[1]);
        }
        if ($request->mobile || $request->name || $request->user_id){
            $orders = $orders->whereHas('user', function ($q) use ($request) {
                if($request->mobile){
                    $q = $q->where('mobile', $request->mobile);
                }

                if($request->name){
                    $q = $q->where('name', $request->name);
                }

                if($request->user_id){
                    $q = $q->where('user_id', (int) $request->user_id);
                }
            });
        }

        if ($request->city){
            $orders = $orders->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            });
        }

        if ($request->payment_method){
            $orders = $orders->whereHas('payment', function ($q) use ($request) {
                $q->where('payment_method_id', (int) $request->payment_method);
            });
        }

        return $orders;
    }

    public function create()
    {
        return view('dashboard.orders.create');
    }

    public function store(Request $request)
    {
        
        if($request->is_new_customer){

            $data = $request->all();
            $data['password'] = Hash::make(uniqid());
            $validator = Validator::make($data, (new User)->rules());
             if ($validator->fails()) {

                  return redirect()->back()->withErrors($validator)->withInput();
              }
 
              $customer = User::create($data);

             }else{

                $customer = User::where('mobile', $request->mobile)->first();

              if(!$customer){

                   return redirect()->back();
                }
             }

             $orderData = [

                'user_id' => $customer->id,
                'status' => Order::STATUS_PENDING,
                'total' => 0,
                'delivery_date' => Carbon::now(),
                'delivery_start_time' => Carbon::now()->format('H:i:s'),
                'delivery_end_time' => Carbon::now()->format('H:i:s')
            ];
  
            $order = Order::create($orderData);

            return redirect()->route('orders.edit', $order->id);
    }

    public function show(Request $request, $id)
    {
        
        $order = Order::findOrFail($id);
        $data['order'] = $order;
        $data['customer'] = $order->user?:new User;
        $data['address'] = $order->address?:new OrderShippingAddress();
        $productsIds = [];
        $data['services'] = $order->services;
        $data['products'] = $order->products;

        $allIds = [];

        foreach ($order->products as $row){
            if($row->pivot->offer_product_id != $id){
                $offerProduct = Product::find($row->pivot->offer_product_id);
            }else{
                $offerProduct = null;
            }

             $productsIds[$row['id']] = ['quantity' => $row->pivot->quantity , 'price' => $row->pivot->quantity > 0?($row->pivot->amount/$row->pivot->quantity):0];
         }

         foreach ($order->servicesandproducts as $row){

             $allIds[$row['id']] = ['quantity' => $row->pivot->quantity , 'price' => $row->pivot->quantity > 0?($row->pivot->amount/$row->pivot->quantity):0];
         }

         $data['orderProductsDetails'] = Checkout::sumCurrentProductsPriceWithIds($productsIds, $request->city_id, $request->promo_code, 0, (int) $order->balanceValue);
         $data['orderallservicesDetails'] = Checkout::sumCurrentProductsPriceWithIds($allIds, $request->city_id, $request->promo_code, 0, (int) $order->balanceValue);
         $data['orderLogs'] = Audit::where('auditable_id', $order->id)->where('auditable_type', 'orders')->get();

          return view('dashboard.orders.show', $data);
    }

    public function edit(Request $request, $id)
    {
         
        $order = Order::findOrFail($id);
        $data['order'] = $order;
        $data['customer'] = $order->user?:new User;
        $data['address'] = $order->address?:new OrderShippingAddress();
        $productsIds = [];
        $data['services'] = $order->services;
        $data['products'] = $order->products;

        $allIds = [];

        foreach ($order->products as $row){
            if($row->pivot->offer_product_id != $id){

                $offerProduct = Product::find($row->pivot->offer_product_id);
            }else{
                
                 $offerProduct = null;
            }

             $productsIds[$row['id']] = ['quantity' => $row->pivot->quantity , 'price' => $row->pivot->quantity > 0?($row->pivot->amount/$row->pivot->quantity):0];
        }  

        foreach ($order->servicesandproducts as $row){

             $allIds[$row['id']] = ['quantity' => $row->pivot->quantity , 'price' => $row->pivot->quantity > 0?($row->pivot->amount/$row->pivot->quantity):0];
        } 

         $data['orderProductsDetails'] = Checkout::sumCurrentProductsPriceWithIds($productsIds, $request->city_id, $request->promo_code, 0, (int) $order->balanceValue);
         $data['orderallservicesDetails'] = Checkout::sumCurrentProductsPriceWithIds($allIds, $request->city_id, $request->promo_code, 0, (int) $order->balanceValue);
         $data['orderLogs'] = Audit::where('auditable_id', $order->id)->where('auditable_type', 'orders')->get();

           return view('dashboard.orders.edit', $data);
     }

     public function ajaxAddToCart(Request $request)
     {

            $productsRequest = $request->get('products', []);
            $productsIds = [];
            $balanceValue = 0;
            foreach ($productsRequest as $row){
                $productsIds[$row['id']] = ['quantity' => $row['quantity'], 'price' => null];
            }

            $data['orderProductsDetails'] = Checkout::sumCurrentProductsPriceWithIds($productsIds, $request->city_id, $request->promo_code, 0, $balanceValue);
            
            return view('dashboard.orders.partials.cart_items', $data);
     }

     public function productsList(Request $request, $orderId)
     {
        
        $order = Order::find($orderId);
        $orderProductsArray = [];
        foreach ($order->products as $product){
            $orderProductsArray[$product->id] = $product->pivot->quantity;
        }
        $address = $order->address;
        $cityId = $address?$address->city_id:null;

        $products = Product::where('available', 1);
        if($cityId){
            $products = $products->whereHas('cities', function ($q) use ($cityId) {
                $q->where('cities.id', $cityId);
            });
        }

           return view('dashboard.orders.partials.products_list', ['products' => $products->get(), 'orderProductsArray' => $orderProductsArray]);
      }

      public function update(Request $request, $id)
      {
        
            $order = Order::findOrFail($id);
            if($paymentMethodId = $request->get('payment_method')){
                $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);
            if($paymentMethod){
                $order->update(['payment_status' =>  $request->get('payment_status', 0)]);
                Payment::updateOrcreate(['referenceable_id' => $order->id, 'referenceable_type' => 'orders',],[
            
                    'payment_method_id' => $paymentMethod->id,
                    'status' =>  $request->get('payment_status', 0),
                    'payment_reference_id' => null,
                    'referenceable_id' => $order->id,
                    'referenceable_type' => 'orders',

                ]);
            }

               return redirect()->back();
         }

        if(!empty($request->get('products', []))){
            $handleProducts = Checkout::handleProductsRequest($request->get('products', []));
            if(!$handleProducts['success']){
                return redirect()->back();
            }
            $address = $order->address;
            $orderPrices = Checkout::sumCurrentProductsPriceWithIds($handleProducts['productsIds'], $address->city_id, $request->get('coupon'), 0, 0);
            $order->total = $orderPrices['finalPrice'];
            Checkout::createReceipt($order, $orderPrices);
            Checkout::saveProducts($order, $orderPrices);
        }

        if($status = $request->status){
            if(!in_array($order->status, [Order::STATUS_CANCELED, Order::STATUS_DELIVERED])){
                $oldStatus = $order->status;
                $updates['status'] = $request->status;
                if($status == Order::STATUS_RESCHEDULED && $request->rescheduled_date){
                    $times = $request->available_times?explode(' - ',$request->available_times):[];
                    $updates['delivery_date'] = $request->rescheduled_date;
                    if(count($times) >= 2){
                        $updates['delivery_start_time'] = $times[0];
                        $updates['delivery_end_time'] = $times[1];
                    }

                 }elseif ($status == Order::STATUS_CANCELED){
                    $updates = array_merge($updates, [
                        'cancelled_reason' => $request->cancelled_reason,
                        'cancelled_by' => auth()->id(),
                        'cancelled_at' => Carbon::now(),
                    ]);

                    }elseif ($status == Order::STATUS_DELIVERED){

                      $updates = array_merge($updates, [

                          'payment_status' => 1,
                     ]);

                    if($orderUser = $order->user){ //add order total to payments not in cashback
                        $orderUser->payments_not_in_cashback += $order->total;
                        $orderUser->save();
                    }
                    
                  }elseif ($status == Order::STATUS_RETURNED){
                    if($orderUser = $order->user){ //subtract order total to payments not in cashback
                        $orderUser->payments_not_in_cashback -= $order->total;
                        $orderUser->save();
                    }
                }

                  Operation::orderLog($order, ['status' => $oldStatus],['status' => $status]);
                  $updates['driver_id'] = $request->driver_id;
                  $order->update($updates);
                   if((int) $request->send_notification > 0){

                     Notification::sendOneNotificationByStatus('order_status_'.$status, $order->user, $order);
                 }

                   return redirect()->back();
              }
          }

          if(!empty($request->get('address', []))){

              $addressData = $request->get('address', []);
              $addressData['order_id'] = $order->id;
              if(isset($addressData['address_id']) && $addressData['address_id']){
                 $addressObj = CustomerShippingAddress::findOrFail($addressData['address_id']);
                  OrderShippingAddress::updateOrCreate(['order_id' => $order->id],[

                        'address' => $addressObj->address,
                        'delivery_type' => $addressObj->type,
                        'username' => $addressObj->username,
                        'mobile' => $addressObj->mobile,
                        'city_id' => $addressObj->city_id,
                        'lat' => $addressObj->lat,
                        'lng' => $addressObj->lng,
                        'order_id' => $order->id,
                 ]);
                
                }else{

                    OrderShippingAddress::updateOrCreate(['order_id' => $order->id],$addressData);
                 }
                    return redirect()->back();
              }
  
               $order->save();

              return redirect()->back();
        }

        public function viewInvoice(Request $request, $orderNumber, $template = 'default')
        {
           
            $order = Order::findOrFail(substr($orderNumber, 2));
            $gs = HomePageSetting::find(1);
            $customer = $order->user;
            $address = $order->address;
            $payment = $order->payment;
            $paymentMethod = $payment?$payment->paymentMethod:null;

            if(!$customer || !$address){

                abort(442, 'حدث خطأ ما يرجى التواصل برقم الطلب على support@mawaredal-hayat.com');
            }

            $productsIds = [];
            $allsIds = [];

            $orderProducts = $order->products;
            $orderServices = $order->services;

            foreach ($orderProducts as $row){

                $productsIds[$row['id']] = ['quantity' => $row->pivot->quantity, 'price' => $row->pivot->quantity > 1?($row->pivot->amount/$row->pivot->quantity):0, 'offerText' => $row->pivot->offer];
            }

            foreach ($order->servicesandproducts as $row){

                $allIds[$row['id']] = ['quantity' => $row->pivot->quantity , 'price' => $row->pivot->quantity > 0?($row->pivot->amount/$row->pivot->quantity):0];
            }

            $orderProductsDetails = Checkout::sumCurrentProductsPriceWithIds($productsIds, $address->city_id, null, 0, (int) $order->balanceValue);
            $orderallservicesDetails = Checkout::sumCurrentProductsPriceWithIds($allsIds, $request->city_id, $request->promo_code, 0, (int) $order->balanceValue);

            if($template == 'qr'){

                 return view('dashboard.orders.qr_invoice', compact('order', 'customer', 'address', 'payment', 'paymentMethod', 'orderProductsDetails', 'orderProducts', 'template','orderServices','orderallservicesDetails','gs'));
            }

             return view('dashboard.orders.html_invoice', compact('order', 'customer', 'address', 'payment', 'paymentMethod', 'orderProductsDetails', 'orderProducts', 'template','orderServices','orderallservicesDetails','gs'));
       }

          public function sendtoDriver(Request $request ,$id)
          { 
             
              $order = Order::findOrFail($id);

              $orderaddress= OrderShippingAddress::where('order_id',$id)->first();

             if($status = $request->status){

                if(!in_array($order->status, [Order::STATUS_CANCELED, Order::STATUS_DELIVERED])){

                     $oldStatus = $order->status;
                     $updates['status'] = $request->status;

                    if($status == Order::STATUS_CANCELED){

                        $updates = array_merge($updates, [

                              'cancelled_reason' => $request->cancelled_reason,
                              'cancelled_by' => auth()->id(),
                              'cancelled_at' => Carbon::now(),
                         ]);
                    
                       }elseif($status == Order::STATUS_DELIVERED){

                         $updates = array_merge($updates, [
                             
                            'payment_status' => 1,
                         ]);
                     }
  
                     $updates['driver_id'] = $request->driver_id;
                     $order->update($updates);
                     $orderid="رقم الطلب:"." ".$order->id;
                     $ordertotal="اجمالى الطلب"." ".$order->total;
                     $orderdate ="تاريخ استلام الطلب"." ". $order->delivery_date;
                     $orderTime="وقت تنفيذ الطلب"." ". $order->delivery_start_time."-". $order->delivery_end_time ;
                     $paymentmethod="وسيلة الدفع:"." ".$order->payment_method;
                     $productstotal="اجمالى المنتجات"." ".$order->products_total;
                     $customername="اسم العميل:"." ".$orderaddress->username;
                     $customeraddress="عنوان العميل:"." ".$orderaddress->address;
                     $customermobile="رقم جوال العميل:"." ".$orderaddress->mobile;
                     $orderCity="المدينة:"." ".$order->city->name;
                     $latitude = $orderaddress->lat ;
                     $langitude = $orderaddress->lng ;
                     $address_link = rawurlencode("http://www.google.com/maps/place/$latitude,$langitude");
                     
                     $orderservices =[];
                     $orderproducts =[];
                     $orderAdditions=[];

                    foreach($order->services as $service)
                    {
                        
                        $orderservices =[

                             'اسم الخدمة'=> $service->title,
                             'حجم السيارة'=> $service->pivot->size,
                             'نوع المرتبة'=> $service->pivot->mattress
                        ];
                    }

                    foreach($order->additions as $addition)
                    {
                        
                        $orderAdditions =[

                          'اسم الاضافة'=> $addition->name

                        ];
                    }

                    foreach($order->products as $product)
                    { 

                        $orderproducts = [

                            'المنتج'=> $product->title,

                        ];
                    }

                    $services= json_encode($orderservices ,JSON_UNESCAPED_UNICODE);
                    $servicez =  str_replace(array("#", ";","{","}"), '', $services);
                    $productz =json_encode($orderproducts ,JSON_UNESCAPED_UNICODE);
                    $products=  str_replace(array("#", "{","}"), '', $productz);
                    $products=  str_replace(array("#", "{","}"), '', $productz);
                    $additionz= json_encode($orderAdditions , JSON_UNESCAPED_UNICODE);
                    $additions=  str_replace(array("#", ";","{","}"), '',  $additionz);

                 if($request->driver_id && $order->driver_id == $request->driver_id  && $order->status != Order::STATUS_DELIVERED){

                     $driver = User::where('id',$request->driver_id)->first();

                    if($productstotal == 0){

                          return Redirect::to("https://api.whatsapp.com/send/?phone=%2B$driver->mobile&text=$orderid%0a$orderdate%0a$orderTime%0a$paymentmethod%0a$ordertotal%0aالخدمات: %0a$servicez%0a الاضافات:%0a$additions%0a$customername%0a$orderCity%0a$customermobile%0a$customeraddress%0a$address_link &app_absent=0");
                     }

                     else{

                           return Redirect::to("https://api.whatsapp.com/send/?phone=%2B$driver->mobile&text=$orderid%0a$orderdate%0a$orderTime%0a$paymentmethod%0a$ordertotal%0aالخدمات:%0a$servicez%0a الاضافات :$additions%0aالمنتجات: %0a$products%0a$customername%0a$orderCity%0a$customermobile%0a$customeraddress%0a$address_link &app_absent=0");
                      }
                   }
                    
                      return redirect()->back();
                  }

                 else{
                        
                   return redirect()->back();
              }
          }
    }

    public function destroy($id)
    {
        

    }

    public function statusesList(Request $request, $id)
    {

        $order = Order::find($id);

        if(!$order){

            return $this->sendError([], trans('messages.not_found_data'));
        }

        $view = view('dashboard.orders.partials.change_status_options', ['order' => $order])->render();

        return $this->sendResponse(['view' => $view], trans('messages.get_data_success'));
    }

    public function getAvailableShifts(Request $request)
    {
        
        $deliveryDate = Carbon::parse($request->get('date'));

        $day = DeliveryDay::whereDayOfWeek($deliveryDate->dayOfWeek)->first();

        return view('dashboard.orders.partials.available_shifts', ['times' => $day->times]);
    }

    public function changeBulkStatuses(Request $request)
    {
       
        $ids = $request->get('selected_orders', []);
        $orders = Order::whereIn('id', $ids)->whereNotIn('status', [Order::STATUS_DELIVERED, Order::STATUS_CANCELED])->get();
        
        foreach ($orders as $order){

            $order->update(['status' => (int) $request->status]);
        }

        return redirect()->back();

    }

    public function cartToOrder(Request $request, $id)
    {
        
        $cart = Cart::findOrFail($id);
        $orderData = [

            'user_id' => $cart->user_id,
            'delivery_date' => Carbon::now(),
            'delivery_start_time' => Carbon::now()->format('H:i:s'),
            'delivery_end_time' => Carbon::now()->format('H:i:s')
        ];

        $cartProducts = CartItem::where('cart_id', $cart->id)->pluck('amount', 'product_id')->toArray();
        $cartProductsArray = [];
        $cartSavedProductsArray = [];

        foreach ($cartProducts as $key => $value){
            
            $product = Product::find($key);

            if($product){

                $cartProductsArray[$key] = ['quantity' => $value];
                $cartSavedProductsArray[$key]['quantity'] = (int)$value;
                $cartSavedProductsArray[$key]['amount'] = (float) $product->price * (int)$value;
            }
        }

        $orderProductsDetails = Checkout::sumCurrentProductsPriceWithIds($cartProductsArray, $cart->city_id);
        $orderData['total'] = $orderProductsDetails['finalPrice'];
        $order = Order::create($orderData);
        $order->products()->sync($cartSavedProductsArray);

        return redirect()->route('orders.edit', $order->id);

    }

    public function importExcel(Request $request)
    {
        \Excel::import(new OrdersImport, $request->file('imported_file'));

        return redirect()->back();
    }

    public function exportToExcel(Request $request)
    {
       
        $orders = $this->searchResult($request, Order::query())->orderBy('id', 'desc')->take($request->get('export_result_count', 100))->get();
        $data['rows'] = [];

        foreach ($orders as $order){
            $orderProducts = $order->products;
            $orderAddress = $order->address?:new OrderShippingAddress;
            $customer = $order->user;
            $customerCityName = null;
            $bank_code = null;
            $isOnlinePayment = 0;
            $orderPayment = $order->payment;

            if($orderPayment){
                
                if(!empty($orderPayment->params)){
                    $bank_code_data= $orderPayment->params;
                    $bank_code=isset($bank_code_data->bank_transfer_no)?$bank_code_data->bank_transfer_no:'';
                }

                if($paymentMethod = $orderPayment->paymentMethod){

                    $isOnlinePayment = $paymentMethod->gateway == 'cash'?0:1;
                }
            }

            if($orderCity = $order->city){

                $customerCityName = $orderCity->name;

            }else{

                if($customer){
                    $customerCity = $customer->city;
                    if($customerCity){
                        $customerCityName = $customerCity->name;
                    }
                }
            }

            $cityName = $customerCityName?:$order->city_name;

            $productsPrices = [];

            foreach ($orderProducts as $product){
                $pricePerUnit = $product->pivot->price;
                if(!$pricePerUnit){
                    $pricePerUnit = $product->pivot->quantity > 1?($product->pivot->amount/$product->pivot->quantity):0;
                }

                $productsPrices[$product->id] =[

                    'price' => $pricePerUnit,
                    'quantity' => $product->pivot->quantity
                ];
            }

            //$priceDetails = Checkout::sumCurrentProductsPriceWithProductsObject($order->products, $productsPrices, $order->city_id, null, $order->promo_code_value);
            $orderVat = $order->vat ?:14;
            $orderDetails = [

                    'OrderNumber' => (string)$order->order_number,
                    'OrderCreationDate' => (string) date('Y-m-d', strtotime($order->created_at)),
                    'OrderCreationTime' => (string) date('h:i A',strtotime($order->created_at)),
                    'DeliveryDate' => (string) date('Y-m-d', strtotime($order->delivery_date)),
                    'DeliveryTime' => (string) $order->delivery_start_time,
                    'CustomerName' => $customer?(string) str_replace('$$', ' ',str_slug($customer->name, '$$')) :null,
                    'CustomerPhone' =>  $orderAddress?(string) $orderAddress->mobile :$customer->mobile,
                    'City' => $cityName?(string) $cityName : $customerCityName,
                    'PhoneNum' => $orderAddress?(string) $orderAddress->mobile :null,
                    'PaymentMethod'=>$order->PaymentMethod ,
                    'Status' =>show_order_status($order->status),
             ];
            
               $data['rows'][] =$orderDetails ;
         }

         $data['headings'] = [

            'رقم الطلب',
            'تاريخ الإنشاء',
            'وقت الإنشاء',
            'تاريخ التوصيل',
            'وقت التوصيل',
            'اسم العميل',
            'جوال العميل',
            'المدينة',
            'الجوال',
            'وسيلة الدفع',
            'الحالة',
        
        ];
    
        return \Excel::download(new CommonExport($data), 'orders-list.xlsx');
    
    }

    public function exportToExcelDownloadsTemplate(Request $request)
    {
        $orders = $this->searchResult($request, Order::query())->orderBy('id', 'desc')->take($request->get('export_result_count', 100))->get();
        $data['rows'] = [];
        foreach ($orders as $order){
            $orderProducts = $order->products;
            $orderAddress = $order->address?:new OrderShippingAddress;
            $customer = $order->user;
            $customerCityName = null;
            $bank_code = null;
            $isOnlinePayment = 0;
            $orderPayment = $order->payment;
            if($orderPayment){
                if(!empty($orderPayment->params)){
                    $bank_code_data= $orderPayment->params;
                    $bank_code=isset($bank_code_data->bank_transfer_no)?$bank_code_data->bank_transfer_no:'';
                }
                if($paymentMethod = $orderPayment->paymentMethod){
                    $isOnlinePayment = $paymentMethod->gateway == 'cash'?0:1;
                }

            }
            if($orderCity = $order->city){
                $customerCityName = $orderCity->name;
            }else{
                if($customer){
                    $customerCity = $customer->city;
                    if($customerCity){
                        $customerCityName = $customerCity->name;
                    }
                }
            }

            $cityName = $customerCityName?:$order->city_name;

            $productsPrices = [];
            foreach ($orderProducts as $product){
                $pricePerUnit = $product->pivot->price;
                if(!$pricePerUnit){
                    $pricePerUnit = $product->pivot->quantity > 1?($product->pivot->amount/$product->pivot->quantity):0;
                }

                $productsPrices[$product->id] =[

                    'price' => $pricePerUnit,
                    'quantity' => $product->pivot->quantity
                ];
            }

            $priceDetails = Checkout::sumCurrentProductsPriceWithProductsObject($order->products, $productsPrices, $order->city_id, null, $order->promo_code_value);
            $orderVat = $order->vat ?:14;
            $orderDetails = [
                'OrderNumber' => (string)$order->order_number,
                'DeliveryDate' => (string) date('Y-m-d', $order->delivery_date->timestamp),
                'DeliveryTime' => (string) $order->delivery_start_time,
                'CustomerName' => $customer?(string) str_replace('$$', ' ',str_slug($customer->name, '$$')) :null,
                'Neighbourhood' => $orderAddress? str_replace('$$', ' ',str_slug($orderAddress->address, '$$')) :null,
                'PhoneNum' => $orderAddress?(string) $orderAddress->mobile :null,
                'driver' => $order->driver?$order->driver->name:'',
                'Status' => $order->status
            ];
            
            $index = 1;
            foreach ($priceDetails['productsDetails'] as $productDetails){
                $productDetails = (object) $productDetails;
                $vatAmount = $productDetails->salePrice + ($orderVat*$productDetails->salePrice);
                $productsArray = [
                    'Quantity' => (float) $productDetails->quantity,
                    'SKU' =>  (string) $productDetails->sku,
                    'UnitPriceWithoutVAT' => (float) number_format((float)$productDetails->price/(int) $productDetails->quantity, 2, '.', ''),
                    'VAT' => (float) number_format((float)$vatAmount, 2, '.', ''),
                    'Total' => (float) number_format((float)($productDetails->salePrice+$vatAmount), 2, '.', ''),
                    'TaxPercentage' => (float) number_format((float)$orderVat, 2, '.', ''),
                ];
                $orderDetails['SNo'] = $index;
                $data['rows'][] = array_merge($orderDetails, $productsArray);
                $index++;

            }
        }

        $data['headings'] = [
            
            'رقم الطلب',
            'تاريخ التوصيل',
            'وقت التوصيل',
            'اسم العميل',
            'المدينة',
            'العنوان',
            'الجوال',
            'المندوب',
            'الحالة',
            'الكمية',
            'SKU',
            'سعر الوحدة',
            'قيمة الضريبة',
            'الإجمالي للمنتج شامل الضريبة',
            'نسبة الضريبة',

        ];

           return \Excel::download(new CommonExport($data), 'orders-list.xlsx');
    }

    public function test()
    {
          
            $orders = array(

                'payment'=>array(

                  "amount"=> "300",
                  "currency"=> "SAR",
                  "description"=> "test",

                'buyer'=>array(

                    "phone"=> "500000001",
                    "email"=> "card.success@tabby.ai",
                    "name"=> "ahmed",
                    "dob"=> "2022-08-24"

                ),

                'shipping_address'=>array(

                     "city"=> "riyah",
                     "address"=> "saudi arabia",
                     "zip"=> "8765"
                 ),
                
                'order'=>array(

                     "tax_amount"=> "0.00",
                     "shipping_amount"=> "0.00",
                     "discount_amount"=> "0.00",
                     "updated_at"=> "2019-08-24T14:15:22Z",
                     "reference_id"=> "87554"

                  ),

                        "lang"=> "ar",
                        "merchant_code"=> "Lamaasau",
                        "merchant_urls"=>array(
                        "success"=> "http://lam3h.badee.com.sa/",
                        "cancel"=> "http://lam3h.badee.com.sa/",
                        "failure"=> "http://lam3h.badee.com.sa/"

                    )
                 ),
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

             dd($order_results['configuration']['available_products']['installments'][0]['web_url']);

        }




}
