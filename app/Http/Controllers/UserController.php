<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use DataTables;
use Illuminate\Support\Arr;
use Auth;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users-list|users-create|users-edit|users-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $data = User::role(['admin', 'siswa', 'guru'])
            ->with('participant', 'teacher')
            ->get();
        $page = 'management_users';
        $auth = Auth::user();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addColumn('fullname', function ($row) {
                    if ($row->participant) {
                        return $row->participant->fullname;
                    } elseif ($row->teacher) {
                        return $row->teacher->fullname;
                    }
                    return $row->name;
                })
                ->addColumn('username', function ($row) {
                    return $row->name;
                })
                ->addColumn('role', function ($row) {
                    if (!empty($row->getRoleNames())) {
                        foreach ($row->getRoleNames() as $role) {
                            if ($role == 'admin') {
                                $badge = '<span class="badge bg-success">' . $role . '</span>';
                            } else {
                                $badge = '<span class="badge bg-primary">' . $role . '</span>';
                            }
                            return $badge;
                        }
                    }
                })
                ->addColumn('action', function ($data) use ($auth) {
                    // $button =  '<a href="#"><i class="fas fa-eye text-success"></i></a>';
                    $button = '';
                    if ($auth->can('users-edit')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('users.edit', $data->id) . '"><i class="fas fa-edit text-secondary"></i></a>';
                    }
                    if ($auth->can('users-delete')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="' . $data->name . '" data-id="' . $data->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'role'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('pages.users.index', compact('data', 'page'));
    }

    public function create()
    {
        $page = 'management_users';
        $roles = Role::all();

        return view('pages.users.create', compact('page', 'roles'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|same:confirm-password',
            'role' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Crypt::encrypt($input['password']);

        $user = User::create($input);
        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully ttt',
        ]);
    }

    public function edit($id)
    {
        $page = 'management_users';
        $user = User::find($id);
        $roles = Role::all();
        $getRole = $user->roles->first();
        if ($getRole == null) {
            $userRole = 'no_role';
        } else {
            $userRole = $getRole->name;
        }

        return view('pages.users.edit', compact('user', 'roles', 'userRole', 'page'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'role' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'User edit successfully!'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }
}
