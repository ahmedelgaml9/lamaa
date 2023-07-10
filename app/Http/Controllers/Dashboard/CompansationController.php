<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compansations;
use App\Models\Product;
use App\Models\CompansationProduct;
use App\Models\UserBalance;
use App\Models\Order;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;


class CompansationController extends Controller
{
    
    public function index()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.compansations'); 
        $data['compansations']= Compansations::orderBy('created_at','desc')->get();

        return view('dashboard.compansations.index', $data);
    
    }

    public function create()
    {
        
         $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.compansations'), 'url' => route('compansations.index')],
             ['name' => trans('admin.create'), 'url' => null],
         ];
        
         $data['page_title'] = trans('admin.compansations');
         $data['data'] = new Compansations;
         $data['products'] = Product::all();
         $data['users'] =  User::where('user_type','=','customer')->select('id','mobile')->get();
         $data['orders'] = Order::orderBy('created_at','desc')->take(500)->pluck('id');

        return view('dashboard.compansations.create_edit', $data);
    }

  
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), (new Compansations)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
       
        $insert = Compansations::create($request->all());

        if($request->has('products'))
        {
          
           $products= $request['products'];
           $quantity=$request['quantity'];

           for( $i =0 ; $i < count($products); $i++)
           {

            if (!empty($products[$i])) {

                $time = new CompansationProduct;
                $time->compansation_id = $insert->id;
                $time->product_id  = $products[$i];
                $time->quantity = $quantity[$i];
                $time->save();

              }
           }
        }

        if($request->type =="balance")
        {

            $balance = new UserBalance;
            $balance->user_id = $insert->user_id;
            $balance->value = $request->value;
            $balance->expiry_date=$request->expiry_date ;
            $balance->order_id = $insert->order_id;
            $balance->status = 1;
            $balance->save();

        }
    
        \LogActivity::addToLog(' تم  انشاء تعويض رقم '." ".$insert['id']);

        return  redirect()->route('compansations.index')->with('success','تم  الانشاءبنجاح');
    }

    public function edit($id)
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.compansations'), 'url' => route('compansations.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.compansations');
        $data['data']= Compansations::findOrFail($id);
        $data['products'] = Product::all();
        $data['orders'] = Order::orderBy('created_at','desc')->take(500)->pluck('id');

        return view('dashboard.compansations.create_edit', $data);
    }

  
    public function update(Request $request, $id)
    {
        
       $validator = Validator::make($request->all(), (new Compansations)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $update = Compansations::find($id);
        $update->update($data);

        if($request->has('products'))
        {
          
           $products = $request['products'];
           $quantity = $request['quantity'];

           for( $i =0 ; $i < count($products); $i++)
           {

            if (!empty($products[$i])) {

                  $time = new CompansationProduct;
                  $time->compansation_id = $id;
                  $tim->product_id  = $products[$i];
                  $time->quantity = $quantity[$i];
                  $time->save();

               }
            }
         }

         if($request->type =="balance")
         {

            $balance = new UserBalance;
            $balance->user_id = $insert->user_id;
            $balance->value = $request->value;
            $balance->expiry_date=$request->expiry_date ;
            $balance->order_id = $insert->order_id;
            $balance->status = 1;
            $balance->save();
         }

         \LogActivity::addToLog(' تم  تعديل تعويض رقم '." ".$update['id']);

         return  redirect()->route('compansations.index')->with('success','تم التعديل بنجاح');

    }

    public function destroy($id)
    {

        $delete = Compansations::destroy($id);

        return redirect()->route('compansations.index');
    }

     public  function getcustomers(Request $request){

        $get_customer_id = Order::where('id',$request->id)->first();

        $data = view('dashboard.compansations.get_order_customer',compact('get_customer_id'))->render();

        return response()->json(['options'=>$data]);

     
    }
}