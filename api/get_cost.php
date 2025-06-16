<?php
require '../config.php'; // Hubungkan ke config utama

header('Content-Type: application/json');

$origin = 152; // ID Kota asal (contoh: Jakarta Selatan) - sesuaikan jika perlu
$destination = $_POST['destination'] ?? null;
$weight = $_POST['weight'] ?? null;
$courier = 'jne'; // Kurir ditetapkan (JNE) untuk simplifikasi

if (!$destination || !$weight) {
    echo json_encode(['error' => 'Parameter tidak lengkap.']);
    exit;
}

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => RAJAONGKIR_BASE_URL . "cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=$origin&destination=$destination&weight=$weight&courier=$courier",
    CURLOPT_HTTPHEADER => [
        "content-type: application/x-www-form-urlencoded",
        "key: " . RAJAONGKIR_API_KEY
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo json_encode(['error' => "cURL Error #:" . $err]);
} else {
    echo $response;
}