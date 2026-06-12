<?php
// memanggil konfigurasi koneksi database
include 'config.php';

// cek apakah id dikirim via url (GET) dan nilainya angka
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // jika tidak valid, langsung kembalikan ke halaman utama
    header('Location: index.php');
    exit();
}

// ambil id dari url dan konversi ke integer agar aman dari serangan sql injection
$id = (int) $_GET['id'];

try {
    // membuat koneksi pdo ke database
    $pdo = new PDO($dsn, $user, $pass);

    // set mode error agar exception muncul jika ada masalah query
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // query delete menggunakan prepared statement dengan parameter id
    $query = "DELETE FROM products WHERE id = :id";
    $stmt  = $pdo->prepare($query);

    // eksekusi penghapusan dengan id yang sudah divalidasi
    $stmt->execute([':id' => $id]);

    // setelah berhasil hapus, redirect ke index.php
    header('Location: index.php?success=deleted');
    exit();

} catch (PDOException $e) {
    // jika gagal, redirect ke index dengan pesan error di url
    $errMsg = urlencode('Gagal menghapus: ' . $e->getMessage());
    header("Location: index.php?error=$errMsg");
    exit();
}
