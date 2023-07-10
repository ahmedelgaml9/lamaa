<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Role;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;
use Hash;
use Alert;

class UserController extends Controller
{

    public function index()
    {
        
         $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
         ];

         $data['page_title'] = trans('admin.users_staf');
         $data['users']= User::whereNotIn('user_type',['customer','driver'])->orderBy('created_at','desc')->paginate(20);

         return view('dashboard.users.index', $data);
    }

    public function create()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.users_staf'), 'url' => route('users.index')],
            ['name' => trans('admin.create'), 'url' => null],
         ];

         $data['page_title'] = trans('admin.users_staf');
         $data['data'] = new User;
         $data['roles'] = Role::all();
         $data['staff']= new Staff;

         return view('dashboard.users.create_edit', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), (new User)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            if($request->role_id == 12){

             $user->user_type = "admin";

            }
            else{

               $user->user_type = "staff";
            }

            $user->password = Hash::make($request->password);
            $user->save();

         if($user)
         {
            
             $staff = new Staff;
             $staff->user_id = $user->id;
             $staff->role_id = $request->role_id;
             if($staff->save()){

                return  redirect()->route('users.index')->with('success','تم الانشاء بنجاح');
            }
        }
     }


    public function edit($id)
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.users_staf'), 'url' => route('users.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.users_staf');
        $data['data']= User::findOrFail($id);
        $data['roles'] = Role::all();
        $data['staff']= Staff::where('user_id',$id)->first();

        return view('dashboard.users.create_edit', $data);
    }

    public function update(Request $request, $id)
    {
         
        $validator = Validator::make($request->all(),
         [
            'name' => 'required|string',
            'password' => 'nullable|min:6|confirmed',

         ]);

        if ($validator->fails()) {

             return redirect()->back()->withErrors($validator)->withInput();
        }

        $update = User::find($id);
        $update->name = $request->name;
        $update->email = $request->email;

        if($request->role_id == 12){

           $update->user_type = "admin";
        }
        else{

            $update->user_type = "staff";
        }
        
        if($request->password)
        {

           $update->password = Hash::make($request->password);
        }

        $update->save();

        $staff = Staff::where('user_id',$id)->first();
        $staff->update(['user_id' => $id, 'role_id' => $request->role_id]);
        
        if($update)
        {
            return  redirect()->route('users.index')->with('success','تم التعديل بنجاح');  
        }
    }

    public function destroy($id)
    {
        $delete = User::destroy($id);

        return redirect()->route('users.index');
    }

    public function myTestAddToLog()
    {

    }

    public function logActivity()
    {

        return view('dashboard.users.show',compact('logs'));
    }


    public function getusers(Request $request){

        $search = $request->search;

        if($search == ''){

           $users = User::where('user_type','=','customer')->select('id','mobile')->get()->take(500);

        }else{
           $users = User::where('user_type','=','customer')->select('id','mobile')->where('mobile', 'like', '%' .$search . '%')->get();
        }

        $response = array();

        foreach($users as $user){

           $response[] = array(

                "id"=>$user->id,
                "text"=>$user->mobile
           );
        }
        
        return response()->json($response);
    }

}
