<?php
use App\Http\Controllers\AiAnalysisController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [
    DashboardController::class,
    'index',
])->name('dashboard');

Route::get(
    '/students/export/csv',
    [StudentController::class, 'exportCsv']
)->name('students.export.csv');

Route::resource('students', StudentController::class);
Route::resource('courses', CourseController::class);
Route::resource('enrollments', EnrollmentController::class);
Route::resource('attendances', AttendanceController::class);
Route::resource('grades', GradeController::class);
Route::post(
    '/ai-analyses/{aiAnalysis}/regenerate',
    [AiAnalysisController::class, 'regenerate']
)->name('ai-analyses.regenerate');
Route::post(
    '/ai-analyses/{aiAnalysis}/regenerate',
    [AiAnalysisController::class, 'regenerate']
)->name('ai-analyses.regenerate');

Route::get(
    '/ai-analyses/{aiAnalysis}/pdf',
    [AiAnalysisController::class, 'downloadPdf']
)->name('ai-analyses.pdf');

Route::resource(
    'ai-analyses',
    AiAnalysisController::class
)->only([
    'index',
    'create',
    'store',
    'show',
    'destroy',
]);
