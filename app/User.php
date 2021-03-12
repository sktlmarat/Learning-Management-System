<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function assignmentSubmissions() {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function adviser() {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    public function schedule_request() {
        return $this->hasOne(ScheduleRequest::class, 'student_id');
    }

    public function grades() {
        return $this->hasMany(Grade::class, 'student_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['courses', 'department', 'adviser', 'schedule_request', 'grades'];

}
