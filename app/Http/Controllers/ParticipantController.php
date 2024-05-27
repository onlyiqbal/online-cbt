<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Participant, ClassRoom, Major, User};
use Auth;
use DataTables;
use Storage;
use DB;
use Image;


class ParticipantController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:peserta-list|peserta-create|peserta-edit|peserta-delete', ['only' => ['index', 'store', 'generateid']]);
        $this->middleware('permission:peserta-create', ['only' => ['create', 'store', 'generateid']]);
        $this->middleware('permission:peserta-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:peserta-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $page = 'participans';
        $data = Participant::all();
        $auth = Auth::user();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addColumn('photo', function ($row) {
                    if (is_null($row->photo) && $row->photo == '') {
                        $url = asset('img/imagePlaceholder.png');
                    } else {
                        $url = Storage::url('public/images/photo_participant/' . $row->photo);
                    }

                    return $image = '<img src="' . $url . '" class="rounded" style="width: 70px; height: 70px;">';
                })
                ->addColumn('name', function ($row) {
                    return $row->fullname;
                })
                ->addColumn('no_peserta', function ($row) {
                    return $row->id;
                })
                ->addColumn('class_room', function ($row) {
                    return $row->kelas->name;
                })
                ->addColumn('major', function ($row) {
                    return $row->major->major;
                })
                ->addColumn('shcool_name', function ($row) {
                    return $row->shcool_name;
                })
                ->addColumn('jenkel', function ($row) {
                    if ($row->jen_kel == 'male') {
                        return 'L';
                    } elseif ($row->jen_kel == 'female') {
                        return 'P';
                    }
                })
                ->addColumn('action', function ($row) use ($auth) {
                    $button = '';
                    if ($auth->can('peserta-edit')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('participant.edit', $row->id) . '"><i class="fas fa-edit text-secondary"></i></a>';
                    }
                    if ($auth->can('peserta-delete')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="' . $row->fullname . '" data-id="' . $row->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                    }
                    return $button;
                })
                ->rawColumns(['photo', 'action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.participants.index', compact('page'));

    }

    public function create()
    {

        $page = 'participans';
        $class = ClassRoom::all();
        $major = Major::all();
        return view('pages.participants.create', compact('page', 'class', 'major'));
    }

    public function generateid()
    {
        $tgl = str_replace('-', '', date('y-m-d'));
        $sql = Participant::max(DB::raw('substr(id, 8, 2)'));
        if (!empty($sql)) {
            $n = (int) $sql + 1;
            $no = sprintf("%02s", $n);
        } else {
            $no = "01";
        }
        return "P$tgl" . $no;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'name' => 'required|regex:/^\S*$/u|unique:users',
            'email' => 'required|email|unique:users',
            'class' => 'required',
            'major' => 'required',
            'jenkel' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
        ], [
            'fullname.required' => 'Nama Lengkap tidak boleh kosong !',
            'name.required' => 'Username tidak boleh kosong !',
            'name.unique' => 'Username telah terdaftar sebelumnya !',
            'name.regex' => 'Tidak boleh ada spasi!',
            'email.required' => 'Email tidak boleh kosong !',
            'email.unique' => 'Email telah terdaftar sebelumnya !',
            'class.required' => 'Kelas tidak boleh Kosong !',
            'major.required' => 'Jurusan tidak boleh kosong !',
            'jenkel.required' => 'Jenis Kelamin tidak boleh kosong !',
            'photo.image' => 'File tidak valid!',
            'photo.mimes' => 'File tidak valid!',
            'photo.max' => 'File tidak boleh lebih dari 2 MB!',
        ]);

        $data = $request->all();
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('siswa123')
            ]);
            $user->assignRole('siswa');

            if ($request->file('photo')) {
                $file = $request->file('photo');
                $file_name = time() . str_replace(" ", "", $file->getClientOriginalName());
                $imageFile = Image::make($file->getRealPath());
                $imageFile->resize(1200, 1200);
                $file->storeAs('public/images/photo_participant', $file_name);
                $data['photo'] = $file_name;
            }

            $participant = Participant::create([
                'id' => $this->generateid(),
                'fullname' => $data['fullname'],
                'jen_kel' => $data['jenkel'],
                'class_room_id' => $data['class'],
                'major_id' => $data['major'],
                'shcool_name' => $data['shcool_name'],
                'photo' => $request->file('photo') ? $data['photo'] : null,
                'user_id' => $user->id
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '' . $participant->name . ' created successfully !'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Participant created failed !'
            ]);
        }
    }

    public function edit($id)
    {

        $page = 'participans';
        $class = ClassRoom::all();
        $major = Major::all();
        $participant = Participant::findOrFail($id);

        return view('pages.participants.edit', compact('page', 'class', 'major', 'participant'));

    }

    public function update(Request $request, $id)
    {

        $participant = Participant::findOrFail($id);
        $user = User::findOrFail($participant->user_id);

        $this->validate($request, [
            'fullname' => 'required',
            'name' => 'required|regex:/^\S*$/u|unique:users,name,' . $participant->user_id,
            'email' => 'required|email|unique:users,email,' . $participant->user_id,
            'class' => 'required',
            'major' => 'required',
            'jenkel' => 'required',
            // 'shcool_name' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
        ], [
            'fullname.required' => 'Nama Lengkap tidak boleh kosong !',
            'name.required' => 'Username tidak boleh kosong !',
            'name.regex' => 'Tidak boleh ada spasi!',
            'name.unique' => 'Username telah terdaftar sebelumnya !',
            'email.required' => 'Email tidak boleh kosong !',
            'email.unique' => 'Email telah terdaftar sebelumnya !',
            'class.required' => 'Kelas tidak boleh Kosong !',
            'major.required' => 'Jurusan tidak boleh kosong !',
            'jenkel.required' => 'Jenis Kelamin tidak boleh kosong !',
            // 'shcool_name'       => 'Nama sekolah tidak boleh kosong !',
            'photo.image' => 'File tidak valid!',
            'photo.mimes' => 'File tidak valid!',
            'photo.max' => 'File tidak boleh lebih dari 2 MB!',
        ]);

        $data = $request->all();
        $data['photo'] = $participant->photo;
        DB::beginTransaction();
        try {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            if ($request->file('photo')) {
                Storage::delete('public/images/photo_participant/' . $participant->photo);

                $file = $request->file('photo');
                $file_name = time() . str_replace(" ", "", $file->getClientOriginalName());
                $imageFile = Image::make($file->getRealPath());
                $imageFile->resize(1200, 1200);
                $file->storeAs('public/images/photo_participant', $file_name);
                $data['photo'] = $file_name;
            }

            $participant->update([
                'fullname' => $data['fullname'],
                'jen_kel' => $data['jenkel'],
                'class_room_id' => $data['class'],
                'major_id' => $data['major'],
                'shcool_name' => $data['shcool_name'],
                'photo' => $data['photo'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Participant update successfully !'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Participant update failed !'
            ]);
        }
    }

    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);
        $user = User::findOrFail($participant->user_id);

        if ($user) {
            Storage::delete('public/images/photo_participant/' . $participant->photo);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Participant delete successfully !'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Participant update failed !'
            ]);
        }
    }
}