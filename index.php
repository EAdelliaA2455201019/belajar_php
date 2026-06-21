<?php
// =============================================================
// file: index.php
// fungsi: halaman utama menampilkan dasbor & list produk
// =============================================================

// hubungkan ke database
include 'config.php';

// buat array kosong untuk menampung data produk
$products = [];
$dbError  = '';

try {
    // buat koneksi pdo ke database
    $pdo = new PDO($dsn, $user, $pass);
    // atur pdo agar melemparkan error exception jika terjadi masalah
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // kueri ambil semua data produk dari yang paling baru
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    // masukkan data ke variabel products
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // jika ada error, tangkap pesannya
    $dbError = $e->getMessage();
}

// hitung total statistik produk dan nilai aset
$totalProducts = count($products);
$totalAssetValue = 0;
$lowStockCount = 0;

foreach ($products as $p) {
    // hitung total nilai berdasarkan harga kali stok produk
    $totalAssetValue += $p['price'] * $p['stock'];
    // tandai produk yang stoknya kurang dari 10
    if ($p['stock'] < 10) {
        $lowStockCount++;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- judul halaman menggunakan bahasa inggris -->
    <title>Product Dashboard - GudangKu</title>
    <!-- load bootstrap 5 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- load icon bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- load font modern dari google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* variabel warna tema sederhana maksimal 3 warna dominan */
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --dark: #0f172a;
            --bg: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
        }

        /* sidebar modern minimalis */
        .sidebar {
            background-color: var(--dark);
            min-height: 100vh;
            color: #ffffff;
            padding: 2rem 1.5rem;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 3rem;
        }

        .sidebar-brand i {
            color: var(--primary);
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.8rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
        }

        .sidebar-nav-item a:hover,
        .sidebar-nav-item a.active {
            background-color: rgba(79, 70, 229, 0.15);
            color: #ffffff;
        }

        .sidebar-nav-item a.active {
            background-color: var(--primary);
        }

        /* area konten utama */
        .content-area {
            padding: 2.5rem 3rem;
        }

        /* kartu statistik */
        .stat-card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background-color: rgba(79, 70, 229, 0.08);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* kartu pembungkus tabel */
        .table-container-card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* tabel kustom */
        .custom-table {
            vertical-align: middle;
        }

        .custom-table thead th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-bottom: 2px solid var(--border);
        }

        .custom-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        /* badge kustom */
        .sku-badge {
            background-color: #f1f5f9;
            color: var(--text-muted);
            font-family: monospace;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .price-badge {
            color: var(--primary);
            font-weight: 700;
        }

        .stock-badge {
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        /* tombol aksi */
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            color: #ffffff;
        }

        .btn-edit { background-color: #f59e0b; }
        .btn-edit:hover { background-color: #d97706; }

        .btn-delete { background-color: #ef4444; }
        .btn-delete:hover { background-color: #dc2626; }

        .btn-stock { background-color: #10b981; }
        .btn-stock:hover { background-color: #059669; }

        /* alert melayang */
        .floating-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>

<!-- layout sistem grid bootstrap: sidebar col-md-3, main content col-md-9 -->
<div class="container-fluid">
    <div class="row">
        
        <!-- kolom sidebar -->
        <div class="col-12 col-md-3 sidebar">
            <div class="sidebar-brand">
                <i class="bi bi-boxes"></i>
                <span>GudangKu</span>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a href="index.php" class="active">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="add.php">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Add Product</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- kolom konten utama -->
        <div class="col-12 col-md-9 content-area">
            
            <!-- header utama -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="fw-800 m-0">Inventory Dashboard</h1>
                    <p class="text-muted m-0">Manage your product stocks and track business performance</p>
                </div>
                <a href="add.php" class="btn btn-primary px-4 py-2" style="border-radius: 12px; font-weight: 600;">
                    <i class="bi bi-plus-lg me-2"></i>Add Product
                </a>
            </div>

            <!-- notifikasi melayang dalam bahasa inggris -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show floating-alert py-3 px-4" role="alert" id="successAlert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Success!</strong> 
                    <?php
                    // pilih pesan sukses bahasa inggris
                    echo match($_GET['success']) {
                        'added'   => 'Product added successfully!',
                        'updated' => 'Product updated successfully!',
                        'deleted' => 'Product deleted successfully!',
                        'stock'   => 'Stock updated successfully!',
                        default   => 'Action completed successfully!'
                    };
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show floating-alert py-3 px-4" role="alert" id="errorAlert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Error!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- row statistik ringkasan -->
            <div class="row g-4 mb-5">
                <!-- total produk -->
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted fw-600 fs-7">Total Products</span>
                                <h3 class="fw-800 mt-2 mb-0"><?php echo $totalProducts; ?></h3>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- nilai aset -->
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted fw-600 fs-7">Total Asset Value</span>
                                <h3 class="fw-800 mt-2 mb-0">Rp <?php echo number_format($totalAssetValue, 0, ',', '.'); ?></h3>
                            </div>
                            <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.08); color: #10b981;">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- stok kritis -->
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted fw-600 fs-7">Low Stock Alert</span>
                                <h3 class="fw-800 mt-2 mb-0" style="color: #ef4444;"><?php echo $lowStockCount; ?></h3>
                            </div>
                            <div class="stat-icon" style="background-color: rgba(239, 68, 68, 0.08); color: #ef4444;">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card tabel produk -->
            <div class="table-container-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-800 m-0">All Products List</h5>
                    <span class="text-muted fs-7 font-weight-bold"><?php echo $totalProducts; ?> products registered</span>
                </div>

                <?php if ($totalProducts > 0): ?>
                    <div class="table-responsive">
                        <!-- tabel menggunakan kelas table-striped dan table-hover sesuai slide 3 -->
                        <table class="table table-striped table-hover custom-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>SKU</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($products as $p):
                                    // tentukan warna badge stok berdasarkan jumlah stoknya
                                    if ($p['stock'] < 10) {
                                        $stockColor = 'background-color: rgba(239,68,68,0.1); color: #ef4444;';
                                    } elseif ($p['stock'] < 30) {
                                        $stockColor = 'background-color: rgba(245,158,11,0.1); color: #f59e0b;';
                                    } else {
                                        $stockColor = 'background-color: rgba(16,185,129,0.1); color: #10b981;';
                                    }
                                ?>
                                    <tr>
                                        <td class="text-center text-muted fw-bold"><?php echo $no++; ?></td>
                                        <td><span class="sku-badge"><?php echo htmlspecialchars($p['sku']); ?></span></td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($p['product_name']); ?></td>
                                        <td class="price-badge">Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <span class="stock-badge" style="<?php echo $stockColor; ?>">
                                                <?php echo $p['stock']; ?> pcs
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- tombol edit arahkan ke edit.php -->
                                                <a href="edit.php?id=<?php echo $p['id']; ?>" class="action-btn btn-edit" title="Edit Product">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <!-- tombol hapus panggil konfirmasi bahasa inggris -->
                                                <a href="delete.php?id=<?php echo $p['id']; ?>" class="action-btn btn-delete" title="Delete Product"
                                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>
                                                <!-- tombol update stok memanggil modal -->
                                                <button type="button" class="action-btn btn-stock" title="Update Stock"
                                                        onclick="triggerStockModal(<?php echo $p['id']; ?>, '<?php echo htmlspecialchars(addslashes($p['product_name'])); ?>', <?php echo $p['stock']; ?>)">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- jika produk kosong, tampilkan info -->
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">No products found</h5>
                        <p class="text-muted fs-7">Get started by creating your first product item.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>

<!-- modal update stok -->
<div class="modal fade" id="stockAdjustmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Stock Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- form post ke update_stok.php -->
            <form action="update_stok.php" method="POST">
                <div class="modal-body">
                    <p class="text-muted mb-3">Product Name: <strong id="modalProdName"></strong></p>
                    <p class="mb-4">Current Stock: <span class="badge bg-dark px-3 py-2 fs-7" id="modalCurrentStock"></span></p>

                    <!-- id produk yang dikirim secara tersembunyi -->
                    <input type="hidden" name="id" id="modalProdId">

                    <!-- pilihan menu tambah/kurang -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Adjustment Type</label>
                        <select name="action" class="form-select py-2" style="border-radius: 10px;" required>
                            <option value="tambah">Stok Masuk / Inbound (Add)</option>
                            <option value="kurang">Stok Keluar / Outbound (Subtract)</option>
                        </select>
                    </div>

                    <!-- input jumlah stok baru -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantity</label>
                        <input type="number" name="qty" class="form-control py-2" style="border-radius: 10px;" min="1" placeholder="Enter amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- load bootstrap bundle javascript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // fungsi javascript untuk memunculkan modal penyesuaian stok
    function triggerStockModal(id, name, stock) {
        document.getElementById('modalProdId').value = id;
        document.getElementById('modalProdName').textContent = name;
        document.getElementById('modalCurrentStock').textContent = stock + ' pcs';
        new bootstrap.Modal(document.getElementById('stockAdjustmentModal')).show();
    }

    // hilangkan alert setelah beberapa detik otomatis
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 4000);
    }
    const errorAlert = document.getElementById('errorAlert');
    if (errorAlert) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(errorAlert);
            bsAlert.close();
        }, 4000);
    }
</script>
</body>
</html>