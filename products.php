<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$products = getAllProducts($conn);

// Ambil pesan dari query parameter
$message = isset($_GET['message']) ? $_GET['message'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="dashboard">
    <div class="dashboard-container">
        <!-- Header Dashboard -->
        <div class="dashboard-header">
            <h1>Daftar Produk</h1>
            <p>Kelola semua produk di gudang</p>
            
            <div class="dashboard-nav">
                <a href="dashboard.php">Kembali ke Dashboard</a>
                <a href="add_product.php">Tambah Produk Baru</a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="dashboard-content">
            <!-- Success/Error Messages -->
            <?php if (!empty($message)): ?>
                <div class="success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <h2>Data Produk</h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($products) > 0): ?>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['kode_produk']); ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['kategori'] ?? '-'); ?></td>
                                <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                <td>
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Hapus produk ini?');">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-message">
                                    Tidak ada produk. <a href="add_product.php">Tambah produk baru</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer dengan Logout Button -->
            <div class="dashboard-footer">
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>