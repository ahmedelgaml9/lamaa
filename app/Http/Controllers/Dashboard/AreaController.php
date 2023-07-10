<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{

    public function index()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.regions');
        $data['areas']= Area::all();

        return view('dashboard.area.index', $data);

    }

    public function create()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.regions'), 'url' => route('region.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];
    
        $data['page_title'] = trans('admin.regions');
        $data['data'] = new Area;

        return view('dashboard.area.create_edit', $data);
    }

     public function store(Request $request)
     {
          
          $validator = Validator::make($request->all(), (new Area)->rules());

         if($validator->fails()) {

             return redirect()->back()->withErrors($validator)->withInput();
         }

          $area = Area::create($request->all());

          return  redirect()->route('region.index')->with('success','تم الانشاء بنجاح');
     }

    public function edit($id)
    {
         
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.regions'), 'url' => route('region.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.regions');
         
        $data['data']= Area::findOrFail($id);

        return view('dashboard.area.create_edit', $data);
    }
    
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), (new Area)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $update = Area::find($id);
        $update->update($data);

        return  redirect()->route('region.index')->with('success','تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        
        $delete = Area::destroy($id);

        return redirect()->route('region.index');
    }
}
