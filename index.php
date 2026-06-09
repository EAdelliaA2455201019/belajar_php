<?php
include 'config.php';

try {
    // ambil data
    $stmt = $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected successfully<br>';
}
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// mengambil semua data produk dari database
$query = "SELECT * FROM products ORDER BY id DESC";
$stmt = $pdo->query($query);
$products = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Produk</title>
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- custom css -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            overflow-x: hidden;
        }

        /* Navbar Floating Pill Style */
        .navbar-wrapper {
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            padding: 12px 24px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: #0f172a !important;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 500;
            color: #64748b !important;
            transition: all 0.2s ease;
            padding: 0.5rem 1rem !important;
            border-radius: 10px;
            margin: 0 4px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #10b981 !important;
            background-color: rgba(16, 185, 129, 0.08);
        }

        /* styling untuk hero section */
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 50px 0;
            border-radius: 24px;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            letter-spacing: -1px;
            font-weight: 800;
        }

        .hero-image-wrapper {
            position: relative;
            z-index: 2;
        }

        .hero-image {
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 4px solid rgba(255, 255, 255, 0.1);
            object-fit: cover;
            width: 100%;
            height: auto;
            max-width: 320px;
        }

        /* styling untuk card */
        .card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
            background: #ffffff;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        /* styling untuk card header */
        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 24px 28px;
            font-weight: 700;
        }

        /* styling untuk form control */
        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
        }

        .custom-input-group {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
        }

        .custom-input-group:focus-within {
            border-color: #10b981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
            background-color: #ffffff;
            transform: translateY(-2px);
        }

        .custom-input-group .icon-wrapper {
            padding: 0 16px;
            color: #94a3b8;
            transition: color 0.3s ease;
            font-size: 1.1rem;
        }

        .custom-input-group:focus-within .icon-wrapper {
            color: #10b981;
        }

        .custom-input-group .form-control {
            border: none;
            background: transparent;
            box-shadow: none !important;
            padding: 14px 16px 14px 0;
            font-weight: 500;
            color: #1e293b;
        }
        
        .custom-input-group .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* styling untuk button primary */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.2);
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(16, 185, 129, 0.3);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        /* styling untuk tabel */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* animasi pulse untuk button */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 12px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .btn-pulse:hover {
            animation: pulse 1.5s infinite;
        }

        /* styling untuk badge harga */
        .price-badge {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            padding: 6px 14px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-block;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        /* Action buttons */
        .btn-action {
            width: 38px;
            height: 38px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin: 0 4px;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 1.1rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-edit {
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid transparent;
        }

        .btn-edit:hover {
            background: #f59e0b;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
        }

        .btn-delete {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid transparent;
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        }

        /* alert styling */
        .alert {
            border-radius: 16px;
            border: none;
            padding: 16px 20px;
        }

        /* icon wrapper styling */
        .icon-wrapper-primary {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .icon-wrapper-info {
            background: rgba(15, 23, 42, 0.08);
            color: #0f172a;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        /* animasi fade in */
        .fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .floating {
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- navbar -->
    <div class="navbar-wrapper container">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid px-2">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <div class="bg-dark rounded-3 p-2 me-3 shadow-sm text-white d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                        <i class="bi bi-boxes fs-5"></i>
                    </div>
                    StoreHub
                </a>
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <i class="bi bi-list fs-2 text-dark"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="bi bi-grid-fill me-2 opacity-75"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-box-seam-fill me-2 opacity-75"></i> Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-pie-chart-fill me-2 opacity-75"></i> Laporan
                            </a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center mt-3 mt-lg-0">
                        <div class="position-relative me-4" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="bi bi-bell-fill fs-5 text-secondary"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle shadow-sm"></span>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" style="color: inherit;">
                                <img src="https://ui-avatars.com/api/?name=Admin+User&background=0f172a&color=fff&bold=true&rounded=4" alt="Profile" class="shadow-sm rounded-3" width="44" height="44">
                                <div class="ms-3 d-none d-xl-block">
                                    <span class="d-block text-dark fw-bold" style="font-size: 0.9rem;">Admin User</span>
                                    <span class="d-block text-muted" style="font-size: 0.75rem;">Administrator</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2 rounded-4">
                                <li><a class="dropdown-item py-2 px-3 rounded-3 fw-medium" href="#"><i class="bi bi-person me-2 opacity-50"></i>Profil Saya</a></li>
                                <li><a class="dropdown-item py-2 px-3 rounded-3 fw-medium" href="#"><i class="bi bi-gear me-2 opacity-50"></i>Pengaturan</a></li>
                                <li>
                                    <hr class="dropdown-divider my-2">
                                </li>
                                <li><a class="dropdown-item py-2 px-3 rounded-3 fw-medium text-danger" href="#"><i class="bi bi-box-arrow-right me-2 opacity-50"></i>Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <div class="container flex-grow-1">
        <!-- Hero Section with Clean Dark Aesthetic -->
        <div class="hero-section px-4 px-md-5 fade-in" style="animation-delay: 0.1s;">
            <div class="row align-items-center hero-content">
                <div class="col-lg-7 text-center text-lg-start">
                    <h1 class="display-4 fw-bolder mb-3 text-white">Kelola Inventaris<br>Lebih Efisien.</h1>
                    <p class="lead mb-4 text-white-50 mx-auto mx-lg-0" style="max-width: 550px; font-weight: 400; font-size: 1.1rem;">Platform modern yang dirancang khusus untuk memaksimalkan produktivitas Anda. Pantau stok barang dan perbarui data dalam hitungan detik.</p>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-end hero-image-wrapper floating" style="animation-delay: 0.5s;">
                    <!-- Gambar relevan (Inventory / Box) dari Unsplash -->
                    <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?auto=format&fit=crop&w=500&q=80" alt="Inventory Management" class="hero-image">
                </div>
            </div>
        </div>

        <!-- main content -->
        <div class="row g-4 g-lg-5 pb-5">

            <!-- form tambah produk -->
            <div class="col-lg-4">
                <div class="card fade-in h-100" style="animation-delay: 0.2s;">
                    <div class="card-header border-0 pt-4 pb-0 bg-transparent">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <div class="icon-wrapper-primary me-3">
                                <i class="bi bi-plus-lg fs-5"></i>
                            </div>
                            Tambah Produk
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-4">

                        <?php if (isset($_GET['error'])) {
                            $error = htmlspecialchars($_GET['error']); ?>
                            <div class="alert alert-danger d-flex align-items-center rounded-3 border-0 bg-danger bg-opacity-10 text-danger mb-4" role="alert">
                                <i class="bi bi-exclamation-octagon-fill me-3 fs-5"></i>
                                <div class="fw-medium"><?php echo $error; ?></div>
                            </div>
                        <?php } ?>

                        <?php if (isset($_GET['success'])) { ?>
                            <div id="success-alert" class="alert alert-success d-flex align-items-center rounded-3 border-0 bg-success bg-opacity-10 text-success mb-4" role="alert">
                                <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                <div class="fw-medium">Produk berhasil ditambahkan!</div>
                            </div>
                        <?php } ?>

                        <form action="process_add.php" method="POST">
                            <div class="mb-4">
                                <label class="form-label">Nama Produk</label>
                                <div class="custom-input-group">
                                    <div class="icon-wrapper">
                                        <i class="bi bi-box-fill"></i>
                                    </div>
                                    <input type="text" class="form-control" id="inputProductName" name="name" placeholder="Misal: iPhone 15 Pro Max" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Harga Produk (Rp)</label>
                                <div class="custom-input-group">
                                    <div class="icon-wrapper">
                                        <i class="bi bi-tags-fill"></i>
                                    </div>
                                    <input type="number" class="form-control" id="inputProductPrice" name="price" placeholder="Contoh: 15000000" min="0" required>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-pulse btn-lg d-flex align-items-center justify-content-center py-2">
                                    <i class="bi bi-cloud-arrow-up-fill me-2 fs-5"></i> Simpan ke Database
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- daftar produk -->
            <div class="col-lg-8">
                <div class="card fade-in h-100" style="animation-delay: 0.3s;">
                    <div class="card-header border-0 pt-4 pb-3 bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <div class="icon-wrapper-info me-3">
                                <i class="bi bi-grid-1x2-fill fs-5"></i>
                            </div>
                            Inventaris Produk
                        </h5>
                        <span class="badge bg-dark text-white rounded-pill px-3 py-2 fw-semibold shadow-sm">
                            <?php echo count($products); ?> Item Tersedia
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($products) > 0) { ?>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th width="50%">Detail Produk</th>
                                            <th width="25%">Harga Jual</th>
                                            <th class="text-center" width="20%">Kelola</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($products as $product) {
                                        ?>
                                            <tr>
                                                <td class="text-center text-muted fw-bold"><?php echo $no++; ?></td>
                                                <td>
                                                    <h6 class="mb-1 fw-bold text-dark fs-6"><?php echo htmlspecialchars($product['name']); ?></h6>
                                                    <span class="badge bg-light text-secondary border px-2 py-1" style="font-family: monospace;">#PRD-<?php echo str_pad($product['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                                </td>
                                                <td>
                                                    <span class="price-badge">
                                                        Rp <?php echo number_format($product['price'], 0, ",", "."); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="javascript:void(0)" class="btn btn-action btn-edit" title="Edit (Demo)" onclick="alert('Fitur Edit belum tersedia')">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="btn btn-action btn-delete" title="Hapus (Demo)" onclick="alert('Fitur Hapus belum tersedia')">
                                                            <i class="bi bi-trash3-fill"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="text-center py-5 my-5">
                                <div class="bg-light rounded-circle d-inline-flex p-4 mb-4">
                                    <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                </div>
                                <h4 class="text-dark fw-bold">Inventaris Masih Kosong</h4>
                                <p class="text-muted mb-0" style="max-width: 400px; margin: 0 auto;">Belum ada data produk yang ditambahkan. Gunakan form di sebelah kiri untuk mulai menambahkan produk pertama Anda.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        // Sembunyikan alert success setelah 3 detik
        document.addEventListener('DOMContentLoaded', function() {
            var successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.transition = 'opacity 0.5s ease';
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        successAlert.style.display = 'none';
                    }, 500); // Tunggu sampai transisi fade out selesai
                }, 3000); // 3 detik sebelum mulai menghilang
            }
        });
    </script>
</body>

</html>