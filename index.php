<?php
include 'config.php';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
    echo "Koneksi Berhasil <br>";

    $query = "SELECT * FROM products";
    $stmt = $pdo->query($query);
    $products = $stmt->fetchAll();
  
    foreach ($products as $product) {
        echo $product['name'] . "<br>";
    }

} catch (PDOException $e) {
    die(" Koneksi Gagal : " . $e->getMessage());
}