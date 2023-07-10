<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;


class RoleController extends Controller
{
   
    public function index()
    {
        
        $roles = Role::paginate(10);

        return view('dashboard.staff_roles.index', compact('roles'));
    }

   
    public function create()
    {
        return view('dashboard.staff_roles.create');
    }

   
    public function store(Request $request)
    {
        
        if($request->has('permissions')){

            $role = new Role;
            $role->name = $request->name;
            $role->permissions = json_encode($request->permissions);
            $role->save();

            return redirect()->route('roles.index');
        }

        return  redirect()->route('roles.index')->with('success','تم الانشاء بنجاح');
    }

    

    public function edit(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        return view('dashboard.staff_roles.edit', compact('role'));
    }

   
    public function update(Request $request, $id)
    {
        
        $role = Role::findOrFail($id);

        if($request->has('permissions'))
        {
           $role->name = $request->name;
        }
            $role->permissions = json_encode($request->permissions);

            $role->save();

            return  redirect()->route('roles.index')->with('success','تم التعديل بنجاح');

    }

    public function destroy($id)
    {
        
        $role = Role::findOrFail($id);
        Role::destroy($id);

        return redirect()->route('roles.index');
    
    }
}
