<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['attendance_type_id','feedback','original_date','session_id','student_id'];

    public function student(){
        return $this->belongsTo(User::class,'student_id');
    }
}
