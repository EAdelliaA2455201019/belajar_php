<?php
include 'config.php';

// inisialisasi awal agar tidak error jika koneksi gagal
$products = [];
$dbError  = '';

try {
    // ambil data
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "SELECT * FROM products ORDER BY id DESC";
    $stmt = $pdo->query($query);
    $products = $stmt->fetchAll();
}
catch (PDOException $e) {
    $dbError = $e->getMessage();
}

// menentukan halaman aktif dari url (default ke dashboard jika kosong)
$page = $_GET['page'] ?? 'dashboard';

?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GudangKu App</title>
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- custom css -->
    <style>
        :root {
            --primary: #4f46e5;      /* Indigo 600 */
            --primary-hover: #4338ca; /* Indigo 700 */
            --secondary: #ec4899;    /* Pink 500 */
            --dark: #0f172a;         /* Slate 900 */
            --light-bg: #f8fafc;     /* Slate 50 */
            --card-bg: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
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
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            padding: 12px 24px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--dark) !important;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 600;
            color: #64748b !important;
            transition: all 0.3s ease;
            padding: 0.6rem 1.2rem !important;
            border-radius: 14px;
            margin: 0 4px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary) !important;
            background-color: rgba(79, 70, 229, 0.1);
        }

        /* styling untuk hero section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, #312e81 100%);
            color: white;
            padding: 50px 0;
            border-radius: 28px;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.25);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
            transform: rotate(30deg);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            letter-spacing: -1.5px;
            font-weight: 800;
        }

        .hero-image-wrapper {
            position: relative;
            z-index: 2;
        }

        .hero-image {
            border-radius: 24px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);
            border: 4px solid rgba(255, 255, 255, 0.15);
            object-fit: cover;
            width: 100%;
            height: auto;
            max-width: 320px;
        }

        /* styling untuk card */
        .card {
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 28px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.08);
            border-color: rgba(255, 255, 255, 1);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 24px 28px;
            font-weight: 800;
        }

        /* styling untuk form control */
        .form-label {
            font-weight: 700;
            color: #334155;
            font-size: 0.9rem;
            margin-bottom: 0.6rem;
        }

        .custom-input-group {
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            background-color: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
        }

        .custom-input-group:focus-within {
            border-color: var(--primary);
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }

        .custom-input-group .icon-wrapper {
            padding: 0 16px;
            color: #94a3b8;
            transition: color 0.3s ease;
            font-size: 1.2rem;
        }

        .custom-input-group:focus-within .icon-wrapper {
            color: var(--primary);
        }

        .custom-input-group .form-control {
            border: none;
            background: transparent;
            box-shadow: none !important;
            padding: 16px 16px 16px 0;
            font-weight: 600;
            color: #1e293b;
        }
        
        .custom-input-group .form-control::placeholder {
            color: #cbd5e1;
            font-weight: 500;
        }

        /* styling untuk button primary */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            border: none;
            padding: 14px 28px;
            border-radius: 16px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.4);
            background: linear-gradient(135deg, var(--primary-hover) 0%, #3730a3 100%);
        }

        .btn-outline-primary {
            color: var(--primary);
            border: 2px solid var(--primary);
            font-weight: 700;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }

        .bg-primary {
            background-color: var(--primary) !important;
        }

        .text-primary {
            color: var(--primary) !important;
        }

        /* styling untuk tabel */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: transparent;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            border-bottom: 2px solid #e2e8f0;
            padding: 16px;
        }

        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            color: #334155;
            font-weight: 500;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.03);
        }

        /* animasi pulse untuk button */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 14px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }

        .btn-pulse:hover {
            animation: pulse 1.5s infinite;
        }

        /* styling untuk badge harga */
        .price-badge {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 30px;
            font-weight: 800;
            font-size: 0.95rem;
            display: inline-block;
            border: 1px solid rgba(79, 70, 229, 0.2);
        }

        /* Action buttons */
        .btn-action {
            width: 42px;
            height: 42px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin: 0 4px;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-size: 1.2rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-edit {
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .btn-edit:hover {
            background: #f59e0b;
            color: white;
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 8px 16px rgba(245, 158, 11, 0.3);
        }

        .btn-delete {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
        }

        /* alert styling */
        .alert {
            border-radius: 20px;
            border: none;
            padding: 16px 24px;
        }

        /* icon wrapper styling */
        .icon-wrapper-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
        }

        .icon-wrapper-info {
            background: rgba(15, 23, 42, 0.05);
            color: var(--dark);
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
        }

        /* animasi */
        .fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            opacity: 0;
        }

        .floating {
            animation: floating 5s ease-in-out infinite;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
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
                    GudangKu
                </a>
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <i class="bi bi-list fs-2 text-dark"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>" href="?page=dashboard">
                                <i class="bi bi-grid-fill me-2 opacity-75"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $page === 'produk' ? 'active' : ''; ?>" href="?page=produk">
                                <i class="bi bi-box-seam-fill me-2 opacity-75"></i> Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $page === 'laporan' ? 'active' : ''; ?>" href="?page=laporan">
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
                                <li><a class="dropdown-item py-2 px-3 rounded-3 fw-medium <?php echo $page === 'profil' ? 'active bg-primary text-white' : ''; ?>" href="?page=profil"><i class="bi bi-person-circle me-2 <?php echo $page === 'profil' ? '' : 'text-primary opacity-75'; ?>"></i>Profil Saya</a></li>
                                <li><a class="dropdown-item py-2 px-3 rounded-3 fw-medium <?php echo $page === 'pengaturan' ? 'active bg-primary text-white' : ''; ?>" href="?page=pengaturan"><i class="bi bi-gear-fill me-2 <?php echo $page === 'pengaturan' ? '' : 'text-secondary opacity-75'; ?>"></i>Pengaturan</a></li>
                                <li>
                                    <hr class="dropdown-divider my-2">
                                </li>
                                <li><a class="dropdown-item py-2 px-3 rounded-3 fw-medium text-danger bg-danger-hover" href="?page=keluar"><i class="bi bi-box-arrow-right me-2 opacity-75"></i>Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <div class="container flex-grow-1">

        <?php if ($page === 'dashboard'): ?>
        <!-- ================= HALAMAN DASHBOARD ================= -->
        <!-- Hero Section with Clean Dark Aesthetic -->
        <div class="hero-section px-4 px-md-5 fade-in" style="animation-delay: 0.1s;">
            <div class="row align-items-center hero-content">
                <div class="col-lg-7 text-center text-lg-start">
                    <h1 class="display-4 fw-bolder mb-3 text-white">Kelola Inventaris<br>Lebih Efisien.</h1>
                    <p class="lead mb-4 text-white-50 mx-auto mx-lg-0" style="max-width: 550px; font-weight: 400; font-size: 1.1rem;">Platform modern yang dirancang khusus untuk memaksimalkan produktivitas Anda. Pantau stok barang dan perbarui data dalam hitungan detik.</p>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-end floating" style="animation-delay: 0.5s;">
                    <div id="heroCarousel" class="carousel slide carousel-fade shadow-lg" data-bs-ride="carousel" style="border-radius: 24px; border: 4px solid rgba(255, 255, 255, 0.15); overflow: hidden; box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="3000">
                                <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?auto=format&fit=crop&w=500&q=80" class="d-block w-100" alt="Inventory Management" style="height: 320px; object-fit: cover;">
                            </div>
                            <div class="carousel-item" data-bs-interval="3000">
                                <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7c508b0?auto=format&fit=crop&w=500&q=80" class="d-block w-100" alt="Warehouse Operations" style="height: 320px; object-fit: cover;">
                            </div>
                            <div class="carousel-item" data-bs-interval="3000">
                                <img src="https://images.unsplash.com/photo-1580674285054-bed31e145f59?auto=format&fit=crop&w=500&q=80" class="d-block w-100" alt="Logistics and Shipping" style="height: 320px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
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
                            $errorMsg = htmlspecialchars($_GET['error']); ?>
                            <div class="alert alert-danger d-flex align-items-center rounded-3 border-0 bg-danger bg-opacity-10 text-danger mb-4" role="alert">
                                <i class="bi bi-exclamation-octagon-fill me-3 fs-5"></i>
                                <div class="fw-medium"><?php echo $errorMsg; ?></div>
                            </div>
                        <?php } ?>

                        <?php if (isset($_GET['success'])) { ?>
                            <div id="success-alert" class="alert alert-success d-flex align-items-center rounded-3 border-0 bg-success bg-opacity-10 text-success mb-4" role="alert">
                                <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                <div class="fw-medium">Aksi berhasil dilakukan!</div>
                            </div>
                        <?php } ?>

                        <form action="process_add.php" method="POST">
                            <div class="mb-4">
                                <label class="form-label">Nama Produk</label>
                                <div class="custom-input-group">
                                    <div class="icon-wrapper">
                                        <i class="bi bi-box-fill"></i>
                                    </div>
                                    <!-- Perbaikan name input disesuaikan dengan form asli -->
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
                                                        <!-- tombol edit: kirim id produk ke halaman edit.php via query string -->
                                                        <a href="edit.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-action btn-edit" title="Edit Produk">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <!-- tombol delete: kirim id ke delete.php, tampilkan konfirmasi sebelum hapus -->
                                                        <a href="delete.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-action btn-delete" title="Hapus Produk" onclick="return confirm('Yakin ingin menghapus produk ini?')">
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
        <!-- ================= AKHIR HALAMAN DASHBOARD ================= -->

        <?php elseif ($page === 'produk'): ?>
        <!-- ================= HALAMAN KHUSUS PRODUK ================= -->
        <div class="row pb-5 fade-in">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                    <h3 class="fw-bold text-dark mb-0">Manajemen Produk</h3>
                    <a href="?page=dashboard" class="btn btn-primary d-flex align-items-center rounded-pill px-4">
                        <i class="bi bi-plus-lg me-2"></i> Tambah Baru
                    </a>
                </div>
                
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-0">
                        <?php if (count($products) > 0) { ?>
                            <div class="table-responsive">
                                <table class="table align-middle table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center py-3" width="5%">#</th>
                                            <th class="py-3" width="50%">Detail Produk</th>
                                            <th class="py-3" width="25%">Harga Jual</th>
                                            <th class="text-center py-3" width="20%">Kelola</th>
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
                                                        <a href="edit.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-action btn-edit" title="Edit Produk">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="delete.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-action btn-delete" title="Hapus Produk" onclick="return confirm('Yakin ingin menghapus produk ini?')">
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
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                <h4 class="text-dark fw-bold mt-3">Belum ada Produk</h4>
                                <p class="text-muted">Klik tombol 'Tambah Baru' untuk mulai memasukkan produk ke inventaris.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- ================= AKHIR HALAMAN KHUSUS PRODUK ================= -->

        <?php elseif ($page === 'laporan'): ?>
        <!-- ================= HALAMAN LAPORAN ================= -->
        <div class="row pb-5 fade-in">
            <div class="col-12">
                <h3 class="fw-bold text-dark mb-4 mt-2">Laporan Ringkasan</h3>
                
                <div class="row g-4">
                    <!-- Kartu Total Produk -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card bg-white border-0 shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="text-muted fw-semibold mb-0">Total Produk</h6>
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-box-seam fs-4"></i>
                                </div>
                            </div>
                            <h2 class="fw-bold mb-0"><?php echo count($products); ?></h2>
                            <span class="text-success small fw-medium mt-2"><i class="bi bi-arrow-up-right me-1"></i> Item terdaftar di sistem</span>
                        </div>
                    </div>

                    <!-- Kartu Estimasi Total Nilai (Total Harga) -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card bg-white border-0 shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="text-muted fw-semibold mb-0">Estimasi Nilai Aset</h6>
                                <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-cash-stack fs-4"></i>
                                </div>
                            </div>
                            <?php 
                                // hitung total harga semua produk
                                $totalValue = 0;
                                foreach ($products as $p) {
                                    $totalValue += $p['price'];
                                }
                            ?>
                            <h2 class="fw-bold mb-0 text-truncate" title="Rp <?php echo number_format($totalValue, 0, ',', '.'); ?>">
                                Rp <?php echo number_format($totalValue, 0, ',', '.'); ?>
                            </h2>
                            <span class="text-muted small fw-medium mt-2">Total harga dari seluruh produk</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ================= AKHIR HALAMAN LAPORAN ================= -->

        <?php elseif ($page === 'profil'): ?>
        <!-- ================= HALAMAN PROFIL ================= -->
        <div class="row justify-content-center pb-5 fade-in">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="bg-primary bg-gradient p-5 text-center position-relative">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=0f172a&color=fff&bold=true&size=120" alt="Profile" class="rounded-circle border border-4 border-white shadow mt-4 position-absolute start-50 translate-middle-x" style="bottom: -50px;">
                    </div>
                    <div class="card-body pt-5 mt-4 text-center px-5 pb-5">
                        <h4 class="fw-bold text-dark mb-1">Admin User</h4>
                        <p class="text-muted fw-medium mb-4"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Administrator Sistem</p>
                        
                        <div class="d-grid gap-2 text-start mt-4">
                            <div class="bg-light p-3 rounded-3 mb-2 d-flex align-items-center">
                                <i class="bi bi-envelope-fill text-primary fs-4 me-3"></i>
                                <div>
                                    <small class="text-muted d-block fw-semibold">Email Aktif</small>
                                    <span class="text-dark fw-bold">admin@gudangku.com</span>
                                </div>
                            </div>
                            <div class="bg-light p-3 rounded-3 d-flex align-items-center">
                                <i class="bi bi-telephone-fill text-success fs-4 me-3"></i>
                                <div>
                                    <small class="text-muted d-block fw-semibold">Nomor HP</small>
                                    <span class="text-dark fw-bold">+62 812 3456 7890</span>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary fw-bold mt-4 px-4 rounded-pill"><i class="bi bi-pencil-square me-2"></i>Edit Profil</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ================= AKHIR HALAMAN PROFIL ================= -->

        <?php elseif ($page === 'pengaturan'): ?>
        <!-- ================= HALAMAN PENGATURAN ================= -->
        <div class="row pb-5 fade-in">
            <div class="col-lg-8 mx-auto">
                <h3 class="fw-bold text-dark mb-4 mt-2"><i class="bi bi-gear-fill me-2 text-secondary"></i> Pengaturan Sistem</h3>
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold border-bottom pb-3 mb-4 text-dark">Preferensi Aplikasi</h5>
                    <form>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Nama Toko / Perusahaan</label>
                            <input type="text" class="form-control form-control-lg bg-light" value="GudangKu Indonesia">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Mata Uang Default</label>
                            <select class="form-select form-select-lg bg-light">
                                <option value="IDR" selected>Rupiah (IDR)</option>
                                <option value="USD">US Dollar (USD)</option>
                            </select>
                        </div>
                        <div class="mb-4 d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                            <div>
                                <h6 class="fw-bold mb-1">Notifikasi Suara</h6>
                                <small class="text-muted">Bunyikan suara saat ada aksi produk</small>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" role="switch" checked>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary px-4 py-2 fw-bold"><i class="bi bi-save2-fill me-2"></i> Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- ================= AKHIR HALAMAN PENGATURAN ================= -->

        <?php elseif ($page === 'keluar'): ?>
        <!-- ================= HALAMAN KELUAR ================= -->
        <div class="row justify-content-center align-items-center h-100 fade-in py-5 mt-5">
            <div class="col-md-6 text-center">
                <div class="bg-white p-5 rounded-4 shadow-sm border border-light">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex p-4 mb-4">
                        <i class="bi bi-door-open-fill" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Sesi Berakhir</h2>
                    <p class="text-muted mb-4 fs-5">Anda telah berhasil keluar dari sistem aplikasi GudangKu.</p>
                    <a href="?page=dashboard" class="btn btn-primary btn-lg px-5 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Kembali
                    </a>
                </div>
            </div>
        </div>
        <!-- ================= AKHIR HALAMAN KELUAR ================= -->

        <?php endif; ?>

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