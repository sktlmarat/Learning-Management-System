<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            for($i=0; $i<count($request->toArray()); $i++)
                Attendance::create([
                    'original_date' => $request[$i]['original_date'],
                    'student_id' => $request[$i]['student_id'],
                    'session_id' => $request[$i]['session_id'],
                    'feedback' => $request[$i]['feedback'],
                    'attendance_type_id' => $request[$i]['attendance_type_id'],
                ]);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            return response([
                'message' => 'Failure! Complete attendance could not be taken.',
                'error' => $exception->getMessage()
            ],'500');
        }
        return response('Success! Attendance has been taken!','200');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function edit($id,Request $request)
    {
        $attendances = Attendance::with('student')->where('session_id',$id)
            ->where('original_date',$request->date)->get();
        $array = [];
        foreach ($attendances as $key => $attendance){
            $array[$key]['name'] = $attendance->student->name;
            $array[$key]['student_id'] = $attendance->student->id;
            $array[$key]['attendance_type_id'] = $attendance->attendance_type_id;
            $array[$key]['feedback'] = $attendance->feedback;
        }
        return $array;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $request->toArray();
    }

    public function customUpdate(Request $request){
        $date = Carbon::parse($request->date)->format('Y-m-d H:i:s');
        $generalAttendanceQuery = Attendance::where('session_id',$request->session_id)->where('original_date',$date);
        foreach ($request->attendances as $attendance)
            $generalAttendanceQuery->where('student_id',$attendance['student_id'])->update([
                'attendance_type_id' => $attendance['attendance_type_id'],
                'feedback' => $attendance['feedback']
            ]);
        return response('The attendance has been successfully updated.',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
