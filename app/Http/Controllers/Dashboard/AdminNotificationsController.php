<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminNotification;


class AdminNotificationsController extends Controller
{

    public function index(Request $request)
    {

        $data['breadcrumb'] = [

            ['name' => 'dashboard', 'url' => route('dashboard')],
       ];

       $data['page_title'] ="notifications";
    
       $data['notifications']= AdminNotification::get();

       return view('dashboard.notifications.index', $data);

    }

    public function show($id)
    {
        
       $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
       ];

       $data['page_title'] ='تفاصيل الاشعار';
       $data['notification'] = AdminNotification::find($id);
       $update = $data['notification']->update(['seen'=> 1]);

       return view('dashboard.notifications.show',$data);

    }


}
