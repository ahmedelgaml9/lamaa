<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class  CarSizeController extends Controller
{
   
    public function index(){
     
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.carsizes');
        $data['sizes'] = Size::whereNotNull('id')->orderBy('created_at','desc')->paginate(20);

        return view('dashboard.carsizes.index', $data);
    }

    public function create()
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.carsizes'), 'url' => route('carsize.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];
        
        $data['page_title'] = trans('admin.carsizes');
        $data['data'] = new Size;

       return view('dashboard.carsizes.create_edit', $data);

    }

    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), (new Size)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        $size = Size::create($data);

        return  redirect()->route('carsize.index')->with('success','تم لانشاء بنجاج');

    }

    public function edit($id)
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.carsizes'), 'url' => route('carsize.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.carsizes');
        
        $data['data'] = Size::findOrFail($id);

        return view('dashboard.carsizes.create_edit', $data);
    }

    public function update(Request $request, $id)
    {
      
        $validator = Validator::make($request->all(), (new Size)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

          $data = $request->all();
          $size= Size::findOrFail($id);
          $size->update($data);

           return  redirect()->route('carsize.index')->with('success','تم التعديل بنجاح');
    
    }

    public function destroy($id)
    {
     
         $delete = Size::destroy($id);
         
         return  redirect()->route('carsize.index')->with('success','تم التعديل بنجاح');
    }
}
