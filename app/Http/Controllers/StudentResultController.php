<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentResultController extends Controller
{
    /**
     * Display the home page with a list of courses.
     */
    public function home()
    {
        $courses = Course::with('semester')->get();
        return view('welcome', compact('courses'));
    }

    /**
     * Display the search page for a specific course.
     */
    public function index($course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return redirect()->route('home')->with('error', 'This course does not exist.');
        }
        
        return view('results.search', compact('course'));
    }

    /**
     * Process the search request for a student's results in a specific course.
     */
    public function search(Request $request, $course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return redirect()->route('home')->with('error', 'This course does not exist.');
        }

        $request->validate([
            'university_id' => 'required|exists:students,university_id',
        ], [
            'university_id.exists' => 'No results found for this University ID.'
        ]);

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
