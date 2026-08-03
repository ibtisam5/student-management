<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Main statistics
        |--------------------------------------------------------------------------
        */

        $totalStudents = Student::count();

        $activeStudents = Student::where(
            'status',
            'Active'
        )->count();

        $totalCourses = Course::count();

        $activeCourses = Course::where(
            'is_active',
            true
        )->count();

        $totalEnrollments = Enrollment::count();

        $totalAttendanceRecords = Attendance::count();

        $attendedRecords = Attendance::whereIn(
            'status',
            ['Present', 'Late']
        )->count();

        $attendanceRate = $totalAttendanceRecords > 0
            ? round(
                ($attendedRecords / $totalAttendanceRecords) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Grade statistics
        |--------------------------------------------------------------------------
        */

        $allGrades = Grade::query()
            ->where('maximum_score', '>', 0)
            ->get();

        $averageGrade = $allGrades->isNotEmpty()
            ? round(
                $allGrades->average(
                    fn (Grade $grade) => $grade->percentage()
                ),
                1
            )
            : 0;

        $passedGrades = $allGrades
            ->filter(
                fn (Grade $grade) =>
                    $grade->percentage() >= 60
            )
            ->count();

        $passRate = $allGrades->isNotEmpty()
            ? round(
                ($passedGrades / $allGrades->count()) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Attendance distribution
        |--------------------------------------------------------------------------
        */

        $attendanceDistribution = [
            'Present' => Attendance::where(
                'status',
                'Present'
            )->count(),

            'Absent' => Attendance::where(
                'status',
                'Absent'
            )->count(),

            'Late' => Attendance::where(
                'status',
                'Late'
            )->count(),

            'Excused' => Attendance::where(
                'status',
                'Excused'
            )->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Students by major
        |--------------------------------------------------------------------------
        */

        $studentsByMajor = Student::query()
            ->selectRaw('major, COUNT(*) as total')
            ->groupBy('major')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Course performance
        |--------------------------------------------------------------------------
        */

        $coursePerformance = Course::query()
            ->with('enrollments.grades')
            ->get()
            ->map(function (Course $course) {
                $grades = $course->enrollments
                    ->flatMap(
                        fn ($enrollment) =>
                            $enrollment->grades
                    )
                    ->filter(
                        fn (Grade $grade) =>
                            (float) $grade->maximum_score > 0
                    );

                $average = $grades->isNotEmpty()
                    ? round(
                        $grades->average(
                            fn (Grade $grade) =>
                                $grade->percentage()
                        ),
                        1
                    )
                    : 0;

                return [
                    'course_code' => $course->course_code,
                    'course_name' => $course->course_name,
                    'average' => $average,
                    'enrollments' =>
                        $course->enrollments->count(),
                ];
            })
            ->sortByDesc('average')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Student performance
        |--------------------------------------------------------------------------
        */

        $studentPerformance = Student::query()
            ->with([
                'enrollments.grades',
                'enrollments.attendances',
            ])
            ->get()
            ->map(function (Student $student) {
                $enrollments = $student->enrollments;

                $grades = $enrollments
                    ->flatMap(
                        fn ($enrollment) =>
                            $enrollment->grades
                    )
                    ->filter(
                        fn (Grade $grade) =>
                            (float) $grade->maximum_score > 0
                    );

                $averageGrade = $grades->isNotEmpty()
                    ? round(
                        $grades->average(
                            fn (Grade $grade) =>
                                $grade->percentage()
                        ),
                        1
                    )
                    : 0;

                $attendances = $enrollments
                    ->flatMap(
                        fn ($enrollment) =>
                            $enrollment->attendances
                    );

                $attended = $attendances
                    ->whereIn(
                        'status',
                        ['Present', 'Late']
                    )
                    ->count();

                $attendanceRate = $attendances->isNotEmpty()
                    ? round(
                        ($attended / $attendances->count())
                        * 100,
                        1
                    )
                    : 0;

                return [
                    'id' => $student->id,
                    'student_number' =>
                        $student->student_number,
                    'full_name' => $student->full_name,
                    'major' => $student->major,
                    'average_grade' => $averageGrade,
                    'attendance_rate' => $attendanceRate,
                    'courses_count' => $enrollments->count(),
                ];
            });

        $topStudents = $studentPerformance
            ->filter(
                fn ($student) =>
                    $student['courses_count'] > 0
            )
            ->sortByDesc('average_grade')
            ->take(5)
            ->values();

        $atRiskStudents = $studentPerformance
            ->filter(
                fn ($student) =>
                    $student['courses_count'] > 0
                    && (
                        $student['average_grade'] < 60
                        || $student['attendance_rate'] < 75
                    )
            )
            ->sortBy('average_grade')
            ->take(6)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Recent records
        |--------------------------------------------------------------------------
        */

        $recentStudents = Student::query()
            ->latest()
            ->take(5)
            ->get();

        $recentEnrollments = Enrollment::query()
            ->with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalStudents',
            'activeStudents',
            'totalCourses',
            'activeCourses',
            'totalEnrollments',
            'attendanceRate',
            'averageGrade',
            'passRate',
            'attendanceDistribution',
            'studentsByMajor',
            'coursePerformance',
            'topStudents',
            'atRiskStudents',
            'recentStudents',
            'recentEnrollments'
        ));
    }
}
