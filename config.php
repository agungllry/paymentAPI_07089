<?php
// Autoload dari Composer
require_once dirname(__FILE__) . '/vendor/autoload.php';

// --- KONFIGURASI RAJAONGKIR ---
define("RAJAONGKIR_API_KEY", "ISI_DENGAN_API_KEY_ANDA");
define("RAJAONGKIR_BASE_URL", "https://api.rajaongkir.com/starter/");

// --- KONFIGURASI MIDTRANS ---
$midtransServerKey = 'ISI_DENGAN_SERVER_KEY_ANDA';
$midtransClientKey = 'ISI_DENGAN_CLIENT_KEY_ANDA';

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = $midtransServerKey;
\Midtrans\Config::$clientKey = $midtransClientKey;
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;