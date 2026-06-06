<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Models\CourseMaterial;
use App\Models\Course;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Notification;

class CertificateController extends Controller
{
    /**
     * Generate / Get Certificate
     *
     * Retrieve the graduation certificate if the student has completed all course materials and quizzes. 
     * If the student meets the criteria and does not have a certificate yet, it will generate a new one.
     * 
     * @urlParam course_id integer required The ID of the course. Example: 5
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Selamat! Sertifikat Anda berhasil diterbitkan.",
     *   "data": {
     *     "id": 1,
     *     "user_id": 101,
     *     "course_id": 5,
     *     "certificate_number": "CERT-XYZ12-U101C5",
     *     "created_at": "2026-05-01T10:00:00Z"
     *   }
     * }
     * @response 403 {
     *   "success": false,
     *   "message": "Anda belum menyelesaikan semua materi di kursus ini."
     * }
     */
    public function show($course_id)
    {
        $user = Auth::user();

        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->first();

        if ($certificate) {
            return response()->json([
                'success' => true,
                'message' => 'Sertifikat ditemukan.',
                'data' => $certificate
            ]);
        }

        $totalMaterials = CourseMaterial::where('course_id', $course_id)->count();
        $completedMaterials = Progress::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->where('is_completed', true)
            ->count();

        if ($totalMaterials === 0 || $completedMaterials < $totalMaterials) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum menyelesaikan semua materi di kursus ini.'
            ], 403);
        }

        $certNumber = 'CERT-' . strtoupper(Str::random(5)) . '-U' . $user->id . 'C' . $course_id;

        $newCertificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course_id,
            'certificate_number' => $certNumber
        ]);

        // Send notification
        $course = Course::find($course_id);
        Notification::send(
            $user->id,
            'certificate',
            'Sertifikat Diterbitkan! 🎓',
            'Selamat! Anda telah menyelesaikan kursus "' . ($course->title ?? 'kursus') . '" dan sertifikat Anda sudah tersedia.',
            ['course_id' => $course_id, 'certificate_number' => $certNumber]
        );

        return response()->json([
            'success' => true,
            'message' => 'Selamat! Sertifikat Anda berhasil diterbitkan.',
            'data' => $newCertificate
        ]);
    }

    /**
     * Download Certificate as PDF
     *
     * Generates a PDF certificate with the student's name overlaid on the custom certificate template.
     */
    public function downloadPdf(Request $request, $course_id)
    {
        // Support token via query param or Authorization header for direct browser / API access
        if ($request->has('token')) {
            $token = $request->query('token');
            $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if ($accessToken) {
                Auth::setUser($accessToken->tokenable);
            }
        }

        if (!Auth::check()) {
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                Auth::setUser($user);
            }
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Ensure certificate exists (auto-generate if eligible)
        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->first();

        if (!$certificate) {
            // Check eligibility
            $totalMaterials = CourseMaterial::where('course_id', $course_id)->count();
            $completedMaterials = Progress::where('user_id', $user->id)
                ->where('course_id', $course_id)
                ->where('is_completed', true)
                ->count();

            if ($totalMaterials === 0 || $completedMaterials < $totalMaterials) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum menyelesaikan semua materi.'
                ], 403);
            }

            $certNumber = 'CERT-' . strtoupper(Str::random(5)) . '-U' . $user->id . 'C' . $course_id;
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'course_id' => $course_id,
                'certificate_number' => $certNumber
            ]);
        }

        $course = Course::find($course_id);
        $studentName = $user->name ?? 'Siswa';

        // Generate base64 of the certificate template
        $templatePath = storage_path('app/certificates/new_cert.png');
        $imageData = base64_encode(file_get_contents($templatePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        // A4 landscape dimensions in mm: 297 x 210
        // The name area for "Yellow Golden Elegant" is usually centered
        // Approximately at 45% from the top of the image
        // In mm: 210 * 0.45 = ~94.5mm from top
        // Using fixed mm positioning for pixel-perfect DomPDF rendering

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            size: 297mm 210mm;
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
        }
        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            overflow: hidden;
        }
        .page img {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
        }
        .name-text {
            position: absolute;
            top: 94mm;
            left: 0;
            width: 297mm;
            text-align: center;
            font-family: "Times New Roman", Times, serif;
            font-size: 32pt;
            font-style: italic;
            font-weight: bold;
            color: #bd9136; /* Dark golden color to match template */
            letter-spacing: 2px;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="page">
        <img src="' . $imageSrc . '" />
        <div class="name-text">' . htmlspecialchars($studentName) . '</div>
    </div>
</body>
</html>';

        $pdf = Pdf::loadHTML($html)
            ->setPaper([0, 0, 841.89, 595.28], 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('defaultFont', 'Times New Roman')
            ->setOption('dpi', 150);

        $filename = 'Sertifikat_' . Str::slug($course->title ?? 'kursus') . '_' . Str::slug($studentName) . '.pdf';

        return $pdf->download($filename);
    }
}