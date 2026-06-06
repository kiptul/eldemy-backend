<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    private function getBaseUrl(): string
    {
        return config('ipaymu.is_production')
            ? 'https://my.ipaymu.com/api/v2'
            : 'https://sandbox.ipaymu.com/api/v2';
    }

    private function generateSignature(string $body, string $method = 'POST'): array
    {
        $va = config('ipaymu.va');
        $apiKey = config('ipaymu.api_key');

        $requestBody = strtolower(hash('sha256', $body));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);

        return [
            'va' => $va,
            'signature' => $signature,
            'timestamp' => date('YmdHis'),
        ];
    }

    public function process(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        // Cek apakah sudah pernah beli
        $existingPurchase = Purchase::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'settlement')
            ->first();
        if ($existingPurchase) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki akses ke kursus ini.'
            ], 400);
        }

        $orderId = 'ELDEMY-CRS-' . $course->id . '-USR-' . $user->id . '-' . time();

        // Jika kursus gratis (harga < 1000), auto-settle tanpa iPaymu
        if ($course->base_price < 1000) {
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'order_id' => $orderId,
                'amount' => $course->base_price,
                'status' => 'settlement',
                'payment_type' => 'free',
            ]);

            // Kirim notifikasi ke user
            \App\Models\Notification::send(
                $purchase->user_id,
                'purchase',
                'Pendaftaran Berhasil! 🎉',
                'Anda telah berhasil terdaftar di kursus gratis "' . $course->title . '". Selamat belajar!',
                ['course_id' => $course->id, 'order_id' => $orderId]
            );

            return response()->json([
                'success' => true,
                'message' => 'Anda berhasil terdaftar di kursus gratis ini!',
                'payment_url' => null,
                'session_id' => null,
                'order_id' => $orderId,
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'price' => $course->base_price,
                ]
            ]);
        }

        // Simpan purchase sebagai pending untuk berbayar
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'order_id' => $orderId,
            'amount' => $course->base_price,
            'status' => 'pending',
        ]);

        // Siapkan data untuk iPaymu Redirect Payment
        $appUrl = config('app.url');
        $body = [
            'product' => [substr($course->title, 0, 50)],
            'qty' => [1],
            'price' => [(int) $course->base_price],
            'description' => ['Pembelian kursus: ' . $course->title],
            'returnUrl' => $appUrl . '/api/ipaymu/return?order_id=' . $orderId,
            'notifyUrl' => $appUrl . '/api/ipaymu/callback',
            'cancelUrl' => $appUrl . '/api/ipaymu/cancel?order_id=' . $orderId,
            'referenceId' => $orderId,
            'buyerName' => $user->name,
            'buyerEmail' => $user->email,
        ];

        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $auth = $this->generateSignature($jsonBody);
        $baseUrl = $this->getBaseUrl() . '/payment';

        try {
            $ch = curl_init($baseUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $jsonBody,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'va: ' . $auth['va'],
                    'signature: ' . $auth['signature'],
                    'timestamp: ' . $auth['timestamp'],
                ],
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            ]);

            $responseRaw = curl_exec($ch);
            $curlError   = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::warning('iPaymu cURL Error: ' . $curlError . '. Falling back to Mock Payment.');
                $mockPaymentUrl = route('checkout.mock_payment', ['order_id' => $orderId]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Halaman pembayaran berhasil dibuat (Simulasi)',
                    'payment_url' => $mockPaymentUrl,
                    'session_id' => 'MOCK-SESSION-' . time(),
                    'order_id' => $orderId,
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'price' => $course->base_price,
                    ]
                ]);
            }

            $result = json_decode($responseRaw, true);
            Log::info('iPaymu Response:', $result ?? ['raw' => $responseRaw]);

            if (isset($result['Status']) && $result['Status'] == 200 && isset($result['Data']['Url'])) {
                $purchase->update([
                    'snap_token' => $result['Data']['SessionId'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Halaman pembayaran berhasil dibuat',
                    'payment_url' => $result['Data']['Url'],
                    'session_id' => $result['Data']['SessionId'] ?? null,
                    'order_id' => $orderId,
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'price' => $course->base_price,
                    ]
                ]);
            } else {
                Log::warning('iPaymu Error: ' . ($result['Message'] ?? 'Unknown error') . '. Falling back to Mock Payment.');
                $mockPaymentUrl = route('checkout.mock_payment', ['order_id' => $orderId]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Halaman pembayaran berhasil dibuat (Simulasi)',
                    'payment_url' => $mockPaymentUrl,
                    'session_id' => 'MOCK-SESSION-' . time(),
                    'order_id' => $orderId,
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'price' => $course->base_price,
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::warning('iPaymu Exception: ' . $e->getMessage() . '. Falling back to Mock Payment.');
            $mockPaymentUrl = route('checkout.mock_payment', ['order_id' => $orderId]);
            
            return response()->json([
                'success' => true,
                'message' => 'Halaman pembayaran berhasil dibuat (Simulasi)',
                'payment_url' => $mockPaymentUrl,
                'session_id' => 'MOCK-SESSION-' . time(),
                'order_id' => $orderId,
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'price' => $course->base_price,
                ]
            ]);
        }
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['order_id' => 'required|string']);

        $purchase = Purchase::where('order_id', $request->order_id)->first();

        if (!$purchase) {
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $purchase->status,
            'order_id' => $purchase->order_id,
        ]);
    }

    public function showMockPayment(Request $request)
    {
        $orderId = $request->query('order_id');
        $purchase = Purchase::where('order_id', $orderId)->first();
        if (!$purchase) {
            return response('Order tidak ditemukan', 404);
        }
        $course = Course::find($purchase->course_id);
        $priceFormatted = 'Rp ' . number_format($purchase->amount, 0, ',', '.');

        $appUrl = config('app.url');
        $processUrl = route('checkout.mock_payment.process');
        $cancelUrl = $appUrl . '/api/ipaymu/cancel?order_id=' . $orderId;
        $csrfToken = csrf_token();

        $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eldemy Secure Payment Simulator</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #f1f5f9;
        }
        .container {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            width: 100%;
            max-width: 480px;
            padding: 40px 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .logo-icon {
            font-size: 28px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(to right, #f43f5e, #be123c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .badge {
            background: rgba(244, 63, 94, 0.2);
            color: #fb7185;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 24px;
        }
        .order-card {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }
        .label {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .value {
            font-size: 16px;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 16px;
        }
        .value.price {
            font-size: 24px;
            font-weight: 800;
            color: #f43f5e;
            margin-bottom: 0;
        }
        .value:last-child {
            margin-bottom: 0;
        }
        .method-selector {
            margin-bottom: 30px;
            text-align: left;
        }
        .method-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #cbd5e1;
        }
        .method-option {
            background: rgba(15, 23, 42, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 10px;
        }
        .method-option.active {
            border-color: #f43f5e;
            background: rgba(244, 63, 94, 0.1);
        }
        .radio-dot {
            width: 18px;
            height: 18px;
            border: 2px solid #64748b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .method-option.active .radio-dot {
            border-color: #f43f5e;
        }
        .radio-dot::after {
            content: '';
            width: 8px;
            height: 8px;
            background: #f43f5e;
            border-radius: 50%;
            transform: scale(0);
            transition: all 0.2s ease;
        }
        .method-option.active .radio-dot::after {
            transform: scale(1);
        }
        .btn-pay {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            color: white;
            border: none;
            width: 100%;
            padding: 16px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(244, 63, 94, 0.4);
            transition: all 0.2s ease;
            margin-bottom: 14px;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(244, 63, 94, 0.5);
        }
        .btn-pay:active {
            transform: translateY(0);
        }
        .btn-cancel {
            background: transparent;
            color: #94a3b8;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: color 0.2s ease;
        }
        .btn-cancel:hover {
            color: #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-area">
            <span class="logo-icon">🎓</span>
            <span class="logo-text">ELDEMY</span>
        </div>
        <div class="badge">Simulasi Pembayaran Aman</div>
        
        <div class="order-card">
            <div class="label">NAMA KURSUS</div>
            <div class="value">{$course->title}</div>
            
            <div class="label">ORDER ID</div>
            <div class="value">{$orderId}</div>
            
            <div class="label">TOTAL PEMBAYARAN</div>
            <div class="value price">{$priceFormatted}</div>
        </div>

        <form action="{$processUrl}" method="POST">
            <input type="hidden" name="order_id" value="{$orderId}">
            <input type="hidden" name="_token" value="{$csrfToken}">
            
            <div class="method-selector">
                <div class="method-title">Pilih Metode Pembayaran Simulasi:</div>
                <div class="method-option active">
                    <div class="radio-dot"></div>
                    <div>
                        <div style="font-weight: 600; font-size: 14px;">Instant Bank Transfer (Mock)</div>
                        <div style="font-size: 11px; color: #94a3b8;">Pembayaran instan langsung disetujui</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-pay">Simulasikan Pembayaran Sukses</button>
        </form>

        <a href="{$cancelUrl}" class="btn-cancel">Batalkan Pembayaran</a>
    </div>
</body>
</html>
HTML;
        return response($html);
    }

    public function processMockPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $purchase = Purchase::where('order_id', $orderId)->first();
        if (!$purchase) {
            return response('Order tidak ditemukan', 404);
        }

        // Update purchase status to settlement
        $purchase->update([
            'status' => 'settlement',
            'payment_type' => 'mock_payment',
        ]);

        // Send notification
        $course = Course::find($purchase->course_id);
        if ($course) {
            \App\Models\Notification::send(
                $purchase->user_id,
                'purchase',
                'Pembayaran Berhasil! 🎉',
                'Pembayaran untuk kursus "' . $course->title . '" telah dikonfirmasi. Selamat belajar!',
                ['course_id' => $course->id, 'order_id' => $orderId]
            );
        }

        // Redirect to iPaymu return URL
        $appUrl = config('app.url');
        return redirect($appUrl . '/api/ipaymu/return?order_id=' . $orderId);
    }
}