<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseManagementController extends Controller
{
    public function index(Request $request)
    {
        $instructorId = Auth::id();
        $query = Course::withCount([
            'purchases' => function ($q) {
                $q->where('status', 'settlement');
            }
        ])->where('instructor_id', $instructorId);

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->orderBy('created_at', 'desc')->get();

        return view('instructor.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('instructor.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'skill_level' => 'required|in:SD,SMP,SMA/SMK,UMUM',
            'category' => 'nullable|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|mimes:jpeg,png,jpg,webp|max:5120'
        ]);
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Course::create([
            'instructor_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'skill_level' => $request->skill_level ?? 'UMUM',
            'category' => $request->category ?? 'Umum',
            'base_price' => $request->base_price,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Kursus berhasil ditambahkan! Silakan klik "Edit Kursus" untuk menyusun kurikulum.');
    }

    public function edit($id)
    {
        $course = Course::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        return view('instructor.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'skill_level' => 'required|in:SD,SMP,SMA/SMK,UMUM',
            'category' => 'nullable|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $thumbnailPath = $course->getRawOriginal('thumbnail');
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'skill_level' => $request->skill_level ?? 'UMUM',
            'category' => $request->category ?? 'Umum',
            'base_price' => $request->base_price,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Info kursus berhasil diperbarui!');
    }
    public function destroy($id)
    {
        $course = Course::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        $course->delete();

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Kursus berhasil dihapus!');
    }
}