<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use function Pest\Laravel\json;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view permissions', only : ['index']),
            new Middleware('permission:add permissions', only : ['create']),
            new Middleware('permission:edit permissions', only : ['edit']),
            new Middleware('permission:delete permissions', only : ['destroy']),
        ];
    }

   public function index()
   {
       $permissions = Permission::orderBy('created_at', 'DESC')->paginate(10);
       return view('permissions.list', compact('permissions'));
   }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:permissions|min:3'
        ]);
        if($validator->passes()){
            Permission::create([
                'name' => $request->name
            ]);
            return to_route('permissions.index')->with('success', 'permissions added');
        }else{
            return to_route('permissions.create')->withInput()->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    public function update($id, Request $request)
    {
        $permission = Permission::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3|unique:permissions,name,'.$id.',id'
        ]);
        if($validator->passes()){
            $permission->name = $request->name;
            $permission->save();
            return to_route('permissions.index')->with('success', 'permission updated');
        }else{
            return to_route('permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $permission = Permission::findOrFail($id);
        if($permission == null){
            session()->flash('error', 'permission not found');
            return response()->json([
               'status' => false
            ]);
        }
            $permission->delete();

            session()->flash('success', 'permission deleted successfully');
            return response()->json([
                'status' => true
            ]);
    }
}
