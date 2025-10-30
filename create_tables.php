<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistemmanajemenuser";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

//user
$sql_users = "CREATE TABLE IF NOT EXISTS `USERS` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `fullname` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(255) NOT NULL,
    `role` ENUM('Admin Gudang') DEFAULT 'Admin Gudang',
    `status` ENUM('INACTIVE', 'ACTIVE') DEFAULT 'INACTIVE',
    `activation_token` VARCHAR(255),
    `reset_token` VARCHAR(255),
    `reset_token_expiry` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `activation_code` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

//products
$sql_products = "CREATE TABLE IF NOT EXISTS `PRODUCTS` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `kode_produk` VARCHAR(50) NOT NULL,
    `nama_produk` VARCHAR(255) NOT NULL,
    `kategori` VARCHAR(100),
    `harga` DECIMAL(15, 2) NOT NULL,
    `stok` INT(11) DEFAULT 0,
    `deskripsi` TEXT,
    `created_by` INT(11),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql_users) === TRUE) {
    echo "Table USERS created successfully.<br>";
} else {
    echo "Error creating USERS table: " . $conn->error . "<br>";
}

if ($conn->query($sql_products) === TRUE) {
    echo "Table PRODUCTS created successfully.<br>";
} else {
    echo "Error creating PRODUCTS table: " . $conn->error . "<br>";
}

$conn->close();

echo "<br><br>Semua tabel berhasil dibuat!<br>";
echo "<a href='register.php'>Register</a> | <a href='login.php'>Login</a>";
?>