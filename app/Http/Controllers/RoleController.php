<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles-list|roles-create|roles-edit|roles-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:roles-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $page = 'management_users';
        $roles = Role::all();
        $auth  = Auth::user();
        if($request->ajax()){
            return DataTables::of($roles)
                            ->addColumn('name', function($row){
                                if($row->name == 'admin'){
                                    $badge = '<span class="badge bg-success">'.$row->name.'</span>';
                                }
                                else{
                                    $badge = '<span class="badge bg-primary">'.$row->name.'</span>';
                                }
                                return $badge;
                            })
                            ->addColumn('action', function($data)use($auth){
                                $button = '';
                                if($auth->can('roles-edit')){
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="'.route('roles.edit',$data->id).'"><i class="fas fa-edit text-secondary"></i></a>';
                                }
                                if($auth->can('roles-delete')){
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="'.$data->name.'" data-id="'.$data->id.'"><i class="fas fa-trash-alt text-danger"></i></a>';
                                }
                                return $button;
                            })
                            ->rawColumns(['name','action'])
                            ->addIndexColumn()
                            ->make(true);
        }
        return view('pages.roles.index',compact('roles','page'));
    }

    public function create(){
        $page = 'management_users';
        $breadcrumb = 'createRole';
        $permissions = Permission::get();
        return view('pages.roles.create',compact('permissions', 'page', 'breadcrumb'));
    }

    public function store(Request $request){

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => strtolower($request->input('name'))]);
        $role->syncPermissions($request->input('permission'));

        return response()->json([ 
            'success'=> true,
            'message'=> "$role->name created successfully"
        ]);
    }

    public function show($id){

        $page = 'users';
        $breadcrumb = 'showRole';
        $role               = Role::findOrFail($id);
        $rolePermissions    = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                                        ->where("role_has_permissions.role_id",$id)
                                        ->get();
    
        return view('pages.roles.show',compact('role','rolePermissions', 'page', 'breadcrumb'));
    }
    
    public function edit($id){
        $page               = 'management_users';
        $breadcrumb         = 'editRole';
        $role               = Role::findOrFail($id);
        $permissions        = Permission::get();
        $rolePermissions    = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();
    
        return view('pages.roles.edit',compact('role','permissions','rolePermissions','page', 'breadcrumb'));
    }

    public function update(Request $request, $id){

        $this->validate($request, [
            'name'       => 'required|unique:roles,name,'.$id,
            'permission' => 'required',
        ]);

        $role       = Role::findOrFail($id);
        $role->name = strtolower($request->input('name'));
        $role->save();
    
        $role->syncPermissions($request->input('permission'));

        return response()->json([ 
            'success'=> true,
            'message'=> "$role->name update successfully"
        ]);
    }

    public function destroy($id){

        DB::table("roles")->where('id',$id)->delete();
        return response()->json([
            'success' => true,
            'message' => "delete role successfully"
        ]);
    }
}
