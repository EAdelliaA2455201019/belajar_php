<?php
// memanggil konfigurasi koneksi database
include 'config.php';

$product = null;
$error   = '';
$success = '';

try {
    // membuat koneksi pdo ke database
    $pdo = new PDO($dsn, $user, $pass);

    // set mode error agar exception muncul jika ada masalah
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // cek apakah ada id yang dikirim via url (GET)
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        // jika id tidak valid, kembalikan ke halaman utama
        header('Location: index.php');
        exit();
    }

    // ambil id dari url dan konversi ke integer agar aman
    $id = (int) $_GET['id'];

    // ---------- proses update jika form disubmit (POST) ----------
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // ambil data baru dari form
        $name  = trim($_POST['name']  ?? '');
        $price = trim($_POST['price'] ?? '');

        // validasi: pastikan nama dan harga tidak kosong
        if (empty($name) || empty($price)) {
            $error = 'Nama dan harga produk tidak boleh kosong.';
        } else {
            // query update menggunakan prepared statement agar aman dari sql injection
            $query = "UPDATE products SET name = :name, price = :price WHERE id = :id";
            $stmt  = $pdo->prepare($query);

            // eksekusi query dengan nilai yang sudah dibind
            $stmt->execute([
                ':name'  => $name,
                ':price' => $price,
                ':id'    => $id
            ]);

            // redirect ke index dengan pesan sukses
            header('Location: index.php?success=updated');
            exit();
        }
    }

    // ---------- ambil data produk berdasarkan id ----------
    $query   = "SELECT * FROM products WHERE id = :id";
    $stmt    = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);

    // fetchAll(PDO::FETCH_ASSOC) untuk hasil berupa array asosiatif
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // jika produk tidak ditemukan di database, kembalikan ke index
    if (!$product) {
        header('Location: index.php');
        exit();
    }

} catch (PDOException $e) {
    // simpan pesan error jika koneksi atau query gagal
    $error = 'Terjadi kesalahan: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk — StoreHub</title>
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* styling wrapper card utama */
        .edit-card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.06);
            background: #fff;
            width: 100%;
            max-width: 520px;
            overflow: hidden;
        }

        /* header card dengan warna gradien */
        .edit-card-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 28px 32px;
        }

        /* styling form input */
        .custom-input-group {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .custom-input-group:focus-within {
            border-color: #f59e0b;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.1);
            background-color: #ffffff;
        }

        .custom-input-group .icon-wrapper {
            padding: 0 16px;
            color: #94a3b8;
            transition: color 0.3s ease;
            font-size: 1.1rem;
        }

        .custom-input-group:focus-within .icon-wrapper {
            color: #f59e0b;
        }

        .custom-input-group .form-control {
            border: none;
            background: transparent;
            box-shadow: none !important;
            padding: 14px 16px 14px 0;
            font-weight: 500;
            color: #1e293b;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
        }

        /* tombol simpan berwarna kuning (amber) sesuai tema edit */
        .btn-save {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(245, 158, 11, 0.25);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(245, 158, 11, 0.35);
            color: white;
        }

        /* tombol batal kembali ke index */
        .btn-cancel {
            background: #f1f5f9;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
            color: #334155;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }

        /* animasi masuk */
        .edit-card {
            animation: fadeSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0);    }
        }
    </style>
</head>

<body>
    <div class="p-3 w-100 d-flex justify-content-center align-items-center">
        <div class="edit-card">

            <!-- header card -->
            <div class="edit-card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-10 rounded-3 p-2 me-3 d-flex align-items-center justify-content-center" style="width:44px; height:44px;">
                        <i class="bi bi-pencil-square fs-5 text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Edit Produk</h5>
                        <!-- tampilkan id produk yang sedang diedit -->
                        <small class="text-white-50">ID: #PRD-<?php echo str_pad($product['id'] ?? 0, 5, '0', STR_PAD_LEFT); ?></small>
                    </div>
                </div>
            </div>

            <!-- body card berisi form -->
            <div class="p-4">

                <?php if ($error): ?>
                    <!-- tampilkan pesan error jika ada masalah -->
                    <div class="alert alert-danger d-flex align-items-center mb-4">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- form edit: method post, action kembali ke halaman ini dengan id yang sama -->
                <form action="edit.php?id=<?php echo htmlspecialchars($product['id'] ?? ''); ?>" method="POST">

                    <!-- field nama produk -->
                    <div class="mb-4">
                        <label class="form-label">Nama Produk</label>
                        <div class="custom-input-group">
                            <div class="icon-wrapper">
                                <i class="bi bi-box-fill"></i>
                            </div>
                            <!-- value diisi otomatis dari data lama di database -->
                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                id="inputEditName"
                                placeholder="Nama produk"
                                value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>"
                                required>
                        </div>
                    </div>

                    <!-- field harga produk -->
                    <div class="mb-5">
                        <label class="form-label">Harga Produk (Rp)</label>
                        <div class="custom-input-group">
                            <div class="icon-wrapper">
                                <i class="bi bi-tags-fill"></i>
                            </div>
                            <!-- value diisi otomatis dari harga lama di database -->
                            <input
                                type="number"
                                class="form-control"
                                name="price"
                                id="inputEditPrice"
                                placeholder="Harga produk"
                                value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>"
                                min="0"
                                required>
                        </div>
                    </div>

                    <!-- tombol aksi: simpan perubahan atau batal -->
                    <div class="d-flex gap-3">
                        <!-- tombol batal: kembali ke index tanpa menyimpan -->
                        <a href="index.php" class="btn btn-cancel flex-fill d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-left me-2"></i> Batal
                        </a>
                        <!-- tombol simpan: submit form dan update database -->
                        <button type="submit" class="btn btn-save flex-fill d-flex align-items-center justify-content-center">
                            <i class="bi bi-floppy-fill me-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
