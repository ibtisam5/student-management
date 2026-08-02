<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('students.index');
});

Route::get('/students/export/csv', [StudentController::class, 'exportCsv'])
    ->name('students.export.csv');

Route::resource('students', StudentController::class);
Route::resource('courses', CourseController::class);
Route::resource('enrollments', EnrollmentController::class);
