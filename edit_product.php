<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';
$product = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM PRODUCTS WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        $error = "Produk tidak ditemukan.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $product) {
    $id = $_POST['id'];
    $kode_produk = trim($_POST['kode_produk']);
    $nama_produk = trim($_POST['nama_produk']);
    $kategori = trim($_POST['kategori']);
    $harga = trim($_POST['harga']);
    $stok = trim($_POST['stok']);
    $deskripsi = trim($_POST['deskripsi']);

    if (empty($kode_produk) || empty($nama_produk) || empty($harga) || empty($stok)) {
        $error = "Semua field wajib diisi kecuali kategori dan deskripsi.";
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $error = "Harga harus berupa angka positif.";
    } elseif (!is_numeric($stok) || $stok < 0) {
        $error = "Stok harus berupa angka positif atau nol.";
    } else {
        $sql = "UPDATE PRODUCTS SET kode_produk = ?, nama_produk = ?, kategori = ?, harga = ?, stok = ?, deskripsi = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdisi", $kode_produk, $nama_produk, $kategori, $harga, $stok, $deskripsi, $id);

        if ($stmt->execute()) {
            $success = "Produk berhasil diupdate! <a href='products.php'>Lihat Produk</a>";
            $product['kode_produk'] = $kode_produk;
            $product['nama_produk'] = $nama_produk;
            $product['kategori'] = $kategori;
            $product['harga'] = $harga;
            $product['stok'] = $stok;
            $product['deskripsi'] = $deskripsi;
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-wide">
        <h2>Edit Produk</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($product): ?>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="kode_produk">Kode Produk</label>
                    <input type="text" name="kode_produk" id="kode_produk" required placeholder="Masukkan kode produk" value="<?php echo htmlspecialchars($product['kode_produk']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" name="nama_produk" id="nama_produk" required placeholder="Masukkan nama produk" value="<?php echo htmlspecialchars($product['nama_produk']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <input type="text" name="kategori" id="kategori" placeholder="Masukkan kategori produk" value="<?php echo htmlspecialchars($product['kategori'] ?? ''); ?>">
                    <small>Opsional</small>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" step="0.01" name="harga" id="harga" required placeholder="Masukkan harga produk" value="<?php echo htmlspecialchars($product['harga']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" name="stok" id="stok" required placeholder="Masukkan jumlah stok" value="<?php echo htmlspecialchars($product['stok']); ?>">
                </div>
            </div>

            <div class="form-row-full">
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" placeholder="Masukkan deskripsi produk" rows="4"><?php echo htmlspecialchars($product['deskripsi'] ?? ''); ?></textarea>
                    <small>Opsional</small>
                </div>
            </div>

            <button type="submit" class="btn">Update Produk</button>
        </form>
        <?php endif; ?>

        <div class="links">
            <p><a href="dashboard.php">Kembali ke Dashboard</a> | <a href="products.php">Lihat Produk</a></p>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>