<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_number' => fake()->unique()->numerify('#######'),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('9#######'),
            'major' => fake()->randomElement([
                'Computer Engineering',
                'Information Systems',
                'Cybersecurity',
                'Software Engineering',
                'Data Science',
            ]),
            'academic_year' => fake()->numberBetween(1, 4),
            'status' => fake()->randomElement([
                'Active',
                'Active',
                'Active',
                'Inactive',
            ]),
        ];
    }
}
