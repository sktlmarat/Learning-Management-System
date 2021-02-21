<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function blocks() {
        return $this->hasMany(Block::class);
    }

    public function schedule_requests() {
        return $this->belongsToMany(ScheduleRequest::class);
    }

    protected $with = ['department'];

}
