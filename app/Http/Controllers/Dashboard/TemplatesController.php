<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Template;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplatesController extends Controller
{

    public function index()
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.notification_templates');
        $data['templates'] =Template::all();

         return view('dashboard.template.index', $data);
    }

    public function create()
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.notification_templates'), 'url' => route('template.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.notification_templates');
        $data['data'] = new Template;

         return view('dashboard.template.create_edit', $data);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), (new Template)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

          $template = Template::create($request->all());

         return  redirect()->route('template.index')->with('success','تم الانشاء بنجاح');
     }

     public function edit($id)
     {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.notification_templates'), 'url' => route('template.index')],
            ['name' => trans('admin.edit'), 'url' => null]

        ];

        $data['page_title'] = trans('admin.notification_templates');
        
        $data['data'] =Template::findOrFail($id);

        return view('dashboard.template.create_edit', $data);
    }

    public function update(Request $request, $id)
    {
       
        $validator = Validator::make($request->all(), (new Template)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $update= Template::find($id);
        $update->update($data);

        return  redirect()->route('template.index')->with('success','تم التعديل بنجاح');

    }

    public function destroy($id)
    {
        $delete =Template::destroy($id);

        return redirect()->route('template.index');
    }
}
