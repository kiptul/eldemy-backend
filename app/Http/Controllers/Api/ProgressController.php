<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Progress;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function markAsCompleted($material_id)
    {
        $user = Auth::user();

        $material = CourseMaterial::findOrFail($material_id);

        Progress::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $material->course_id,
                'course_material_id' => $material->id,
            ],
            [
                'is_completed' => true
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil ditandai selesai'
        ]);
    }

    public function checkProgress($course_id)
    {
        $user = Auth::user();

        $totalMaterials = CourseMaterial::where('course_id', $course_id)->count();

        if ($totalMaterials === 0) {
            return response()->json([
                'success' => true,
                'percentage' => 0,
                'completed_materials' => []
            ]);
        }

        $completedMaterials = Progress::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->where('is_completed', true)
            ->pluck('course_material_id');

        $percentage = ($completedMaterials->count() === $totalMaterials) ? 100 : floor(($completedMaterials->count() / $totalMaterials) * 100);

        return response()->json([
            'success' => true,
            'percentage' => $percentage,
            'completed_materials' => $completedMaterials
        ]);
    }
}