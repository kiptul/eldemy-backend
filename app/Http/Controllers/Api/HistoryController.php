<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Get Enrolled Courses
     *
     * Retrieve all courses the authenticated student has purchased, along with their progress.
     */
    public function myCourses()
    {
        $user = Auth::user();

        $histories = Purchase::where('user_id', $user->id)
            ->whereIn('status', ['settlement', 'success', 'capture'])
            ->with(['course.instructor:id,name', 'course.materials'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('course_id')
            ->values();

        if ($histories->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Anda belum membeli kursus apapun.',
                'data' => []
            ]);
        }

        $formattedHistory = $histories->map(function ($purchase) use ($user) {
            $course = $purchase->course;
            $totalMaterials = $course->materials->count();

            // Hitung progress nyata dari tabel progress
            $completedMaterials = 0;
            if ($totalMaterials > 0) {
                $completedMaterials = DB::table('progress')
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->where('is_completed', true)
                    ->count();
            }

            $progressPercent = ($totalMaterials > 0 && $completedMaterials === $totalMaterials) ? 100 : ($totalMaterials > 0 ? floor(($completedMaterials / $totalMaterials) * 100) : 0);

            // Dapatkan aktivitas terakhir (materi terakhir yang diselesaikan)
            $lastMaterialTitle = null;
            $lastAccessedAt = $purchase->created_at->format('Y-m-d H:i:s');
            
            try {
                $lastProgress = DB::table('progress')
                    ->where('progress.user_id', $user->id)
                    ->where('progress.course_id', $course->id)
                    ->join('course_materials', 'progress.course_material_id', '=', 'course_materials.id')
                    ->orderBy('progress.updated_at', 'desc')
                    ->select('course_materials.title', 'progress.updated_at')
                    ->first();

                if ($lastProgress) {
                    $lastMaterialTitle = $lastProgress->title;
                    $lastAccessedAt = $lastProgress->updated_at;
                }
            } catch (\Exception $e) {
                // Fallback jika terjadi error pada query join
            }

            return [
                'purchase_id' => $purchase->id,
                'purchase_date' => $purchase->created_at->format('Y-m-d H:i:s'),
                'course' => $course,
                'progress' => $progressPercent,
                'last_material_title' => $lastMaterialTitle,
                'last_accessed_at' => $lastAccessedAt,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil riwayat pembelajaran.',
            'data' => $formattedHistory
        ]);
    }

    /**
     * Get Last Learning History
     *
     * Retrieve the last course or material the student was learning to "Continue Learning" on mobile.
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "course_id": 5,
     *     "course_title": "Fullstack Laravel & Ionic",
     *     "last_material_id": 12,
     *     "last_material_title": "Understanding Controllers",
     *     "last_accessed_at": "2026-05-01 10:00:00"
     *   }
     * }
     */
    public function lastLearning()
    {
        return response()->json([
            'success' => true,
            'data' => null // TODO: Implement real logic later
        ]);
    }
}