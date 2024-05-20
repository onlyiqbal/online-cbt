<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Teacher,ClassRoom,Major,User};
use Auth;
use DataTables;
use Storage;
use DB;
use Image;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:guru-list|guru-create|guru-edit|guru-delete', ['only' => ['index', 'store','generateid']]);
        $this->middleware('permission:guru-create', ['only' => ['create', 'store','generateid']]);
        $this->middleware('permission:guru-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:guru-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $page = 'teachers';
        $data = Teacher::all();
        $auth = Auth::user();

        if($request->ajax()){
            return DataTables::of($data)
            ->addColumn('photo', function($row){
                if (is_null($row->photo) && $row->photo == null) {
                    $url = asset('img/imagePlaceholder.png');
                } else {
                    $url = Storage::url('public/images/photo_teacher/'.$row->photo);
                }

                return $image = '<img src="'. $url . '" class="rounded" style="width: 70px; height: 70px;">';
            })
            ->addColumn('nip', function($row){
                return $row->nip;
            })
            ->addColumn('name', function($row){
                return $row->fullname;
            })
            ->addColumn('email', function($row){
                return $row->user->email;
            })
            ->addColumn('username', function($row){
                return $row->user->name;
            })
            ->addColumn('jenkel', function($row){
                if($row->jen_kel == 'male'){
                    return 'Laki-Laki';
                }
                elseif($row->jen_kel == 'female'){
                    return 'Perempuan';
                }
            })
            ->addColumn('action', function($row)use($auth){
                $button = '';
                if($auth->can('guru-edit')){
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="'.route('teachers.edit',$row->id).'"><i class="fas fa-edit text-secondary"></i></a>';
                }
                if($auth->can('guru-delete')){
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="'.$row->fullname.'" data-id="'.$row->id.'"><i class="fas fa-trash-alt text-danger"></i></a>';
                }
                return $button;
            })
            ->rawColumns(['photo','action'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('pages.teacher.index',compact('page'));

    }

    public function create(){

        $page  = 'teachers';
        return view('pages.teacher.create',compact('page'));
    }

    public function generateid(){
        $tgl = str_replace('-','',date('Y-m'));
        $sql = Teacher::max(DB::raw('substr(id, 10, 4)'));
        if(!empty($sql)){
           $n   = (int)$sql + 1;
           $no  = sprintf("%04s",$n);
        }
        else{
            $no = "0001";
        }
        return "G-$tgl".$no;
    }

    public function store(Request $request){
        $this->validate($request,[
            'nip'         => 'required|unique:teachers',
            'fullname'    => 'required|unique:teachers',
            'name'        => 'required|regex:/^\S*$/u|unique:users',
            'email'       => 'required|email|unique:users',
            'jenkel'      => 'required',
            'photo'       => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ],[
            'nip.required'      => 'NIP Lengkap tidak boleh kosong !',
            'nip.unique'        => 'NIP sudah terdaftar sebelumnya !',
            'fullname.required' => 'Nama Lengkap tidak boleh kosong !',
            'fullname.unique'   => 'Nama Lengkap sudah terdaftar sebelumnya !',
            'name.required'     => 'Username tidak boleh kosong !',
            'name.regex'    => 'Tidak boleh ada spasi!',
            'name.unique'       => 'Username telah terdaftar sebelumnya !',
            'email.required'    => 'Email tidak boleh kosong !',
            'email.unique'      => 'Email telah terdaftar sebelumnya !',
            'jenkel.required'   => 'Jenis Kelamin tidak boleh kosong !',
            'photo.image'       => 'File tidak valid!',
            'photo.mimes'       => 'File tidak valid!',
            'photo.max'         => 'File tidak boleh lebih dari 2 MB!',
        ]);

        $data = $request->all();
        DB::beginTransaction();
        try{
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => bcrypt('123456')
            ]);
            $user->assignRole('guru');

            if($request->file('photo')){
                $file = $request->file('photo');
                $file_name = time() . str_replace(" ", "", $file->getClientOriginalName());
                $imageFile = Image::make($file->getRealPath());
                $imageFile->resize(1200,1200);
                $file->storeAs('public/images/photo_teacher', $file_name);
                $data['photo'] = $file_name;
            }

            $teachers = Teacher::create([
                'id'            => $this->generateid(),
                'nip'           => $request->nip ? $data['nip'] : '',
                'fullname'      => $data['fullname'],
                'jen_kel'       => $data['jenkel'],
                'photo'         => $request->file('photo') ? $data['photo'] : null,
                'user_id'       => $user->id
            ]);
            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => ''.$teachers->name.' created successfully !'
            ]);
        }
        catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => 'Teacher created failed !'
            ]);
        }
    }
    
    public function edit($id){
        $page  = 'teachers';
        $teachers = Teacher::findOrFail($id);

        return view('pages.teacher.edit',compact('page','teachers'));
    }

    public function update(Request $request, $id){

        $teachers    = Teacher::findOrFail($id);
        $user        = User::findOrFail($teachers->user_id);

        $this->validate($request,[
            'nip'         => 'required|unique:teachers,nip,'.$id,
            'fullname'    => 'required',
            'name'        => 'required|regex:/^\S*$/u|unique:users,name,'.$teachers->user_id,
            'email'       => 'required|email|unique:users,email,'.$teachers->user_id,
            'jenkel'      => 'required',
            'photo'       => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ],[
            'nip.required'      => 'NIP Lengkap tidak boleh kosong !',
            'nip.unique'        => 'NIP sudah terdaftar sebelumnya !',
            'fullname.required' => 'Nama Lengkap tidak boleh kosong !',
            'name.required'     => 'Username tidak boleh kosong !',
            'name.regex'    => 'Tidak boleh ada spasi!',
            'name.unique'       => 'Username telah terdaftar sebelumnya !',
            'email.required'    => 'Email tidak boleh kosong !',
            'email.unique'      => 'Email telah terdaftar sebelumnya !',
            'jenkel.required'   => 'Jenis Kelamin tidak boleh kosong !',
            'photo.image'       => 'File tidak valid!',
            'photo.mimes'       => 'File tidak valid!',
            'photo.max'         => 'File tidak boleh lebih dari 2 MB!',
        ]);

        $data = $request->all();
        $data['photo'] = $teachers->photo;
        DB::beginTransaction();
        try{
            $user->update([
                'name'      => $data['name'],
                'email'     => $data['email'],
            ]);

            if($request->file('photo')){
                Storage::delete('public/images/photo_teacher/'.$teachers->photo);

                $file = $request->file('photo');
                $file_name = time() . str_replace(" ", "", $file->getClientOriginalName());
                $imageFile = Image::make($file->getRealPath());
                $imageFile->resize(1200,1200);
                $file->storeAs('public/images/photo_teacher', $file_name);
                $data['photo'] = $file_name;
            }

            $teachers->update([
                'nip'           => $request->nip ? $data['nip'] : '',
                'fullname'      => $data['fullname'],
                'jen_kel'       => $data['jenkel'],
                'photo'         => $data['photo'],
            ]);

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Teacher update successfully !'
            ]);
        }
        catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => 'Teacher update failed !'
            ]);
        }
    }

    public function destroy($id){

        $teachers    = Teacher::findOrFail($id);
        $user        = User::findOrFail($teachers->user_id);

        if($user){
            Storage::delete('public/images/photo_teacher/'.$teachers->photo);
            $user->delete();

            return response()->json([
                'success'   => true,
                'message'   => 'Teacher delete successfully !'
            ]);
        }
        else{
            return response()->json([
                'success'   => false,
                'message'   => 'Teacher update failed !'
            ]);
        }
    }
}
