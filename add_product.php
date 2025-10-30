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
$kode_produk = '';
$nama_produk = '';
$kategori = '';
$harga = '';
$stok = '';
$deskripsi = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        // Cek apakah kode produk sudah ada
        $cek_sql = "SELECT id FROM PRODUCTS WHERE kode_produk = ?";
        $cek_stmt = $conn->prepare($cek_sql);
        $cek_stmt->bind_param("s", $kode_produk);
        $cek_stmt->execute();
        $cek_result = $cek_stmt->get_result();
        
        if ($cek_result->num_rows > 0) {
            $error = "Kode produk '<strong>" . htmlspecialchars($kode_produk) . "</strong>' sudah ada di database. Gunakan kode produk yang berbeda!";
        } else {
            // Kode produk tidak ada, lakukan insert
            $sql = "INSERT INTO PRODUCTS (kode_produk, nama_produk, kategori, harga, stok, deskripsi) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdis", $kode_produk, $nama_produk, $kategori, $harga, $stok, $deskripsi);

            if ($stmt->execute()) {
                $success = "Produk berhasil ditambahkan. <a href='products.php'>Lihat Produk</a>";
                // Clear form setelah berhasil
                $kode_produk = '';
                $nama_produk = '';
                $kategori = '';
                $harga = '';
                $stok = '';
                $deskripsi = '';
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        
        $cek_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container-wide {
            background: #ffffff;
            padding: 40px;
            border: 1px solid #cccccc;
            max-width: 700px;
            width: 100%;
            margin: 20px auto;
        }

        .container-wide h2 {
            text-align: center;
            color: #000000;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: normal;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .container-wide {
                padding: 30px 20px;
                max-width: 100%;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container-wide">
        <h2>Tambah Produk Baru</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="kode_produk">Kode Produk</label>
                    <input type="text" name="kode_produk" id="kode_produk" required placeholder="Masukkan kode produk" value="<?php echo htmlspecialchars($kode_produk); ?>">
                </div>
                
                <div class="form-group">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" name="nama_produk" id="nama_produk" required placeholder="Masukkan nama produk" value="<?php echo htmlspecialchars($nama_produk); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <input type="text" name="kategori" id="kategori" placeholder="Masukkan kategori produk" value="<?php echo htmlspecialchars($kategori); ?>">
                    <small>Opsional</small>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" step="0.01" name="harga" id="harga" required placeholder="Masukkan harga produk" value="<?php echo htmlspecialchars($harga); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" name="stok" id="stok" required placeholder="Masukkan jumlah stok" value="<?php echo htmlspecialchars($stok); ?>">
                </div>
            </div>

            <div class="form-row-full">
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" placeholder="Masukkan deskripsi produk" rows="4"><?php echo htmlspecialchars($deskripsi); ?></textarea>
                    <small>Opsional</small>
                </div>
            </div>

            <button type="submit" class="btn">Tambah Produk</button>
        </form>

        <div class="links">
            <p><a href="dashboard.php">Kembali ke Dashboard</a> | <a href="products.php">Lihat Produk</a></p>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>