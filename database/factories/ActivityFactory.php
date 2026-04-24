<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'semester_id' => function (array $attributes) {
                return Course::find($attributes['course_id'])->semester_id;
            },
            'activity_type' => $this->faker->randomElement(['Midterm', 'Final', 'Homework', 'Project', 'Quiz']),
            'score' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
