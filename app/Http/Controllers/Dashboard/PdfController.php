<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Order;
use App\Models\PromoCode;
use App\User;
use App\Models\City;
use App\Models\OfferCity;
use App\Models\OfferProduct;
use App\Models\Payment;
use App\Models\UserBalance;
use App\Models\Offer;
use App\Models\Compansations;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use ZanySoft\LaravelPDF\PDF;

class PdfController extends Controller
{

     public function export_cancelled_pdf()
     {
        
        $data['orders'] = Order::where('status','=','5')->orderBy('created_at','desc')->get()->take(25);
        $data['cities'] = City::all();
        $data['count'] = Order::where('status','=','5')->count();

         return view('dashboard.pdf.cancelled_orders_pdf',$data);

      }

       public function cashback_reports_pdf()
       {
          
           $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->orderBy('created_at','desc')->get();
           $data['count'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->sum('value');
          
           return view('dashboard.pdf.cash_reports_pdf',$data);
       
       }

       public function export_returned_pdf()
       {

           $data['orders'] = Order::where('status','=','11')->orderBy('created_at','desc')->get()->take(25);
           $data['cities'] = City::all();
           $data['count'] = Order::where('status','=','11')->count();
  
            return view('dashboard.pdf.returned_orders_pdf',$data);
      
        }

        public function carts_reports_pdf()
        {

            $data['carts'] = Cart::whereNotNull('user_id')->select('id','user_id','updated_at')->orderBy('created_at','desc')->paginate(20);
            $data['count'] = Cart::whereNotNull('user_id')->select('id','user_id','updated_at')->orderBy('created_at','desc')->count();

            return view('dashboard.pdf.carts_pdf',$data);
        }

        public function cartorders_pdf()
        {
        
            $data['orders'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->orderBy('created_at','desc')->paginate(15);
            $data['count'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->orderBy('created_at','desc')->count();

            return view('dashboard.pdf.cartorders_reports_pdf', $data);
        
        }

        public function newcustomers_reports_pdf()
        {
        
            $data['getorders'] = Order::pluck('user_id');
            $data['customers'] = User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->orderBy('id', 'desc')->paginate(50);
            $data['count'] = User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->orderBy('id', 'desc')->count();

            return view('dashboard.pdf.newcustomers_pdf', $data);
        }
    

        public function export_compansations_pdf()
        {
            $data['compansations'] = Compansations::get();
            $data['count'] = Compansations::count();

            return view('dashboard.pdf.compansation_pdf',$data);
        }
  
        public function export_promocodes_pdf()
        { 
            
            $data['promocodes'] = PromoCode::get();

            return view('dashboard.pdf.promocode_report_pdf',$data);
        }

        public function export_offers_pdf()
        {
        
            $data['offers'] = Offer::all();
                 
            return view('dashboard.pdf.offer_report_pdf',$data);       
        }
             
        public function printsales(Request $request)
        {

            $data['breadcrumb'] = [

                ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ];

            $data['page_title'] = trans('admin.reports');
            $data['sales'] = Order::where('status','=','8')->orderBy('created_at', 'desc')->sum('total');
            $data['delivered'] =  Order::where('status','=','8')->orderBy('created_at', 'desc')->count();
          
           return view('dashboard.pdf.sales_reports_pdf',$data);

        }

         protected function FilterResults($request){
        
               if($request){
                
                    $array = explode('>>', $request);
                    $data['sales'] =Order::where('status','=','8')->where('created_at', '>=', $array[0])
                    ->where('created_at', '<=', $array[1])->sum('total');
                    $data['delivered'] =Order::where('status','=','8')->where('created_at', '>=', $array[0])
                    ->where('created_at', '<=', $array[1])->count();
                    $data['request'] =$request;
                
                     return view('dashboard.pdf.sales_reports_pdf',$data);
                 }
             }

             public function orders_pdf()
             {
                 
                $data['breadcrumb'] = [
        
                    ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
                    ['name' => trans('admin.orders_reports'), 'url' => route('order_reports')],
                
                ];
        
                $data['page_title'] = trans('admin.reports');
                $data['current'] = Order::where('status','=','3')->count();
                $data['current_sales'] = Order::where('status','=','3')->sum('total');
            
                $data['returned'] = Order::where('status','=','11')->count();
                $data['returned_sales'] = Order::where('status','=','11')->sum('total');
        
                $data['delivered'] = Order::where('status','=','8')->count();
                $data['delivered_sales'] = Order::where('status','=','8')->sum('total');
        
                $data['new'] = Order::where('status','=','0')->count();
                $data['new_sales'] = Order::where('status','=','0')->sum('total');
        
                $data['stoped'] = Order::where('status','=','9')->count();
                $data['stoped_sales'] = Order::where('status','=','9')->sum('total');
                
                $data['rescheduled'] = Order::where('status','=','7')->count();
                $data['rescheduled_sales'] = Order::where('status','=','7')->sum('total');
                        
                 return view('dashboard.pdf.orders_status_pdf', $data);
            
             }
    
              public function printview($id)
              {
               
                   $data['breadcrumb'] = [
  
                       ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
                
                   ];
  
                   if($id == 1){
        
                    $data['page_title'] = trans('admin.reports');
                    $data['orders'] = Order::count();
                    $data['sales']= (int)Order::where('status',8)->OrderBy('created_at','desc')->sum('total');
                    $data['customers'] = User::where('user_type','customer')->count();
                
                    $data['getorders'] = Order::pluck('user_id');
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->count();
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->sum('value');

                    $data['carts'] = Cart::count();
                    $data['cancelled']= Order::where('status',5)->count();
                    $data['returned']= Order::where('status',11)->count();
                    $data['delivered']= Order::where('status',8)->count();
                    $data['compansations']= Compansations::count();
                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->orderBy('created_at','desc')->count();
            
                }

                if($id == 2){

                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
            
                    $data['ordercash'] = Order::whereIN('id',$cash)->sum('total');
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                    $data['orderbank'] = Order::whereIN('id',$bank)->sum('total');
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                    $data['ordervisa'] = Order::whereIN('id',$visa)->sum('total');
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->sum('total');
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->sum('total');
                
                }

                $data['id'] =$id;

                return view('dashboard.pdf.reports_printview',$data);
              
            }

            public function printview_filters($id ,$type,$request ,$city)
            {
            
                $today = Carbon::today();  
                $data['type'] = $type;
                $data['request'] =$request;
                $data['id'] =$id;
                $data['city'] =$city;
                $data['getorders'] = Order::pluck('user_id');

               if($id == 1)
               {
               
                if($type =="fromto" && $city == "null" ){

                    $array = explode('الى', $request);
        
                    $data['sales']= (int)Order::where('status',8)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->sum('total');
        
                    $data['customers'] = User::where('user_type','customer')->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->count();

                    $data['getorders'] = Order::pluck('user_id');
          
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->count();
        
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->where('created_at', '>=', $array[0])

                    ->where('created_at', '<=', $array[1])->sum('value');
        
                    $data['carts'] = Cart::where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->count();
        
                    $data['cancelled']= Order::where('status',5)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->count();
        
                    $data['returned']= Order::where('status',11)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->count();
        
                    $data['delivered']= Order::where('status',8)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->count();

                    $data['compansations'] = Compansations::where('created_at', '>=', $array[0])

                    ->where('created_at', '<=', $array[1])->count();

                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->where('created_at', '>=', $array[0])->where('created_at', '<=', $array[1])->count();

                    return view('dashboard.pdf.printview_filters',$data);

                
                 }

                     if($type =="fromto" && $city != "null" ){

                        $array = explode('الى', $request);

                        $data['sales']= (int)Order::where('status',8)->where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])->whereHas('address', function ($q) use ($city) {
                        
                            $q->where('city_id',$city);
                        
                            })->sum('total');
                        
                        $data['customers'] = User::where('user_type','customer')->where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])->where('city_id', $city)->count();
                        
                        $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])
                        
