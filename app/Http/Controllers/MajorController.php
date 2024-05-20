<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Major;
use Auth;
use DataTables;

class MajorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:jurusan-list|jurusan-create|jurusan-edit|jurusan-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:jurusan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:jurusan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:jurusan-delete', ['only' => ['destroy']]);
    }
    
    public function index(Request $request){
        
        $page  = 'kelas';
        $auth  = Auth::user();
        $jurusan = Major::all();
        if($request->ajax()){
            return DataTables::of($jurusan)
                ->addColumn('major', function($row){
                    return $row->major;
                })
                ->addColumn('action', function($data)use($auth){
                        // $button =  '<a href="#"><i class="fas fa-eye text-success"></i></a>';
                        $button  = '';
                        if($auth->can('jurusan-edit')){
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="'.route('majors.edit',$data->id).'"><i class="fas fa-edit text-secondary"></i></a>';
                        }
                        if($auth->can('jurusan-delete')){
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="'.$data->major.'" data-id="'.$data->id.'"><i class="fas fa-trash-alt text-danger"></i></a>';
                        }

                        return $button;
                })
                ->rawColumns(['action','role'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('pages.majors.index', compact('page'));
    }

    public function create(){
        $page  = 'kelas';
        return view('pages.majors.create', compact('page'));
    }

    public function store(Request $request){

        $this->validate($request,[
            'major'  => 'required|unique:majors',
        ],[
            'major.required'  => 'jurusan harus terisi !',
            'major.unique'    => 'jurusan sudah ada !',
        ]);

        $data = $request->all();
        $data['major'] = strtoupper($request->major);
        $data['created_by'] = Auth::user()->name;

        $jurusan = Major::create($data);

        if($jurusan){

            return response()->json([
                'success' => true,
                'message' => 'Class created successfully !',
            ]);
        }


    }

    public function edit($id) {
        $page  = 'kelas';
        $class = Major::findOrFail($id);
        return view('pages.majors.edit', compact('page','class'));
    }

    public function update(Request $request, $id) {
        
        $this->validate($request,[
            'major'  => 'required|unique:majors,major,'.$id
        ],[
            'major.required'  => 'jurusan harus terisi !',
            'major.unique'    => 'jurusan sudah ada !',
        ]);

        $data = $request->all();
        $data['major'] = strtoupper($request->major);
        $data['updated_by'] = Auth::user()->name;

        $jurusan = Major::findOrFail($id);

        $jurusan->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Jurusan update successfully !',
        ]);
    }

    public function destroy($id){

        $jurusan = Major::findOrFail($id)->delete();

        if($jurusan){
            return response()->json([
                'success' => true,
                'message' => 'Jurusan delete successfully !',
            ]);
        }
    }
}
