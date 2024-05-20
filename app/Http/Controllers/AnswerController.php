<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Auth;
use App\Models\{Answer,ParticipantSession,Question,Exam,DetailQuestion,User,Participant,Nilai};
use DB;

class AnswerController extends Controller
{
    public function index(Request $request){

        $page = 'answer';
        if(Auth::user()->hasRole('guru')){
            $data = ParticipantSession::join('exams','exams.id','participant_sessions.exam_id')
                        ->where('exams.user_id',Auth::user()->id)
                        ->where('status', 'finished')
                        ->get([
                            'participant_sessions.id',
                            'participant_sessions.exam_id',
                            'participant_sessions.participant_id',
                            'participant_sessions.exam_session_id',
                            'participant_sessions.status',
                            'participant_sessions.date',
                            'participant_sessions.created_at',
                            'participant_sessions.updated_at'
                        ]);
        }
        else{

            $data = ParticipantSession::where('status', 'finished')->get();
        }
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
                // if($auth->can('guru-edit')){
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="'.route('answer.listujian',$row->id).'"><i class="fas fa-edit text-secondary">koreksi</i></a>';
                // }
                return $button;
            })
            ->rawColumns(['photo','action','status'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('pages.answer.index',compact('page'));

    }

    public function listUjian(Request $request, $id){

        $page   = 'answer';
        $user   = Auth::user();
        $data   = ParticipantSession::where('id', $id)->first();
        if($data){
            $exam   = Exam::where('id',$data->exam_id)->get();
        }
        else{
            $exam = [];
        }
        // $question = Question::all();

        if($request->ajax()){
            return DataTables::of($exam)
                ->addColumn('kode_ujian', function($row){
                    return $row->kode_ujian;
                })
                ->addColumn('name', function($row){
                    return $row->name;
                })
                ->addColumn('soal_pilgan', function($row)use($id,$user){
                    if(is_null($row->soal_1)){
                        $label = '<span class="badge bg-danger">Tidak ada ujian Pilgan</span>';
                    }
                    else{
                        // $question = $question->where('id',$row->soal_1)->first();
                        if($row->user_id != $user->id && !$user->hasRole('admin')){
                            $label = '<span class="badge bg-danger">Anda tidak mempunyai akses</span>';
                        }
                        else{
                            $label = '<a href="/koreksi-soal/'.$row->soal_1.'/'.$id.'">(dikoreksi sistem)</a>';
                        }
                    }

                    return $label;
                })
                ->addColumn('soal_essay', function($row)use($id,$user){
                    if(is_null($row->soal_2)){
                        $label = '<span class="badge bg-danger">Tidak ada ujian Essay</span>';
                    }
                    else{
                        // $question = $question->where('id',$row->soal_2)->first();
                        if($row->user_id != $user->id && !$user->hasRole('admin')){
                            $label = '<span class="badge bg-danger">Anda tidak mempunyai akses</span>';
                        }
                        else{
                            $label = '<a href="/koreksi-soal/'.$row->soal_2.'/'.$id.'"><i class="fas fa-edit text-secondary">koreksi soal</i></a>';
                        }
                    }

                    return $label;
                })
                ->rawColumns(['soal_pilgan','soal_essay'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.answer.list-ujian',compact('page','id'));

    }

    public function koreksiSoal(Request $request, $id_soal, $id_participan_session) {

        $page           = 'answer';
        $data           = ParticipantSession::where('id', $id_participan_session)->first();
        $participant    = Participant::findOrFail($data->participant_id);
        $soal           = DetailQuestion::join('answers','answers.detail_questions_id','detail_questions.id')
                                    ->where('question_id',$id_soal)
                                    ->where('exam_id',$data->exam_id)
                                    ->where('participant_id',$data->participant_id)
                                    ->where('participant_session_id',$id_participan_session)
                                    ->get();
        $benar          = DetailQuestion::join('answers','answers.detail_questions_id','detail_questions.id')
                                    ->where('question_id',$id_soal)
                                    ->where('exam_id',$data->exam_id)
                                    ->where('participant_id',$data->participant_id)
                                    ->where('participant_session_id',$id_participan_session)
                                    ->where('status','correct')
                                    ->count();
        $salah          = DetailQuestion::join('answers','answers.detail_questions_id','detail_questions.id')
                                    ->where('question_id',$id_soal)
                                    ->where('exam_id',$data->exam_id)
                                    ->where('participant_id',$data->participant_id)
                                    ->where('participant_session_id',$id_participan_session)
                                    ->where('status','wrong')
                                    ->count();
        $banyak_soal    = DetailQuestion::join('answers','answers.detail_questions_id','detail_questions.id')
                                    ->where('question_id',$id_soal)
                                    ->where('exam_id',$data->exam_id)
                                    ->where('participant_id',$data->participant_id)
                                    ->where('participant_session_id',$id_participan_session)
                                    ->count();

        $kosong         = DetailQuestion::join('answers','answers.detail_questions_id','detail_questions.id')
                                    ->where('question_id',$id_soal)
                                    ->where('exam_id',$data->exam_id)
                                    ->where('participant_id',$data->participant_id)
                                    ->where('participant_session_id',$id_participan_session)
                                    ->where('status','!=','wrong')
                                    ->where('status','!=','correct')
                                    ->count();
        // dd($soal);
        return view('pages.answer.koreksi',compact('page','soal','benar','salah','banyak_soal','kosong','participant','data'));
    }

    public function koreksi(Request $request){

        $data = $request->all();
        DB::beginTransaction();
        try{
            foreach($data['koreksi'] as $value){
                $answer = Answer::where('detail_questions_id',$value['id_soal'])
                                ->where('participant_id',$data['participant_id'])
                                ->where('exam_id',$data['exam_id'])
                                ->where('participant_session_id',$data['participant_session_id'])
                                ->where('type','essay')
                                ->first();

                $answer->update([
                    'status'        => $value['answer'],
                    'correct_by'    => Auth::user()->name,
                ]);
            }
            $nilai = Nilai::where('participant_id',$data['participant_id'])
                                ->where('exam_id',$data['exam_id'])
                                ->where('participant_session_id',$data['participant_session_id'])
                                ->count();

            $benar  = Answer::where('participant_id', $data['participant_id'])
                            ->where('exam_id',$data['exam_id'])
                            ->where('participant_session_id',$data['participant_session_id'])
                            ->where('status','correct')
                            ->where('type','essay')->count();
                            
            $semua  = Answer::where('participant_id', $data['participant_id'])
                            ->where('exam_id',$data['exam_id'])
                            ->where('participant_session_id',$data['participant_session_id'])
                            ->where('type','essay')->count();

            $hitung = 100 / $semua;

            if($nilai > 0) {
                $update_nilai = Nilai::where('participant_id',$data['participant_id'])
                                ->where('exam_id',$data['exam_id'])
                                ->where('participant_session_id',$data['participant_session_id'])
                                ->first();

                $update_nilai->update([
                    'jawaban_essay'     => $benar,
                    'nilai_essay'       => $hitung * $benar,
                    'nilai_rata_rata'   => ( ( ($hitung * $benar) + $update_nilai->nilai_pilgan ) / 2),
                ]);
            }
            else{

                $insert_nilai = Nilai::create([
                    'participant_id'            => $data['participant_id'],
                    'exam_id'                   => $data['exam_id'],
                    'participant_session_id'    => $data['participant_session_id'],
                    'jawaban_essay'             => $benar,
                    'nilai_essay'               => $hitung * $benar,
                    'nilai_rata_rata'           => $hitung * $benar,
                ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'koreksi soal berhasil'
            ]);
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'koreksi soal gagal'
            ]);
        }
    }
}
