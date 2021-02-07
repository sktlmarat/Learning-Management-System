<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Block;
use App\Course;
use App\Department;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/course/{id}', function ($id) {
    return Course::find($id);
});

Route::get('/course/block/{id}', function ($id) {
    return Course::find($id)->blocks;
});

Route::get('/all-courses', function () {
    return Course::with(['department'])->orderBy('title')->get();
});

Route::get('/all-users', function () {
    return User::all();
});

Route::get('/all-departments', function () {
    return Department::all();
});

Route::post('/course-block', function (Request $request) {
    $block = new Block;
    $block->title = $request->title;
    $block->course_id = $request->course_id;
    $block->save();
    return $block;
});

Route::post('/edit-course-block', function (Request $request) {
    $block = Block::find($request->block_id);
    $block->title = $request->title;
    $block->save();
    return $block;
});

Route::post('/delete-course-block', function (Request $request) {
    $block = Block::find($request->block_id);
    $block->delete();
});

Route::post('/register-course', function (Request $request) {
    $user = User::find($request->user_id);
    $user->courses()->attach($request->course_id);
    return User::find($request->user_id);
});

Route::post('/add-user', function (Request $request) {
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->password = bcrypt($request->password);
    $user->department_id = $request->department;
    $user->save();
    return User::find($user->id);
});

Route::post('/add-course', function (Request $request) {
    $course = new Course();
    $course->title = $request->title;
    $course->abbreviation = $request->abbreviation;
    $course->department_id = $request->department;
    $course->capacity = $request->capacity;
    $course->save();
    return Course::find($course->id);
});