                        ->where('city_id', $city)->count();
                        
                        $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])->sum('value');
                        
                        $data['carts'] = Cart::where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])->count();

                        $data['cancelled']= Order::where('status',5)->where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])
                        
                        ->whereHas('address', function ($q) use ($city) {
                            $q->where('city_id',$city);
                        })->count();

                        
                        $data['returned']= Order::where('status',11)->where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])
                        
                        ->whereHas('address', function ($q) use ($city) {

                            $q->where('city_id', $city);

                            })->count();

                        $data['delivered']= Order::where('status',8)->where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])
                            
                            ->whereHas('address', function ($q) use ($city) {
                                $q->where('city_id', $city);
                            })->count();

                    
                        $data['compansations']= Compansations::where('created_at', '>=', $array[0])

                        ->where('created_at', '<=', $array[1])->count();

                        $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereYear('created_at', date('Y'))->orderBy('created_at','desc')
                        
                        ->count();

                        return view('dashboard.pdf.printview_filters',$data);
 
                   }

                  if($type =="today" && $city == "null"){

                    $data['sales']= (int)Order::where('status',8)->whereDate('created_at',Carbon::today())->sum('total');
                    $data['customers'] = User::where('user_type','customer')->whereDate('created_at',Carbon::today())->count();
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereDate('created_at',Carbon::today())->count();
                    $data['cashback']  = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereDate('created_at', Carbon::today())->sum('value');

                    $data['carts'] = Cart::where('created_at',Carbon::today())->count();
                    $data['cancelled']= Order::where('status',5)->whereDate('created_at', Carbon::today())->count();
                    $data['returned']= Order::where('status',11)->whereDate('created_at', Carbon::today())->count();
                    $data['delivered']= Order::where('status',8)->whereDate('created_at', Carbon::today())->count();
                    $data['compansations']= Compansations::whereDate('created_at', Carbon::today())->count();
                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->where('created_at',Carbon::today())->orderBy('created_at','desc')->count();
                   
                    return view('dashboard.pdf.printview_filters',$data);
                
                  }

                if($type =="today" && $city != "null"){

                    $data['sales']= (int)Order::where('status',8)->whereDate('created_at',Carbon::today())->whereHas('address', function ($q) use ($city) {
              
                        $q->where('city_id', $city);
                      
                        })->sum('total');

                    $data['customers'] = User::where('user_type','customer')->whereDate('created_at',Carbon::today())->where('city_id', $city)->count();
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereDate('created_at',Carbon::today())
                    
                    ->where('city_id', $city)->count();
                    
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereYear('created_at', date('Y'))->sum('value');
                    
                    $data['carts'] = Cart::whereDate('created_at',Carbon::today())->count();
                    $data['cancelled']= Order::where('status',5)->whereDate('created_at',Carbon::today())
                    
                        ->whereHas('address', function ($q) use ($city) {
                            $q->where('city_id', $city);
                        })->count();
            
                    
                      $data['returned']= Order::where('status',11)->whereDate('created_at',Carbon::today())
                    
                        ->whereHas('address', function ($q) use ($city) {
        
                           $q->where('city_id', $city);
        
                          })->count();
        
                       $data['delivered']= Order::where('status',8)->whereDate('created_at',Carbon::today())
                        
                         ->whereHas('address', function ($q) use ($city) {
                             $q->where('city_id', $city);
                        })->count();
        
                       $data['compansations']= Compansations::whereDate('created_at',Carbon::today())->count();
                       $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereDate('created_at',Carbon::today())->orderBy('created_at','desc')->count();
                
                       return view('dashboard.pdf.printview_filters',$data);

                    }

                if($type =="yesterday" && $city == "null"){
        
                    $data['sales']= (int)Order::where('status',8)->whereDate('created_at' ,Carbon::yesterday())->sum('total');
        
                    $data['customers'] = User::where('user_type','customer')->whereDate('created_at', Carbon::yesterday())->count();
            
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereDate('created_at' ,Carbon::yesterday())->count();
    
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereDate('created_at', Carbon::yesterday())->sum('value');

                    $data['carts'] = Cart::where('created_at',Carbon::today())->count();
        
                    $data['cancelled']= Order::where('status',5)->whereDate('created_at', Carbon::yesterday())->count();
        
                    $data['returned']= Order::where('status',11)->whereDate('created_at', Carbon::yesterday())->count();
        
                    $data['delivered']= Order::where('status',8)->whereDate('created_at', Carbon::yesterday())->count();

                    $data['compansations']= Compansations::whereDate('created_at', Carbon::yesterday())->count();
                   
                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereDate('created_at', Carbon::yesterday())->orderBy('created_at','desc')->count();
        
                    return view('dashboard.pdf.printview_filters',$data);

                }


                if($type =="yesterday" && $city != "null"){
        
                    $data['sales']= (int)Order::where('status',8)->whereDate('created_at', Carbon::yesterday())->whereHas('address', function ($q) use ($city) {
              
                        $q->where('city_id', $city);
                      
                        })->sum('total');

                    $data['customers'] = User::where('user_type','customer')->whereDate('created_at', Carbon::yesterday())->where('city_id', $city)->count();
                
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereDate('created_at', Carbon::yesterday())
                    
                    ->where('city_id', $city)->count();
                    
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereDate('created_at', Carbon::yesterday())->sum('value');
                    
                    $data['carts'] = Cart::whereDate('created_at', Carbon::yesterday())->count();
        
                    $data['cancelled']= Order::where('status',5)->whereDate('created_at', Carbon::yesterday())
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->count();
        
                    
                    $data['returned']= Order::where('status',11)->whereDate('created_at', Carbon::yesterday())
                    
                       ->whereHas('address', function ($q) use ($city) {
        
                           $q->where('city_id', $city);
        
                          })->count();
        
                    $data['delivered']= Order::where('status',8)->whereDate('created_at', Carbon::yesterday())
                        
                        ->whereHas('address', function ($q) use ($city) {
                            $q->where('city_id', $city);
                      })->count();
    
                
                    $data['compansations']= Compansations::whereDate('created_at', Carbon::yesterday())->count();
                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereDate('created_at', Carbon::yesterday())->orderBy('created_at','desc')
                    ->count();

                    return view('dashboard.pdf.printview_filters',$data);

                  }
                
                  if($type =="week" && $city == "null"){
        
                    $data['sales']= (int)Order::where('status',8)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
        
                    $data['customers'] = User::where('user_type','customer')->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                    
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                   
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('value');

                    $data['carts'] = Cart::whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        
                    $data['cancelled']= Order::where('status',5)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        
                    $data['returned']= Order::where('status',11)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        
                    $data['delivered']= Order::where('status',8)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                   
                    $data['compansations']= Compansations::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('created_at','desc')->count();
            
                    return view('dashboard.pdf.printview_filters',$data);


                  }

                  if($type =="week" && $city != "null"){
        
                    $data['sales']= (int)Order::where('status',8)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereHas('address', function ($q) use ($city) {
              
                        $q->where('city_id', $city);
                        
                        })->sum('total');
            
                    $data['customers'] = User::where('user_type','customer')->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('city_id', $city)->count();
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    
                    ->where('city_id', $city)->count();
                    
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('value');
                    $data['carts'] = Cart::whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                    $data['cancelled']= Order::where('status',5)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->count();
        
                    
                    $data['returned']= Order::where('status',11)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    
                       ->whereHas('address', function ($q) use ($city) {
        
                           $q->where('city_id', $city);
        
                          })->count();
        
                     $data['delivered']= Order::where('status',8)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        
                        ->whereHas('address', function ($q) use ($city) {
                            $q->where('city_id', $city);
                    })->count();
        
                
                    $data['compansations']= Compansations::whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('created_at','desc')
                    ->count();

                    return view('dashboard.pdf.printview_filters',$data);

                  }

                if($type =="month" && $city == "null"){

                    $data['sales']= (int)Order::where('status',8)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('total');
                   
                    $data['customers'] = User::where('user_type','customer')->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
                    

                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
            
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('value');
        
                    $data['carts'] = Cart::whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
        
                    $data['cancelled']= Order::where('status',5)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
        
                    $data['returned']= Order::where('status',11)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
        
                    $data['delivered']= Order::where('status',8)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
        
                    $data['compansations']= Compansations::whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
        
                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereMonth('created_at',  date('m'))->orderBy('created_at','desc')->count();
                    
                    return view('dashboard.pdf.printview_filters',$data);

                }

                 if($type =="month"  && $city != "null"){

                       $data['sales']= (int)Order::where('status',8)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->whereHas('address', function ($q) use ($city) {
              
                         $q->where('city_id', $city);
                      
                         })->sum('total');
                
                       $data['customers'] = User::where('user_type','customer')->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->where('city_id', $city)->count();
                   
                       $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                    
                        ->where('city_id', $city)->count();
                    
                       $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('value');
                    
                       $data['carts'] = Cart::whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
        
                       $data['cancelled']= Order::where('status',5)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                    
                        ->whereHas('address', function ($q) use ($city) {
                             $q->where('city_id', $city);
                         })->count();
        
                        $data['returned']= Order::where('status',11)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                    
                          ->whereHas('address', function ($q) use ($city) {
        
                           $q->where('city_id', $city);
        
                          })->count();
         
                         $data['delivered']= Order::where('status',8)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                        
                          ->whereHas('address', function ($q) use ($city) {
                            $q->where('city_id', $city);
                         })->count();

                         $data['compansations']= Compansations::whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
                         $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereMonth('created_at',  date('m'))->orderBy('created_at','desc')->count();
                        
                            return view('dashboard.pdf.printview_filters',$data);
                  
                        }

                      if($type =="year" && $city == "null"){
            
                        $data['sales']= (int)Order::where('status',8)->whereYear('created_at',date('Y'))->sum('total');
                        $data['customers'] = User::where('user_type','customer')->whereYear('created_at',date('Y'))->count();
                
                        $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereYear('created_at',date('Y'))->count();
                        $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereYear('created_at', date('Y'))->sum('value');

                        $data['carts'] = Cart::whereYear('created_at',date('Y'))->count();
                        $data['cancelled']= Order::where('status',5)->whereYear('created_at', date('Y'))->count();
                        $data['returned']= Order::where('status',11)->whereYear('created_at', date('Y'))->count();
                        $data['delivered']= Order::where('status',8)->whereYear('created_at', date('Y'))->count();
                        $data['compansations']= Compansations::whereYear('created_at', date('Y'))->count();

                        $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereYear('created_at', date('Y'))->orderBy('created_at','desc')->count();
                       
                        return view('dashboard.pdf.printview_filters',$data);
                  
                  }

                  if($type =="year" && $city != "null"){
                
                    $data['sales']= (int)Order::where('status',8)->whereYear('created_at',date('Y'))->whereHas('address', function ($q) use ($city) {
                    
                        $q->where('city_id', $city);
                    
                        })->sum('total');
                    
                    $data['customers'] = User::where('user_type','customer')->whereYear('created_at',date('Y'))->where('city_id', $city)->count();
                
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereYear('created_at',date('Y'))
                    
                    ->where('city_id', $city)->count();
                    
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereYear('created_at', date('Y'))->sum('value');
                    
                    $data['carts'] = Cart::whereYear('created_at',date('Y'))->count();
                    $data['cancelled']= Order::where('status',5)->whereYear('created_at', date('Y'))
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->count();

                    $data['returned']= Order::where('status',11)->whereYear('created_at', date('Y'))
                    
                    ->whereHas('address', function ($q) use ($city) {

                        $q->where('city_id', $city);

                        })->count();

                    $data['delivered']= Order::where('status',8)->whereYear('created_at', date('Y'))
                        
                        ->whereHas('address', function ($q) use ($city) {
                            $q->where('city_id', $city);
                    })->count();

                
                    $data['compansations']= Compansations::whereYear('created_at', date('Y'))->count();
                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereYear('created_at', date('Y'))->orderBy('created_at','desc')->count();
                   
                    return view('dashboard.pdf.printview_filters',$data);

                  }

                if($city){

                    $data['sales']= (int)Order::where('status',8)->whereHas('address', function ($q) use ($city) {
                         $q->where('city_id', $city);
                        })->sum('total');
        
                    $data['customers'] = User::where('user_type','customer')->count();
                    $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->where('city_id', $city)->count();
                    $data['carts'] = Cart::count();
        
                    $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->sum('value');
                    $data['cancelled']= Order::where('status',5)->whereHas('address', function ($q) use ($city) {
                           $q->where('city_id',$city);
                       })->count();
        
                    $data['returned']= Order::where('status',11)->whereHas('address', function ($q) use ($city) {
                          $q->where('city_id', $city);
                         })->count();
        
                    $data['delivered']= Order::where('status',8)->whereHas('address', function ($q) use ($city) {
                            $q->where('city_id', $city);
                       })->count();

                    $data['compansations']= Compansations::count();
                    $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->orderBy('created_at','desc')->count();
            
                     return view('dashboard.pdf.printview_filters',$data);
                }
            
            }

            if($id == 2)
            {
                $data['type'] = $type;
                $data['request'] =$request;
                $data['id'] =$id;
                $data['city']=$city;

                if($type =="fromto" && $city == "null"){

                    $array = explode('الى', $request);
        
                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
                    $data['ordercash'] = Order::whereIN('id',$cash)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->sum('total');
             
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                     
                    $data['orderbank'] = Order::whereIN('id',$bank)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->sum('total');
             
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->sum('total');
             
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])->sum('total');

                    return view('dashboard.pdf.printview_filters',$data);

                }

                if($type =="fromto" && $city != "null"){

                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
                    $data['ordercash'] = Order::whereIN('id',$cash)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])
                    
                    ->whereHas('address', function ($q) use ($request) {
                        $q->where('city_id', $city);
                     }) ->sum('total');
                    
                
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                     
                    $data['orderbank'] = Order::whereIN('id',$bank)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])
                    
                    ->whereHas('address', function ($q) use ($city) {
        
                        $q->where('city_id', $city);
                     })->sum('total');
        
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])
                    
                      ->whereHas('address', function ($q) use ($city) {
                           $q->where('city_id', $city);
                      })->sum('total');
                    
                
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->where('created_at', '>=', $array[0])
        
                    ->where('created_at', '<=', $array[1])
                    
                    ->whereHas('address', function ($q) use ($city){
        
                        $q->where('city_id', $city);
        
                    })->sum('total');

                       return view('dashboard.pdf.printview_filters',$data);

                    }


                  if($type =="today" && $city == "null"){
        
                       $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
                    
                        $data['ordercash'] = Order::whereIN('id',$cash)->whereDate('created_at', Carbon::today())->sum('total');
                
                        $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                        
                        $data['orderbank'] = Order::whereIN('id',$bank)->whereDate('created_at', Carbon::today())->sum('total');
                
                        $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                        
                        $data['ordervisa'] = Order::whereIN('id',$visa)->whereDate('created_at', Carbon::today())->sum('total');
                
                        $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                        
                        $data['ordersadad'] = Order::whereIN('id',$sadad)->whereDate('created_at', Carbon::today())->sum('total');
                
                        $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                        
                        $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereDate('created_at', Carbon::today())->sum('total');
                       
                        return view('dashboard.pdf.printview_filters',$data);

                    }

                  if($type =="today" && $city != "null"){
        
                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereDate('created_at',Carbon::today())
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                     }) ->sum('total');
                    
                
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                     
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereDate('created_at',Carbon::today())
                    
                    ->whereHas('address', function ($q) use ($city) {
        
                        $q->where('city_id', $city);
                            })->sum('total');
        
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereDate('created_at',Carbon::today())
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                     })->sum('total');
                    
                
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereDate('created_at',Carbon::today())
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereDate('created_at',Carbon::today())
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
                
                    return view('dashboard.pdf.printview_filters',$data);
                  
                }

               
               if($type =="yesterday" && $city == "null"){
        
                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereDate('created_at', Carbon::yesterday())->sum('total');
             
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                     
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereDate('created_at', Carbon::yesterday())->sum('total');
             
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereDate('created_at', Carbon::yesterday())->sum('total');
             
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereDate('created_at', Carbon::yesterday())->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereDate('created_at', Carbon::yesterday())->sum('total');
                 
                    return view('dashboard.pdf.printview_filters',$data);

                }

        
                if($type =="yesterday" && $city != "null"){
          
                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereYear('created_at', date('Y'))
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                     }) ->sum('total');
                    
                
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                     
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereDate('created_at', Carbon::yesterday())
                    
                    ->whereHas('address', function ($q) use ($city){
        
                        $q->where('city_id', $city);
                     })->sum('total');
        
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereDate('created_at', Carbon::yesterday())
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                   })->sum('total');
                    
                
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereDate('created_at', Carbon::yesterday())
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereDate('created_at', Carbon::yesterday())
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
                    
                    return view('dashboard.pdf.printview_filters',$data);

                }
        
                if($type =="week" && $city == "null"){
                            
                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
                
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
            
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                    
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
            
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                    
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
            
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                    
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
            
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                    
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
            
                    return view('dashboard.pdf.printview_filters',$data);

                  }

                   if($type =="week" && $city != "null"){
                  
                        $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
                
                        $data['ordercash'] = Order::whereIN('id',$cash)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
                
                        $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                        
                        $data['orderbank'] = Order::whereIN('id',$bank)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
                
                        $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                        
                        $data['ordervisa'] = Order::whereIN('id',$visa)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
                
                        $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                        
                        $data['ordersadad'] = Order::whereIN('id',$sadad)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
                
                        $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                        
                        $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
                
                        return view('dashboard.pdf.printview_filters',$data);

                   }


                if($type =="month" && $city == "null"){
        
                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('total');
             
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                     
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('total');
             
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('total');
             
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('total');
                  
                      return view('dashboard.pdf.printview_filters',$data);

                }


                if($type =="month" && $city != "null"){
        
                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                     }) ->sum('total');
                    
                
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                     
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                    
                    ->whereHas('address', function ($q) use ($city) {
        
                        $q->where('city_id', $city);
                     })->sum('total');
        
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                   })->sum('total');
                    
                
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
                

                    return view('dashboard.pdf.printview_filters',$data);
 
                }

                if($type =="year" && $city == "null"){
        
                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereYear('created_at', date('Y'))->sum('total');
             
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                     
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereYear('created_at', date('Y'))->sum('total');
             
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereYear('created_at', date('Y'))->sum('total');
             
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereYear('created_at', date('Y'))->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereYear('created_at', date('Y'))->sum('total');
                   
                    return view('dashboard.pdf.printview_filters',$data);

                 }

                if($type =="year" && $city != "null"){

                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereYear('created_at', date('Y'))
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                     }) ->sum('total');
                    
                
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereYear('created_at', date('Y'))
                    
                    ->whereHas('address', function ($q) use ($city) {
        
                        $q->where('city_id', $city);
                     })->sum('total');
        
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereYear('created_at', date('Y'))
                    
                      ->whereHas('address', function ($q) use ($city) {
                         $q->where('city_id', $city);
                     })->sum('total');
                    
                
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereYear('created_at', date('Y'))
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereYear('created_at', date('Y'))
                    
                    ->whereHas('address', function ($q) use ($city) {
                        $q->where('city_id', $city);
                    })->sum('total');
                    
                

                    return view('dashboard.pdf.printview_filters',$data);

                }

                if($city){

                    $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
                    $data['ordercash'] = Order::whereIN('id',$cash)->whereHas('address', function ($q) use ($request) {
                           $q->where('city_id',$city);
                        })->sum('total');
             
                    $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
                    $data['orderbank'] = Order::whereIN('id',$bank)->whereHas('address', function ($q) use ($request) {
                            $q->where('city_id',$city);
                         })->sum('total');
               
                    $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
                     
                    $data['ordervisa'] = Order::whereIN('id',$visa)->whereHas('address', function ($q) use ($request) {
                             $q->where('city_id', $city);
                        })->sum('total');
             
                    $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
                     
                    $data['ordersadad'] = Order::whereIN('id',$sadad)->whereHas('address', function ($q) use ($request) {
                        $q->where('city_id', $city);
                    })->sum('total');
             
                    $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
                     
                    $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereHas('address', function ($q) use ($request) {
                         $q->where('city_id', $city);
                     })->sum('total');

                     return view('dashboard.pdf.printview_filters',$data);

            
                }
            }
            }
        }
