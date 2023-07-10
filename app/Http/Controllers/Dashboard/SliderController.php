<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Slide;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Alert;


class SliderController extends Controller
{
    
    public function index()
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];
      
        $data['page_title'] = trans('admin.slider');
        
        $data['slides'] = Slide::all();

        return view('dashboard.slides.index', $data);
    }

    public function create()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.slider'), 'url' => route('slider.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.slider');
        $data['data'] = new Slide;

       return view('dashboard.slides.create',$data);

    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), (new Slide)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $slide = Slide::create($request->all());

        if ($request->hasFile('image')) {
            $slide->addMedia($request->file('image'))
                ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                ->toMediaCollection($slide->mediaImageCollectionName);
        }

         return  redirect()->route('slider.index')->with('success','تم الانشاء بنجاح');
    }

    public function edit($id)
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.slider'), 'url' => route('slider.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.slider');
        $data['data'] = Slide::findOrFail($id);

        return view('dashboard.slides.edit',$data);
    }

    public function update(Request $request, $id)
    {
      
        $validator = Validator::make($request->all(), (new Slide)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

          $data = $request->all();
 
          $update =Slide::find($id);

          $update->update($data);

          if($request->get('image_remove') || $request->hasFile('image')) {

              $update->clearMediaCollection($update->mediaImageCollectionName);
        
         }

        if($request->hasFile('image')) {
            $update->addMedia($request->file('image'))
                 ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                 ->toMediaCollection($update->mediaImageCollectionName);

        }

          return  redirect()->route('slider.index')->with('success','تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        $delete = Slide::destroy($id);

        return redirect()->route('slider.index');
    }
}
