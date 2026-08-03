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

        $activeStudents = Student::query()
            ->where('status', 'Active')
            ->count();

        $totalCourses = Course::count();

        $activeCourses = Course::query()
            ->where('is_active', true)
            ->count();

        $totalEnrollments = Enrollment::count();

        $totalAttendanceRecords = Attendance::count();

        $totalGrades = Grade::count();

        /*
        |--------------------------------------------------------------------------
        | Attendance statistics
        |--------------------------------------------------------------------------
        */

        $attendedRecords = Attendance::query()
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        $attendanceRate = $totalAttendanceRecords > 0
            ? round(
                ($attendedRecords / $totalAttendanceRecords) * 100,
                1
            )
            : 0;

        $attendanceDistribution = [
            'Present' => Attendance::query()
                ->where('status', 'Present')
                ->count(),

            'Absent' => Attendance::query()
                ->where('status', 'Absent')
                ->count(),

            'Late' => Attendance::query()
                ->where('status', 'Late')
                ->count(),

            'Excused' => Attendance::query()
                ->where('status', 'Excused')
                ->count(),
        ];

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
                    fn (Grade $grade): float =>
                        $grade->percentage()
                ),
                1
            )
            : 0;

        $passedGrades = $allGrades
            ->filter(
                fn (Grade $grade): bool =>
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
        | Grade distribution for charts
        |--------------------------------------------------------------------------
        */

        $gradeDistribution = [
            '90–100' => $allGrades
                ->filter(
                    fn (Grade $grade): bool =>
                        $grade->percentage() >= 90
                )
                ->count(),

            '80–89' => $allGrades
                ->filter(function (Grade $grade): bool {
                    $percentage = $grade->percentage();

                    return $percentage >= 80
                        && $percentage < 90;
                })
                ->count(),

            '70–79' => $allGrades
                ->filter(function (Grade $grade): bool {
                    $percentage = $grade->percentage();

                    return $percentage >= 70
                        && $percentage < 80;
                })
                ->count(),

            '60–69' => $allGrades
                ->filter(function (Grade $grade): bool {
                    $percentage = $grade->percentage();

                    return $percentage >= 60
                        && $percentage < 70;
                })
                ->count(),

            'Below 60' => $allGrades
                ->filter(
                    fn (Grade $grade): bool =>
                        $grade->percentage() < 60
                )
                ->count(),
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
            ->map(function (Course $course): array {
                $grades = $course->enrollments
                    ->flatMap(
                        fn (Enrollment $enrollment) =>
                            $enrollment->grades
                    )
                    ->filter(
                        fn (Grade $grade): bool =>
                            (float) $grade->maximum_score > 0
                    );

                $average = $grades->isNotEmpty()
                    ? round(
                        $grades->average(
                            fn (Grade $grade): float =>
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
            ->map(function (Student $student): array {
                $enrollments = $student->enrollments;

                $grades = $enrollments
                    ->flatMap(
                        fn (Enrollment $enrollment) =>
                            $enrollment->grades
                    )
                    ->filter(
                        fn (Grade $grade): bool =>
                            (float) $grade->maximum_score > 0
                    );

                $studentAverageGrade = $grades->isNotEmpty()
                    ? round(
                        $grades->average(
                            fn (Grade $grade): float =>
                                $grade->percentage()
                        ),
                        1
                    )
                    : 0;

                $attendances = $enrollments
                    ->flatMap(
                        fn (Enrollment $enrollment) =>
                            $enrollment->attendances
                    );

                $attended = $attendances
                    ->whereIn('status', ['Present', 'Late'])
                    ->count();

                $studentAttendanceRate =
                    $attendances->isNotEmpty()
                        ? round(
                            (
                                $attended
                                / $attendances->count()
                            ) * 100,
                            1
                        )
                        : 0;

                return [
                    'id' => $student->id,

                    'student_number' =>
                        $student->student_number,

                    'full_name' =>
                        $student->full_name,

                    'major' =>
                        $student->major,

                    'average_grade' =>
                        $studentAverageGrade,

                    'attendance_rate' =>
                        $studentAttendanceRate,

                    'courses_count' =>
                        $enrollments->count(),
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | Top and at-risk students
        |--------------------------------------------------------------------------
        */

        $topStudents = $studentPerformance
            ->filter(
                fn (array $student): bool =>
                    $student['courses_count'] > 0
            )
            ->sortByDesc('average_grade')
            ->take(5)
            ->values();

        $atRiskStudents = $studentPerformance
            ->filter(
                fn (array $student): bool =>
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
            'totalAttendanceRecords',
            'totalGrades',
            'attendanceRate',
            'averageGrade',
            'passRate',
            'attendanceDistribution',
            'gradeDistribution',
            'studentsByMajor',
            'coursePerformance',
            'topStudents',
            'atRiskStudents',
            'recentStudents',
            'recentEnrollments'
        ));
    }
}
