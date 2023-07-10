<?php

namespace App\Http\Controllers\Api\V3;
use App\Http\Controllers\Controller;
use App\Http\Resources\V3\ProductResource;
use App\Http\Resources\V3\CategoryResource;
use App\Http\Resources\V3\ServiceResource;
use App\Models\City;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App;


class ProductController extends Controller
{
    public function __construct(Request $request)
    {
        App::setLocale($request->header('lang'));
    }
    
    public function index(Request $request)
    {
        
        $city_id = $request->header('city_id');
        $products = Product::where('available',1);

        if($productType = $request->get('product_type')){

            $products = $products->where('type', $productType);
        }

        if( $city_id ){

            $products = $products->whereHas('cities', function ($query) use ($city_id) {

                return $query->where('cities.id', $city_id)->where('active','=',1);
            });
        }

         $products = ProductResource::collection($products->get());
 
         return response()->json([

              'status' => 200,
              'success' => true,
              'message' => trans('messages.get_data_success'),
              "products" => $products
        ]);
    }

    public function show(Request $request, $id)
    {
        $product = Product::find($id);

        if(!$product){
            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        return $this->sendResponse(new ProductResource($product), trans('messages.get_data_success'));
    }

    public function categories()
    {
      
       
        $categories = CategoryResource::collection(Category::get());

         return response()->json([

             'status' => 200,
             'success' => true,
             'message' => trans('messages.get_data_success'),
             "categories" => $categories
        ]);
    }

    public function category(Request $request ,$id)
    {

        $city_id = $request->header('city_id');
        
        $products = Product::where('available',1);
 
        if($city_id ){

             $products = $products->whereHas('cities', function ($query) use ($city_id) {

                return $query->where('cities.id', $city_id)->where('active','=',1);
          });
        }

        $products = ProductResource::collection($products->where('category_id',$id)->get());
      
         return response()->json([

               'status' => 200,
               'success' => true,
               'message' => trans('messages.get_data_success'),
               "products" => $products
           ]);
     }

    public function servicedetail($id)
    {
        
        $service = Product::find($id);
        
        if(!$service){

            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

         return $this->sendResponse(new ServiceResource($service), trans('messages.get_data_success')); 
    }

    public function notifyMeIfAvailable(Request $request, $id)
    {
       
        $product = Product::find($id);

        if(!$product){

            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        return $this->sendResponse([], trans('messages.sent_data_success'));
    }
}
