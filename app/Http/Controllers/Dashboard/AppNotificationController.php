<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appnotification;
use App\User;
use App\Models\UserNotification;
use App\Models\CityUser;
use App\Models\City;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use DB;

class AppNotificationController extends Controller
{

    public function index()
    {
          
        $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.app_notifications'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.app_notifications');
        $data['apps'] = Appnotification::where('group', '!=', 'private')->orderBy('created_at','desc')->get();

        return view('dashboard.appnotification.index', $data);
    }

    public function create()
    {
           
        $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.app_notifications'), 'url' => route('notification-messages.index')],
             ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.app_notifications');
        $data['data'] = new Appnotification;
        $data['products'] = Product::all();

        return view('dashboard.appnotification.create_edit', $data);
    }

    public function store(Request $request)
    {

          $Appnotification = new Appnotification;
          $Appnotification->title= $request->title;
          $Appnotification->content = $request->get('content');
          $Appnotification->send_at = $request->send_at;
          $Appnotification->status = 0;
          $Appnotification->group = $request->group;
          $Appnotification->save();

        if($request->has('users'))
         {
            
             $users = $request['users'];

            foreach($users as $user)
             {
 
                  $insert = new  UserNotification;
                  $insert->user_id= $user;
                  $insert->notification_id = $Appnotification->id;
                  $insert->save();

             }
         }

        if($request->hasFile('image')) {

             $Appnotification->addMedia($request->file('image'))
              ->withCustomProperties(['root' => 'user_prr'.uniqid()])
             ->toMediaCollection('images');
         }
          
          return redirect()->route('notification-messages.index')->with('success','تم التعديل بنجاح');
    }

    public function showusers()
    {
        
         $data['breadcrumb'] = [

              ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
              ['name' => trans('admin.users'), 'url' => route('notification-messages.index')],
              ['name' => trans('admin.users'), 'url' => null],
         ];

         $data['page_title'] = trans('admin.users');
         $data['users'] =  UserNotification::all();

         return view('dashboard.appnotification.show', $data);
    }

    public function getusers()
    {
          
        $users = User::where('user_type','=','customer')->get()->pluck('mobile','id');

         return response()->json($users);
    }

    public function notifications_show(Request $request)
    {
    
        if($request->from && $request->to)
        {
        
            $from =$request->from;
            $to = $request->to;
            $data['notifications'] = Appnotification::where('created_at','>=', $from)->where('created_at','<=', $to)->get();

            return view('dashboard.appnotification.results',$data);
        }

        if(($request->from && $request->to) == '')
        {

            return redirect()->back()->with('message','يجب اختيار تاريخ فى الفلتر');
        }
    }
 
}
