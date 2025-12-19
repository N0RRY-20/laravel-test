<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\WalletTransactionController;
use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\BehaviorRecordController;
use App\Http\Controllers\Api\TahfidzRecordController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TeacherSubjectController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\GradeController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // HRIS
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('attendances', AttendanceController::class)->except(['update', 'destroy']);
    Route::post('attendances/clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('attendances/clock-out', [AttendanceController::class, 'clockOut']);
    Route::apiResource('payrolls', PayrollController::class);

    // Finance
    Route::apiResource('wallet-transactions', WalletTransactionController::class)->only(['index', 'store', 'show']);
    Route::apiResource('billings', BillingController::class);
    Route::post('billings/{id}/pay', [BillingController::class, 'pay']);

    // Boarding
    Route::apiResource('behavior-records', BehaviorRecordController::class)->only(['index', 'store', 'show']);
    Route::apiResource('tahfidz-records', TahfidzRecordController::class)->only(['index', 'store', 'show']);

    // Academic
    Route::apiResource('classrooms', ClassroomController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('teacher-subjects', TeacherSubjectController::class)->except(['update']);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('grades', GradeController::class)->only(['index', 'store', 'show']);
});
