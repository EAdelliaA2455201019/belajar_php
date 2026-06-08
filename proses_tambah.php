<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data, memberikan fallback string kosong jika tidak ada
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    
    // Menggunakan fungsi empty() sesuai permintaan user
    if (empty($name) || empty($price)) {
        // Redirect kembali ke index.php dengan pesan error persis seperti yang diminta
        header("Location: index.php?error=" . urlencode("Error: Data tidak boleh kosong!"));
        exit();
    }
    
    // Validasi harga harus angka positif
    if (!is_numeric($price) || $price < 0) {
        header("Location: index.php?error=" . urlencode("Error: Harga harus berupa angka positif!"));
        exit();
    }
    
    try {
        $query = "INSERT INTO products (name, price) VALUES (:name, :price)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->execute();
        
        // Redirect jika sukses
        header("Location: index.php?success=1");
        exit();
    } catch (PDOException $e) {
        header("Location: index.php?error=" . urlencode("Error: Gagal menyimpan ke database!"));
        exit();
    }
} else {
    // Redirect jika halaman diakses secara langsung
    header("Location: index.php");
    exit();
}
