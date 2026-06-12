<?php
// memanggil koneksi dan ambil data produk untuk ditampilkan di tabel
include 'config.php';
$products = [];
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM products ORDER BY id DESC";
    $stmt = $pdo->query($query);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    // abaikan jika gagal koneksi
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container-xxl">
        <nav class="navbar bg-primary navbar-expand-lg text-white" data-bs-theme="light">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active text-white" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled text-white" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- form input -->
        <div class="container mb-5">
            <h3 class="mt-4 mb-3">Add Product</h3>
            <form method="POST" action="process_add.php">
                <div class="mb-3">
                    <label for="inputProuctName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="inputProuctName" name="product_name" aria-describedby="productNameHelp">
                    <div id="productNameHelp" class="form-text">Enter the product name</div>
                </div>
                <div class="mb-3">
                    <label for="inputProductPrice" class="form-label">Product Price</label>
                    <input type="number" class="form-control" id="inputProductPrice" name="product_price" aria-describedby="productPriceHelp">
                    <div id="productPriceHelp" class="form-text">Enter the product price</div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <!-- tabel daftar produk ditambahkan di sini -->
        <div class="container">
            <h3 class="mb-3">Products List</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($product['name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($product['price'] ?? ''); ?></td>
                            <td>
                                <!-- tombol edit dan delete -->
                                <a href="edit.php?id=<?php echo htmlspecialchars($product['id'] ?? ''); ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete.php?id=<?php echo htmlspecialchars($product['id'] ?? ''); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>