<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InstructorDashboardController extends Controller
{
    public function index()
    {
        $instructorId = Auth::id();

        // 1. Total Kursus
        $totalCourses = Course::where('instructor_id', $instructorId)->count();

        // 2. Siswa Aktif (Unique users who bought courses)
        $activeStudents = Purchase::whereHas('course', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->where('status', 'settlement')
            ->distinct('user_id')
            ->count('user_id');

        // 3. Pendapatan Bersih (Total purchases * base_price)
        $courses = Course::withCount(['purchases' => function ($query) {
            $query->where('status', 'settlement');
        }])->where('instructor_id', $instructorId)->get();

        $netRevenue = 0;
        foreach ($courses as $course) {
            $netRevenue += $course->purchases_count * $course->base_price;
        }

        // 4. Siswa Terbaru (Latest students)
        $recentStudents = User::whereHas('purchases', function ($query) use ($instructorId) {
            $query->whereHas('course', function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })->where('status', 'settlement');
        })->with(['purchases' => function($query) use ($instructorId) {
             $query->whereHas('course', function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })->where('status', 'settlement')->latest();
        }])->get()->sortByDesc(function($user) {
            return $user->purchases->first()->created_at ?? now();
        })->take(3);

        // 5. Data Grafik (Penjualan 7 hari terakhir)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $count = Purchase::whereHas('course', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->where('status', 'settlement')
              ->whereDate('created_at', $date)
              ->count();
            $chartData[] = $count;
        }

        // Normalize chart data for SVG (0-100)
        $maxSales = max($chartData) > 0 ? max($chartData) : 1;
        $svgPoints = [];
        $width = 500;
        $height = 80; // keep padding for y
        $step = $width / 6;

        foreach ($chartData as $index => $value) {
            $x = $index * $step;
            $y = 100 - (($value / $maxSales) * $height) - 10; // invert Y for SVG, keep 10px padding
            $svgPoints[] = "$x,$y";
        }
        
        $pathD = "M0,100 "; // Start from bottom left for gradient
        if (count($svgPoints) > 0) {
            $pathD .= "L" . $svgPoints[0] . " ";
            for ($i = 1; $i < count($svgPoints); $i++) {
                 // Simple straight lines for now, or bezier if we calculate control points. Let's do simple lines.
                 $pathD .= "L" . $svgPoints[$i] . " ";
            }
        }
        $pathD .= "L500,100 Z"; // close path for gradient

        $lineD = "M" . implode(" L", $svgPoints); // For the stroke

        return view('instructor.dashboard', compact(
            'totalCourses',
            'activeStudents',
            'netRevenue',
            'recentStudents',
            'svgPoints',
            'pathD',
            'lineD'
        ));
    }
}