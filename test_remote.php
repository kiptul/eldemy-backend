<?php
// test_remote.php

$apiUrl = 'https://eldemy.eltaimayu.my.id/api';

echo "1. Logging in...\n";
$ch = curl_init("$apiUrl/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'email' => 'siswa@gmail.com',
    'password' => 'password123'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$res = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "Login status: " . $info['http_code'] . "\n";
echo "Response: $res\n\n";

$data = json_decode($res, true);
if (empty($data['token'])) {
    die("Login failed - no token received.\n");
}
$token = $data['token'];

echo "2. Fetching my-courses...\n";
$ch = curl_init("$apiUrl/my-courses");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$res = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "my-courses status: " . $info['http_code'] . "\n";
$coursesData = json_decode($res, true);
if (empty($coursesData['data'])) {
    echo "No courses found.\n\n";
} else {
    foreach ($coursesData['data'] as $c) {
        $progress = $c['progress'] ?? 0;
        $id = $c['course']['id'] ?? 'unknown';
        $title = $c['course']['title'] ?? 'unknown';
        echo "Course ID $id: $title - Progress: $progress%\n";
        
        if ($progress == 100) {
            echo "Attempting to get certificate for course ID $id...\n";
            $ch2 = curl_init("$apiUrl/courses/$id/certificate");
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $token",
                'Accept: application/json'
            ]);
            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
            $res2 = curl_exec($ch2);
            $info2 = curl_getinfo($ch2);
            curl_close($ch2);
            
            echo "Certificate status: " . $info2['http_code'] . "\n";
            echo "Certificate response: $res2\n\n";
        }
    }
}

echo "3. Attempting checkout for course ID 1...\n";
$ch = curl_init("$apiUrl/checkout");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'course_id' => 1
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$res = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "Checkout status: " . $info['http_code'] . "\n";
echo "Checkout response: $res\n";
