<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'] ?? '';
    $productPrice = $_POST['product_price'] ?? '';

    if (empty($productName) || empty($productPrice)) {
        echo "<script>alert('Please fill in all fields'); 
        window.location.href='add.php';</script>";
        exit;
    }

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        // Insert the product
        $stmt = $pdo->prepare(
            "INSERT INTO products (name, price) VALUES (:name, :price)"
        );
        $stmt->execute([
            ':name' => $productName,
            ':price' => $productPrice
        ]);

        echo "<script>alert('Product added successfully!'); 
        window.location.href='index.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " .
            addslashes($e->getMessage()) . "'); 
           window.location.href='add.php';</script>";
    }
} else {
    header('Location: add.php');
    exit;
}