<?php

namespace App\Http\Controllers;

use App\Models\AiAnalysis;
use App\Models\Student;
use App\Services\StudentAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiAnalysisController extends Controller
{
    public function __construct(
        private readonly StudentAnalysisService $analysisService
    ) {
    }

    public function index(Request $request): View
    {
        $query = AiAnalysis::query()
            ->with('student')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')
                ->trim()
                ->toString();

            $query->whereHas(
                'student',
                function ($studentQuery) use ($search) {
                    $studentQuery
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhere(
                            'student_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'major',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }

        if ($request->filled('analysis_type')) {
            $query->where(
                'analysis_type',
                $request->analysis_type
            );
        }

        $analyses = $query
            ->paginate(10)
            ->withQueryString();

        $totalAnalyses = AiAnalysis::count();

        $analyzedStudents = AiAnalysis::query()
            ->distinct('student_id')
            ->count('student_id');

        $latestAnalysis = AiAnalysis::query()
            ->latest()
            ->first();

        return view('ai-analyses.index', compact(
            'analyses',
            'totalAnalyses',
            'analyzedStudents',
            'latestAnalysis'
        ));
    }

    public function create(Request $request): View
    {
        $students = Student::query()
            ->where('status', 'Active')
            ->orderBy('full_name')
            ->get();

        $selectedStudentId = $request->integer('student_id');

        return view('ai-analyses.create', compact(
            'students',
            'selectedStudentId'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'analysis_type' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $student = Student::query()
            ->findOrFail($validated['student_id']);

        $result = $this->analysisService->analyze($student);

        $inputSummary = sprintf(
            'Student: %s | Student Number: %s | Major: %s | Attendance Rate: %.2f%% | Average Grade: %.2f%% | Risk Level: %s',
            $student->full_name,
            $student->student_number,
            $student->major,
            $result['attendance_rate'],
            $result['average_grade'],
            $result['risk_level']
        );

        $analysis = AiAnalysis::create([
            'student_id' => $student->id,
            'analysis_type' => $validated['analysis_type'],
            'input_summary' => $inputSummary,
            'analysis' => $result['analysis'],
            'recommendations' => $result['recommendations'],
            'provider' => 'Internal Rules Engine',
            'model' => 'Student Analysis Engine v1',
        ]);

        return redirect()
            ->route('ai-analyses.show', $analysis)
            ->with(
                'success',
                'Student analysis generated successfully.'
            );
    }

    public function show(AiAnalysis $aiAnalysis): View
    {
        $aiAnalysis->load([
            'student.enrollments.grades',
            'student.enrollments.attendances',
            'student.enrollments.course',
        ]);

        $student = $aiAnalysis->student;

        $currentResult = $this->analysisService->analyze($student);

        $enrollmentsCount = $student->enrollments->count();

        $gradesCount = $student->enrollments
            ->flatMap(fn ($enrollment) => $enrollment->grades)
            ->count();

        $attendanceRecordsCount = $student->enrollments
            ->flatMap(fn ($enrollment) => $enrollment->attendances)
            ->count();

        return view('ai-analyses.show', compact(
            'aiAnalysis',
            'currentResult',
            'enrollmentsCount',
            'gradesCount',
            'attendanceRecordsCount'
        ));
    }

    public function regenerate(
        AiAnalysis $aiAnalysis
    ): RedirectResponse {
        $student = $aiAnalysis->student;

        $result = $this->analysisService->analyze($student);

        $inputSummary = sprintf(
            'Student: %s | Student Number: %s | Major: %s | Attendance Rate: %.2f%% | Average Grade: %.2f%% | Risk Level: %s',
            $student->full_name,
            $student->student_number,
            $student->major,
            $result['attendance_rate'],
            $result['average_grade'],
            $result['risk_level']
        );

        $aiAnalysis->update([
            'input_summary' => $inputSummary,
            'analysis' => $result['analysis'],
            'recommendations' => $result['recommendations'],
            'provider' => 'Internal Rules Engine',
            'model' => 'Student Analysis Engine v1',
        ]);

        return redirect()
            ->route('ai-analyses.show', $aiAnalysis)
            ->with(
                'success',
                'Student analysis regenerated successfully.'
            );
    }

    public function destroy(
        AiAnalysis $aiAnalysis
    ): RedirectResponse {
        $aiAnalysis->delete();

        return redirect()
            ->route('ai-analyses.index')
            ->with(
                'success',
                'Analysis deleted successfully.'
            );
    }
}
