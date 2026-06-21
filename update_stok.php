<?php
// =============================================================
// file: update_stok.php
// fungsi: memproses perubahan nilai stok produk
// =============================================================

// hubungkan konfigurasi database
include 'config.php';

// pastikan request menggunakan metode post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // jika bukan post, kembalikan ke halaman utama
    header('Location: index.php');
    exit();
}

// ambil data input dari form modal
$id     = $_POST['id'] ?? '';
$action = $_POST['action'] ?? '';
$qty    = $_POST['qty'] ?? '';

// validasi inputan stok agar aman
if (!is_numeric($id) || !in_array($action, ['tambah', 'kurang']) || !is_numeric($qty) || $qty < 1) {
    // jika tidak valid, kembalikan dengan pesan error bahasa inggris
    header('Location: index.php?error=' . urlencode('Invalid stock update data!'));
    exit();
}

// ubah tipe data ke integer demi keamanan
$id  = (int) $id;
$qty = (int) $qty;

try {
    // lakukan koneksi ke database dengan pdo
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // cek terlebih dahulu stok saat ini jika melakukan pengurangan
    if ($action === 'kurang') {
        $checkStmt = $pdo->prepare("SELECT stock FROM products WHERE id = :id");
        $checkStmt->execute([':id' => $id]);
        $currentStock = (int) $checkStmt->fetchColumn();

        // jika stok yang dikurangi melebihi stok yang ada
        if ($qty > $currentStock) {
            header('Location: index.php?error=' . urlencode('Insufficient stock for outbound transaction!'));
            exit();
        }
    }

    // logika esensial update_stok.php sesuai slide
    if ($action === 'tambah') {
        // kueri menambah nilai kolom kuantitas fisik (stok masuk / inbound)
        $sql = "UPDATE products SET stock = stock + :jumlah WHERE id = :id";
    } elseif ($action === 'kurang') {
        // kueri mengurangi nilai kolom kuantitas fisik (stok keluar / outbound)
        $sql = "UPDATE products SET stock = stock - :jumlah WHERE id = :id";
    }

    // siapkan kueri prepared statement
    $stmt = $pdo->prepare($sql);

    // jalankan kueri sesuai dengan parameter jumlah dan id produk
    $stmt->execute([
        'jumlah' => $qty,
        'id'     => $id
    ]);

    // arahkan kembali ke index.php dengan status sukses update stok bahasa inggris
    header('Location: index.php?success=stock');
    exit();

} catch (PDOException $e) {
    // tangkap pesan error database jika ada masalah
    $errMsg = urlencode('Stock update failed: ' . $e->getMessage());
    header("Location: index.php?error=$errMsg");
    exit();
}
