<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function user() {
        return $this->belongsToMany(User::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function blocks() {
        return $this->hasMany(Block::class);
    }

    protected $with = ['department'];

}
