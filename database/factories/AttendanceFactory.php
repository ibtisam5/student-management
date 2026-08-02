<?php

namespace Database\Factories;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'attendance_date' => fake()->dateTimeBetween('-4 months', 'now'),
            'status' => fake()->randomElement([
                'Present',
                'Present',
                'Present',
                'Present',
                'Late',
                'Absent',
                'Excused',
            ]),
            'notes' => null,
        ];
    }
}
