<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    public function assignmentSubmissions() {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function grades() {
        return $this->hasMany(Grade::class, 'assignment_id');
    }
}
