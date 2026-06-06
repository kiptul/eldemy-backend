<?php
/**
 * iPaymu API Test Script (FIXED)
 * Jalankan: php ~/eldemy/test_ipaymu.php
 */

$va     = '1179005282777446';
$apiKey = '8E3DA4EF-1866-4CD0-B9F6-ED7DED6F0D64';

echo "=== TEST PRODUCTION (with JSON_UNESCAPED_SLASHES) ===\n";
testApi($va, $apiKey, 'https://my.ipaymu.com/api/v2/payment');

function testApi($va, $apiKey, $url) {
    $body = [
        'product'     => ['Test Kursus'],
        'qty'         => [1],
        'price'       => [10000],
        'description' => ['Test pembelian'],
        'returnUrl'   => 'https://eldemy.eltaimayu.my.id/',
        'notifyUrl'   => 'https://eldemy.eltaimayu.my.id/api/ipaymu/callback',
        'cancelUrl'   => 'https://eldemy.eltaimayu.my.id/',
        'referenceId' => 'TEST-' . time(),
        'buyerName'   => 'Test User',
        'buyerEmail'  => 'test@eldemy.eltaimayu.my.id',
    ];

    // KEY FIX: JSON_UNESCAPED_SLASHES required by iPaymu!
    $jsonBody    = json_encode($body, JSON_UNESCAPED_SLASHES);
    $requestBody = strtolower(hash('sha256', $jsonBody));
    $stringToSign = 'POST:' . $va . ':' . $requestBody . ':' . $apiKey;
    $signature   = hash_hmac('sha256', $stringToSign, $apiKey);
    $timestamp   = date('YmdHis');

    echo "URL: $url\n";
    echo "Body: $jsonBody\n";
    echo "Signature: $signature\n\n";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $jsonBody,
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp,
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response  = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        echo "cURL Error: $curlError\n";
    } else {
        echo "Response: $response\n";
    }
}
