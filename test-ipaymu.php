<?php
$va = "1179005282777446";
$apiKey = "8E3DA4EF-1866-4CD0-B9F6-ED7DED6F0D64";
$baseUrl = "https://my.ipaymu.com/api/v2/payment";

$body = [
    'product' => ['Test Course'],
    'qty' => [1],
    'price' => [10000],
    'description' => ['Test Description'],
    'returnUrl' => 'https://eldemy.eltaimayu.my.id/return',
    'notifyUrl' => 'https://eldemy.eltaimayu.my.id/notify',
    'cancelUrl' => 'https://eldemy.eltaimayu.my.id/cancel',
    'referenceId' => 'ORDER-' . time(),
    'buyerName' => 'Zikra',
    'buyerEmail' => 'zikra@example.com',
];

$jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);

$requestBody = strtolower(hash('sha256', $jsonBody));
$stringToSign = 'POST:' . $va . ':' . $requestBody . ':' . $apiKey;
$signature = hash_hmac('sha256', $stringToSign, $apiKey);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'va: ' . $va,
    'signature: ' . $signature,
    'timestamp: ' . date('YmdHis'),
]);

$response = curl_exec($ch);
echo "Status Code: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
echo "Response: " . $response . "\n";
curl_close($ch);
