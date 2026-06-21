<?php
// =============================================================
// file: delete.php
// fungsi: memproses penghapusan data produk berdasarkan id
// =============================================================

// hubungkan konfigurasi database
include 'config.php';

// cek parameter id dari url (metode get)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // jika id tidak ada atau tidak valid, kembalikan ke halaman utama
    header('Location: index.php');
    exit();
}

// ubah id menjadi tipe data integer demi keamanan data
$id = (int) $_GET['id'];

try {
    // lakukan koneksi ke database dengan pdo
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // query delete menggunakan prepared statement
    $query = "DELETE FROM products WHERE id = :id";
    $stmt  = $pdo->prepare($query);

    // jalankan penghapusan data produk
    $stmt->execute([':id' => $id]);

    // arahkan kembali ke index.php dengan status sukses bahasa inggris
    header('Location: index.php?success=deleted');
    exit();

} catch (PDOException $e) {
    // jika gagal, tangkap pesan error database lalu arahkan kembali
    $errMsg = urlencode('Failed to delete: ' . $e->getMessage());
    header("Location: index.php?error=$errMsg");
    exit();
}
