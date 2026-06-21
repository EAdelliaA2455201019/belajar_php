<?php
// =============================================================
// file: config.php
// fungsi: menyimpan konfigurasi koneksi ke database mysql
// semua file php lain akan memanggil file ini agar terhubung
// ke database yang sama tanpa perlu menulis ulang konfigurasi
// =============================================================

// variabel host menyimpan alamat server database
// 'localhost' berarti database ada di komputer yang sama (xampp)
$host = 'localhost';

// nama database yang digunakan untuk proyek ini
$db   = 'belajar_php';

// username default mysql di xampp adalah 'root'
$user = 'root';

// password default mysql di xampp biasanya kosong
$pass = '';

// dsn (data source name) adalah string format koneksi untuk pdo
// format: "mysql:host=alamat;dbname=nama_db;charset=encoding"
// charset utf8mb4 mendukung karakter unicode termasuk emoji
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
