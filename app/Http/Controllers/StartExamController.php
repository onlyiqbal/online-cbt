<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\{ParticipantSession,Participant,Question,DetailQuestion,Answer,Nilai};
use DB;

class StartExamController extends Controller
{
    public function confirm(){

        if(Auth::user()->hasRole('siswa')){
            $time = date("H:i:s", strtotime(date('H:i:s')));
            $cekujian = Participant::join('participant_sessions as ps','participants.id','ps.participant_id')
                                    ->join('exam_sessions as es','ps.exam_session_id','es.id')
                                    ->join('exams','exams.id','ps.exam_id')
                                    ->join('mapels','mapels.id','exams.mapel_id')
                                    ->join('majors','majors.id','exams.major_id')
                                    ->join('class_rooms','class_rooms.id','exams.class_room_id')
                                    ->select('exams.name as nama_ujian','participants.fullname','es.time_start',
                                            'es.time_end','es.name as sesi','mapels.mapel','majors.major',
                                            'class_rooms.name','exams.kode_ujian','participants.shcool_name',
                                            'exams.description','ps.id as id_participant_sessions','ps.status')
                                    ->where('ps.status','!=','finished')
                                    ->whereDate('ps.date',date('Y-m-d'))
                                    // ->whereDate('es.time_end','>', $time)
                                    ->where('participants.user_id',Auth::user()->id)->first();

            if($cekujian->status == 'started'){

                return redirect()->route('start-exam');
            }
            else{
                return view('pages.start-exam.confirm',compact('cekujian','time'));
            }                    
        }
        else{
            return redirect('/');
        }
    }

    public function showQuestion(){

        if(Auth::user()->hasRole('siswa')){
            $cekujian = Participant::join('participant_sessions as ps','participants.id','ps.participant_id')
                                    ->join('exam_sessions as es','ps.exam_session_id','es.id')
                                    ->join('exams','exams.id','ps.exam_id')
                                    ->select('exams.soal_1','exams.soal_2','ps.id as id_participant_sessions',
                                            'exams.random_question','es.time_end','participants.id as participant_id')
                                    ->where('ps.status','!=','finished')
                                    ->whereDate('ps.date',date('Y-m-d'))
                                    // ->orderBy('exams.exam_date','DESC')
                                    ->where('participants.user_id',Auth::user()->id)->first();

            $update_status = ParticipantSession::findOrFail($cekujian->id_participant_sessions);
            $update_status->status = "started";
            $update_status->save();

            if($cekujian->random_question == 'random'){
                $pilgan = DetailQuestion::where('question_id',$cekujian->soal_1)->inRandomOrder()->get();
                $essay  = DetailQuestion::where('question_id',$cekujian->soal_2)->inRandomOrder()->get();
            }
            else{
                $pilgan = DetailQuestion::where('question_id',$cekujian->soal_1)->get();
                $essay  = DetailQuestion::where('question_id',$cekujian->soal_2)->get();
            }
            
            return view('pages.start-exam.exam',compact('cekujian','essay','pilgan'));
        }
        else{
            return redirect('/');
        }
    }

    public function finishExam(Request $request){

        $data = $request->all();
        // dd($data);
        $participant_session = ParticipantSession::where('participant_id',$data['participant_id'])
                                                    ->where('id',$data['id_participant_sessions'])
                                                    ->where('status','=','started')->first();
        // dd($participant_session);
        if($participant_session->status == 'finished'){
            return response()->json([
                'success'   => false,
                'message'   => 'Ujian tidak di temukan!'
            ]);
        }
        else{
            DB::beginTransaction();
            try{

                if(isset($data['pilgan'])){

                    foreach ($data['pilgan'] as $item){
        
                        $soal = DetailQuestion::findOrFail($item['id']);
                        $answer = isset($item['answer']) ? $item['answer'] : '';
                        if($soal->key == $answer){
                            $status = "correct";
                        }
                        elseif($soal->key != $answer){
                            $status = "wrong";
                        }
                        else{
                            $status = '';
                        }
                        $jawaban = Answer::create([
                            'detail_questions_id'       => $soal->id,
                            'answer'                    => $answer,
                            'status'                    => $status,
                            'participant_id'            => $data['participant_id'],
                            'exam_id'                   => $participant_session->exam_id,
                            'participant_session_id'    => $participant_session->id,
                            'correct_by'                => 'SYS',
                            'type'                      => 'pilgan',
                        ]);
                    }

                    $benar  = Answer::where('participant_id', $data['participant_id'])
                                        ->where('exam_id',$participant_session->exam_id)
                                        ->where('participant_session_id', $participant_session->id)
                                        ->where('status','correct')
                                        ->where('type','pilgan')->count();
                    $semua  = Answer::where('participant_id', $data['participant_id'])
                                        ->where('exam_id',$participant_session->exam_id)
                                        ->where('participant_session_id', $participant_session->id)
                                        ->where('type','pilgan')->count();

                    $nilai = 100 / $semua;

                    $nilai = Nilai::create([
                        'participant_id'            => $data['participant_id'],
                        'exam_id'                   => $participant_session->exam_id,
                        'participant_session_id'    => $participant_session->id,
                        'jawaban_pilgan'            => $benar,
                        'nilai_pilgan'              => $nilai * $benar,
                        'nilai_rata_rata'           => $nilai * $benar,
                    ]);

                }

                if(isset($data['essay'])){

                    foreach ($data['essay'] as $item) {

                        $soal       = DetailQuestion::findOrFail($item['id']);
                        $answer     = isset($item['answer']) ? $item['answer'] : '';
                        $jawaban    = Answer::create([
                            'detail_questions_id'   => $soal->id,
                            'answer'                => $answer,
                            'participant_id'        => $data['participant_id'],
                            'exam_id'               => $participant_session->exam_id,
                            'participant_session_id'=> $participant_session->id,
                            'type'                  => 'essay'
                        ]);
                    }
                }

                $participant_session->status = 'finished';
                $participant_session->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Ujian selesai'
                ]);

            }
            catch(Exception $e){
                DB::rollback();

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal Ujian'
                ]);
            }
        }
    }
}
