<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantSession extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function peserta(){
        return $this->belongsTo(Participant::class,'participant_id','id');
    }

    public function ujian(){
        return $this->belongsTo(Exam::class,'exam_id','id');
    }

    public function sesi(){
        return $this->belongsTo(ExamSession::class,'exam_session_id','id');
    }

}
