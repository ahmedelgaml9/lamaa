<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Contactus;
use Illuminate\Http\Request;

class ContactusController extends Controller
{
    
    public function index()
    {
     
       $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
       ];
        
       $data['page_title'] = trans('admin.contactus');

       $data['contacts'] =  Contactus::orderBy('created_at','desc')->paginate(20);

       return view('dashboard.contactus.index',$data);

    }

    public function show($id)
    {
        
         $data['breadcrumb'] = [

               ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
               ['name' => trans('admin.contactus'), 'url'=>url('admin/contacts')],
               ['name' => trans('admin.edit'), 'url' => null]
         ];

         $page_title =trans('admin.contactus');

         $contacts = Contactus::find($id);
         
         return view('dashboard.contactus.show',compact('page_title','contacts'));

    }

}
