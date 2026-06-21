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
            --primary: #4f46e5;
            --dark: #0f172a;
            --bg: #f8fafc;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* kartu form modern */
        .form-card {
            background-color: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
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
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: #ffffff;
            padding: 2rem;
        }

        .form-body {
            padding: 2.5rem 2rem;
        }

        .form-label {
            font-weight: 700;
            font-size: 0.85rem;
            color: #334155;
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
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .btn-submit {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 700;
            padding: 0.8rem;
            border-radius: 12px;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn-submit:hover {
            background-color: #3730a3;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background-color: #f1f5f9;
            color: #64748b;
            font-weight: 700;
            padding: 0.8rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            background-color: #e2e8f0;
            color: #334155;
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