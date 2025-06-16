<?php
require_once 'config.php';

// Ambil data JSON dari request
$json_str = file_get_contents('php://input');
$data = json_decode($json_str, true);

// Validasi data dasar
if (!$data || !isset($data['destination'], $data['berat'], $data['harga_barang'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Data tidak lengkap.']);
    exit;
}

// 1. Hitung Ongkos Kirim (Sama seperti di get_cost.php)
$origin = 152; // ID Kota asal (Jakarta Selatan)
$destination = $data['destination'];
$weight = $data['berat'];
$courier = 'jne';

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => RAJAONGKIR_BASE_URL . "cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => "origin=$origin&destination=$destination&weight=$weight&courier=$courier",
    CURLOPT_HTTPHEADER => ["key: " . RAJAONGKIR_API_KEY, "content-type: application/x-www-form-urlencoded"],
]);
$response = curl_exec($curl);
curl_close($curl);
$ongkir_data = json_decode($response, true);

if (empty($ongkir_data['rajaongkir']['results'][0]['costs'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal menghitung ongkos kirim.']);
    exit;
}
$ongkir_reg = $ongkir_data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'];
$harga_barang = (int) $data['harga_barang'];
$total_harga = $harga_barang + $ongkir_reg;

// 2. Siapkan data untuk Midtrans
$transaction_details = [
    'order_id' => 'ORDER-' . time() . rand(),
    'gross_amount' => $total_harga,
];

$item_details = [
    [
        'id' => 'PRODUK01',
        'price' => $harga_barang,
        'quantity' => 1,
        'name' => $data['nama_barang']
    ],
    [
        'id' => 'ONGKIR01',
        'price' => $ongkir_reg,
        'quantity' => 1,
        'name' => 'Ongkos Kirim (JNE REG)'
    ]
];

$customer_details = [
    'first_name' => $data['nama_pelanggan'],
    'email' => $data['email_pelanggan'],
    'phone' => $data['telepon_pelanggan'],
];

$transaction = [
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details,
];

// 3. Dapatkan Snap Token dari Midtrans
try {
    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    echo json_encode(['token' => $snapToken]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}