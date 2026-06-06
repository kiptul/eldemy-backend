<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group Instructor Students
 *
 * APIs for instructors to view their students and their learning progress.
 */
class InstructorStudentController extends Controller
{
    /**
     * Get All Enrolled Students
     *
     * Retrieve a list of all students enrolled in the courses taught by the authenticated instructor, including their overall progress percentages.
     * 
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "student_id": 101,
     *       "student_name": "John Doe",
     *       "course_id": 5,
     *       "course_name": "Fullstack Laravel & Ionic",
     *       "progress_percentage": 75
     *     }
     *   ]
     * }
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [] // TODO: Implement real logic later
        ]);
    }

    /**
     * Get Student Detailed Progress
     *
     * Retrieve detailed progress of a specific student for a specific course, including which materials/quizzes have been completed.
     * 
     * @urlParam student_id integer required The ID of the student. Example: 101
     * @urlParam course_id integer required The ID of the course. Example: 5
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "student_name": "John Doe",
     *     "course_name": "Fullstack Laravel & Ionic",
     *     "overall_progress": 75,
     *     "materials": [
     *       { "material_id": 1, "title": "Introduction", "is_completed": true },
     *       { "material_id": 2, "title": "Setup Environment", "is_completed": true },
     *       { "material_id": 3, "title": "Advanced Topics", "is_completed": false }
     *     ],
     *     "quiz_score": null
     *   }
     * }
     */
    public function showProgress($student_id, $course_id)
    {
        return response()->json([
            'success' => true,
            'data' => null // TODO: Implement real logic later
        ]);
    }
}
