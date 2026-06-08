<?php

// memanggil file konfigurasi database
include 'config.php';

try {

    $pdo = new PDO($dsn, $user, $pass);

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // menampilkan pesan jika koneksi berhasil
    echo "Koneksi Berhasil <br>";

    $query = "SELECT * FROM products";

    $stmt = $pdo->query($query);

    $products = $stmt->fetchAll();

    // menampilkan data produk
    foreach ($products as $product) {

        echo htmlspecialchars($product['name']) .
        " - Rp " .
        number_format($product['price'], 0, ",", ".") .
        "<br>";

    }

} catch (PDOException $e) {

    // menampilkan pesan jika koneksi gagal
    die(" Koneksi Gagal : " . $e->getMessage());

}

?>