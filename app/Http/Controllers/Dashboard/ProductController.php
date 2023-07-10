<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        
        $data['breadcrumb'] = [
            
            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.products'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.products_list');
        $data['products'] = $this->searchResult($request, Product::query()->where('type','=','product'))->orderBy('id', 'desc')->paginate($request->get('show_result_count', 15))->withQueryString();
       
        if ($request->ajax()) {
            $gridView = view('dashboard.products.partials.products_grid', $data)->render();
            $listView = view('dashboard.products.partials.products_list', $data)->render();
            return response()->json(['gridView' => $gridView, 'listView' => $listView]);
        }

         return view('dashboard.products.index', $data);
    }

    protected function searchResult($request, $products){
        
         if($searchWord = $request->get('search_word')){
            
             $products = $products->where('title','like', '%'.$searchWord.'%');
         }

        return $products;
    } 

    public function create()
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.products'), 'url' => route('products.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.products');
        $data['data'] = new Product;
        $data['products'] = Product::where('available',1)->orderBy('id', 'desc')->get();
        $data['categories'] = Category::orderBy('created_at', 'desc')->get();
        $data['cities'] = City::where('active', true)->get();
        $data['selected_cities_ids'] = [];

         return view('dashboard.products.create', $data);
    }

    public function store(Request $request)
    {
        
         $validator = Validator::make($request->all(), (new Product)->rules());

         if ($validator->fails()) {

               return redirect()->back()->withErrors($validator)->withInput();
         }

         $data = $request->all();
         $data['available'] = $request->available?1:0;
         $data['type'] = "product";
         $product = Product::create($data);
         $product->cities()->sync($request->get('cities', []));

          return redirect()->route('products.index');
      
    }
 
    public function show($id)
    {
    
        $product = Product::find($id);

        if(!$product){
            
            return response()->json([

                'status' => 404,
                'success' => false,
                'message' => trans('messages.something_went_wrong'),
                
            ]);
        }

        $view = view('dashboard.products.partials.ajax_show', ['product' => $product])->render();

        return response()->json([
            
            'status' => 200,
            'success' => true,
            'message' => trans('messages.get_data_success'),
            'content' => $view
        ]);
    }

    public function edit($id)
    {
        
        $data['breadcrumb'] = [

                ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
                ['name' => trans('admin.products'), 'url' => route('products.index')],
                ['name' => trans('admin.edit'), 'url' => null]
        ];
        
        $data['page_title'] = trans('admin.products');
        $data['data'] = Product::findOrFail($id);
        $data['products'] = Product::where('available',1)->orderBy('id', 'desc')->get();
        $data['categories'] = Category::orderBy('created_at', 'desc')->get();
        $data['cities'] = City::where('active', true)->get();
        $data['selected_cities_ids'] = $data['data']->cities()->pluck('cities.id')->toArray();

        return view('dashboard.products.edit', $data);
    }
 
    public function update(Request $request, $id)
    {
    
        $data = $request->all();
        $data['available'] = $request->available?1:0;
        $data['type']="product";
        $product = Product::find($id);
        $product->update($data);
        $product->cities()->sync($request->get('cities', []));

        return redirect()->route('products.index');
    }

    public function destroy($id)
    {

         $delete = Product::destroy($id);

        return redirect()->route('products.index');

    }
      
}
