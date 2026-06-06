<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    public function index()
    {
        $courses = Course::with('instructor:id,name')
            ->withCount('purchases')
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        $stats = [
            'courses' => Course::count(),
            'students' => User::where('role', 'student')->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'transactions' => Purchase::whereIn('status', ['settlement', 'success', 'capture'])->count(),
        ];

        return view('public.landing', compact('courses', 'stats'));
    }

    public function checkout($courseId)
    {
        $course = Course::findOrFail($courseId);

        if ($course->base_price < 1000) {
            return redirect('/')->with('error', 'Kursus gratis hanya dapat diakses melalui aplikasi mobile setelah masuk log.');
        }

        $va = config('ipaymu.va');
        $apiKey = config('ipaymu.api_key');
        $isProduction = config('ipaymu.is_production');
        $baseUrl = $isProduction
            ? 'https://my.ipaymu.com/api/v2/payment'
            : 'https://sandbox.ipaymu.com/api/v2/payment';

        $appUrl = config('app.url');
        $orderId = 'WEB-' . $course->id . '-' . time();

        $body = [
            'product'     => [substr($course->title, 0, 50)],
            'qty'         => [1],
            'price'       => [(int) $course->base_price],
            'description' => ['Pembelian kursus: ' . $course->title],
            'returnUrl'   => $appUrl . '/',
            'notifyUrl'   => $appUrl . '/api/ipaymu/callback',
            'cancelUrl'   => $appUrl . '/',
            'referenceId' => $orderId,
            'buyerName'   => 'Pengunjung Web',
            'buyerEmail'  => 'visitor@eldemy.eltaimayu.my.id',
        ];

        $jsonBody    = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign = 'POST:' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature   = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp   = date('YmdHis');

        Log::info('Web Checkout Debug', [
            'va' => $va,
            'url' => $baseUrl,
            'production' => $isProduction,
            'jsonBody' => $jsonBody,
        ]);

        try {
            $ch = curl_init($baseUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $jsonBody,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'va: ' . $va,
                    'signature: ' . $signature,
                    'timestamp: ' . $timestamp,
                ],
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);

            $responseRaw = curl_exec($ch);
            $curlError   = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error('Web Checkout cURL Error: ' . $curlError);
                return redirect('/')->with('error', 'Gagal terhubung ke iPaymu: ' . $curlError);
            }

            $result = json_decode($responseRaw, true);
            Log::info('Web Checkout iPaymu Response:', $result ?? ['raw' => $responseRaw]);

            if (isset($result['Status']) && $result['Status'] == 200 && isset($result['Data']['Url'])) {
                return redirect()->away($result['Data']['Url']);
            }

            return redirect('/')->with('error', 'Gagal membuat transaksi: ' . ($result['Message'] ?? 'Coba lagi nanti'));

        } catch (\Exception $e) {
            Log::error('Web Checkout Exception: ' . $e->getMessage());
            return redirect('/')->with('error', 'Gagal terhubung ke iPaymu: ' . $e->getMessage());
        }
    }
}

