<?php
// memanggil file konfigurasi database
include 'config.php';

try {
    // membuat koneksi pdo
    $pdo = new PDO($dsn, $user, $pass);
    
    // mengatur error mode ke exception
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    // menampilkan pesan jika koneksi gagal
    die("Koneksi Gagal: " . $e->getMessage());
}
?>
