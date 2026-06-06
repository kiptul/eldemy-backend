<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase;
use App\Models\Course;
use App\Models\Notification;

class WebhookController extends Controller
{
    /**
     * Handle iPaymu payment callback/notification (unotify)
     * iPaymu sends POST request with transaction status
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('iPaymu Callback Masuk:', $payload);

        // iPaymu sends these fields in callback:
        // trx_id, sid, reference_id, status, status_code, via
        $trxId = $payload['trx_id'] ?? null;
        $referenceId = $payload['reference_id'] ?? null;
        $status = $payload['status'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $via = $payload['via'] ?? null;

        if (!$referenceId) {
            Log::error('iPaymu Callback: reference_id kosong');
            return response()->json(['message' => 'reference_id kosong'], 400);
        }

        $purchase = Purchase::where('order_id', $referenceId)->first();

        if (!$purchase) {
            Log::error('iPaymu Callback Gagal: Order ID ' . $referenceId . ' tidak ditemukan di database.');
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        // iPaymu status: 1 = berhasil, 0 = pending, -1 = expired/gagal
        if ($statusCode == 1 || $status == 'berhasil') {
            $purchase->update([
                'status' => 'settlement',
                'payment_type' => $via ?? 'ipaymu',
            ]);
            Log::info("SUKSES: Kursus terbuka untuk Order ID: " . $referenceId . " via " . $via);

            // Send notification to user
            $course = Course::find($purchase->course_id);
            if ($course) {
                Notification::send(
                    $purchase->user_id,
                    'purchase',
                    'Pembayaran Berhasil! 🎉',
                    'Pembayaran untuk kursus "' . $course->title . '" telah dikonfirmasi. Selamat belajar!',
                    ['course_id' => $course->id, 'order_id' => $referenceId]
                );
            }

        } elseif ($statusCode == -1 || $status == 'expired' || $status == 'gagal') {
            $purchase->update([
                'status' => 'expire',
            ]);
            Log::info("GAGAL/EXPIRE: Order ID: " . $referenceId);
        }

        return response()->json(['message' => 'Callback iPaymu Berhasil Diproses']);
    }

    /**
     * Handle iPaymu return URL (user redirected back after payment)
     */
    public function returnHandler(Request $request)
    {
        $orderId = $request->query('order_id');
        Log::info('iPaymu Return: User kembali dari halaman pembayaran', ['order_id' => $orderId]);

        return response('
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Pembayaran Sukses | Eldemy</title>
                <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    * {
                        box-sizing: border-box;
                    }
                    body {
                        font-family: "Plus Jakarta Sans", sans-serif;
                        background: radial-gradient(circle at top right, #fdf2f8, #f8fafc);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 100vh;
                        margin: 0;
                        padding: 20px;
                    }
                    .container {
                        background: rgba(255, 255, 255, 0.85);
                        backdrop-filter: blur(12px);
                        -webkit-backdrop-filter: blur(12px);
                        border: 1px solid rgba(255, 255, 255, 0.5);
                        padding: 40px 30px;
                        border-radius: 24px;
                        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
                        text-align: center;
                        max-width: 440px;
                        width: 100%;
                    }
                    .icon-box {
                        width: 72px;
                        height: 72px;
                        background: #ecfdf5;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 24px auto;
                    }
                    .icon {
                        color: #10b981;
                        font-size: 32px;
                        font-weight: bold;
                    }
                    h2 {
                        margin: 0 0 12px 0;
                        color: #0f172a;
                        font-size: 22px;
                        font-weight: 700;
                    }
                    p {
                        color: #64748b;
                        font-size: 14px;
                        line-height: 1.6;
                        margin: 0 0 28px 0;
                    }
                    .order-info {
                        background: #f1f5f9;
                        padding: 12px 16px;
                        border-radius: 12px;
                        font-size: 13px;
                        color: #475569;
                        font-family: monospace;
                        margin-bottom: 28px;
                        word-break: break-all;
                    }
                    .btn {
                        background: #e11d48;
                        color: white;
                        border: none;
                        padding: 14px 28px;
                        border-radius: 14px;
                        font-weight: 600;
                        font-size: 15px;
                        cursor: pointer;
                        text-decoration: none;
                        display: inline-block;
                        width: 100%;
                        box-shadow: 0 10px 15px -3px rgba(225, 29, 72, 0.3);
                        transition: all 0.2s ease;
                    }
                    .btn:hover {
                        background: #be123c;
                        transform: translateY(-1px);
                    }
                    .btn:active {
                        transform: translateY(1px);
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="icon-box">
                        <span class="icon">✓</span>
                    </div>
                    <h2>Pembayaran Sukses!</h2>
                    <p>Terima kasih. Pembayaran Anda telah diterima dan sedang diverifikasi secara otomatis. Silakan kembali ke aplikasi.</p>
                    <div class="order-info">
                        Order ID: ' . htmlspecialchars($orderId) . '
                    </div>
                    <button onclick="window.close()" class="btn">Kembali ke Aplikasi</button>
                </div>
                <script>
                    // Auto close window if possible after 2 seconds
                    setTimeout(function() {
                        try {
                            window.close();
                        } catch(e) {}
                    }, 2000);
                </script>
            </body>
            </html>
        ');
    }

    /**
     * Handle iPaymu cancel URL (user cancelled payment)
     */
    public function cancelHandler(Request $request)
    {
        $orderId = $request->query('order_id');
        Log::info('iPaymu Cancel: User membatalkan pembayaran', ['order_id' => $orderId]);

        return response('
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Pembayaran Dibatalkan | Eldemy</title>
                <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    * {
                        box-sizing: border-box;
                    }
                    body {
                        font-family: "Plus Jakarta Sans", sans-serif;
                        background: radial-gradient(circle at top right, #fef2f2, #f8fafc);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 100vh;
                        margin: 0;
                        padding: 20px;
                    }
                    .container {
                        background: rgba(255, 255, 255, 0.85);
                        backdrop-filter: blur(12px);
                        -webkit-backdrop-filter: blur(12px);
                        border: 1px solid rgba(255, 255, 255, 0.5);
                        padding: 40px 30px;
                        border-radius: 24px;
                        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
                        text-align: center;
                        max-width: 440px;
                        width: 100%;
                    }
                    .icon-box {
                        width: 72px;
                        height: 72px;
                        background: #fef2f2;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 24px auto;
                    }
                    .icon {
                        color: #ef4444;
                        font-size: 32px;
                        font-weight: bold;
                    }
                    h2 {
                        margin: 0 0 12px 0;
                        color: #0f172a;
                        font-size: 22px;
                        font-weight: 700;
                    }
                    p {
                        color: #64748b;
                        font-size: 14px;
                        line-height: 1.6;
                        margin: 0 0 28px 0;
                    }
                    .order-info {
                        background: #f1f5f9;
                        padding: 12px 16px;
                        border-radius: 12px;
                        font-size: 13px;
                        color: #475569;
                        font-family: monospace;
                        margin-bottom: 28px;
                        word-break: break-all;
                    }
                    .btn {
                        background: #64748b;
                        color: white;
                        border: none;
                        padding: 14px 28px;
                        border-radius: 14px;
                        font-weight: 600;
                        font-size: 15px;
                        cursor: pointer;
                        text-decoration: none;
                        display: inline-block;
                        width: 100%;
                        box-shadow: 0 10px 15px -3px rgba(100, 116, 139, 0.3);
                        transition: all 0.2s ease;
                    }
                    .btn:hover {
                        background: #475569;
                        transform: translateY(-1px);
                    }
                    .btn:active {
                        transform: translateY(1px);
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="icon-box">
                        <span class="icon">✕</span>
                    </div>
                    <h2>Pembayaran Dibatalkan</h2>
                    <p>Anda telah membatalkan proses pembayaran. Silakan kembali ke aplikasi untuk mencoba lagi atau memilih kursus lain.</p>
                    <div class="order-info">
                        Order ID: ' . htmlspecialchars($orderId) . '
                    </div>
                    <button onclick="window.close()" class="btn">Kembali ke Aplikasi</button>
                </div>
                <script>
                    // Auto close window if possible after 2 seconds
                    setTimeout(function() {
                        try {
                            window.close();
                        } catch(e) {}
                    }, 2000);
                </script>
            </body>
            </html>
        ');
    }
}