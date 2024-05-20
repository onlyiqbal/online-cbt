<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\{Nilai,Major,ClassRoom,Exam};
use Auth;

class NilaiController extends Controller
{
    public function index(Request $request){
        $page   = 'nilai';
        $major  = Major::all();
        $class  = ClassRoom::all();
        $exam   = Exam::all();
        if($request->filter_exam == '' && $request->filter_major == '' && $request->filter_class == '' && $request->filter_date == ''){
            if(Auth::user()->hasRole('guru')){
                $data = Nilai::join('exams','exams.id','nilais.exam_id')
                                ->where('exams.user_id',Auth::user()->id)->get();
            }
            else{
                $data = Nilai::all();
            }
        }
        else{
            if(Auth::user()->hasRole('guru')){
                $data = Nilai::join('exams','exams.id','nilais.exam_id')
                                ->join('participant_sessions','participant_sessions.id','nilais.participant_session_id')
                                ->where('exams.user_id',Auth::user()->id)
                                ->where('nilais.exam_id',$request->filter_exam)
                                ->where('exams.major_id',$request->filter_major)
                                ->where('exams.class_room_id',$request->filter_class)
                                ->where('participant_sessions.date',$request->filter_date)->get();
            }
            else{
                $data = Nilai::join('exams','exams.id','nilais.exam_id')
                            ->join('participant_sessions','participant_sessions.id','nilais.participant_session_id')
                            ->where('nilais.exam_id',$request->filter_exam)
                            ->where('exams.major_id',$request->filter_major)
                            ->where('exams.class_room_id',$request->filter_class)
                            ->where('participant_sessions.date',$request->filter_date)->get();
            }
        }

        if($request->ajax()){
            return DataTables::of($data)
                ->addColumn('nama', function($row){
                    return $row->participant->fullname;
                })
                ->addColumn('ujian', function($row){
                    return $row->exam->name;
                })
                ->addColumn('kelas', function($row){
                    return $row->participant->kelas->name;
                })
                ->addColumn('jurusan', function($row){
                    return $row->participant->major->major;
                })
                ->addColumn('jawaban_pilgan', function($row){
                    return $row->jawaban_pilgan;
                })
                ->addColumn('nilai_pilgan', function($row){
                    return $row->nilai_pilgan;
                })
                ->addColumn('jawaban_essay', function($row){
                    return $row->jawaban_essay;
                })
                ->addColumn('nilai_essay', function($row){
                    return $row->nilai_essay;
                })
                ->addColumn('nilai_rata_rata', function($row){
                    return $row->nilai_rata_rata;
                })
                ->addIndexColumn()
                ->make(true);
                
        }

        return view('pages.nilai.index',compact('page','class','major','exam'));
    }
}
