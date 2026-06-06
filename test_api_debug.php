<?php
$apiUrl = 'https://eldemy.eltaimayu.my.id/api';

// Login
$ch = curl_init("$apiUrl/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['email' => 'siswa@gmail.com', 'password' => 'password123']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch); curl_close($ch);
$data = json_decode($res, true);
$token = $data['token'];
echo "Token: $token\n";

// Test: mark material as completed (POST /materials/1/complete)
echo "\nTesting POST /materials/1/complete...\n";
$ch = curl_init("$apiUrl/materials/1/complete");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token", 'Content-Type: application/json', 'Accept: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res2 = curl_exec($ch); $info2 = curl_getinfo($ch); curl_close($ch);
echo "Status: " . $info2['http_code'] . "\n";
echo "Response: $res2\n";

// Test: get certificate for course 1
echo "\nTesting GET /courses/1/certificate...\n";
$ch = curl_init("$apiUrl/courses/1/certificate");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token", 'Accept: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res3 = curl_exec($ch); $info3 = curl_getinfo($ch); curl_close($ch);
echo "Status: " . $info3['http_code'] . "\n";
echo "Response: $res3\n";

// Test: checkout for course 1  
echo "\nTesting POST /checkout for course 1...\n";
$ch = curl_init("$apiUrl/checkout");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['course_id' => 1]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token", 'Content-Type: application/json', 'Accept: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res4 = curl_exec($ch); $info4 = curl_getinfo($ch); curl_close($ch);
echo "Status: " . $info4['http_code'] . "\n";
echo "Response: $res4\n";
