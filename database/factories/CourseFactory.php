<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    public function definition(): array
    {
        $courses = [
            ['COMP101', 'Introduction to Programming'],
            ['COMP202', 'Data Structures'],
            ['COMP305', 'Database Systems'],
            ['NETW210', 'Computer Networks'],
            ['CYBR310', 'Cybersecurity Fundamentals'],
            ['SOFT220', 'Software Engineering'],
            ['MATH201', 'Discrete Mathematics'],
            ['AI401', 'Artificial Intelligence'],
            ['CLOUD320', 'Cloud Computing'],
            ['WEB230', 'Web Application Development'],
        ];

        [$code, $name] = fake()->unique()->randomElement($courses);

        return [
            'course_code' => $code,
            'course_name' => $name,
            'description' => fake()->sentence(12),
            'credit_hours' => fake()->randomElement([3, 3, 3, 4]),
            'is_active' => true,
        ];
    }
}
