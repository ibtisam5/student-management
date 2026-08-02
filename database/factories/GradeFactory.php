<?php

namespace Database\Factories;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    public function definition(): array
    {
        $maximum = fake()->randomElement([10, 20, 30, 40, 100]);

        return [
            'enrollment_id' => Enrollment::factory(),
            'assessment_name' => fake()->randomElement([
                'Quiz 1',
                'Quiz 2',
                'Assignment',
                'Midterm Exam',
                'Final Exam',
                'Project',
            ]),
            'assessment_type' => fake()->randomElement([
                'Quiz',
                'Assignment',
                'Midterm',
                'Final',
                'Project',
            ]),
            'score' => fake()->numberBetween(
                (int) round($maximum * 0.45),
                $maximum
            ),
            'maximum_score' => $maximum,
            'weight' => fake()->randomElement([10, 15, 20, 25, 30, 40]),
            'assessment_date' => fake()->dateTimeBetween('-4 months', 'now'),
            'notes' => null,
        ];
    }
}
