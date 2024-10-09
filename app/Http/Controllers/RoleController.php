<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view roles', only : ['index']),
            new Middleware('permission:add roles', only : ['create']),
            new Middleware('permission:edit roles', only : ['edit']),
            new Middleware('permission:delete roles', only : ['destroy']),
        ];
    }

    public function index()
    {
        $roles = Role::orderBy('name', 'ASC')->paginate(10);
        return view('roles.list', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:roles|min:3'
        ]);
        if($validator->passes()){
            $role = Role::create([
                'name' => $request->name
            ]);

            if(!empty($request->permission)){
                foreach ($request->permission as $name){
                    $role->givePermissionTo($name);
                }
            }
            return to_route('roles.index')->with('success', 'role added');
        }
            else{
                return to_route('roles.create')->withInput()->withErrors($validator);
            }

    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('roles.edit', compact('role', 'hasPermissions', 'permissions'));

    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:roles,name,' . $id
        ]);

        if($validator->passes()){
            $role->name = $request->name;
            $role->save();

            if(!empty($request->permission)) {
                $role->syncPermissions($request->permission);
            }else{
                $role->syncPermissions([]);
            }
            return to_route('roles.index')->with('success', 'Role updated successfully');
        }
        else{
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $role = Role::find($id);

        if($role == null){
            session()->flash('error', 'role not found');
            return response()->json([
                'status' => false
            ]);
        }

        $role->delete();
        session()->flash('success', 'role deleted successfully');
        return response()->json([
            'status' => true
        ]);

    }


}
