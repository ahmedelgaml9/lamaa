<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{

    public function index()
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.categories');
        $data['categories']= Category::all();

        return view('dashboard.category.index', $data);

    }

    public function create()
    {
      
         $data['breadcrumb'] = [

              ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
              ['name' => trans('admin.categories'), 'url' => route('category.index')],
              ['name' => trans('admin.create'), 'url' => null],
         ];
        
         $data['page_title'] = trans('admin.categories');

         $data['data'] = new Category ;

         return view('dashboard.category.create_edit', $data);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), (new Category)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
       
        $category = Category::create($request->all());

        return  redirect()->route('category.index')->with('success','تم الانشاء بنجاح');
    }

    public function edit($id)
    {  
        
         $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.categories'), 'url' => route('category.index')],
            ['name' => trans('admin.edit'), 'url' => null]

        ];

        $data['page_title'] = trans('admin.categories');

        $data['data'] = Category::findOrFail($id);

        return view('dashboard.category.create_edit', $data);
    }
    
    public function update(Request $request, $id)
    {
       
        $validator = Validator::make($request->all(), (new Category)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $update = Category::find($id);
        $update->update($data);
      
        return  redirect()->route('category.index')->with('success','تم التعديل بنجاح');
    }

    public function destroy($id)
    {
          
         $delete = Category::destroy($id);

         return redirect()->route('category.index');
    }
}
