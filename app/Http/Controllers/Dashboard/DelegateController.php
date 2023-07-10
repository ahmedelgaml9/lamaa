<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Hash;
use Carbon\Carbon;

class DelegateController extends Controller
{

    public function index(Request $request)
    {  
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.delegates');
        $data['delegates']= User::where('user_type', 'driver')->paginate(20);

        return view('dashboard.delegates.index', $data);
    }


    public  function print_delegates(Request $request)
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.delegates');
        $data['delegates']= User::where('user_type', 'driver')->paginate(20);

        return view('dashboard.delegates.delegates_pdf', $data);
    }

    public function create()
    {
       
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.delegates'), 'url' => route('users.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.delegates');
        $data['data'] = new User;
        $data['cities']= City::all();

          return view('dashboard.delegates.create_edit', $data);

     }

    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            
            'name' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

          $data = $request->all();
          $data['user_type'] = 'driver';
          $data['email'] = 'random_driver_'.hexdec(uniqid()).'@lam3h.com';
          $data['password'] = Hash::make(rand(2,50));
          $data['route_code'] =rand();
          $insert = User::create($data);

          return  redirect()->route('delegates.index')->with('success','تم الانشاء بنجاح');
    }

    public function edit($id)
    {
        
           $data['breadcrumb'] = [
              ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
              ['name' => trans('admin.delegates'), 'url' => route('users.index')],
              ['name' => trans('admin.edit'), 'url' => null]
           ];

           $data['page_title'] = trans('admin.delegates');
           $data['data'] = User::findOrFail($id);
           $data['cities']= City::all();

           return view('dashboard.delegates.create_edit', $data);
     }

     public function update(Request $request, $id)
     {

        $validator = Validator::make($request->all(), [
        
            'name' => 'required',
            'mobile' => 'required',
        ]);

        if($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $update = User::findOrFail($id);
        $update->update($data);

        return  redirect()->route('delegates.index')->with('success','تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        $delete = User::destroy($id);

        return redirect()->route('delegates.index');
    }

    public function orders($id)
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.delegates'), 'url' => route('users.index')],
        ];

        $data['page_title'] = trans('admin.delegates');
        $data['orders'] = Order::where('driver_id',$id)->where('status',Order::STATUS_DELIVERED)->paginate(20);
        $data['driverssales'] = Order::where('driver_id',$id)->where('status',Order::STATUS_DELIVERED)->sum('total');
        $data['data']=$id ;
        
        return view('dashboard.delegates.orders', $data);
   }
     
}
