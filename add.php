<?php
// =============================================================
// file: add.php
// fungsi: menampilkan form input untuk menambah data produk baru
// =============================================================

// ambil pesan error jika dikirim dari process_add.php via url
$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- judul halaman menggunakan bahasa inggris -->
    <title>Add Product - GudangKu</title>
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
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-800 m-0">Add New Product</h4>
                    <small class="opacity-75">Create a brand new item in your inventory database</small>
                </div>
            </div>
        </div>

        <!-- bagian isi form -->
        <div class="form-body">

            <!-- tampilkan pesan error jika ada kiriman error dari URL -->
            <?php if ($error): ?>
                <div class="alert alert-danger mb-4" style="border-radius: 10px;">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- form diarahkan ke process_add.php dengan metode POST sesuai request -->
            <form method="POST" action="process_add.php">

                <!-- field sku -->
                <div class="mb-3">
                    <label class="form-label">SKU (Stock Keeping Unit) *</label>
                    <input type="text" name="sku" class="form-control custom-input" placeholder="Enter SKU" required>
                </div>

                <!-- field nama produk -->
                <div class="mb-3">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="product_name" class="form-control custom-input" placeholder="Enter product name" required>
                </div>

                <!-- field harga produk -->
                <div class="mb-3">
                    <label class="form-label">Price (Rupiah) *</label>
                    <input type="number" name="price" class="form-control custom-input" min="0" step="0.01" placeholder="Enter product price" required>
                </div>

                <!-- field stok awal -->
                <div class="mb-4">
                    <label class="form-label">Initial Stock</label>
                    <input type="number" name="stock" class="form-control custom-input" min="0" placeholder="Enter initial stock" value="0">
                </div>

                <!-- baris tombol simpan & batal -->
                <div class="row g-3">
                    <div class="col-6">
                        <a href="index.php" class="btn-cancel w-100">Cancel</a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn-submit w-100">Save Product</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>