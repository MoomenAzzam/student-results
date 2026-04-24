<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentResultController extends Controller
{
    /**
     * Display the search page for a specific course.
     */
    public function index($course_id)
    {
        $course = Course::findOrFail($course_id);
        
        return view('results.search', compact('course'));
    }

    /**
     * Process the search request for a student's results in a specific course.
     */
    public function search(Request $request, $course_id)
    {
        $request->validate([
            'university_id' => 'required|exists:students,university_id',
        ]);

        $course = Course::findOrFail($course_id);
        $university_id = $request->input('university_id');

        // Fetch activities matching the student (via university_id) and the course
        $results = Activity::with('student')
            ->whereHas('student', function ($query) use ($university_id) {
                $query->where('university_id', $university_id);
            })
            ->where('course_id', $course_id)
            ->get();

        return view('results.search', [
            'course' => $course,
            'results' => $results,
            'university_id' => $university_id,
        ]);
    }
}
