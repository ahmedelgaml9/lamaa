<?php

namespace App\Http\Controllers\Dashboard;
use App\Classes\Checkout;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\City;
use Illuminate\Support\Facades\Validator;

class CartsController extends Controller
{

    public function index(Request $request)
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.oldcarts');
        
        $data['carts'] = $this->searchCartResult($request, Cart::whereNotNull('user_id')->select('id','user_id','updated_at'))->orderBy('created_at','desc')->paginate(15)->withQueryString();

         return view('dashboard.carts.index', $data);
     }
    
     
     protected function searchCartResult($request, $orders){

         if($searchWord = $request->get('search_word')){
               $orders = $orders->where(function ($q) use ($searchWord) {
                     $q->whereHas('user', function ($q) use ($searchWord) {
                         $q->where('name', $searchWord)
                           ->orWhere('mobile', $searchWord);
                     });
               })
               ->orWhere('id', substr($searchWord, 2));
            }
     
           if($createdAt= $request->created_at){
               $array = explode(' >> ', $createdAt);
               $orders = $orders->where('created_at', '>=', $array[0])
               ->where('created_at', '<=', isset($array[1]));
           }
   
           if ($request->mobile || $request->name){
                 $orders = $orders->whereHas('user', function ($q) use ($request) {
                   $q->where('mobile', $request->mobile)
                     ->orWhere('name', $request->name);
               });
           }
               return $orders;
        }

        public function cartorder(Request $request)
        {
           
            $data['breadcrumb'] = [
    
                ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ];
    
            $data['page_title'] = trans('admin.carts');
            $data['cities'] = City::get();
            $data['orders'] = $this->searchOrderCartResult($request, Order::query()->where('status','!=','5')->where('source','=','dash_abandoned_carts'))->orderBy('created_at','desc')->paginate(15);
            $data['count'] = Order::where('status','!=','5')->where('source','=','dash_abandoned_carts')->orderBy('created_at','desc')->count();

            return view('dashboard.carts.orderscarts', $data);
        
        }
        
        protected function searchOrderCartResult($request, $orders){
    
            if($searchWord = $request->get('search_word')){
                   $orders = $orders->where(function ($q) use ($searchWord) {
                         $q->whereHas('user', function ($q) use ($searchWord) {
                             $q->where('name', $searchWord)
                               ->orWhere('mobile', $searchWord);
                         });
                   })
                   ->orWhere('id', substr($searchWord, 2));
               }
            
               if($createdAt= $request->created_at){
                   $array = explode(' >> ', $createdAt);
                   $orders = $orders->where('created_at', '>=', $array[0])
                   ->where('created_at', '<=', isset($array[1]));
               }
       
               if ($request->mobile || $request->name){
                     $orders = $orders->whereHas('user', function ($q) use ($request) {
                       $q->where('mobile', $request->mobile)
                         ->orWhere('name', $request->name);
                   });
               }
       
         return $orders;
     }
          
    public function show($id)
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.oldcarts'), 'url' => route('carts.index')],
            ['name' => trans('admin.show'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.oldcarts');
        $data['cart'] = Cart::findOrFail($id);

        return view('dashboard.carts.show', $data);
    }


}
