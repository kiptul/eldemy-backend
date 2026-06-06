<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class InstructorStudentController extends Controller
{
    public function index()
    {
        $instructorId = Auth::id();

        // Ambil semua kursus milik instruktur beserta materinya dan kuis
        $courses = \App\Models\Course::with(['materials', 'quizzes'])
            ->where('instructor_id', $instructorId)
            ->get();

        // Siapkan struktur data hierarkis
        $courseData = [];

        foreach ($courses as $course) {
            $totalMaterials = $course->materials->count();

            // Ambil semua siswa yang berlangganan kursus ini (status sukses)
            $purchases = \App\Models\Purchase::with('user')
                ->where('course_id', $course->id)
                ->whereIn('status', ['settlement', 'success', 'capture'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('user_id')
                ->values();

            $students = [];
            foreach ($purchases as $purchase) {
                $user = $purchase->user;
                if (!$user) continue;

                // Hitung progres
                $completedMaterials = 0;
                if ($totalMaterials > 0) {
                    $completedMaterials = \Illuminate\Support\Facades\DB::table('progress')
                        ->where('user_id', $user->id)
                        ->where('course_id', $course->id)
                        ->where('is_completed', true)
                        ->count();
                }
                $progressPercent = ($totalMaterials > 0 && $completedMaterials === $totalMaterials) ? 100 : ($totalMaterials > 0 ? floor(($completedMaterials / $totalMaterials) * 100) : 0);

                // Cari materi/kuis terakhir yang diakses
                $lastMaterialTitle = '-';
                $lastProgress = \Illuminate\Support\Facades\DB::table('progress')
                    ->where('progress.user_id', $user->id)
                    ->where('progress.course_id', $course->id)
                    ->join('course_materials', 'progress.course_material_id', '=', 'course_materials.id')
                    ->orderBy('progress.updated_at', 'desc')
                    ->select('course_materials.title', 'progress.updated_at')
                    ->first();
                
                if ($lastProgress) {
                    $lastMaterialTitle = $lastProgress->title;
                }

                // Ambil nilai kuis terbaik
                $quizScores = [];
                foreach ($course->quizzes as $quiz) {
                    $bestScore = \App\Models\QuizAnswer::where('quiz_id', $quiz->id)
                        ->where('user_id', $user->id)
                        ->max('score');
                    
                    if ($bestScore !== null) {
                        $quizScores[] = [
                            'title' => $quiz->title,
                            'score' => $bestScore
                        ];
                    }
                }

                $students[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'enroll_date' => $purchase->created_at->format('d M Y'),
                    'progress' => $progressPercent,
                    'last_material' => $lastMaterialTitle,
                    'quiz_scores' => $quizScores
                ];
            }

            $courseData[] = [
                'id' => $course->id,
                'title' => $course->title,
                'total_students' => count($students),
                'students' => $students
            ];
        }

        return view('instructor.students.index', compact('courseData'));
    }
}