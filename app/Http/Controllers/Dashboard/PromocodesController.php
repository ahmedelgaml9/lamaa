<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\PromoCodeProduct;
use App\Models\CouponCategory;
use App\Models\PromocodeCity;
use App\Models\Product;
use App\Models\OfferProduct;
use App\Models\City;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class PromocodesController extends Controller
{
   
    public function index()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.promocodes');
        $data['promocodes']  = PromoCode::orderBy('id', 'DESC')->get();

        return view('dashboard.promocode.index', $data);
    }

    public function create()
    {
    
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.promocodes'), 'url' => route('promocodes.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.promocodes');
        $data['data'] = new PromoCode;
        $data['products'] = Product::all();

        return view('dashboard.promocode.create_edit', $data);

    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(),
        [  
            'code' => 'required|unique:promocodes',
            'start_at' => 'required',
            'expires_at' => 'required',
        ]);

        if($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $collect = array(

            "percent" => $request->settings

        );

        $settings = json_encode($collect);
        $promocode= new PromoCode;
        $promocode->code= $request->code;
        $promocode->type = $request->type;
        $promocode->description = $request->description;
        $promocode->product_type = $request->product_type;
        $promocode->amount = $request->amount;
        $promocode->settings =$settings;
        $promocode->start_at=$request->start_at;
        $promocode->expires_at= $request->expires_at;
        $promocode->status  =$request->status;
        $promocode->min_value = $request->min_value;
        $promocode->max_value = $request->max_value;
        $promocode->num_of_use =$request->num_of_use;
        $promocode->save();
        $products = Product::all();

    if($request->product_type == 1)
     {

        foreach($products as $product)
        {

            $insert = new PromocodeProduct;
            $insert->product_id= $product->id;
            $insert->promocode_id = $promocode->id;
            $insert->save();

        }
    }

    elseif($request->product_type == 2) 
    {

        $selected = $request['products'];
        
        foreach($selected as $product)
        {
        
            $insert = new PromocodeProduct;
            $insert->product_id= $product;
            $insert->promocode_id = $promocode->id;
            $insert->save();

        }
    }
        return redirect()->route('promocodes.index');
    }

    public function edit($id)
    {
    
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.promocodes'), 'url' => route('promocodes.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.promocodes');
        $data['data'] = PromoCode::findOrFail($id);
        $data['cities'] = City::all();
        $data['products'] = Product::all();
        $data['selected_products_ids'] = PromocodeProduct::where('promocode_id',$id)->pluck('product_id')->toArray();

        return view('dashboard.promocode.edit', $data);
    }

    public function update(Request $request, $id)
    {

         $validator = Validator::make($request->all(),
         [

            'code' => 'required',
            'start_at' => 'required',
            'expires_at' => 'required',    

          ]);

         if ($validator->fails()) {

              return redirect()->back()->withErrors($validator)->withInput();
          }

            $collect = array(

                 "percent" => $request->settings
            );

            $settings = json_encode($collect);
            $promocode = PromoCode::find($id);
            $promocode->code= $request->code;
            $promocode->type = $request->type;
            $promocode->description = $request->description;
            $promocode->product_type = $request->product_type;
            $promocode->amount = $request->amount;
            $promocode->settings=$settings;
            $promocode->start_at=$request->start_at;
            $promocode->expires_at= $request->expires_at;
            $promocode->status  =$request->status;
            $promocode->min_value = $request->min_value;
            $promocode->max_value = $request->max_value;
            $promocode->num_of_use =$request->num_of_use;
            $promocode->save();

        if($request->product_type == 1)
         {

            foreach($products as $product)
            {

                $insert = new PromocodeProduct;
                $insert->product_id= $product->id;
                $insert->promocode_id = $promocode->id;
                $insert->save();
            }
        }

        elseif($request->product_type == 2 )
        {
            
            $delete = PromocodeProduct::where('promocode_id',$id)->delete();
            $selected = $request['products'];

          foreach($selected as $product)
          {
              
               $insert = new PromocodeProduct;
               $insert->product_id= $product;
               $insert->promocode_id = $promocode->id;
               $insert->save();

           }
        }
          return redirect()->route('promocodes.index');
    }

    public function destroy($id)
    {
         $delete = PromoCode::destroy($id);
 
         return redirect()->route('promocodes.index');
    }

}
