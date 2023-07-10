<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteService;
use Illuminate\Support\Facades\Validator;


class WebsiteServicesController extends Controller
{
    public function index()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

         $data['page_title'] = trans('admin.websiteservices');
         $data['services'] = WebsiteService::orderBy('created_at','desc')->get();

         return view('dashboard.websiteservices.index', $data);
    }

    public function create()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.websiteservices'), 'url' => route('websiteservice.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.websiteservices');
        $data['data'] =new WebsiteService;

         return view('dashboard.websiteservices.create', $data);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), (new WebsiteService)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except('img');
        $services = WebsiteService::create($data);

        if ($request->hasFile('img')) {
            $services->addMedia($request->file('img'))
                ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                ->toMediaCollection($services->mediaPhotoCollectionName);
          }

           return  redirect()->route('websiteservice.index')->with('success','تم الانشاء بنجاح');
    }

    public function edit($id)
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.websiteservices'), 'url' => route('websiteservice.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.websiteservices');
        $data['data'] = WebsiteService::findOrFail($id);
      
        return view('dashboard.websiteservices.edit', $data);
    
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), (new WebsiteService)->rules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $update = WebsiteService::find($id);
        $update->update($data);

        if ($request->get('img_remove') || $request->hasFile('img')) {
            
             $update->clearMediaCollection($update->mediaPhotoCollectionName);
        }

        if ($request->hasFile('img')) {
            $update->addMedia($request->file('img'))
                ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                ->toMediaCollection($update->mediaPhotoCollectionName);
        }
           return  redirect()->route('websiteservice.index')->with('success','تم التعديل بنجاح');
      }

     public function destroy($id)
     {
          $delete = WebsiteService::destroy($id);

         return redirect()->route('websiteservice.index');
    }
}
