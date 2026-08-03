<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Attendance::query()
            ->with([
                'enrollment.student',
                'enrollment.course',
            ]);

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();

            $query->whereHas(
                'enrollment',
                function ($enrollmentQuery) use ($search) {
                    $enrollmentQuery
                        ->whereHas(
                            'student',
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
                            'course',
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
                }
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('attendance_date')) {
            $query->whereDate(
                'attendance_date',
                $request->attendance_date
            );
        }

        if ($request->filled('course_id')) {
            $query->whereHas(
                'enrollment',
                fn ($enrollmentQuery) => $enrollmentQuery
                    ->where('course_id', $request->course_id)
            );
        }

        $attendances = $query
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $totalRecords = Attendance::count();
        $presentCount = Attendance::where('status', 'Present')->count();
        $absentCount = Attendance::where('status', 'Absent')->count();
        $lateCount = Attendance::where('status', 'Late')->count();
        $excusedCount = Attendance::where('status', 'Excused')->count();

        $attendanceRate = $totalRecords > 0
            ? round(
                (($presentCount + $lateCount) / $totalRecords) * 100,
                1
            )
            : 0;

        $courses = \App\Models\Course::query()
            ->orderBy('course_code')
            ->get();

        return view('attendances.index', compact(
            'attendances',
            'totalRecords',
            'presentCount',
            'absentCount',
            'lateCount',
            'excusedCount',
            'attendanceRate',
            'courses'
        ));
    }

    public function create(): View
    {
        $enrollments = Enrollment::query()
            ->with(['student', 'course'])
            ->whereHas(
                'student',
                fn ($query) => $query->where('status', 'Active')
            )
            ->whereHas(
                'course',
                fn ($query) => $query->where('is_active', true)
            )
            ->orderBy('course_id')
            ->get();

        return view(
            'attendances.create',
            compact('enrollments')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'enrollment_id' => [
                    'required',
                    'integer',
                    'exists:enrollments,id',
                    Rule::unique('attendances')
                        ->where(
                            fn ($query) => $query->where(
                                'attendance_date',
                                $request->attendance_date
                            )
                        ),
                ],

                'attendance_date' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                ],

                'status' => [
                    'required',
                    Rule::in([
                        'Present',
                        'Absent',
                        'Late',
                        'Excused',
                    ]),
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'enrollment_id.unique' =>
                    'Attendance has already been recorded for this student, course and date.',
            ]
        );

        Attendance::create($validated);

        return redirect()
            ->route('attendances.index')
            ->with(
                'success',
                'Attendance record created successfully.'
            );
    }

    public function show(Attendance $attendance): View
    {
        $attendance->load([
            'enrollment.student',
            'enrollment.course',
        ]);

        return view(
            'attendances.show',
            compact('attendance')
        );
    }

    public function edit(Attendance $attendance): View
    {
        $enrollments = Enrollment::query()
            ->with(['student', 'course'])
            ->orderBy('course_id')
            ->get();

        return view(
            'attendances.edit',
            compact('attendance', 'enrollments')
        );
    }

    public function update(
        Request $request,
        Attendance $attendance
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'enrollment_id' => [
                    'required',
                    'integer',
                    'exists:enrollments,id',
                    Rule::unique('attendances')
                        ->ignore($attendance->id)
                        ->where(
                            fn ($query) => $query->where(
                                'attendance_date',
                                $request->attendance_date
                            )
                        ),
                ],

                'attendance_date' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                ],

                'status' => [
                    'required',
                    Rule::in([
                        'Present',
                        'Absent',
                        'Late',
                        'Excused',
                    ]),
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'enrollment_id.unique' =>
                    'Attendance has already been recorded for this student, course and date.',
            ]
        );

        $attendance->update($validated);

        return redirect()
            ->route('attendances.index')
            ->with(
                'success',
                'Attendance record updated successfully.'
            );
    }

    public function destroy(
        Attendance $attendance
    ): RedirectResponse {
        $attendance->delete();

        return redirect()
            ->route('attendances.index')
            ->with(
                'success',
                'Attendance record deleted successfully.'
            );
    }
}
