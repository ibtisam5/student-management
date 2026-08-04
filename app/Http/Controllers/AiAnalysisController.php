<?php

namespace App\Http\Controllers;

use App\Models\AiAnalysis;
use App\Models\Student;
use App\Services\StudentAnalysisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            $search = trim(
                $request->string('search')->toString()
            );

            $query->whereHas(
                'student',
                function ($studentQuery) use ($search): void {
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
                $request->string('analysis_type')->toString()
            );
        }

        $analyses = $query
            ->paginate(10)
            ->withQueryString();

        $totalAnalyses = AiAnalysis::count();

        $analyzedStudents = AiAnalysis::query()
            ->distinct()
            ->count('student_id');

        $latestAnalysis = AiAnalysis::query()
            ->with('student')
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
                'in:Comprehensive Analysis,Academic Performance,Attendance Risk',
            ],
        ]);

        $student = Student::query()
            ->findOrFail($validated['student_id']);

        $result = $this->analysisService->analyze($student);

        $analysis = AiAnalysis::create(
            $this->analysisPayload(
                $student,
                $validated['analysis_type'],
                $result
            )
        );

        return redirect()
            ->route('ai-analyses.show', $analysis)
            ->with(
                'success',
                'Structured student analysis generated successfully.'
            );
    }

    public function show(AiAnalysis $aiAnalysis): View
    {
        $aiAnalysis->load([
            'student.enrollments.course',
            'student.enrollments.grades',
            'student.enrollments.attendances',
        ]);

        return view(
            'ai-analyses.show',
            compact('aiAnalysis')
        );
    }

    public function regenerate(
        AiAnalysis $aiAnalysis
    ): RedirectResponse {
        $student = $aiAnalysis->student;

        $result = $this->analysisService->analyze($student);

        $aiAnalysis->update(
            $this->analysisPayload(
                $student,
                $aiAnalysis->analysis_type,
                $result
            )
        );

        return redirect()
            ->route('ai-analyses.show', $aiAnalysis)
            ->with(
                'success',
                'Student analysis regenerated successfully.'
            );
    }

    public function downloadPdf(AiAnalysis $aiAnalysis)
    {
        $aiAnalysis->load('student');

        $studentName = $aiAnalysis->student?->full_name
            ?? 'student';

        $filename = Str::slug($studentName)
            . '-academic-intelligence-report.pdf';

        return Pdf::loadView(
            'ai-analyses.pdf',
            [
                'analysis' => $aiAnalysis,
            ]
        )
            ->setPaper('a4', 'portrait')
            ->download($filename);
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

    private function analysisPayload(
        Student $student,
        string $analysisType,
        array $result
    ): array {
        $inputSummary = sprintf(
            'Student: %s | Student Number: %s | Major: %s | Average Grade: %.1f%% | Attendance Rate: %.1f%% | Risk: %s',
            $student->full_name,
            $student->student_number,
            $student->major,
            $result['average_grade'],
            $result['attendance_rate'],
            $result['risk_level']
        );

        return [
            'student_id' => $student->id,
            'analysis_type' => $analysisType,
            'input_summary' => $inputSummary,
            'analysis' => $result['analysis'],
            'recommendations' => $result['recommendations'],
            'performance_summary' =>
                $result['performance_summary'],
            'strengths' => $result['strengths'],
            'weaknesses' => $result['weaknesses'],
            'risk_level' => $result['risk_level'],
            'prediction' => $result['prediction'],
            'metrics' => $result['metrics'],
            'provider' => 'Internal Rules Engine',
            'model' => 'Academic Recommendation Engine v2',
        ];
    }
}
