<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseManagementController extends Controller
{
    private function checkInstructor()
    {
        if (Auth::user()->role === 'student') {
            abort(403, 'Akses ditolak. Anda bukan Instruktur.');
        }
    }

    public function index()
    {
        $this->checkInstructor();
        $courses = Course::where('instructor_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }
    public function store(Request $request)
    {
        $this->checkInstructor();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $course = Course::create([
            'instructor_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil dibuat!',
            'data' => $course
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkInstructor();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $course = Course::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();

        $course->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil diperbarui!',
            'data' => $course
        ]);
    }

    public function destroy($id)
    {
        $this->checkInstructor();

        $course = Course::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil dihapus!'
        ]);
    }
}