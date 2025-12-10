<?php

use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\GradeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('schools', [\App\Http\Controllers\SchoolController::class,'register']);
// Route::apiResource('teachers', \App\Http\Controllers\TeacherController::class);

Route::middleware([
    // 'auth:sanctum',
    InitializeTenancyByDomain::class,
])->group(function () {
    Route::apiResource('/grades', GradeController::class);
    Route::apiResource('/academic-session', AcademicSessionController::class);
    Route::post('/auth/teacher/register', [\App\Http\Controllers\Auth\TeacherAuthController::class, 'register']);
    Route::post('auth/student/register', [\App\Http\Controllers\Auth\StudentAuthController::class, 'register']);
    Route::apiResource('classrooms', \App\Http\Controllers\ClassRoomController::class);
    Route::post('/auth/admin/login', [\App\Http\Controllers\Auth\AdminAuthController::class, 'login']);

});


