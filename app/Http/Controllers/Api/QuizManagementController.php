<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizManagementController extends Controller
{
    private function checkOwnership($course_id)
    {
        if (Auth::user()->role === 'student') {
            abort(403, 'Akses ditolak. Anda bukan Instruktur.');
        }

        $course = Course::where('id', $course_id)->where('instructor_id', Auth::id())->first();
        if (!$course) {
            abort(403, 'Anda tidak memiliki hak akses untuk kursus ini.');
        }
        return $course;
    }

    /**
     * Show quiz for a specific material
     */
    public function show($course_id, Request $request)
    {
        $this->checkOwnership($course_id);

        $materialId = $request->query('material_id');

        $query = Quiz::where('course_id', $course_id);
        if ($materialId) {
            $query->where('course_material_id', $materialId);
        }

        $quiz = $query->first();

        return response()->json([
            'success' => true,
            'data' => $quiz
        ]);
    }

    /**
     * Store or update quiz for a material
     */
    public function storeOrUpdate(Request $request, $course_id)
    {
        $this->checkOwnership($course_id);

        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array',
            'course_material_id' => 'nullable|integer',
            'duration' => 'nullable|integer|min:0',
            'min_score' => 'nullable|integer|min:0|max:100',
        ]);

        $matchCriteria = ['course_id' => $course_id];
        if ($request->input('course_material_id')) {
            $matchCriteria['course_material_id'] = $request->input('course_material_id');
        }

        $quiz = Quiz::updateOrCreate(
            $matchCriteria,
            [
                'title' => $request->input('title'),
                'course_material_id' => $request->input('course_material_id'),
                'duration' => $request->input('duration', 0),
                'min_score' => $request->input('min_score', 0),
                'questions' => $request->input('questions')
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Kuis berhasil disimpan!',
            'data' => $quiz
        ]);
    }

    public function destroy($course_id, Request $request)
    {
        $this->checkOwnership($course_id);

        $materialId = $request->query('material_id');

        $query = Quiz::where('course_id', $course_id);
        if ($materialId) {
            $query->where('course_material_id', $materialId);
        }

        $quiz = $query->first();

        if ($quiz) {
            $quiz->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Kuis berhasil dihapus!'
        ]);
    }
}