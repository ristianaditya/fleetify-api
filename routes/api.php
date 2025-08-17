<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('departements', DepartementController::class);

Route::apiResource('employees', EmployeeController::class);

Route::post('attendance', [AttendanceController::class, 'store']);
Route::put('attendance', [AttendanceController::class, 'update']);
Route::get('attendance/history/{employeeId}', [AttendanceController::class, 'history']);
Route::get('attendance/today', [AttendanceController::class, 'today']);

Route::get('/attendance/report', [AttendanceReportController::class, 'index']);

