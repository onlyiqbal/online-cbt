<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id','exam_id','participant_session_id','jawaban_pilgan','nilai_pilgan','jawaban_essay','nilai_essay','nilai_rata_rata'];

    protected $table = 'nilais';

    public function participant(){
        return $this->belongsTo(Participant::class,'participant_id','id');
    }

    public function exam(){
        return $this->belongsTo(Exam::class,'exam_id','id');
    }
}
