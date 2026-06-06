<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda bukan Instruktur/Admin.'
            ], 403);
        }

        $myCourseIds = Course::where('instructor_id', $user->id)->pluck('id');

        $totalCourses = $myCourseIds->count();

        $totalPurchases = Purchase::whereIn('course_id', $myCourseIds)->count();

        $totalStudents = Purchase::whereIn('course_id', $myCourseIds)
            ->distinct('user_id')
            ->count('user_id');

        $recentActivities = Purchase::whereIn('course_id', $myCourseIds)
            ->with(['user:id,name', 'course:id,name'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Dashboard berhasil diambil',
            'data' => [
                'total_courses' => $totalCourses,
                'total_students' => $totalStudents,
                'total_purchases' => $totalPurchases,
                'recent_activities' => $recentActivities
            ]
        ]);
    }
}