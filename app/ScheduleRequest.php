<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleRequest extends Model
{
    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    protected $with = ['courses'];
}
