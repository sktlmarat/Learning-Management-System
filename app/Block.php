<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    public function assignments() {
        return $this->hasMany(Assignment::class);
    }

    public function materials() {
        return $this->hasMany(Material::class);
    }

    protected $with = ['assignments', 'materials'];
}
