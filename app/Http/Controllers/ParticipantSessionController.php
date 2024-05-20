<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\{ParticipantSession,Exam,ExamSession,Major,ClassRoom,Participant};
use DataTables;
use DB;


class ParticipantSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:peserta-sesi-list|peserta-sesi-create|peserta-sesi-edit|peserta-sesi-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:peserta-sesi-create', ['only' => ['create', 'store','generateid']]);
        $this->middleware('permission:peserta-sesi-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:peserta-sesi-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $page = 'participan_session';
        $data = ParticipantSession::all();
        $auth = Auth::user();

        if($request->ajax()){
            return DataTables::of($data)
            ->addColumn('exam_id', function($row){
                return $row->ujian->kode_ujian;
            })
            ->addColumn('ujian', function($row){
                return $row->ujian->name;
            })
            ->addColumn('participant_id', function($row){
                return $row->peserta->fullname;
            })
            ->addColumn('exam_session_id', function($row){
                return $row->sesi->name;
            })
            ->addColumn('status', function($row){
                if($row->status == 'not_started'){
                    $label = '<span class="badge bg-danger">Belum Mengerjakan</span>';
                }
                elseif($row->status == 'started'){
                    $label = '<span class="badge bg-warning">Sedang Mengerjakan</span>';
                }
                else{
                    $label = '<span class="badge bg-success">Sudah Mengerjakan</span>';
                }
                return $label;
            })
            ->addColumn('action', function($row)use($auth){
                $button = '';
                if($auth->can('peserta-sesi-edit')){
                    if($row->status == 'not_started'){
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="'.route('participant-session.edit',$row->id).'"><i class="fas fa-edit text-secondary"></i></a>';
                    }
                }
                if($auth->can('peserta-sesi-delete')){
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="'.$row->id.'" data-id="'.$row->id.'"><i class="fas fa-trash-alt text-danger"></i></a>';
                }
                return $button;
            })
            ->rawColumns(['photo','action','status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('pages.participant-session.index',compact('page'));
    }

    public function create(){

        $page   = 'participan_session';
        $sesi   = ExamSession::all();
        $major  = Major::all();
        $class  = ClassRoom::all();
        return view('pages.participant-session.create',compact('page','sesi','major','class'));
    }

    public function store(Request $request){

        $this->validate($request,[

            'exam_session_id' => 'required',
            'exam_id'         => 'required',
            'participant_id'  => 'required',
        ],[
            'exam_session_id.required' => 'Sesi ujian tidak boleh kosong !',
            'exam_id.required'         => 'Kode ujian tidak boleh kosong !',
            'participant_id.required'  => 'Harap pilih peserta ujian !',
            'exam_id.required'         => 'Ujian telah ada!'
        ]);

        $data = $request->all();
        DB::beginTransaction();
        try{

            foreach($data['participant_id'] as $participant){

                $cek = ParticipantSession::where('participant_id',$participant)
                                            ->where('exam_id',$data['exam_id'])
                                            ->where('exam_session_id',$data['exam_session_id'])
                                            ->count();
                if($cek >= 1){

                    continue;
                }
                else{
                    $participant_session = ParticipantSession::create([
                        'exam_session_id' => $data['exam_session_id'],
                        'exam_id'         => $data['exam_id'],
                        'date'            => $data['date'],
                        'participant_id'  => $participant,
                        'status'          => 'not_started',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peserta per sesi berhasil di tambahkan !'
            ]);

        }  catch(Exception $e) {

            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Peserta per sesi gagal di tambahkan !'
            ]);
        }
    }

    public function edit($id){

        $page   = 'participan_session';
        $sesi   = ExamSession::all();
        $data = ParticipantSession::findOrFail($id);

        return view('pages.participant-session.edit',compact('page','sesi','data'));
    }

    public function update(Request $request ,$id){
        $this->validate($request,[

            'exam_session_id' => 'required',
            'exam_id'         => 'required'
        ],[
            'exam_session_id.required' => 'Sesi ujian tidak boleh kosong !',
            'exam_id.required'         => 'Kode ujian tidak boleh kosong !',
            'exam_id.unique'           => 'Ujian telah ada!'
        ]);

        $data = $request->all();
        $participant_session = ParticipantSession::findOrFail($id);
        $participant_session->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Peserta per sesi berhasil di update !'
        ]);
    }

    public function destroy($id) {

        $data = ParticipantSession::findOrFail($id);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peserta per sesi berhasil di hapus !'
        ]);
    }

    public function getParticipant(){

        $participant = Participant::join('class_rooms','participants.class_room_id','class_rooms.id')
                                    ->join('majors','majors.id','participants.major_id')
                                    ->select('participants.id','participants.fullname','class_rooms.name as kelas','majors.major')->get();

        return response()->json($participant);
    }

    public function getParticipantbyNoPeserta($no_peserta){

        $participant = Participant::where('id','like','%' .$no_peserta. '%')->get();

        return response()->json($participant);
    }
    
    public function getParticipantBySelect(Request $request){

        if($request->jurusan && $request->kelas){

            $participant = Participant::where('major_id',$request->jurusan)->where('class_room_id',$request->kelas)->get();
        }
        elseif($request->jurusan){

            $participant = Participant::where('major_id',$request->jurusan)->get();
        }
        elseif($request->kelas){

            $participant = Participant::where('class_room_id',$request->kelas)->get();
        }
        else{

            $participant = Participant::all();
        }

        return response()->json($participant);
    }

    public function getExam(Request $request){

        $question = Exam::all();

        if($request->ajax()){
            return DataTables::of($question)
            ->addColumn('kode_ujian', function($row){
                return $row->kode_ujian;
            })
            ->addColumn('name', function($row){
                return $row->name;
            })
            ->addColumn('exam_date', function($row){
                return $row->exam_date;
            })
            ->addColumn('action', function($row){
                $button = '';
                $button .= '<button class="btn btn-primary round" onclick="pilih_ujian(this)" data-id="'.$row->id.'" data-name="'.$row->kode_ujian.'">
                                Pilih
                            </button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
}
