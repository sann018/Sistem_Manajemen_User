<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $sql = "DELETE FROM PRODUCTS WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: products.php?message=Produk berhasil dihapus");
    } else {
        header("Location: products.php?error=Error menghapus produk");
    }

    $stmt->close();
} else {
    header("Location: products.php?error=ID produk tidak ditemukan");
}

$conn->close();
?>