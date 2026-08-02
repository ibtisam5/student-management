<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'semester' => fake()->randomElement([
                'Fall',
                'Spring',
                'Summer',
            ]),
            'academic_year' => 2026,
            'enrolled_at' => fake()->dateTimeBetween('-8 months', 'now'),
        ];
    }
}
