<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Additions;
use App\Models\Size;
use App\Models\CartSizes;
use App\Models\City;
use Illuminate\Support\Facades\Validator;




class ServiceController extends Controller
{
   
    public function index()
    {
         $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
         ];


         $data['page_title'] = trans('admin.services');
         $data['services'] = Product::where('type','=','service')->orderBy('created_at','desc')->get();

        return view('dashboard.services.index', $data);
    }

    public function create()
    {
        
          $data['breadcrumb'] = [

               ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
               ['name' => trans('admin.services'), 'url' => route('services.index')],
               ['name' => trans('admin.create'), 'url' => null],
           ];

            $data['page_title'] = trans('admin.services');
            $data['data'] = new Product;
            $data['categories'] = Category::orderBy('created_at', 'desc')->get();
            $data['sizes'] = Size::get();
            $data['cities'] = City::where('active', true)->get();
            $data['selected_cities_ids'] = [];

           return view('dashboard.services.create', $data);
     }

    public function store(Request $request)
    {
        
          $validator = Validator::make($request->all(), (new Product)->rules());

         if($validator->fails()) {

             return redirect()->back()->withErrors($validator)->withInput();
          }
        
         $data = $request->all();
         $data['type']="service";
         $services = Product::create($data);
         $services->cities()->sync($request->get('cities', []));

     

         if($request->has('addition'))
          {
           
              $additions = $request['addition'];
              $prices = $request['addition_price'];

              for($i =0 ; $i < count($additions); $i++)
              {

                 if(!empty($additions[$i])) {

                     $addition = new Additions;
                     $addition->service_id = $services->id;
                     $addition->addition = $additions[$i];
                     $addition->addition_price = $prices[$i];
                     $addition->save();
                 }
              }
          }

         if($request->has('size'))
          {
           
              $sizes = $request['size'];
              $prices = $request['size_price'];

              for($i = 0; $i < count( $sizes); $i++)
              {

                if(!empty( $sizes[$i])) {

                    $size = new CartSizes ;
                    $size->service_id = $services->id;
                    $size->size = $sizes[$i];
                    $size->size_price = $prices[$i];
                    $size->save();

                }
            }
        }
       
          return  redirect()->route('services.index')->with('success','تم الانشاء بنجاح');
    }

    public function edit($id)
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.services'), 'url' => route('services.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.services');
        $data['data'] = Product::findOrFail($id);
        $data['categories'] = Category::orderBy('created_at', 'desc')->get();
        $data['additions'] = Additions::where('service_id',$id)->get();
        $data['sizes'] = Size::get();
        $data['all_sizes'] = CartSizes::where('service_id',$id)->get();
        $data['cities'] = City::where('active', true)->get();
        $data['selected_cities_ids'] = $data['data']->cities()->pluck('cities.id')->toArray();

        return view('dashboard.services.edit', $data);
    }

    public function update(Request $request, $id)
    {
  
         $validator = Validator::make($request->all(), (new Product)->rules());
 
        if ($validator->fails()) {

             return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $update = Product::find($id);
        $update->update($data);
        $update->cities()->sync($request->get('cities', []));

        if($request->has('addition'))
        {
            
             $delete_additions =Additions::where('service_id',$id)->delete();
             $additions = $request['addition'];
             $prices = $request['addition_price'];

            for($i =0 ; $i < count($additions); $i++)
            {
           
                if(!empty($additions[$i])) {

                     $data['service_id'] =$id ;
                     $data['addition'] = $additions[$i];
                     $data['addition_price'] = $prices[$i];
                     Additions::create($data);
                 }
               }
            }

             if($request->has('size'))
               {

                  $deleteSizes = CartSizes::where('service_id',$id)->delete();
                  $sizes = $request['size'];
                  $prices = $request['size_price'];
 
                    for($i =0 ; $i < count( $sizes); $i++)
                    {

                       if(!empty( $sizes[$i])) {

                            $size = new CartSizes;
                            $size->service_id =$id;
                            $size->size = $sizes[$i];
                            $size->size_price = $prices[$i];
                            $size->save();
                      }
                   }
               }
    
             return  redirect()->route('services.index')->with('success','تم التعديل بنجاح');
     }           

     public function destroy($id)
     {
          $delete = Product::destroy($id);

          return redirect()->route('services.index');
     }
}
