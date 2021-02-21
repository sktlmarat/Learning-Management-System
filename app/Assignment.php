<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    public function assignmentSubmissions() {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
