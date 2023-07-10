<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\Validator;

class PagesController extends Controller
{

    public function index()
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.pages');
        
        $data['pages'] = Page::all();

        return view('dashboard.pages.index', $data);
    }

    public function create()
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.pages'), 'url' => route('pages.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.pages');
        $data['data'] = new Page;

        return view('dashboard.pages.create_edit', $data);
    }

    public function store(Request $request)
    {
       
         $validator = Validator::make($request->all(), (new Page)->rules());
 
         if ($validator->fails()) {

             return redirect()->back()->withErrors($validator)->withInput();
         }

          $page = Page::create($request->all());
       
          return redirect()->route('pages.index');
    }

    public function edit($id)
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.pages'), 'url' => route('pages.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.pages');
        $data['data'] = Page::findOrFail($id);

        return view('dashboard.pages.create_edit', $data);
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), (new Page)->rules());

        if ($validator->fails()) {

              return redirect()->back()->withErrors($validator)->withInput();
        }

        $data =$request->all();

        if (!isset($this->request['public'])) {

            $data = array_add($data, 'public', "0");
        }

        $page= Page::findOrFail($id);
        $page->update($data);

        return  redirect()->back()->with('success','تم التعديل بنجاح');
    }

    public function destroy($id)
    {


    }
}
