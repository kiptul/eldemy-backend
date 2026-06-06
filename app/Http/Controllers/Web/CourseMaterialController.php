<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    private function checkOwnership($course_id)
    {
        return Course::where('id', $course_id)->where('instructor_id', Auth::id())->firstOrFail();
    }

    /**
     * Normalize old content format to new structured format.
     * Old: { video_url, pdf_path, pdf_paths }
     * New: { videos: [{title, url}], modules: [{title, path}] }
     */
    private function normalizeContent(array $data): array
    {
        // Already in new format
        if (isset($data['videos']) || isset($data['modules'])) {
            $data['videos'] = $data['videos'] ?? [];
            $data['modules'] = $data['modules'] ?? [];
            return $data;
        }

        $videos = [];
        $modules = [];

        // Migrate old video_url
        if (!empty($data['video_url'])) {
            $videos[] = ['title' => 'Video Pembelajaran', 'url' => $data['video_url']];
        }

        // Migrate old pdf_paths / pdf_path
        $pdfPaths = $data['pdf_paths'] ?? [];
        if (empty($pdfPaths) && !empty($data['pdf_path'])) {
            $pdfPaths = [$data['pdf_path']];
        }
        foreach ($pdfPaths as $i => $path) {
            $modules[] = ['title' => 'Modul ' . ($i + 1), 'path' => $path];
        }

        return [
            'description' => $data['description'] ?? '',
            'videos' => $videos,
            'modules' => $modules,
        ];
    }

    public function index($course_id)
    {
        $course = $this->checkOwnership($course_id);
        $course->load([
            'materials' => function ($query) {
                $query->orderBy('order', 'asc');
            }
        ]);
        return view('instructor.materials.index', compact('course'));
    }

    public function create(Request $request, $course_id)
    {
        $course = $this->checkOwnership($course_id);
        return view('instructor.materials.create', compact('course'));
    }

    public function store(Request $request, $course_id)
    {
        $course = $this->checkOwnership($course_id);

        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url',
            'pdf_file.*' => 'nullable|mimes:pdf|max:15360',
        ]);

        $pdfPaths = [];
        if ($request->hasFile('pdf_file')) {
            foreach ($request->file('pdf_file') as $file) {
                if ($file->isValid()) {
                    $pdfPaths[] = $file->store('modules', 'public');
                }
            }
        }

        $contentData = [
            'description' => $request->description ?? '',
            'video_url' => $request->video_url ?? '',
            'pdf_path' => count($pdfPaths) > 0 ? $pdfPaths[0] : null, // backward compatibility
            'pdf_paths' => $pdfPaths
        ];

        $mainType = $request->filled('video_url') ? 'video' : 'module';
        $lastOrder = CourseMaterial::where('course_id', $course->id)->max('order') ?? 0;

        CourseMaterial::create([
            'course_id' => $course_id,
            'title' => $request->title,
            'type' => $mainType,
            'content' => json_encode($contentData),
            'order' => $lastOrder + 1
        ]);

        return redirect()->route('instructor.materials.index', $course_id)
            ->with('success', 'Pelajaran berhasil ditambahkan!');
    }

    public function edit($course_id, $material_id)
    {
        $course = $this->checkOwnership($course_id);
        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();
        return view('instructor.materials.edit', compact('course', 'material'));
    }

    public function update(Request $request, $course_id, $material_id)
    {
        $this->checkOwnership($course_id);

        $request->validate([
            'title' => 'required|string|max:255',
            'video_urls.*' => 'nullable|url',
            'video_titles.*' => 'nullable|string|max:255',
            'pdf_file.*' => 'nullable|mimes:pdf|max:15360',
            'pdf_titles.*' => 'nullable|string|max:255',
        ]);

        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();
        $contentData = json_decode($material->content, true) ?? [];
        $contentData = $this->normalizeContent($contentData);

        // Update description
        $contentData['description'] = $request->description ?? '';

        // Update existing videos titles
        if ($request->has('old_video_titles')) {
            foreach ($request->old_video_titles as $i => $title) {
                if (isset($contentData['videos'][$i])) {
                    $contentData['videos'][$i]['title'] = $title;
                }
            }
        }

        // Update existing modules titles
        if ($request->has('old_pdf_titles')) {
            foreach ($request->old_pdf_titles as $i => $title) {
                if (isset($contentData['modules'][$i])) {
                    $contentData['modules'][$i]['title'] = $title;
                }
            }
        }

        // Handle new videos
        if ($request->has('video_urls')) {
            foreach ($request->video_urls as $i => $url) {
                if (!empty($url)) {
                    $title = $request->video_titles[$i] ?? 'Video ' . (count($contentData['videos']) + 1);
                    $contentData['videos'][] = ['title' => $title, 'url' => $url];
                }
            }
        }

        // Handle new PDFs
        if ($request->hasFile('pdf_file')) {
            foreach ($request->file('pdf_file') as $i => $file) {
                if ($file->isValid()) {
                    $path = $file->store('modules', 'public');
                    $title = ($request->pdf_titles[$i] ?? '') ?: 'Modul ' . (count($contentData['modules']) + 1);
                    $contentData['modules'][] = ['title' => $title, 'path' => $path];
                }
            }
        }

        // Keep backward compat fields
        $contentData['video_url'] = !empty($contentData['videos']) ? $contentData['videos'][0]['url'] : '';
        $contentData['pdf_paths'] = array_map(fn($m) => $m['path'], $contentData['modules']);
        $contentData['pdf_path'] = $contentData['pdf_paths'][0] ?? null;

        $mainType = !empty($contentData['videos']) ? 'video' : 'module';

        $material->update([
            'title' => $request->title,
            'type' => $mainType,
            'content' => json_encode($contentData),
        ]);

        return redirect()->route('instructor.materials.edit', [$course_id, $material_id])
            ->with('success', 'Pelajaran berhasil diperbarui!');
    }

    /**
     * Delete a specific PDF module by index.
     */
    public function deleteModule($course_id, $material_id, $index)
    {
        $this->checkOwnership($course_id);
        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();
        $contentData = json_decode($material->content, true) ?? [];
        $contentData = $this->normalizeContent($contentData);

        if (isset($contentData['modules'][$index])) {
            // Delete the file from storage
            $path = $contentData['modules'][$index]['path'] ?? null;
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            // Remove from array and re-index
            array_splice($contentData['modules'], $index, 1);

            // Update backward compat
            $contentData['pdf_paths'] = array_map(fn($m) => $m['path'], $contentData['modules']);
            $contentData['pdf_path'] = $contentData['pdf_paths'][0] ?? null;

            $material->update(['content' => json_encode($contentData)]);
        }

        return redirect()->route('instructor.materials.edit', [$course_id, $material_id])
            ->with('success', 'Modul PDF berhasil dihapus!');
    }

    /**
     * Delete a specific video by index.
     */
    public function deleteVideo($course_id, $material_id, $index)
    {
        $this->checkOwnership($course_id);
        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();
        $contentData = json_decode($material->content, true) ?? [];
        $contentData = $this->normalizeContent($contentData);

        if (isset($contentData['videos'][$index])) {
            array_splice($contentData['videos'], $index, 1);

            // Update backward compat
            $contentData['video_url'] = !empty($contentData['videos']) ? $contentData['videos'][0]['url'] : '';

            $mainType = !empty($contentData['videos']) ? 'video' : 'module';
            $material->update([
                'type' => $mainType,
                'content' => json_encode($contentData),
            ]);
        }

        return redirect()->route('instructor.materials.edit', [$course_id, $material_id])
            ->with('success', 'Video berhasil dihapus!');
    }

    public function destroy($course_id, $material_id)
    {
        $this->checkOwnership($course_id);
        $material = CourseMaterial::where('id', $material_id)->where('course_id', $course_id)->firstOrFail();

        $contentData = json_decode($material->content, true);
        // Delete all PDF files
        $contentData = $this->normalizeContent($contentData);
        foreach ($contentData['modules'] as $mod) {
            if (!empty($mod['path'])) {
                Storage::disk('public')->delete($mod['path']);
            }
        }

        $material->delete();

        return redirect()->route('instructor.materials.index', $course_id)
            ->with('success', 'Pelajaran berhasil dihapus!');
    }
}