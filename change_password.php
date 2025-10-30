<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Semua field harus diisi.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Password baru tidak cocok.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        $email = $_SESSION['email'];
        $sql = "SELECT password FROM USERS WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($current_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE USERS SET password = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $hashed_password, $email);
            
            if ($update_stmt->execute()) {
                $success = "Password berhasil diubah!";
            } else {
                $error = "Error: " . $conn->error;
            }
            $update_stmt->close();
        } else {
            $error = "Password saat ini salah.";
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
    <title>Ubah Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Ubah Password</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="current_password">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password" required placeholder="Masukkan password saat ini">
            </div>
            
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" name="new_password" id="new_password" required placeholder="Minimal 6 karakter">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" id="confirm_password" required placeholder="Ketik ulang password baru">
            </div>
            
            <button type="submit" class="btn">Ubah Password</button>
        </form>
        
        <div class="links">
            <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
        </div>
    </div>
</body>
</html>