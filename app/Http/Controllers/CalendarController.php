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
                $date = date('Y-m-d', strtotime($day, strtotime($startDate)));
                array_push($dates, [
                    'title' => $session->course->abbreviation,
//                    .'|'.Carbon::parse($session->date)->format('H:i:s').'-'.
//                    Carbon::parse($session->date)->addMinutes($session->duration)->format('H:i') Add for Time
                    'start' => $date.'T'.Carbon::parse($session->date)->format('H:i:s'),
                    'end' => $date.'T'.Carbon::parse($session->date)
                            ->addMinutes($session->duration)->format('H:i:s'),
                ]);
            }
            $startDate->addWeeks($session->frequency);
        }
        return $dates;
    }
}
