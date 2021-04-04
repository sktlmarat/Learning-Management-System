<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function getSchedule(User $student){
        $event = [];
        foreach ($student->session as $session){
            $event = array_merge($event,$this->getCalendarDates($session));
        }
        return $event;
    }
    private function getCalendarDates($session){
        $startDate = Carbon::parse($session->date);
        $endDate = Carbon::parse($session->end_date);
        $days = explode(',',$session->session_days);
        $dates = [];
        while($startDate->lt($endDate)) {
            foreach ($days as $day) {
                array_push($dates, [
                    'title' => $session->course->abbreviation,
                    'date' => date('Y-m-d', strtotime($day, strtotime($startDate)))
                ]);
            }
            $startDate->addWeeks($session->frequency);
        }
        return $dates;
    }
}
