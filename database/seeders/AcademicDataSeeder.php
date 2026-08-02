<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Seeder;

class AcademicDataSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::factory()
            ->count(25)
            ->create();

        $courses = Course::factory()
            ->count(10)
            ->create();

        foreach ($students as $student) {
            $selectedCourses = $courses
                ->random(random_int(2, 4));

            foreach ($selectedCourses as $course) {
                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'semester' => 'Fall',
                    'academic_year' => 2026,
                    'enrolled_at' => now()->subMonths(
                        random_int(1, 4)
                    ),
                ]);

                for ($day = 0; $day < 12; $day++) {
                    Attendance::factory()->create([
                        'enrollment_id' => $enrollment->id,
                        'attendance_date' => now()
                            ->subWeeks(12 - $day)
                            ->toDateString(),
                    ]);
                }

                Grade::factory()->count(4)->create([
                    'enrollment_id' => $enrollment->id,
                ]);
            }
        }
    }
}
