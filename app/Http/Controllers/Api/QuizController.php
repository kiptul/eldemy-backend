<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Get quiz for a course (legacy endpoint)
     */
    public function show($course_id)
    {
        $quiz = Quiz::where('course_id', $course_id)->first();

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Kuis belum tersedia untuk kursus ini.'
            ], 404);
        }

        $questionsForStudent = collect($quiz->questions)->map(function ($q) {
            unset($q['correct_answer']);
            return $q;
        });

        $user = Auth::user();
        $bestScore = QuizAnswer::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->max('score');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'duration' => $quiz->duration ?? 0,
                'min_score' => $quiz->min_score ?? 0,
                'questions' => $questionsForStudent,
                'best_score' => $bestScore,
            ]
        ]);
    }

    /**
     * Get quiz for a specific material (per-kurikulum)
     */
    public function showByMaterial($material_id)
    {
        $quiz = Quiz::where('course_material_id', $material_id)->first();

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Kuis belum tersedia untuk materi ini.'
            ], 404);
        }

        $questionsForStudent = $quiz->questions;

        $user = Auth::user();
        $attempts = QuizAnswer::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get(['score', 'created_at']);
        
        $bestScore = $attempts->max('score');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'duration' => $quiz->duration ?? 0,
                'min_score' => $quiz->min_score ?? 0,
                'total_questions' => count($quiz->questions ?? []),
                'questions' => $questionsForStudent,
                'best_score' => $bestScore,
                'attempts' => $attempts
            ]
        ]);
    }

    /**
     * Submit quiz answers — unlimited attempts, saves highest score
     */
    public function submit(Request $request, $quiz_id)
    {
        $user = Auth::user();
        $quiz = Quiz::findOrFail($quiz_id);

        $userAnswers = $request->input('answers', []); // Could be array of letters 'A','B' or text

        $totalQuestions = count($quiz->questions);
        $correctCount = 0;

        foreach ($quiz->questions as $index => $question) {
            if (!isset($userAnswers[$index])) continue;
            
            $userAns = $userAnswers[$index];
            $correctAns = $question['correct_answer'];
            
            // if the user sent a letter (A,B,C,D), find the text for it
            $letterMap = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
            $userAnsText = null;
            if (array_key_exists($userAns, $letterMap) && isset($question['options'][$letterMap[$userAns]])) {
                $userAnsText = $question['options'][$letterMap[$userAns]];
            }

            // Also check if correctAns is a letter
            $correctAnsText = null;
            if (array_key_exists($correctAns, $letterMap) && isset($question['options'][$letterMap[$correctAns]])) {
                $correctAnsText = $question['options'][$letterMap[$correctAns]];
            }

            // Match logic:
            // 1. Direct match (e.g. 'A' === 'A' or 'Text' === 'Text')
            // 2. User sent letter, DB has text
            // 3. User sent text, DB has letter
            if (
                $userAns === $correctAns || 
                ($userAnsText && $userAnsText === $correctAns) ||
                ($correctAnsText && $userAns === $correctAnsText) ||
                ($userAnsText && $correctAnsText && $userAnsText === $correctAnsText)
            ) {
                $correctCount++;
            }
        }

        $score = ($totalQuestions > 0) ? round(($correctCount / $totalQuestions) * 100) : 0;

        // Simpan jawaban (setiap attempt disimpan)
        QuizAnswer::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => $score,
            'answers_data' => $userAnswers
        ]);

        // Ambil best score
        $bestScore = QuizAnswer::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->max('score');

        $passed = $score >= ($quiz->min_score ?? 0);

        return response()->json([
            'success' => true,
            'message' => $passed ? 'Selamat! Anda lulus kuis ini.' : 'Kuis selesai. Anda belum mencapai nilai minimum.',
            'score' => $score,
            'best_score' => $bestScore,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'min_score' => $quiz->min_score ?? 0,
            'passed' => $passed
        ]);
    }
}