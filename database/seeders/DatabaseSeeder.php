<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 3 semesters
        $semesters = Semester::factory(3)->create();

        // Create 10 courses distributed among the semesters
        $courses = collect();
        foreach ($semesters as $semester) {
            $courses = $courses->concat(
                Course::factory(rand(2, 4))->create(['semester_id' => $semester->id])
            );
        }
        
        // Ensure we have at least 10 courses total if the random distribution didn't hit it
        if ($courses->count() < 10) {
            $courses = $courses->concat(
                Course::factory(10 - $courses->count())->create([
                    'semester_id' => $semesters->random()->id
                ])
            );
        }

        // Create 50 students
        $students = Student::factory(50)->create();

        // Activity types
        $activityTypes = ['Midterm', 'Final', 'Homework', 'Project', 'Quiz'];

        // Generate activities for students in courses
        foreach ($students as $student) {
            // Each student takes 3-5 random courses
            $enrolledCourses = $courses->random(rand(3, 5));
            
            foreach ($enrolledCourses as $course) {
                // For each course, generate 2-4 activities
                $selectedActivities = (array) array_rand(array_flip($activityTypes), rand(2, 4));
                
                foreach ($selectedActivities as $type) {
                    Activity::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'semester_id' => $course->semester_id,
                        'activity_type' => $type,
                        'score' => rand(40, 100), // More realistic passing scores mostly
                    ]);
                }
            }
        }

        // Also create a default user for login if it doesn't exist
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
