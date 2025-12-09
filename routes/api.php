<?php

use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('schools', [\App\Http\Controllers\SchoolController::class,'register']);
// Route::apiResource('teachers', \App\Http\Controllers\TeacherController::class);

Route::middleware([
    // 'auth:sanctum', // Assuming auth is handled or will be handled globally or per route
    InitializeTenancyByDomain::class,
])->group(function () {

    // Public or semi-public routes
    Route::post('/auth/teacher/register', [\App\Http\Controllers\Auth\TeacherAuthController::class, 'register']);
    Route::post('auth/student/register', [\App\Http\Controllers\Auth\StudentAuthController::class, 'register']);
    Route::post('/auth/admin/login', [\App\Http\Controllers\Auth\AdminAuthController::class, 'login']);

    // Protected Routes
    // Note: Add 'auth:sanctum' to routes that need authentication
    Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource('/grades', GradeController::class);
        Route::apiResource('/academic-session', AcademicSessionController::class);
        Route::apiResource('classrooms', \App\Http\Controllers\ClassRoomController::class);

        // Term Routes
        Route::apiResource('terms', TermController::class);
        Route::post('terms/{term}/release-results', [TermController::class, 'releaseResults']);
        Route::get('terms/{term}/countdown', [TermController::class, 'getResetCountdown']);

        // Payment Routes
        Route::post('payments', [PaymentController::class, 'store']); // Admin/Accountant
        Route::get('payments/status', [PaymentController::class, 'checkStatus']); // Student/Admin

        // School Settings
        Route::put('schools/{id}/settings', [SchoolController::class, 'updateSettings']);

        // Role Management
        Route::apiResource('roles', RoleController::class);

        // Routes requiring Payment
        Route::middleware('check.payment')->group(function () {
             // Add routes that require payment here, e.g., viewing results
             // Route::get('results', [ResultController::class, 'index']);
             Route::get('my-portal', function() {
                 return response()->json(['message' => 'Welcome to the student portal']);
             });
        });
    });

});
