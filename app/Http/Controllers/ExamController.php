<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Exam,Mapel,ClassRoom,Major,Question,User};
use Auth;
use DataTables;
use DB;

class ExamController extends Controller
{
    public function index(Request $request){

        $page  = 'exam';
        $data  = Exam::all();
        $auth  = Auth::user();

        if($request->ajax()){
            return DataTables::of($data)
                ->addColumn('kode_ujian', function($row){
                    return $row->kode_ujian;
                })
                ->addColumn('name', function($row){
                    return $row->name;
                })
                ->addColumn('guru', function($row){
                    return $row->guru->teacher->fullname;
                })
                ->addColumn('action', function($data)use($auth){
                    $button  = '';
                    if($auth->can('sesi-edit')){
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="'.route('exam.edit',$data->id).'"><i class="fas fa-edit text-secondary"></i></a>';
                    }
                    if($auth->can('sesi-delete')){
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="'.$data->kode_ujian.'" data-id="'.$data->id.'"><i class="fas fa-trash-alt text-danger"></i></a>';
                    }

                    return $button;
            })
            ->rawColumns(['action','role'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('pages.exam.index',compact('page'));
    }

    public function create(){

        $page  = 'exam';
        $mapel = Mapel::all();
        $class = ClassRoom::all();
        $major = Major::all();
        if(Auth::user()->hasRole('guru')){
            $user  = User::role(['guru'])
                            ->leftjoin('teachers','teachers.user_id','users.id')
                            ->select('users.id','fullname')
                            ->where('users.id',Auth::user()->id)->get();     
        }
        else{

            $user  = User::role(['guru'])->leftjoin('teachers','teachers.user_id','users.id')->select('users.id','fullname')->get();
        }

        return view('pages.exam.create', compact('page','class','major','mapel','user'));

    }

    private function generateId(){
        $tgl = str_replace('-','',date('Y-m'));
        $sql = Exam::max(DB::raw('substr(kode_ujian, 9, 4)'));
        if(!empty($sql)){
           $n   = (int)$sql + 1;
           $no  = sprintf("%04s",$n);
        }
        else{
            $no = "0001";
        }
        return "U-$tgl".$no;
    }

    public function store(Request $request){

        $this->validate($request, [
            'mapel_id'          => 'required',
            'class_room_id'     => 'required',
            'major_id'          => 'required',
            'random_question'   => 'required',
            'user_id'           => 'required'
        ],[
            'mapel_id.required'         => 'Mapel tidak boleh kosong !',
            'class_room_id.required'    => 'Kelas tidak boleh kosong !',
            'major_id.required'         => 'Jurusan tidak tidak boleh kosong !',
            'random_question.required'  => 'Pilih acak soal !',
            'user_id.required'          => 'Pilih Guru !'
        ]);

        $mapel     = Mapel::findOrFail($request->mapel_id);
        $prefix    = substr($mapel->mapel,0,3);
        $major     = Major::findOrFail($request->major_id);
        $prefix   .= substr($major->major,0,3);
        $class     = ClassRoom::findOrFail($request->class_room_id);
        $prefix   .= substr($class->name,0,3);
        
        if($request->soal_1 == null && $request->soal_2 == null){
            return response()->json([
                'success' => false,
                'message' => 'Salah satu soal Pilihan Ganda atau Essay harus terisi !'
            ]);
        }
        elseif($request->soal_1 != null && $request->soal_2 == null){
            try {
                $soal_1 = Question::findOrFail($request->soal_1);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Soal Pilihan Ganda tidak terdaftar !'
                ]);
            }
            $explode = explode("-",$request->soal_1);
            if($explode[0] != $prefix){
                return response()->json([
                    'success' => false,
                    'message' => 'Soal Pilihan Ganda tidak cocok dengan Mapel,Kelas, atau Jurusan !'
                ]);
            }
        }
        elseif($request->soal_1 == null && $request->soal_2 != null){
            try {
                $soal_2 = Question::findOrFail($request->soal_2);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Soal Essay tidak terdaftar !'
                ]);
            }
            $explode = explode("-",$request->soal_2);
            if($explode[0] != $prefix){
                return response()->json([
                    'success' => false,
                    'message' => 'Soal Essay tidak cocok dengan Mapel,Kelas, atau Jurusan !'
                ]);
            }
        }
        else{
            try {
                $soal_1 = Question::findOrFail($request->soal_1);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Soal Pilihan Ganda tidak terdaftar !'
                ]);
            }
            try {
                $soal_2 = Question::findOrFail($request->soal_2);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Soal Essay tidak terdaftar !'
                ]);
            }
            if($explode1[0] != $prefix && $explode2[0] != $prefix){
                $explode1 = explode("-",$request->soal_1);
                $explode2 = explode("-",$request->soal_2);
                if($explode1[0] != $prefix){
                    return response()->json([
                        'success' => false,
                        'message' => 'Soal Pilihan Ganda tidak cocok dengan Mapel,Kelas, atau Jurusan !'
                    ]);
                }
                elseif($explode2[0] != $prefix){
                    return response()->json([
                        'success' => false,
                        'message' => 'Soal Essay tidak cocok dengan Mapel,Kelas, atau Jurusan !'
                    ]);
                }
            }
        }
        $data               = $request->all();
        $data['kode_ujian'] = $this->generateId();
        $data['name']       = 'UJIAN '.$mapel->mapel.' '.$major->major.' '.$class->name.'';
        $exam = Exam::create($data);

        if($exam){
            return response()->json([
                'success'   => true,
                'message'   => 'Exam created successfully !'
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Exam created failed !'
            ]);
        }
    }

    public function edit($id){

        $page  = 'exam';
        $mapel = Mapel::all();
        $class = ClassRoom::all();
        $major = Major::all();
        $exam  = Exam::findOrFail($id);
        if(Auth::user()->hasRole('guru')){
            $user  = User::role(['guru'])
                            ->leftjoin('teachers','teachers.user_id','users.id')
                            ->select('users.id','fullname')
                            ->where('users.id',Auth::user()->id)->get();     
        }
        else{

            $user  = User::role(['guru'])->leftjoin('teachers','teachers.user_id','users.id')->select('users.id','fullname')->get();
        }

        return view('pages.exam.edit', compact('page','mapel','class','major','exam','user'));
    }

    public function update(Request $request, $id){

        $this->validate($request, [
            'mapel_id'          => 'required',
            'class_room_id'     => 'required',
            'major_id'          => 'required',
            'exam_date'         => 'required',
            'random_question'   => 'required',
            'user_id'           => 'required'
        ],[
            'mapel_id.required'         => 'Mapel tidak boleh kosong !',
            'class_room_id.required'    => 'Kelas tidak boleh kosong !',
            'major_id.required'         => 'Jurusan tidak tidak boleh kosong !',
            'exam_date.required'        => 'Tanggal Ujian tidak boleh kosong !',
            'random_question.required'  => 'Pilih acak soal !',
            'user_id.required'          => 'Pilih Guru !'
        ]);

        $mapel     = Mapel::findOrFail($request->mapel_id);
        $prefix    = substr($mapel->mapel,0,3);
        $major     = Major::findOrFail($request->major_id);
        $prefix   .= substr($major->major,0,3);
        $class     = ClassRoom::findOrFail($request->class_room_id);
        $prefix   .= substr($class->name,0,3);
        
        if($request->soal_1 == null && $request->soal_2 == null){
            return response()->json([
                'success' => false,
                'message' => 'Salah satu soal Pilihan Ganda atau Essay harus terisi !'
            ]);
        }
        elseif($request->soal_1 != null && $request->soal_2 == null){
            $explode1 = explode("-",$request->soal_1);
            try {
                $soal_1 = Question::findOrFail($request->soal_1);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Soal Pilihan Ganda tidak terdaftar !'
                ]);
            }
            if($explode1[0] != $prefix){
                return response()->json([
                    'success' => false,
                    'message' => 'Soal Pilihan Ganda tidak cocok dengan Mapel,Kelas, atau Jurusan !'
                ]);
            }
        }
        elseif($request->soal_1 == null && $request->soal_2 != null){
            $explode2 = explode("-",$request->soal_2);
            try {
                $soal_2 = Question::findOrFail($request->soal_2);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Soal Essay tidak terdaftar !'
                ]);
            }
            if($explode2[0] != $prefix){
                return response()->json([
                    'success' => false,
                    'message' => 'Soal Essay tidak cocok dengan Mapel,Kelas, atau Jurusan !'
                ]);
            }
        }
        else{
            try {
                $soal_1 = Question::findOrFail($request->soal_1);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Soal Pilihan Ganda tidak terdaftar !'
                ]);
            }
            try {
                $soal_2 = Question::findOrFail($request->soal_2);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Soal Essay tidak terdaftar !'
                ]);
            }
            $explode1 = explode("-",$request->soal_1);
            $explode2 = explode("-",$request->soal_2);
            if($explode1[0] != $prefix && $explode2[0] != $prefix){
                if($explode1[0] != $prefix){
                    return response()->json([
                        'success' => false,
                        'message' => 'Soal Pilihan Ganda tidak cocok dengan Mapel,Kelas, atau Jurusan !'
                    ]);
                }
                elseif($explode2[0] != $prefix){
                    return response()->json([
                        'success' => false,
                        'message' => 'Soal Essay tidak cocok dengan Mapel,Kelas, atau Jurusan !'
                    ]);
                }
            }
        }
        $data               = $request->all();
        $data['name']       = 'UJIAN '.$mapel->mapel.' '.$major->major.' '.$class->name.'';
        $exam               = Exam::findOrFail($id);
        $exam->update($data);

        if($exam){
            return response()->json([
                'success'   => true,
                'message'   => 'Exam updated successfully !'
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Exam updated failed !'
            ]);
        }
    }

    public function destroy($id){

        $exam               = Exam::findOrFail($id);
        $exam->delete();

        if($exam){
            return response()->json([
                'success'   => true,
                'message'   => 'Exam deleted successfully !'
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Exam deleted failed !'
            ]);
        }
    }

    public function getQuestion(Request $request, $type){

        $question = Question::where('type',$type)->get();

        if($request->ajax()){
            return DataTables::of($question)
            ->addColumn('kode_soal', function($row){
                return $row->id;
            })
            ->addColumn('type', function($row){
                if($row->type == 'pilgan'){
                    return 'Pilihan Ganda';
                }
                else{
                    return 'Essay';
                }
               
            })
            ->addColumn('action', function($row)use($type){
                $button = '';
                $button .= '<button class="btn btn-primary round" onclick="pilih_soal(this)" data-id="'.$row->id.'" data-name="'.$type.'">
                                Pilih
                            </button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
}
