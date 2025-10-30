<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

include 'includes/db.php';
include 'includes/functions.php';

$user = getUserByEmail($conn, $email);
$products = getAllProducts($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Gudang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="dashboard">
    <div class="dashboard-container">
        <!-- Header Dashboard -->
        <div class="dashboard-header">
            <h1>Dashboard Admin Gudang</h1>
            <p>Selamat datang, <strong><?php echo htmlspecialchars($user['fullname']); ?></strong> (<?php echo htmlspecialchars($user['role']); ?>)</p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            
            <div class="dashboard-nav">
                <a href="profile.php">Profile</a>
                <a href="change_password.php">Ubah Password</a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="dashboard-content">
            <h2>Manajemen Produk</h2>
            
            <div class="action-buttons">
                <a href="add_product.php">Tambah Produk Baru</a>
                <a href="products.php">Lihat Semua Produk</a>
            </div>

            <div class="dashboard-footer">
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>