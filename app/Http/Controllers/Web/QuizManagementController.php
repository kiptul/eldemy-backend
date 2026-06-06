<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizManagementController extends Controller
{
    public function index($course_id, $material_id)
    {
        $course = Course::where('id', $course_id)->where('instructor_id', Auth::id())->firstOrFail();

        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();

        $quiz = Quiz::where('course_material_id', $material_id)->first();

        return view('instructor.quizzes.index', compact('course', 'material', 'quiz'));
    }

    public function storeOrUpdate(Request $request, $course_id, $material_id)
    {
        $course = Course::where('id', $course_id)->where('instructor_id', Auth::id())->firstOrFail();
        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'min_score' => 'nullable|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
        ]);

        $formattedQuestions = [];
        foreach ($request->questions as $q) {
            $formattedQuestions[] = [
                'question' => $q['text'],
                'options' => [
                    $q['option_a'],
                    $q['option_b'],
                    $q['option_c'],
                    $q['option_d']
                ],
                'correct_answer' => $q['correct']
            ];
        }

        Quiz::updateOrCreate(
            ['course_material_id' => $material->id],
            [
                'course_id' => $course->id,
                'title' => $request->title,
                'duration' => $request->duration,
                'min_score' => $request->input('min_score', 0),
                'questions' => $formattedQuestions
            ]
        );

        return redirect()->route('instructor.materials.index', $course->id)
            ->with('success', 'Kuis berhasil disimpan!');
    }

    public function destroy($course_id, $material_id)
    {
        $course = Course::where('id', $course_id)->where('instructor_id', Auth::id())->firstOrFail();

        $quiz = Quiz::where('course_material_id', $material_id)->firstOrFail();
        $quiz->delete();

        return redirect()->route('instructor.materials.index', $course->id)
            ->with('success', 'Seluruh kuis berhasil dihapus dari materi ini!');
    }
}