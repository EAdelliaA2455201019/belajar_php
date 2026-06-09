<?php
// memanggil config
include 'config.php';

// cek request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ambil data form
    $productName  = $_POST['name'] ?? '';
    $productPrice = $_POST['price'] ?? '';

    // validasi input kosong
    if (empty($productName) || empty($productPrice)) {
        echo "<script>
                alert('Please fill in all fields');
                window.location.href='add.php';
              </script>";
        exit();
    }

    try {
        // koneksi PDO dari config
        $pdo = new PDO($dsn, $user, $pass);

        // set error mode
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // query insert
        $query = "INSERT INTO products (name, price) VALUES (:name, :price)";
        $stmt = $pdo->prepare($query);

        // eksekusi
        $stmt->execute([
            ':name'  => $productName,
            ':price' => $productPrice
        ]);

        echo "<script>
                alert('Product added successfully!');
                window.location.href='index.php';
              </script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>
                alert('Error: " . $e->getMessage() . "');
                window.location.href='add.php';
              </script>";
        exit();
    }
}