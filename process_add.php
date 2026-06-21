<?php
// =============================================================
// file: process_add.php
// fungsi: memproses input data produk baru dan menyimpannya ke database
// =============================================================

// hubungkan konfigurasi database
include 'config.php';

// pastikan request menggunakan metode post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // jika diakses langsung lewat url, kembalikan ke add.php
    header('Location: add.php');
    exit();
}

// ambil data masukan dari form
$sku   = trim($_POST['sku'] ?? '');
$name  = trim($_POST['product_name'] ?? '');
$price = trim($_POST['price'] ?? '');
$stock = trim($_POST['stock'] ?? '0');

// validasi dasar agar data penting tidak kosong
if (empty($sku) || empty($name) || empty($price)) {
    // jika kosong, kembalikan ke form add dengan pesan error bahasa inggris
    $error = urlencode('All required fields must be filled out!');
    header("Location: add.php?error=$error");
    exit();
}

try {
    // lakukan koneksi ke database dengan pdo
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // kueri sql untuk menyimpan data produk baru
    $query = "INSERT INTO products (sku, product_name, price, stock) VALUES (:sku, :name, :price, :stock)";
    $stmt  = $pdo->prepare($query);

    // jalankan kueri pdo dengan mengikat nilai parameter secara aman
    $stmt->execute([
        ':sku'   => $sku,
        ':name'  => $name,
        ':price' => $price,
        ':stock' => $stock
    ]);

    // jika berhasil disimpan, arahkan kembali ke index.php dengan pesan sukses bahasa inggris
    header('Location: index.php?success=added');
    exit();

} catch (PDOException $e) {
    // jika terjadi error database (misal sku kembar), kembalikan ke form add
    $error = urlencode('Database error: ' . $e->getMessage());
    header("Location: add.php?error=$error");
    exit();
}