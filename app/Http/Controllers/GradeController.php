<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Grade::query()
            ->with([
                'enrollment.student',
                'enrollment.course',
            ]);

        if ($request->filled('search')) {
            $search = $request->string('search')
                ->trim()
                ->toString();

            $query->where(function ($gradeQuery) use ($search) {
                $gradeQuery
                    ->where(
                        'assessment_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas(
                        'enrollment.student',
                        function ($studentQuery) use ($search) {
                            $studentQuery
                                ->where(
                                    'full_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'student_number',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    )
                    ->orWhereHas(
                        'enrollment.course',
                        function ($courseQuery) use ($search) {
                            $courseQuery
                                ->where(
                                    'course_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'course_code',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
            });
        }

        if ($request->filled('assessment_type')) {
            $query->where(
                'assessment_type',
                $request->assessment_type
            );
        }

        if ($request->filled('course_id')) {
            $query->whereHas(
                'enrollment',
                fn ($enrollmentQuery) => $enrollmentQuery
                    ->where('course_id', $request->course_id)
            );
        }

        if ($request->filled('minimum_percentage')) {
            $minimumPercentage = (float) $request->minimum_percentage;

            $query->whereRaw(
                '(score / maximum_score) * 100 >= ?',
                [$minimumPercentage]
            );
        }

        $grades = $query
            ->orderByDesc('assessment_date')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $totalGrades = Grade::count();

        $averagePercentage = Grade::query()
            ->where('maximum_score', '>', 0)
            ->selectRaw(
                'AVG((score / maximum_score) * 100) as average'
            )
            ->value('average');

        $averagePercentage = round(
            (float) ($averagePercentage ?? 0),
            1
        );

        $passedCount = Grade::query()
            ->where('maximum_score', '>', 0)
            ->whereRaw(
                '(score / maximum_score) * 100 >= 60'
            )
            ->count();

        $failedCount = Grade::query()
            ->where('maximum_score', '>', 0)
            ->whereRaw(
                '(score / maximum_score) * 100 < 60'
            )
            ->count();

        $highestPercentage = Grade::query()
            ->where('maximum_score', '>', 0)
            ->selectRaw(
                'MAX((score / maximum_score) * 100) as highest'
            )
            ->value('highest');

        $highestPercentage = round(
            (float) ($highestPercentage ?? 0),
            1
        );

        $courses = Course::query()
            ->orderBy('course_code')
            ->get();

        return view('grades.index', compact(
            'grades',
            'totalGrades',
            'averagePercentage',
            'passedCount',
            'failedCount',
            'highestPercentage',
            'courses'
        ));
    }

    public function create(): View
    {
        $enrollments = Enrollment::query()
            ->with(['student', 'course'])
            ->whereHas(
                'student',
                fn ($query) => $query->where(
                    'status',
                    'Active'
                )
            )
            ->whereHas(
                'course',
                fn ($query) => $query->where(
                    'is_active',
                    true
                )
            )
            ->orderBy('course_id')
            ->get();

        return view(
            'grades.create',
            compact('enrollments')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateGrade($request);

        Grade::create($validated);

        return redirect()
            ->route('grades.index')
            ->with(
                'success',
                'Grade record created successfully.'
            );
    }

    public function show(Grade $grade): View
    {
        $grade->load([
            'enrollment.student',
            'enrollment.course',
        ]);

        return view('grades.show', compact('grade'));
    }

    public function edit(Grade $grade): View
    {
        $enrollments = Enrollment::query()
            ->with(['student', 'course'])
            ->orderBy('course_id')
            ->get();

        return view(
            'grades.edit',
            compact('grade', 'enrollments')
        );
    }

    public function update(
        Request $request,
        Grade $grade
    ): RedirectResponse {
        $validated = $this->validateGrade(
            $request,
            $grade
        );

        $grade->update($validated);

        return redirect()
            ->route('grades.index')
            ->with(
                'success',
                'Grade record updated successfully.'
            );
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $grade->delete();

        return redirect()
            ->route('grades.index')
            ->with(
                'success',
                'Grade record deleted successfully.'
            );
    }

    private function validateGrade(
        Request $request,
        ?Grade $grade = null
    ): array {
        $uniqueAssessment = Rule::unique('grades')
            ->where(
                fn ($query) => $query
                    ->where(
                        'enrollment_id',
                        $request->enrollment_id
                    )
                    ->where(
                        'assessment_type',
                        $request->assessment_type
                    )
            );

        if ($grade) {
            $uniqueAssessment->ignore($grade->id);
        }

        return $request->validate(
            [
                'enrollment_id' => [
                    'required',
                    'integer',
                    'exists:enrollments,id',
                ],

                'assessment_name' => [
                    'required',
                    'string',
                    'max:255',
                    $uniqueAssessment,
                ],

                'assessment_type' => [
                    'required',
                    Rule::in([
                        'Quiz',
                        'Assignment',
                        'Midterm',
                        'Final',
                        'Project',
                        'Other',
                    ]),
                ],

                'score' => [
                    'required',
                    'numeric',
                    'min:0',
                    'lte:maximum_score',
                ],

                'maximum_score' => [
                    'required',
                    'numeric',
                    'gt:0',
                    'max:1000',
                ],

                'weight' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:100',
                ],

                'assessment_date' => [
                    'nullable',
                    'date',
                    'before_or_equal:today',
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'assessment_name.unique' =>
                    'This assessment already exists for the selected enrollment and assessment type.',

                'score.lte' =>
                    'The score cannot be greater than the maximum score.',

                'maximum_score.gt' =>
                    'The maximum score must be greater than zero.',
            ]
        );
    }
}
