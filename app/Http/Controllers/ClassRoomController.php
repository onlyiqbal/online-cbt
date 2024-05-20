<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use Auth;
use DataTables;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kelas-list|kelas-create|kelas-edit|kelas-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:kelas-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:kelas-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:kelas-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        
        $page  = 'kelas';
        $auth  = Auth::user();
        $kelas = ClassRoom::all();
        if($request->ajax()){
            return DataTables::of($kelas)
                ->addColumn('name', function($row){
                    return $row->name;
                })
                ->addColumn('action', function($data)use($auth){
                        // $button =  '<a href="#"><i class="fas fa-eye text-success"></i></a>';
                        $button  = '';
                        if($auth->can('kelas-edit')){
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="'.route('class.edit',$data->id).'"><i class="fas fa-edit text-secondary"></i></a>';
                        }
                        if($auth->can('kelas-delete')){
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="'.$data->name.'" data-id="'.$data->id.'"><i class="fas fa-trash-alt text-danger"></i></a>';
                        }

                        return $button;
                })
                ->rawColumns(['action','role'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('pages.class.index', compact('page'));
    }

    public function create(){
        $page  = 'kelas';
        return view('pages.class.create', compact('page'));
    }

    public function store(Request $request){

        $this->validate($request,[
            'name'  => 'required|unique:class_rooms',
        ],[
            'name.required'  => 'Kelas harus terisi !',
            'name.unique'    => 'Kelas sudah ada !',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::user()->name;

        $class = ClassRoom::create($data);

        if($class){

            return response()->json([
                'success' => true,
                'message' => 'Class created successfully !',
            ]);
        }


    }

    public function edit($id) {
        $page  = 'kelas';
        $class = ClassRoom::findOrFail($id);
        return view('pages.class.edit', compact('page','class'));
    }

    public function update(Request $request, $id) {
        
        $this->validate($request,[
            'name'  => 'required|unique:class_rooms,name,'.$id
        ],[
            'name.required'  => 'Kelas harus terisi !',
            'name.unique'    => 'Kelas sudah ada !',
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::user()->name;

        $class = ClassRoom::findOrFail($id);

        $class->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Class update successfully !',
        ]);
    }

    public function destroy($id){

        $class = ClassRoom::findOrFail($id)->delete();

        if($class){
            return response()->json([
                'success' => true,
                'message' => 'Class delete successfully !',
            ]);
        }
    }
}
