<?php
// memanggil file koneksi database
include 'koneksi.php';

// mengecek apakah form disubmit dengan method post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // mengambil data dari form
    $name = $_POST['name'];
    $price = $_POST['price'];
    
    // validasi menggunakan empty() untuk mengecek input kosong
    if (empty($name)) {
        // redirect dengan pesan error jika nama kosong
        header("Location: index.php?error=" . urlencode("Error: Nama produk tidak boleh kosong!"));
        exit();
    }
    
    if (empty($price)) {
        // redirect dengan pesan error jika harga kosong
        header("Location: index.php?error=" . urlencode("Error: Harga produk tidak boleh kosong!"));
        exit();
    }
    
    // validasi tambahan: harga harus angka positif
    if (!is_numeric($price) || $price < 0) {
        // redirect dengan pesan error jika harga tidak valid
        header("Location: index.php?error=" . urlencode("Error: Harga harus berupa angka positif!"));
        exit();
    }
    
    try {
        // query insert menggunakan prepared statement untuk keamanan
        $query = "INSERT INTO products (name, price) VALUES (:name, :price)";
        
        // menyiapkan statement
        $stmt = $pdo->prepare($query);
        
        // binding parameter
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        
        // eksekusi query
        $stmt->execute();
        
        // redirect ke halaman utama dengan pesan sukses
        header("Location: index.php?success=1");
        exit();
        
    } catch (PDOException $e) {
        // redirect dengan pesan error jika gagal menyimpan
        header("Location: index.php?error=" . urlencode("Error: Gagal menyimpan data - " . $e->getMessage()));
        exit();
    }
    
} else {
    // redirect jika akses langsung tanpa form
    header("Location: index.php");
    exit();
}
?>
                  