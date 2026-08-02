<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Enrollment::query()
            ->with(['student', 'course']);

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();

            $query->where(function ($enrollmentQuery) use ($search) {
                $enrollmentQuery
                    ->whereHas('student', function ($studentQuery) use ($search) {
                        $studentQuery
                            ->where('full_name', 'like', "%{$search}%")
                            ->orWhere('student_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('course', function ($courseQuery) use ($search) {
                        $courseQuery
                            ->where('course_name', 'like', "%{$search}%")
                            ->orWhere('course_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $enrollments = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $totalEnrollments = Enrollment::count();
        $totalStudents = Enrollment::distinct('student_id')->count('student_id');
        $totalCourses = Enrollment::distinct('course_id')->count('course_id');

        return view('enrollments.index', compact(
            'enrollments',
            'totalEnrollments',
            'totalStudents',
            'totalCourses'
        ));
    }

    public function create(): View
    {
        $students = Student::query()
            ->where('status', 'Active')
            ->orderBy('full_name')
            ->get();

        $courses = Course::query()
            ->where('is_active', true)
            ->orderBy('course_code')
            ->get();

        return view('enrollments.create', compact('students', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
                Rule::unique('enrollments')->where(
                    fn ($query) => $query
                        ->where('course_id', $request->course_id)
                        ->where('semester', $request->semester)
                        ->where('academic_year', $request->academic_year)
                ),
            ],
            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
            ],
            'semester' => [
                'required',
                Rule::in(['Fall', 'Spring', 'Summer']),
            ],
            'academic_year' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],
            'enrolled_at' => [
                'nullable',
                'date',
            ],
        ], [
            'student_id.unique' =>
                'This student is already enrolled in this course for the selected semester and year.',
        ]);

        Enrollment::create($validated);

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'Student enrolled successfully.');
    }

    public function show(Enrollment $enrollment): View
    {
        $enrollment->load([
            'student',
            'course',
            'attendances',
            'grades',
        ]);

        return view('enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment): View
    {
        $students = Student::query()
            ->orderBy('full_name')
            ->get();

        $courses = Course::query()
            ->orderBy('course_code')
            ->get();

        return view('enrollments.edit', compact(
            'enrollment',
            'students',
            'courses'
        ));
    }

    public function update(
        Request $request,
        Enrollment $enrollment
    ): RedirectResponse {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
                Rule::unique('enrollments')
                    ->ignore($enrollment->id)
                    ->where(
                        fn ($query) => $query
                            ->where('course_id', $request->course_id)
                            ->where('semester', $request->semester)
                            ->where('academic_year', $request->academic_year)
                    ),
            ],
            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
            ],
            'semester' => [
                'required',
                Rule::in(['Fall', 'Spring', 'Summer']),
            ],
            'academic_year' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],
            'enrolled_at' => [
                'nullable',
                'date',
            ],
        ], [
            'student_id.unique' =>
                'This student is already enrolled in this course for the selected semester and year.',
        ]);

        $enrollment->update($validated);

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }
}
