<?php
// =============================================================
// file: edit.php
// fungsi: menangani form edit dan proses pembaruan data produk
// =============================================================

// hubungkan konfigurasi database
include 'config.php';

$product = null;
$error   = '';

try {
    // lakukan koneksi ke database dengan pdo
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // cek parameter id dari url (metode get)
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        // jika id tidak ada atau tidak valid, kembalikan ke index.php
        header('Location: index.php');
        exit();
    }

    // ubah id menjadi tipe data integer demi keamanan data
    $id = (int) $_GET['id'];

    // ---------- proses pembaruan jika form dikirim (post) ----------
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // ambil nilai masukan baru dari form
        $sku   = trim($_POST['sku'] ?? '');
        $name  = trim($_POST['product_name'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $stock = trim($_POST['stock'] ?? '0');

        // validasi data inputan wajib
        if (empty($sku) || empty($name) || empty($price)) {
            // pesan error menggunakan bahasa inggris
            $error = 'All required fields must be filled out!';
        } else {
            // kueri update menggunakan prepared statement
            $query = "UPDATE products SET sku = :sku, product_name = :name, price = :price, stock = :stock WHERE id = :id";
            $stmt  = $pdo->prepare($query);

            // jalankan pembaruan data produk
            $stmt->execute([
                ':sku'   => $sku,
                ':name'  => $name,
                ':price' => $price,
                ':stock' => $stock,
                ':id'    => $id
            ]);

            // arahkan kembali ke index.php dengan status sukses bahasa inggris
            header('Location: index.php?success=updated');
            exit();
        }
    }

    // ---------- ambil data produk lama untuk diisi ke form ----------
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt  = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);

    // dapatkan satu baris data sebagai array asosiatif
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // jika produk tidak ditemukan, arahkan ke halaman utama
    if (!$product) {
        header('Location: index.php');
        exit();
    }
} catch (PDOException $e) {
    // tangkap pesan error database jika ada masalah
    $error = 'Database error: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- judul halaman menggunakan bahasa inggris -->
    <title>Edit Product - GudangKu</title>
    <!-- load css bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- load icon bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- load google font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #e0a6b1;      /* soft blush pink */
            --primary-dark: #c28b96; /* darker blush */
            --bg: #fcfaf8;           /* warm cream / off-white */
            --border: #f2e8ea;       /* soft dusty border */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            /* hapus background pattern */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            color: #5a5254;
        }

        /* kartu form modern */
        .form-card {
            background-color: #ffffff;
            border: 1px solid var(--border);
            border-radius: 18px;
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.015);
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .form-header {
            background: #fdf8f9;
            color: #736769;
            padding: 2rem;
            border-bottom: 1px solid var(--border);
        }

        .form-body {
            padding: 2.5rem 2rem;
        }

        .form-label {
            font-weight: 700;
            font-size: 0.85rem;
            color: #736769;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-input {
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .custom-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(224, 166, 177, 0.15);
            outline: none;
        }

        .btn-submit {
            background: var(--primary);
            color: #ffffff;
            font-weight: 700;
            padding: 0.8rem;
            border-radius: 12px;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(224, 166, 177, 0.2);
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(224, 166, 177, 0.3);
        }

        .btn-cancel {
            background-color: #fdf8f9;
            color: #9e9396;
            font-weight: 700;
            padding: 0.8rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            background-color: #f2e8ea;
            color: #5a5254;
        }
    </style>
</head>

<body>

    <div class="form-card">
        <!-- bagian header kartu form -->
        <div class="form-header">
            <div class="d-flex align-items-center gap-3">
                <div style="background-color: rgba(255,255,255,0.2); padding: 10px; border-radius: 10px;">
                    <i class="bi bi-pencil-square fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-800 m-0">Edit Product</h4>
                    <small class="opacity-75">Modify existing product information</small>
                </div>
            </div>
        </div>

        <!-- bagian isi form -->
        <div class="form-body">

            <!-- tampilkan pesan error jika ada -->
            <?php if ($error): ?>
                <div class="alert alert-danger mb-4" style="border-radius: 10px;">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- form menggunakan metode post -->
            <form method="POST" action="edit.php?id=<?php echo htmlspecialchars($product['id'] ?? ''); ?>">

                <!-- field sku -->
                <div class="mb-3">
                    <label class="form-label">SKU (Stock Keeping Unit) *</label>
                    <input type="text" name="sku" class="form-control custom-input" required
                        value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>">
                </div>

                <!-- field nama produk -->
                <div class="mb-3">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="product_name" class="form-control custom-input" required
                        value="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>">
                </div>

                <!-- field harga produk -->
                <div class="mb-3">
                    <label class="form-label">Price (Rupiah) *</label>
                    <input type="number" name="price" class="form-control custom-input" min="0" step="0.01" required
                        value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>">
                </div>

                <!-- field stok awal -->
                <div class="mb-4">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control custom-input" min="0"
                        value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>">
                </div>

                <!-- baris tombol simpan & batal -->
                <div class="row g-3">
                    <div class="col-6">
                        <a href="index.php" class="btn-cancel w-100">Cancel</a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn-submit w-100">Save Changes</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>