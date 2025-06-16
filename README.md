Proyek Integrasi Pembayaran Midtrans dan Ongkos Kirim RajaOngkir
Ini adalah proyek contoh sederhana yang menunjukkan cara mengintegrasikan dua API populer dalam sebuah alur pemesanan:

RajaOngkir API: Untuk mendapatkan data provinsi & kota serta menghitung ongkos kirim secara dinamis.
Midtrans API: Untuk memproses pembayaran menggunakan payment gateway Midtrans dengan antarmuka Snap.
Proyek ini dibuat sebagai praktik untuk mata kuliah Pemrograman Web Lanjut, mencakup konsep API eksternal, AJAX, dan proses pembayaran.

Fitur Utama
Formulir Pemesanan Dinamis: Mengisi data pelanggan, detail produk, dan tujuan pengiriman.
Dropdown Wilayah Otomatis: Daftar provinsi dan kota diambil langsung dari API RajaOngkir.
Kalkulasi Ongkos Kirim Real-time: Menghitung biaya pengiriman (menggunakan kurir JNE) berdasarkan kota tujuan dan berat barang.
Rincian Biaya: Menampilkan rincian harga barang, ongkos kirim, dan total yang harus dibayar sebelum melanjutkan transaksi.
Integrasi Midtrans Snap: Membuka jendela pembayaran Midtrans dengan detail transaksi yang sudah disesuaikan secara dinamis.
Struktur Folder
/
├── api/
│   ├── get_cities.php       # API untuk mengambil daftar kota
│   ├── get_cost.php         # API untuk menghitung ongkos kirim
│   └── get_provinces.php    # API untuk mengambil daftar provinsi
│
├── views/
│   └── order_form.php       # Halaman utama (tampilan form)
│
├── .gitignore               # Mengabaikan file sensitif (vendor, config.php)
├── charge.php               # Backend untuk membuat token Midtrans
├── composer.json            # Konfigurasi dependensi Composer
├── config.php.example       # Contoh file konfigurasi
├── index.php                # Titik masuk utama aplikasi
└── README.md                # Dokumentasi ini

(Folder /vendor/ akan dibuat otomatis oleh Composer)
Prasyarat
Sebelum menjalankan proyek ini, pastikan Anda memiliki:

PHP versi 7.4 atau lebih baru
Composer terinstal
Web Server lokal (seperti XAMPP)
Akun RajaOngkir (tipe Starter sudah cukup) untuk mendapatkan API Key.
Akun Midtrans Sandbox untuk mendapatkan Server Key dan Client Key.
Instalasi & Konfigurasi
Clone Repositori
Buka terminal atau command prompt, lalu jalankan perintah berikut:

Bash

git clone https://github.com/NAMA_USERNAME_ANDA/NAMA_REPOSITORY_ANDA.git
cd NAMA_REPOSITORY_ANDA
Install Dependensi
Jalankan Composer untuk mengunduh Midtrans PHP SDK. Perintah ini akan membuat folder vendor.

Bash

composer install
Konfigurasi Kunci API

Salin file config.php.example dan ganti namanya menjadi config.php.
Buka file config.php yang baru Anda buat.
Masukkan API Key RajaOngkir, serta Server Key dan Client Key Midtrans Anda pada variabel yang sesuai.
Jalankan Proyek

Pindahkan folder proyek ke dalam direktori web server Anda (misalnya C:/xampp/htdocs/).
Buka browser dan akses proyek Anda (misalnya http://localhost/nama-folder-proyek/).
Cara Penggunaan
Buka aplikasi di browser, Anda akan langsung diarahkan ke form pemesanan.
Isi semua data yang diperlukan: detail pelanggan, detail barang, dan berat.
Pilih provinsi dan kota tujuan. Daftar akan terisi secara otomatis.
Klik tombol "Cek Ongkos Kirim". Sistem akan menampilkan rincian biaya di bawah form.
Jika rincian sudah benar, klik tombol "Lanjut ke Pembayaran".
Jendela pembayaran Midtrans akan muncul. Lakukan simulasi pembayaran menggunakan kartu uji coba yang disediakan.
