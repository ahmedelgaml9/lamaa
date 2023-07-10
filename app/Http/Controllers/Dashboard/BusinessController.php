<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Artisan;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\HomePageSetting;
use App\Models\Photoes;
use Spatie\MediaLibrary\Models\Media;

class BusinessController extends Controller
{
    
    public function main_settings()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.general_settings'), 'url' => url('admin/settings/app')],
            ['name' => trans('admin.create'), 'url' => null],
        ]; 
 
        $data['page_title'] = trans('admin.general_settings');

        return view('dashboard.setting.main_setting',$data);
    }

    public function homepage_settings()
    {

        $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.home_page'), 'url' => url('admin/settings/homepage')],
             ['name' => trans('admin.create'), 'url' => null],
         ];

         $data['page_title'] = trans('admin.home_page');

         $data['gs'] = HomePageSetting::find(1);

         return view('dashboard.setting.home_page_settings',$data);

    }

    public function home_settings()
    {
         
         $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.home_settings'), 'url' => url('admin/settings/home')],
             ['name' => trans('admin.create'), 'url' => null],  
         ];

         $data['page_title'] = trans('admin.home_settings');

         return view('dashboard.setting.home',$data);
    }

    public function order_settings()
    {

         $data['breadcrumb'] = [

              ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
              ['name' => trans('admin.order_settings'), 'url' => url('admin/settings/orders')],
              ['name' => trans('admin.create'), 'url' => null],
         ];

         $data['page_title'] = trans('admin.order_settings');

         return view('dashboard.setting.orders', $data);
    }

    public function wallet_settings()
    {
         
          $data['breadcrumb'] = [

               ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
               ['name' => trans('admin.order_settings'), 'url' => url('admin/settings/orders')],
               ['name' => trans('admin.create'), 'url' => null],
          ]; 

          $data['page_title'] = trans('admin.wallet_settings');

          return view('dashboard.setting.wallet', $data);
          
    }

    public function store(Request $request)
    {
        //
    }

    public function updatesetting(Request $request)
    {

         $update = HomePageSetting::find(1);
         $update->update($request->all());

        if($update)
         {

          if($request->hasFile('image')) {

               $update->addMedia($request->file('image'))
                  ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                 ->toMediaCollection('homepage_images');
         }

         if($request->hasfile('photoes'))
         {
    
             $images = $request['photoes'];

             foreach($images as  $photo)
             {
                  $update->addMedia($photo)
                   ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                   ->toMediaCollection($update->photoesMediaCollection);
              }
         }

               Artisan::call('cache:clear');

               return  redirect()->back()->with('success','تم التعديل بنجاح');
          }
          else{

             Artisan::call('cache:clear');
             
             return back();
        }
    }

    public function update(Request $request)
    {
        
         foreach ($request->get('types', []) as $key => $type) {

            if($type == 'site_name'){
                $this->overWriteEnvFile('APP_NAME', $request[$type]);
            }
            if($type == 'timezone'){
                $this->overWriteEnvFile('APP_TIMEZONE', $request[$type]);
            }
            else {
                $lang = null;
                if(gettype($type) == 'array'){
                    $lang = array_key_first($type);
                    $type = $type[$lang];
                    $business_settings = Settings::where('key', $type)->first();
                }else{
                    $business_settings=Settings::where('key', $type)->first();
                }

                $requestValue = is_array($request[$type])?json_encode($request[$type]):$request[$type];

                if($business_settings!=null){
                    $business_settings->value = $requestValue;
                    $business_settings->save();
                  }

                else{

                    $business_settings = new Settings;
                    $business_settings->key = $type;
                    $business_settings->value = $requestValue;
                    $business_settings->save();
                }
            }
        }

         Artisan::call('cache:clear');

        return  redirect()->back()->with('success','تم التعديل بنجاح');

    }

    public function deleteimages($id)
    {
       
        $delete = Media::find($id)->delete();

        return redirect()->back();
    }
}
