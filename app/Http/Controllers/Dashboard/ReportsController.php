<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Order;
use App\Models\Compansations;
use App\Models\PromoCode;
use App\Models\PromocodeCity;
use App\Models\PromoCodeuser;
use App\Models\Campaign;
use App\User;
use App\Models\City;
use App\Models\UserBalance;
use App\Models\OfferCity;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\OfferProduct;
use App\Models\Payment;
use App\Models\Offer;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;


class ReportsController extends Controller
{
    
    public function promocode_reports()
    {
        
         $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.promocodes_report'), 'url' => url('admin/reports/promocode_reports')],
        ];

        $data['page_title'] = trans('admin.reports');
       // $promocodes= Campaign::get()->pluck('promocode');
        //$data['promocodes'] = PromoCode::whereIN('id' ,$promocodes)->get();
        $data['promocodes'] = PromoCode::get();
        $data['cities'] = City::get();

        return view('dashboard.reports.promocodes_reports', $data);
    
    }

    public function promocode_report_show(Request $request)
    {
        
        if($usedAt= $request->used_at){

            $array = explode('>>', $usedAt);
            $data['promocodes'] = PromoCodeuser::where('used_at', '>=', $array[0])
            ->where('used_at', '<=', $array[1])->get();
        }

        if($request->city)
        {
         
            $city = $request->city;

            $data['promocodes'] = PromocodeCity::where('city_id',$city)->get();

        }

        if($request->used_at && $request->city){

            $city = $request->city;

            $data['promocodes'] = PromocodeCity::where('city_id',$city)->get();
        }

          return view('dashboard.reports.promocode_report_results', $data);
    }

    public function promocode_sales_results($id)
    {
        
        $data['result'] = PromoCodeUser::where('promocode_id',$id)->where('referenceable_type','orders')->count();
        $data['promocode_orders'] = PromoCodeUser::where('promocode_id',$id)->where('referenceable_type','orders')->pluck('referenceable_id');
        $data['promocode_results']  = Order::whereIN('id',$data['promocode_orders'])->sum('total');
        $data['promocodes'] = PromoCodeUser::where('promocode_id',$id)->pluck('user_id');
        $data['users'] = User::whereIn('id',$data['promocodes'])->get();
        $data['cities'] = PromoCodeCity::where('promocode_id',$id)->get();
        $data['id']=$id ;

        return view('dashboard.reports.promocode_sales_results', $data);
    }

    public function cashback_reports(Request $request)
    { 
         
         $data['breadcrumb'] = [
            
             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.cashback_reports'), 'url' => url('admin/reports/cashback')],
        
          ];

          $data['page_title'] = trans('admin.reports');
          $data['cashback'] = $this->searchBalancesResult($request, UserBalance::query())->where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->orderBy('created_at','desc')->paginate(15)->withQueryString();
          $data['cities'] = City::get();
          $data['count'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->sum('value');

         return view('dashboard.reports.cashback_report', $data);
    }

    protected function searchBalancesResult($request, $customers){

        if($searchWord = $request->get('search_word')){
            $customers = $customers->where(function ($q) use ($searchWord) {
                  $q->whereHas('user', function ($q) use ($searchWord) {
                      $q->where('name', $searchWord)
                        ->orWhere('mobile', $searchWord);
                  });
            })
                ->orWhere('id', substr($searchWord, 2));
        }

        if(!is_null($request->status)){
            $customers = $customers->where('status', $request->status);
        }

        if($createdAt= $request->created_at){
            $array = explode(' >> ', $createdAt);
            $customers = $customers->where('created_at', '>=', $array[0])
                ->where('created_at', '<=', $array[1]);
        }

        if ($request->mobile || $request->name){
            $customers = $customers->whereHas('user', function ($q) use ($request) {
                $q->where('mobile', $request->mobile)
                  ->orWhere('name', $request->name);
            });
        }

        if ($request->city){
              $customers = $customers->whereHas('user', function ($q) use ($request) {
                 $q->where('city_id', $request->city);
                
            });
        }

        return $customers;
    }

    public function newcustomers_reports(Request $request)
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.newcustomers'), 'url' => url('admin/reports/newcustomers')],
        
         ];

        $data['page_title'] = trans('admin.reports');
        $data['getorders'] = Order::pluck('user_id');
        $data['customers'] = $this->searchResult($request, User::query()->where('user_type','customer')->whereNotIN('id', $data['getorders']))->orderBy('id', 'desc')->paginate($request->get('show_result_count', 15))->withQueryString();
        $data['count'] = User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->orderBy('id', 'desc')->count();
        $data['cities'] = City::get();

        return view('dashboard.reports.newcustomers_reports', $data);
    
    }

    protected function searchResult($request, $customers){

        if($searchWord = $request->get('search_word')){
            $customers = $customers->where('name', $searchWord)->orWhere('mobile', $searchWord)->orWhere('email', $searchWord);
        }

        if(!is_null($request->status)){
            $customers = $customers->where('status', $request->status);
        }

        if($createdAt= $request->created_at){
            $array = explode(' >> ', $createdAt);
            $customers = $customers->where('created_at', '>=', $array[0])
                ->where('created_at', '<=', $array[1]);
        }


        if ($request->mobile || $request->name){
            
            $customers = $customers->where('mobile', $request->mobile)
                    ->orWhere('name', $request->name);
        }

        if ($request->city){
            $customers = $customers->whereHas('addresses', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            });
        
        }

         if(!is_null($request->cart_rate)){

            $customers = $customers->where('cart_rate', $request->cart_rate);
        }

           return $customers;
      }

     public function compansations_reports(Request $request)
     {
        
            $data['breadcrumb'] = [

                ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
                ['name' => trans('admin.compansations'), 'url' => url('dmin/reports/promocode_reports')],
            
            ];

            $data['page_title'] = trans('admin.reports');
            $data['compansations'] = $this->searchCompansationResult($request,  Compansations::query())->paginate(20);
            $data['count'] = $this->searchCompansationResult($request,  Compansations::query())->count();

             $data['cities'] = City::all();

             return view('dashboard.reports.compansations', $data);
      }

       protected function searchCompansationResult($request, $orders){
        
           if($searchWord = $request->get('search_word')){
               $orders = $orders->whereHas('order', function ($result) use ($searchWord) {
                  $result->whereHas('user', function ($q) use ($searchWord) {
                   $q->where('mobile', $searchWord )
                     ->orWhere('name', $searchWord );
    
                 });
              });
    
            }
    
              if($orderNumber = $request->order_number){
                   $orders = $orders->whereHas('order', function ($q) use ($request) {
                     $q->where('id', $request->order_number);
                  
                 });
              }
     
              if(!is_null($request->status)){
                  $orders = $orders->whereHas('order', function ($q) use ($request) {
                   $q->where('status', $request->status);
                 
                 });
             }
     
             if($createdAt= $request->created_at){
                  $array = explode(' >> ', $createdAt);
                  $orders = $orders->where('created_at', '>=', $array[0])
                  ->where('created_at', '<=', $array[1]);
             }
    
              if ($request->mobile || $request->name){
                     $orders = $orders->whereHas('order', function ($result) use ($request) {
                      $result->whereHas('user', function ($q) use ($request) {
                        $q->where('mobile', $request->mobile)
                         ->orWhere('name', $request->name);
                   });
               });
           }
    
             return $orders;
    }

    public function search_compansations(Request $request)
    {
        if($request->ajax())
         {

         $output = '';
         $query = $request->get('query');

         if($query != '')
         {
            $data = Compansations::where('order_id','LIKE','%'.$query .'%')->orwhere('type','LIKE','%'.$query .'%')->get();
          }
         else
         {

          $data = Compansations::get();
           
         }

         $total_row = $data->count();
         if($total_row > 0)
          {
          foreach($data as $row)
          {
              $output .= '
               <tr>
                <td>'.$row->id.'</td>
                <td>'.$row->type.'</td>
                <td>'.$row->action.'</td>
                <td>'.$row->order_id.'</td>
                <td>'.$row->staff_notes.'</td>
                </tr>
              ';
              }
            }

            else
            {

            $output = '
            <tr>
            <td align="center" colspan="5">No Data Found</td>
            </tr>
            ';
            }

            $data = array(
            'table_data'  => $output,
            'total_data'  => $total_row
            );
    
            echo json_encode($data);
           }
        }

     public function offers_reports_show(Request $request)
     {
        if($request->product)
        {
        
         $product = $request->product;

         $data['offers'] = OfferProduct::where('product_id',$product)->get();

        }

        if($request->city)
        {
        
         $city = $request->city;

         $data['offers'] = OfferCity::where('city_id',$city)->get();

        }

        return view('dashboard.reports.offer_report',$data);
    }


    public function orders_cancel_show(Request $request)
    {
       
        $query = Order::query()->where('status','=','5')->orderBy('created_at','desc');

        if($searchWord = $request->get('search_word')){
            $query->where(function ($q) use ($searchWord) {
                  $q->whereHas('user', function ($q) use ($searchWord) {
                      $q->where('name', $searchWord)
                        ->orWhere('mobile', $searchWord);
                  });
            })
            ->orWhere('id', substr($searchWord, 2));
        
        }
    
        if($createdAt= $request->created_at){
            $array = explode(' >> ', $createdAt);
            $query->where('created_at', '>=', $array[0])
            ->where('created_at', '<=', $array[1]);
        }

    if ($request->city) {
        
        $query->where('city_name', $request->city);
    }

    if ($request->order_id) {

        $query->where('id', $ordernumber);
    }

    if ($request->mobile || $request->name){

         $query->whereHas('user', function ($q) use ($request) {
            $q->where('mobile', $request->mobile)
              ->orWhere('name', $request->name);
        });
      }

    if ($request->payment_method){
        $query->whereHas('payment', function ($q) use ($request) {
            $q->where('payment_method_id', $request->payment_method);
        });
      }

       $data['orders'] = $query->get();
       $data['count'] = $query->get()->count();
       $data['sales'] = $query->sum('total');

        return view('dashboard.reports.cancelled_order_results',$data);
    
    }

    public function cancelled_orders()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.cancelled_orders'), 'url' => route('cities.index')],
        
         ];

        $data['page_title'] = trans('admin.reports');
        $data['orders'] = Order::where('status','=','5')->paginate(20);
        $data['count'] = Order::where('status','=','5')->count();
        $data['sales'] = Order::where('status','=','5')->sum('total');
        $data['cities'] = City::all();

        return view('dashboard.reports.cancelled_orders', $data);

    }


    public function orders_report()
    {
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.orders_status'), 'url' => url('admin/reports/orders')],
        
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
        
        $data['cities'] = City::all();

        return view('dashboard.reports.order_reports', $data);

    }


    public function orders_returned_show(Request $request)
    {
      
        $query = Order::query()->where('status','=','11')->orderBy('created_at','desc');
        if($searchWord = $request->get('search_word')){
                 $query->where(function ($q) use ($searchWord) {
                  $q->whereHas('user', function ($q) use ($searchWord) {
                      $q->where('name', $searchWord)
                        ->orWhere('mobile', $searchWord);
                  });
            })
            ->orWhere('id', substr($searchWord, 2));
    
        }
    
        if($createdAt= $request->created_at){
            $array = explode(' >> ', $createdAt);
            $query->where('created_at', '>=', $array[0])
            ->where('created_at', '<=', $array[1]);
        }

        if ($request->city) {
            
            $query->where('city_name', $request->city);
        }

        if ($request->order_id) {
            
            $query->where('id', $ordernumber);
        }

        if ($request->mobile || $request->name){

            $query->whereHas('user', function ($q) use ($request) {
                $q->where('mobile', $request->mobile)
                ->orWhere('name', $request->name);
            });
        }

        if ($request->payment_method){
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('payment_method_id', $request->payment_method);
            });
        }

       $data['orders'] = $query->get();
       $data['count'] = $query->get()->count();
       
       return view('dashboard.reports.returned_order_results',$data);
    
}

    public function  returned_orders()
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.returned_orders'), 'url' => route('cities.index')],
        ];

        $data['page_title'] = trans('admin.reports');
        $data['orders'] = Order::where('status','=','11')->paginate(20);
        $data['cities'] = City::all();
        $data['count'] = Order::where('status','=','11')->count();

        return view('dashboard.reports.returned_orders', $data);
    }


    public function  reports()
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.reports');
        $data['orders'] = Order::count();
        $data['sales']= (int)Order::where('status',8)->OrderBy('created_at','desc')->sum('total');
        $data['customers'] = User::where('user_type','customer')->count();
        $data['getorders'] = Order::pluck('user_id');
        $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->count();
        $data['cashback']= UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->sum('value');
        $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->orderBy('created_at','desc')->count();

        $data['carts'] = Cart::count();
        $data['cancelled']= Order::where('status',5)->count();
        $data['returned']= Order::where('status',11)->count();
        $data['delivered']= Order::where('status',8)->count();
        $data['compansations']= Compansations::count();

        $data['cities']= City::all();

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

        $status =['8','11','5'];
        
        $data['orders_numbers'] = [];

        foreach ($status as $key => $value) {

            $data['orders_numbers'] [] = Order::where('status',$value)->count();
        }

        return view('dashboard.reports.reports', $data);
    
     }

    public function reports_result(Request $request)
    {
        
        $data['cities']= City::all();
        $data['getorders'] = Order::pluck('user_id');
        $status =['8','11','5'];
        $data['orders_numbers'] = [];

        if($request->created_at && !$request->city){

            $array = explode('>>' ,$request->created_at);

            $data['sales']= (int)Order::where('status',8)->where('created_at', '>=', $array[0])->where('created_at', '<=', $array[1])->sum('total');

            $data['customers'] = User::where('user_type','customer')->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])->count();

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

            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])->count();

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
     
         
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->where('created_at', '>=', $array[0])

                ->where('created_at', '<=', $array[1])->count();
            
            }

            return view('dashboard.reports.reports_results',$data);
        }


        if($request->created_at && $request->city){

            $array = explode(' >> ', $request->created_at);

            $data['sales']= (int)Order::where('status',8)->where('created_at', '>=', $array[0])

             ->where('created_at', '<=', $array[1])->whereHas('address', function ($q) use ($request) {
              
                $q->where('city_id', $request->city);
              
                })->sum('total');
            
            $data['customers'] = User::where('user_type','customer')->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])->where('city_id', $request->city)->count();
            
            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])
            
            ->where('city_id', $request->city)->count();
            
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])->sum('value');
            
            $data['carts'] = Cart::where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])->count();

            $data['cancelled']= Order::where('status',5)->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->count();

            
            $data['returned']= Order::where('status',11)->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])
            
               ->whereHas('address', function ($q) use ($request) {

                   $q->where('city_id', $request->city);

                  })->count();

              $data['delivered']= Order::where('status',8)->where('created_at', '>=', $array[0])

              ->where('created_at', '<=', $array[1])
                
                  ->whereHas('address', function ($q) use ($request) {
                      $q->where('city_id', $request->city);
                })->count();

        
            $data['compansations']= Compansations::where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])->count();

            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereYear('created_at', date('Y'))->orderBy('created_at','desc')
            
            ->count();

            $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
            $data['ordercash'] = Order::whereIN('id',$cash)->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
             }) ->sum('total');
            
        
            $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
             
            $data['orderbank'] = Order::whereIN('id',$bank)->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])
            
            ->whereHas('address', function ($q) use ($request) {

                $q->where('city_id', $request->city);
             })->sum('total');

            $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
             
            $data['ordervisa'] = Order::whereIN('id',$visa)->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])
            
              ->whereHas('address', function ($q) use ($request) {
                   $q->where('city_id', $request->city);
              })->sum('total');
            
        
            $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
             
            $data['ordersadad'] = Order::whereIN('id',$sadad)->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
     
            $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
             
            $data['orderapplepay'] = Order::whereIN('id', $applepay)->where('created_at', '>=', $array[0])

            ->where('created_at', '<=', $array[1])
            
            ->whereHas('address', function ($q) use ($request){

                $q->where('city_id', $request->city);

            })->sum('total');
            
        
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->where('created_at', '>=', $array[0])

                ->where('created_at', '<=', $array[1])
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                })->count();
                
             }

               return view('dashboard.reports.reports_results',$data);

            }

        if( $request->type =="today" && !$request->city ){

            $data['sales']= (int)Order::where('status',8)->whereDate('created_at',Carbon::today())->sum('total');

            $data['customers'] = User::where('user_type','customer')->whereDate('created_at',Carbon::today())->count();

            $data['newcustomers'] = User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereDate('created_at',Carbon::today())->count();
    
            $data['cashback']  = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereDate('created_at', Carbon::today())->sum('value');

            $data['carts'] = Cart::where('created_at',Carbon::today())->count();

            $data['cancelled']= Order::where('status',5)->whereDate('created_at', Carbon::today())->count();

            $data['returned']= Order::where('status',11)->whereDate('created_at', Carbon::today())->count();

            $data['delivered']= Order::where('status',8)->whereDate('created_at', Carbon::today())->count();
           
            $data['compansations']= Compansations::whereDate('created_at', Carbon::today())->count();
            
            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereDate('created_at', Carbon::today())->orderBy('created_at','desc')->count();

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

            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereDate('created_at',Carbon::today())->count();
             }

             return view('dashboard.reports.reports_results',$data);

           }

       
        if($request->type =="today" && $request->city){
            
            $data['sales']= (int)Order::where('status',8)->whereDate('created_at',Carbon::today())->whereHas('address', function ($q) use ($request) {
              
                $q->where('city_id', $request->city);

                })->sum('total');
    
            $data['customers'] = User::where('user_type','customer')->whereDate('created_at',Carbon::today())->where('city_id', $request->city)->count();

            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereDate('created_at',Carbon::today())
            
            ->where('city_id', $request->city)->count();
            
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereYear('created_at', date('Y'))->sum('value');
            
            $data['carts'] = Cart::whereDate('created_at',Carbon::today())->count();
            $data['cancelled']= Order::where('status',5)->whereDate('created_at',Carbon::today())
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->count();

            
             $data['returned']= Order::where('status',11)->whereDate('created_at',Carbon::today())
            
               ->whereHas('address', function ($q) use ($request) {

                   $q->where('city_id', $request->city);

                  })->count();

             $data['delivered']= Order::where('status',8)->whereDate('created_at',Carbon::today())
                
                 ->whereHas('address', function ($q) use ($request) {
                     $q->where('city_id', $request->city);
                })->count();

        
            $data['compansations']= Compansations::whereDate('created_at',Carbon::today())->count();
            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereDate('created_at',Carbon::today())->orderBy('created_at','desc')->count();
            $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
            $data['ordercash'] = Order::whereIN('id',$cash)->whereDate('created_at',Carbon::today())
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
             }) ->sum('total');
            
        
            $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
             
            $data['orderbank'] = Order::whereIN('id',$bank)->whereDate('created_at',Carbon::today())
            
            ->whereHas('address', function ($q) use ($request) {

                $q->where('city_id', $request->city);
                    })->sum('total');

            $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
             
            $data['ordervisa'] = Order::whereIN('id',$visa)->whereDate('created_at',Carbon::today())
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
             })->sum('total');
            
        
            $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
             
            $data['ordersadad'] = Order::whereIN('id',$sadad)->whereDate('created_at',Carbon::today())
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
     
            $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
             
            $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereDate('created_at',Carbon::today())
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
            
        
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereDate('created_at',Carbon::today())
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                })
                
                ->count();
            }

            return view('dashboard.reports.reports_results',$data);
 
        }


        if($request->type =="yesterday" && !$request->city ){

            $data['sales']= (int)Order::where('status',8)->whereDate('created_at' ,Carbon::yesterday())->sum('total');

            $data['customers'] = User::where('user_type','customer')->whereDate('created_at', Carbon::yesterday())->count();

            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereDate('created_at' ,Carbon::yesterday())->count();
    
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereDate('created_at', Carbon::yesterday())->sum('value');

            $data['carts'] = Cart::whereDate('created_at', Carbon::yesterday())->count();

            $data['cancelled']= Order::where('status',5)->whereDate('created_at', Carbon::yesterday())->count();

            $data['returned']= Order::where('status',11)->whereDate('created_at', Carbon::yesterday())->count();

            $data['delivered']= Order::where('status',8)->whereDate('created_at', Carbon::yesterday())->count();

            $data['compansations']= Compansations::whereDate('created_at', Carbon::yesterday())->count();

            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereDate('created_at', Carbon::yesterday())->orderBy('created_at','desc')->count();

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

            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereDate('created_at', Carbon::yesterday())->count();
            }

            return view('dashboard.reports.reports_results',$data);

        }


        if($request->type =="yesterday" && $request->city){

            $data['sales']= (int)Order::where('status',8)->whereDate('created_at', Carbon::yesterday())->whereHas('address', function ($q) use ($request) {
              
                $q->where('city_id', $request->city);
              
                })->sum('total');
            
            $data['customers'] = User::where('user_type','customer')->whereDate('created_at', Carbon::yesterday())->where('city_id', $request->city)->count();
           
            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereDate('created_at', Carbon::yesterday())
            
            ->where('city_id', $request->city)->count();
            
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereDate('created_at', Carbon::yesterday())->sum('value');
            
            $data['carts'] = Cart::whereDate('created_at', Carbon::yesterday())->count();

            $data['cancelled']= Order::where('status',5)->whereDate('created_at', Carbon::yesterday())
            
            ->whereHas('address', function ($q) use ($request) {

                $q->where('city_id', $request->city);

            })->count();

            
            $data['returned']= Order::where('status',11)->whereDate('created_at', Carbon::yesterday())
            
               ->whereHas('address', function ($q) use ($request) {

                   $q->where('city_id', $request->city);

                  })->count();

            $data['delivered']= Order::where('status',8)->whereDate('created_at', Carbon::yesterday())
                
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
            })->count();

        
            $data['compansations']= Compansations::whereYear('created_at', date('Y'))->count();

            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereDate('created_at', Carbon::yesterday())->orderBy('created_at','desc')
            
            ->count();

            $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
            $data['ordercash'] = Order::whereIN('id',$cash)->whereYear('created_at', date('Y'))
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
             }) ->sum('total');
            
        
            $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
             
            $data['orderbank'] = Order::whereIN('id',$bank)->whereDate('created_at', Carbon::yesterday())
            
            ->whereHas('address', function ($q) use ($request) {

                $q->where('city_id', $request->city);
             })->sum('total');

            $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
             
            $data['ordervisa'] = Order::whereIN('id',$visa)->whereDate('created_at', Carbon::yesterday())
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
           })->sum('total');
            
        
            $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
             
            $data['ordersadad'] = Order::whereIN('id',$sadad)->whereDate('created_at', Carbon::yesterday())
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
     
            $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
             
            $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereDate('created_at', Carbon::yesterday())
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
            
        
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereDate('created_at', Carbon::yesterday())
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                })
                
                ->count();
            }

            return view('dashboard.reports.reports_results',$data);

        }


        if($request->type =="week" && !$request->city){

            $data['sales']= (int)Order::where('status',8)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
            
            $data['customers'] = User::where('user_type','customer')->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            
            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('value');
           
            $data['carts'] = Cart::whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data['cancelled']= Order::where('status',5)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data['returned']= Order::where('status',11)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data['delivered']= Order::where('status',8)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data['compansations']= Compansations::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data['orderscount'] =  Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('created_at','desc')->count();
    
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
     
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            }

             return view('dashboard.reports.reports_results',$data);
        
          }

        if($request->type =="week" && $request->city){

            $data['sales']= (int)Order::where('status',8)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereHas('address', function ($q) use ($request) {
              
                $q->where('city_id', $request->city);
              
                })->sum('total');
            
            $data['customers'] = User::where('user_type','customer')->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('city_id', $request->city)->count();
           
            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            
            ->where('city_id', $request->city)->count();
            
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('value');
            
            $data['carts'] = Cart::whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $data['cancelled']= Order::where('status',5)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->count();

            
             $data['returned']= Order::where('status',11)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            
               ->whereHas('address', function ($q) use ($request) {

                   $q->where('city_id', $request->city);

                  })->count();

             $data['delivered']= Order::where('status',8)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);

                })->count();
  
        
            $data['compansations']= Compansations::whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('created_at','desc')
            
            ->count();

            $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
            $data['ordercash'] = Order::whereIN('id',$cash)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
             }) ->sum('total');
            
        
            $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');

            $data['orderbank'] = Order::whereIN('id',$bank)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            
            ->whereHas('address', function ($q) use ($request) {

                $q->where('city_id', $request->city);
             })->sum('total');

            $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
             
            $data['ordervisa'] = Order::whereIN('id',$visa)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
           })->sum('total');
            
        
            $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
             
            $data['ordersadad'] = Order::whereIN('id',$sadad)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
     
            $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
             
            $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
            
        
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                }) ->count();
                
            }

            return view('dashboard.reports.reports_results',$data);

        }

        if( $request->type =="month" && !$request->city){

            $today = Carbon::today();

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
     
            foreach($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereMonth('created_at', date('m'))->count();
            }

            return view('dashboard.reports.reports_results',$data);

        }

        if( $request->type =="month" && $request->city){

            $today = Carbon::today();
            
            $data['sales']= (int)Order::where('status',8)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->whereHas('address', function ($q) use ($request) {
              
                $q->where('city_id', $request->city);
              
                })->sum('total');
        
            $data['customers'] = User::where('user_type','customer')->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->where('city_id', $request->city)->count();
            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
            
            ->where('city_id', $request->city)->count();
            
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->sum('value');
            
            $data['carts'] = Cart::whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();

            $data['cancelled']= Order::where('status',5)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->count();

            $data['returned']= Order::where('status',11)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
            
               ->whereHas('address', function ($q) use ($request) {

                   $q->where('city_id', $request->city);

                  })->count();

            $data['delivered']= Order::where('status',8)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
            })->count();

        
            $data['compansations']= Compansations::whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->count();
            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])->orderBy('created_at','desc')
            
            ->count();

            $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
            $data['ordercash'] = Order::whereIN('id',$cash)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
             }) ->sum('total');
            
        
            $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
             
            $data['orderbank'] = Order::whereIN('id',$bank)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
            
            ->whereHas('address', function ($q) use ($request) {

                $q->where('city_id', $request->city);
             })->sum('total');

              $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
               
              $data['ordervisa'] = Order::whereIN('id',$visa)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
            
              ->whereHas('address', function ($q) use ($request) {
                  $q->where('city_id', $request->city);
              })->sum('total');
            
            $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
             
            $data['ordersadad'] = Order::whereIN('id',$sadad)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
     
            $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
             
            $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
            
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereBetween('created_at', [$today->startOfMonth(), $today->endOfMonth()])
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                })
                
                ->count();
            }

            return view('dashboard.reports.reports_results',$data);

        }

        if($request->type =="year" && !$request->city){

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
     
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereYear('created_at', date('Y'))->count();
            }

                return view('dashboard.reports.reports_results',$data);
        
        }

    
        if($request->type =="year" && $request->city){

            $data['sales']= (int)Order::where('status',8)->whereYear('created_at',date('Y'))->whereHas('address', function ($q) use ($request) {
              
                $q->where('city_id', $request->city);
              
                })->sum('total');
            
            $data['customers'] = User::where('user_type','customer')->whereYear('created_at',date('Y'))->where('city_id', $request->city)->count();
        
            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->whereYear('created_at',date('Y'))
            
            ->where('city_id', $request->city)->count();
            
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->whereYear('created_at', date('Y'))->sum('value');
            
            $data['carts'] = Cart::whereYear('created_at',date('Y'))->count();
            $data['cancelled']= Order::where('status',5)->whereYear('created_at', date('Y'))
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->count();

            
            $data['returned']= Order::where('status',11)->whereYear('created_at', date('Y'))
            
               ->whereHas('address', function ($q) use ($request) {

                   $q->where('city_id', $request->city);

                  })->count();

            $data['delivered']= Order::where('status',8)->whereYear('created_at', date('Y'))
                
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
            })->count();

            $data['compansations']= Compansations::whereYear('created_at', date('Y'))->count();
           
            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->whereYear('created_at', date('Y'))->orderBy('created_at','desc')
            
            ->count();

            $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
            $data['ordercash'] = Order::whereIN('id',$cash)->whereYear('created_at', date('Y'))
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
             }) ->sum('total');
            
        
            $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
            $data['orderbank'] = Order::whereIN('id',$bank)->whereYear('created_at', date('Y'))
            
            ->whereHas('address', function ($q) use ($request) {

                $q->where('city_id', $request->city);
             })->sum('total');

            $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
             
            $data['ordervisa'] = Order::whereIN('id',$visa)->whereYear('created_at', date('Y'))
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
           })->sum('total');
            
        
            $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
             
            $data['ordersadad'] = Order::whereIN('id',$sadad)->whereYear('created_at', date('Y'))
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
     
            $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
             
            $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereYear('created_at', date('Y'))
            
            ->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
            
        
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereYear('created_at', date('Y'))
                ->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                })
                
                ->count();
            }

             return view('dashboard.reports.reports_results',$data);
        }


        if($request->city){

            $data['sales']= (int)Order::where('status',8)->whereHas('address', function ($q) use ($request) {
              
                $q->where('city_id', $request->city);
              
                })->sum('total');

            $data['customers'] = User::where('user_type','customer')->where('city_id', $request->city)->count();

            $data['getorders'] = Order::pluck('user_id');
           
            $data['newcustomers']= User::where('user_type','customer')->whereNotIN('id', $data['getorders'])->where('city_id', $request->city)->count();
    
            $data['cashback'] = UserBalance::where([['type', '=', 'cashback'],  ['operation_type', '=', 'plus'],['expiry_date','>=',date('Y-m-d' ,strtotime(Carbon::now()))]])->sum('value');

            $data['carts'] = Cart::count();

            $data['cancelled']= Order::where('status',5)->whereHas('address', function ($q) use ($request) {
                   $q->where('city_id', $request->city);
               })->count();

            $data['returned']= Order::where('status',11)->whereHas('address', function ($q) use ($request) {
                  $q->where('city_id', $request->city);
                 })->count();

            $data['delivered']= Order::where('status',8)->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
               })->count();

            $data['compansations'] = Compansations::count();

            $data['orderscount'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->orderBy('created_at','desc')->count();

            $cash = Payment::where('payment_method_id',1)->get()->pluck('referenceable_id');
        
            $data['ordercash'] = Order::whereIN('id',$cash)->whereHas('address', function ($q) use ($request) {
                   $q->where('city_id', $request->city);
                })->sum('total');
     
            $bank = Payment::where('payment_method_id',2)->get()->pluck('referenceable_id');
             
            $data['orderbank'] = Order::whereIN('id',$bank)->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                 })->sum('total');
       
            $visa = Payment::where('payment_method_id',3)->get()->pluck('referenceable_id');
             
            $data['ordervisa'] = Order::whereIN('id',$visa)->whereHas('address', function ($q) use ($request) {
                     $q->where('city_id', $request->city);
                })->sum('total');
     
            $sadad = Payment::where('payment_method_id',4)->get()->pluck('referenceable_id');
             
            $data['ordersadad'] = Order::whereIN('id',$sadad)->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })->sum('total');
     
            $applepay = Payment::where('payment_method_id',5)->get()->pluck('referenceable_id');
             
            $data['orderapplepay'] = Order::whereIN('id', $applepay)->whereHas('address', function ($q) use ($request) {
                 $q->where('city_id', $request->city);
             })->sum('total');
     
            foreach ($status as $key => $value) {

                $data['orders_numbers'] [] = Order::where('status',$value)->whereHas('address', function ($q) use ($request) {
                    $q->where('city_id', $request->city);
                })->count();
            }

            return view('dashboard.reports.reports_results',$data);

        }

    }
    }
