<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\MattressType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class  CarMatterssController extends Controller
{
   
    public function index(){
     
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.mattress');
        
        $data['types'] = MattressType::whereNotNull('id')->orderBy('created_at','desc')->paginate(20);

        return view('dashboard.matterstype.index', $data);
    }

    public function create()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.mattress'), 'url' => route('mattersstype.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];
        
        $data['page_title'] = trans('admin.mattress');
        $data['data'] = new MattressType;

       return view('dashboard.matterstype.create_edit', $data);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), (new MattressType)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $size = MattressType::create($data);

        return  redirect()->route('mattersstype.index')->with('success','تم لانشاء بنجاج');

    }

    public function edit($id)
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.mattress'), 'url' => route('mattersstype.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.mattress');
        $data['data'] = MattressType::findOrFail($id);

        return view('dashboard.matterstype.create_edit', $data);
    }

    public function update(Request $request, $id)
    {
       
        $validator = Validator::make($request->all(), (new MattressType)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $size= MattressType::findOrFail($id);
        $size->update($data);
 
        return  redirect()->route('mattersstype.index')->with('success','تم التعديل بنجاح');
    }

    public function destroy($id)
    {
         $delete = MattressType::destroy($id);
         
         return  redirect()->route('mattersstype.index')->with('success','تم التعديل بنجاح');
    }
}
