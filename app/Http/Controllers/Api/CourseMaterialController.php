<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;

class CourseMaterialController extends Controller
{
    private function checkOwnership($course_id)
    {
        if (Auth::user()->role === 'student') {
            abort(403, 'Akses ditolak. Anda bukan Instruktur.');
        }

        $course = Course::where('id', $course_id)->where('instructor_id', Auth::id())->first();
        if (!$course) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah kursus ini.');
        }
        return $course;
    }

    public function index($course_id)
    {
        $this->checkOwnership($course_id);

        $materials = CourseMaterial::where('course_id', $course_id)->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }

    public function store(Request $request, $course_id)
    {
        $this->checkOwnership($course_id);

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,text',
            'content' => 'required|string',
        ]);

        $material = CourseMaterial::create([
            'course_id' => $course_id,
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil ditambahkan!',
            'data' => $material
        ]);
    }

    public function update(Request $request, $course_id, $material_id)
    {
        $this->checkOwnership($course_id);

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,text',
            'content' => 'required|string',
        ]);

        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();

        $material->update([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diperbarui!',
            'data' => $material
        ]);
    }

    public function destroy($course_id, $material_id)
    {
        $this->checkOwnership($course_id);

        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();
        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dihapus!'
        ]);
    }
}