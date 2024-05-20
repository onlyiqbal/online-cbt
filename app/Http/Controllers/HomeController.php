<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\{ParticipantSession,Participant,User,Exam};

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(Auth::user()->hasRole('siswa')){
            $time = date("H:i:s", strtotime(date('H:i:s')));
            $cekujian = Participant::join('participant_sessions as ps','participants.id','ps.participant_id')
                                    ->join('exam_sessions as es','ps.exam_session_id','es.id')
                                    ->join('exams','exams.id','ps.exam_id')
                                    ->join('mapels','mapels.id','exams.mapel_id')
                                    ->join('majors','majors.id','exams.major_id')
                                    ->join('class_rooms','class_rooms.id','exams.class_room_id')
                                    ->select('exams.name as nama_ujian','participants.fullname','es.time_start','es.time_end','es.name as sesi','mapels.mapel','majors.major','class_rooms.name')
                                    ->where('ps.status','!=','finished')
                                    ->whereDate('ps.date',date('Y-m-d'))
                                    ->where('participants.user_id',Auth::user()->id)->first();
                                    
            return view('pages.home.ujian',compact('cekujian','time'));
        }
        $page  = "home";
        $user  = User::role(['admin'])->count();
        $guru  = User::role(['guru'])->count();
        $siswa = User::role(['siswa'])->count();
        $ujian = Exam::count();
        return view('pages.home.index',compact('page','user','guru','siswa','ujian'));
    }
}
