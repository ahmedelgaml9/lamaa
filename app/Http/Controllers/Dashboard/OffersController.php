<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\OfferCity;
use App\Models\OfferProduct;
use App\Models\City;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;


class OffersController extends Controller
{
    public function index(Request $request)
    {
        
        $data['breadcrumb'] = [

           ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.offers');
        $data['offers']= $this->searchoffersResult($request,  Offer::query())->orderBy('id','desc')->get();
        $data['cities'] = City::all();
        $data['products'] = Product::all();

        return view('dashboard.offers.index', $data);
   }


   protected function searchoffersResult($request, $offers){
        
    if($searchWord = $request->get('search_word')){

        $offers = $offers->where('name', 'like', '%'.$searchWord.'%'); 
     }

       return $offers;
    }


    public function create()
    {
         
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.offers'), 'url' => route('offers.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.offers');
        $data['data'] = new Offer;
        $data['products'] = Product::get();
        $data['cities'] = City::all();
        $data['selected_cities_ids'] = $data['data']->cities()->pluck('cities.id')->toArray();
        $data['selected_products_ids'] = $data['data']->products()->pluck('products.id')->toArray();

          return view('dashboard.offers.create_edit', $data);
    }

    public function store(Request $request)
    {

         $validator = Validator::make($request->all(), (new Offer)->rules());

         if ($validator->fails()) {

             return redirect()->back()->withErrors($validator)->withInput();
         }

        $data = $request->except('products', 'cities');

        $offers = Offer::create($data);

        if($request->has('cities'))
         {

           $cities= $request['cities'];

          foreach($cities as  $city)
          {

              $insert = new OfferCity;
              $insert->city_id= $city;
              $insert->offer_id = $offers->id;
              $insert->save();
          }
        }

        if($request->has('products'))
         {

          $products= $request['products'];

          foreach($products as  $product)
          {

              $insert = new OfferProduct;
              $insert->product_id = $product;
              $insert->offer_id = $offers->id;
              $insert->save();

          }

         }


         return  redirect()->route('offers.index')->with('success','تم الانشاء بنجاح');
        }


    public function edit($id)
    {
         
        $data['breadcrumb'] = [

              ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
              ['name' => trans('admin.offers'), 'url' => route('offers.index')],
              ['name' => trans('admin.edit'), 'url' => null]
          
          ];

          $data['page_title'] = trans('admin.offers');
          $data['data']= Offer::findOrFail($id);
          $data['products'] = Product::get();
          $data['cities'] = City::all();
          $data['selected_cities_ids'] = $data['data']->cities()->pluck('cities.id')->toArray();
          $data['selected_products_ids'] = $data['data']->products()->pluck('products.id')->toArray();

          return view('dashboard.offers.create_edit', $data);
    }


    public function update(Request $request, $id)
    {

         $validator = Validator::make($request->all(), (new Offer)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('products', 'cities');
        $update = Offer::find($id);
        $update->update($data);

        if($request->has('cities'))
        {
         
            $delete = OfferCity::where('offer_id',$id)->delete();
            $cities= $request['cities'];
            foreach($cities as  $city)
            {

                $insert = new OfferCity;
                $insert->city_id= $city;
                $insert->offer_id =$id;
                $insert->save();
            }
        }

       if($request->has('products'))
        {

            $products= $request['products'];

            foreach($products as  $product)
            {

                $insert = new OfferProduct;
                $insert->product_id = $product;
                $insert->offer_id =$id;
                $insert->save();

            }
        }

          return  redirect()->route('offers.index')->with('success','تم التعديل بنجاح');

    }


    public function destroy($id)
    {

        $delete = Offer::destroy($id);

        \LogActivity::addToLog('تم حذف عرض');

        return redirect()->route('offers.index');
    }

    public function offers_filter()
    {

          $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
         ];

         $data['page_title'] = trans('admin.offers');
         $data['offers']= Offer::orderBy('created_at','desc')->get();
         $data['cities'] = City::all();
         $data['products'] = Product::all();
 
         return view('dashboard.offers.offersfilter', $data);
   }

}
