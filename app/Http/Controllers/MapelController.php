<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mapel;
use DataTables;
use Auth;

class MapelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:mapel-list|mapel-create|mapel-edit|mapel-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:mapel-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:mapel-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:mapel-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $page   = 'mapel';
        $mapel  = Mapel::all();
        $auth   = Auth::user();

        if($request->ajax()){
            return DataTables::of($mapel)
            ->addColumn('mapel', function($row){
                return $row->mapel;
            })
            ->addColumn('action', function($data)use($auth){
                    // $button =  '<a href="#"><i class="fas fa-eye text-success"></i></a>';
                    $button  = '';
                    if($auth->can('mapel-edit')){
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="'.route('mapels.edit',$data->id).'"><i class="fas fa-edit text-secondary"></i></a>';
                    }
                    if($auth->can('mapel-delete')){
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="'.$data->mapel.'" data-id="'.$data->id.'"><i class="fas fa-trash-alt text-danger"></i></a>';
                    }

                    return $button;
            })
            ->rawColumns(['action','role'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('pages.mapels.index', compact('page'));
    }

    public function create(){

        $page   = 'mapel';
        return view('pages.mapels.create', compact('page'));
    }

    public function store(Request $request){
        $this->validate($request, [
            'mapel'  => 'required|unique:mapels'
        ],[
            'mapel.required'   => 'Mata Pelajaran harus terisi !',
            'mapel.unique'     => 'Mata Pelajaran sudah ada !'
        ]);

        $data = $request->all();
        $data['mapel'] = strtoupper($request->mapel);
        $data['created_by'] = Auth::user()->name;

        $mapel = Mapel::create($data);

        if($mapel){

            return response()->json([
                'success' => true,
                'message' => 'Mata Pelajaran created successfully !',
            ]);
        }
    }

    public function edit($id){

        $page   = 'mapel';
        $mapel  = Mapel::findOrFail($id);
        return view('pages.mapels.edit', compact('page','mapel'));
    }

    public function update(Request $request, $id) {
        
        $this->validate($request,[
            'mapel'  => 'required|unique:mapels,mapel,'.$id
        ],[
            'mapel.required'  => 'Mata pelajaran harus terisi !',
            'mapel.unique'    => 'Mata pelajaran sudah ada !',
        ]);

        $data = $request->all();
        $data['mapel'] = strtoupper($request->mapel);
        $data['updated_by'] = Auth::user()->name;

        $mapel = Mapel::findOrFail($id);

        $mapel->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran update successfully !',
        ]);
    }

    public function destroy($id){

        $mapel = Mapel::findOrFail($id)->delete();

        if($mapel){
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran delete successfully !',
            ]);
        }
    }
}
