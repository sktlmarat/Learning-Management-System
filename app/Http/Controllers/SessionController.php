<?php

namespace App\Http\Controllers;

use App\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SessionController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->date = Carbon::parse($request->date)->format('Y-m-d H:i:s');
        $request->end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        return Session::create([
            'date' => $request->date,
            'duration' => $request->duration,
            'end_date' => $request->end_date,
            'session_days' => $request->session_days,
            'frequency' => $request->frequency,
            'description' => $request->description,
            'course_id' => $request->course_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        return $session;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Session $session
     * @return \Illuminate\Http\Response
     */
    public function edit(Session $session)
    {
        $session->date = Carbon::parse($session->date)->format('Y-m-d\TH:i');
        $session->end_date = Carbon::parse($session->end_date)->format('Y-m-d\TH:i');
        $session->session_days = explode(',',$session->session_days);
        return $session;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Session  $courseSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Session $session)
    {
        $request->date = Carbon::parse($request->date)->format('Y-m-d H:i:s');
        $request->end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $session->update($request->toArray());
        return $session;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Session  $courseSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        $session->delete();
        return response('The session has been successfully deleted.',200);
    }
}
