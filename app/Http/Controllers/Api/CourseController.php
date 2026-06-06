<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $query = Course::with('instructor:id,name')
            ->withCount('purchases')
            ->orderBy('created_at', 'desc');

        $token = request()->bearerToken();
        if ($token) {
            $personalToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if ($personalToken) {
                $user = $personalToken->tokenable;
                $purchasedCourseIds = \App\Models\Purchase::where('user_id', $user->id)
                    ->whereIn('status', ['settlement', 'success', 'capture'])
                    ->pluck('course_id')
                    ->toArray();
                
                if (!empty($purchasedCourseIds)) {
                    $query->whereNotIn('id', $purchasedCourseIds);
                }
            }
        }

        $courses = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil daftar kursus',
            'data' => $courses
        ]);
    }
    public function show($id)
    {
        $course = Course::with(['instructor:id,name,avatar_url', 'materials'])
            ->withCount('purchases')
            ->findOrFail($id);

        $hasPurchased = false;

        // Optional auth: cek token dari header jika ada
        $token = request()->bearerToken();
        if ($token) {
            $personalToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if ($personalToken) {
                $user = $personalToken->tokenable;
                $hasPurchased = \App\Models\Purchase::where('user_id', $user->id)
                    ->where('course_id', $id)
                    ->whereIn('status', ['settlement', 'success', 'capture'])
                    ->exists();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $course,
            'has_purchased' => $hasPurchased
        ]);
    }
}