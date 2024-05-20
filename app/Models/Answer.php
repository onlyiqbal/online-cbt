<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['detail_questions_id','answer','status','participant_id','exam_id','participant_session_id','correct_by','type'];
}
