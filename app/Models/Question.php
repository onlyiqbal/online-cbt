<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $autoincrement = false;
    public $keyType = 'string';


    public function guru(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function kelas(){
        return $this->belongsTo(ClassRoom::class,'class_room_id','id');
    }

    public function major(){
        return $this->belongsTo(Major::class,'major_id','id');
    }

    public function mapel(){
        return $this->belongsTo(Mapel::class,'mapel_id','id');
    }
}
