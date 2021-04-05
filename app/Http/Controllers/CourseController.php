<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Course;
use App\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseController extends Controller
{

//    public function show(Course $course){
//        return $course;
//    }

    /**
     * returns session list of the course
     * @param Course $course
     * @return mixed
     */
    public function getSessions(Course $course){
        return $course->session;
    }

    /**
     * returns class date and time of a specific session in a course
     * @param Session $session
     * @return array
     */
    public function getSessionAttendance(Session $session){
        $startDate = Carbon::parse($session->date);
        $endDate = Carbon::parse($session->end_date);
        $days = explode(',',$session->session_days);
        $date = [];
        while($startDate->lt($endDate)){
            foreach ($days as $day){
                $timestamp = date('Y-m-d H:i', strtotime($day, strtotime($startDate)));
//                array_push($date,date('Y-m-d D', strtotime($day, strtotime($startDate))));
                array_push($date,[
                    'date' => date('Y-m-d D', strtotime($day, strtotime($startDate))),
                    'original_date' => $timestamp,
                    'taken' => Attendance::where('session_id',$session->id)->where('original_date',$timestamp)->exists()
                ]);
            }
            $startDate->addWeeks($session->frequency);
        }
        return [
            'classes' => $date,
            'time' => Carbon::parse($session->date)->format('H:i')
        ];
    }

    /**
     * returns students of a course
     * @param Course $course
     * @return mixed
     */
    public function getStudents(Course $course){
        return $course->students;
    }

    public function addInstructorToStudents(Request $request){
        return Course::find($request->course_id)->users()->attach([
            'user_id' => $request->user_id
        ]);
    }
}
