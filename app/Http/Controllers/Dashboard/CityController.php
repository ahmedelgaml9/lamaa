<?php

namespace App\Http\Controllers\Dashboard;
use App\Classes\Polygon;
use App\Models\City;
use App\Models\Area;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{

    public function index()
    {
       
        $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.cities');
        $data['cities'] = City::all();

        return view('dashboard.city.index', $data);
        
    }

    public function create()
    {
         
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.cities'), 'url' => route('cities.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.cities');
        $data['data'] = new City;
        $data['regions'] = Area::all();

        return view('dashboard.city.create_edit', $data);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), (new City)->rules());

        if ($validator->fails()) {

             return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['polygon'] = json_encode(Polygon::WKTToArray($request->polygons));
        $inserts = City::create($data);

        return  redirect()->route('cities.index')->with('success','تم الانشاء بنجاح');
    }


    public function edit($id)
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.cities'), 'url' => route('cities.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.cities');
        $data['data'] = City::findOrFail($id);
        $data['regions']= Area::all();

        return view('dashboard.city.create_edit', $data);
    }

    public function update(Request $request, $id)
    {
        
         $validator = Validator::make($request->all(), (new City)->rules());

        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['polygon'] = json_encode(Polygon::WKTToArray($request->polygons));
        
        $update = City::find($id);
        $update->update($data);

        return  redirect()->route('cities.index')->with('success','تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        
        $delete = City::destroy($id);

        return redirect()->route('cities.index');
    }
}
