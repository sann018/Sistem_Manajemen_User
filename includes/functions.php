<?php
function sendActivationEmail($email, $token) {
    $subject = "Activate Your Account";
    $message = "Click the link below to activate your account:\n";
    $message .= "http://yourdomain.com/activate.php?token=" . $token;
    $headers = "From: no-reply@yourdomain.com";

    mail($email, $subject, $message, $headers);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateRandomToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function redirectTo($url) {
    header("Location: $url");
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserByEmail($conn, $email) {
    $stmt = $conn->prepare("SELECT * FROM USERS WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getUserById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM USERS WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateUserStatus($conn, $id, $status) {
    $stmt = $conn->prepare("UPDATE USERS SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    return $stmt->execute();
}

function sendResetPasswordEmail($email, $token) {
    $subject = "Reset Your Password";
    $message = "Click the link below to reset your password:\n";
    $message .= "http://yourdomain.com/reset_password.php?token=" . $token;
    $headers = "From: no-reply@yourdomain.com";

    mail($email, $subject, $message, $headers);
}

/**
 * Cek apakah email sudah terdaftar.
 * Menggunakan $conn dari includes/db.php (global).
 * @param string $email
 * @return bool
 */
function emailExists($email) {
    global $conn;
    if (!isset($conn)) return false;
    $stmt = $conn->prepare("SELECT id FROM USERS WHERE email = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = ($result->num_rows > 0);
    $stmt->close();
    return $exists;
}

/**
 * Get all products from database
 */
function getAllProducts($conn) {
    $sql = "SELECT * FROM PRODUCTS ORDER BY id DESC";
    $result = $conn->query($sql);
    $products = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Map kolom database ke format yang diharapkan dashboard
            $products[] = [
                'id' => $row['id'],
                'name' => $row['nama_produk'],
                'description' => $row['deskripsi'],
                'price' => $row['harga'],
                'quantity' => $row['stok'],
                'kode_produk' => $row['kode_produk'],
                'kategori' => $row['kategori']
            ];
        }
    }
    return $products;
}

/**
 * Get product by ID
 */
function getProductById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM PRODUCTS WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    
    if ($row) {
        return [
            'id' => $row['id'],
            'kode_produk' => $row['kode_produk'],
            'name' => $row['nama_produk'],
            'kategori' => $row['kategori'],
            'price' => $row['harga'],
            'quantity' => $row['stok'],
            'description' => $row['deskripsi']
        ];
    }
    return null;
}

/**
 * Add new product
 */
function addProduct($conn, $kode_produk, $nama_produk, $kategori, $harga, $stok, $deskripsi) {
    $stmt = $conn->prepare("INSERT INTO PRODUCTS (kode_produk, nama_produk, kategori, harga, stok, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdis", $kode_produk, $nama_produk, $kategori, $harga, $stok, $deskripsi);
    return $stmt->execute();
}

/**
 * Update product
 */
function updateProduct($conn, $id, $kode_produk, $nama_produk, $kategori, $harga, $stok, $deskripsi) {
    $stmt = $conn->prepare("UPDATE PRODUCTS SET kode_produk = ?, nama_produk = ?, kategori = ?, harga = ?, stok = ?, deskripsi = ? WHERE id = ?");
    $stmt->bind_param("sssdisi", $kode_produk, $nama_produk, $kategori, $harga, $stok, $deskripsi, $id);
    return $stmt->execute();
}

/**
 * Delete product
 */
function deleteProduct($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM PRODUCTS WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>

?>