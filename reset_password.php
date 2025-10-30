<?php
include 'includes/db.php';

$error = '';
$success = '';
$valid_token = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $sql = "SELECT email, reset_token_expiry FROM USERS WHERE reset_token = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (strtotime($user['reset_token_expiry']) > time()) {
            $valid_token = true;
        } else {
            $error = "Token sudah kadaluarsa. <a href='forgot_password.php'>Minta token baru</a>";
        }
    } else {
        $error = "Token tidak valid.";
    }
    $stmt->close();
} else {
    $error = "Token tidak ditemukan.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $valid_token) {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_password) || empty($confirm_password)) {
        $error = "Semua field harus diisi.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Password tidak cocok.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE USERS SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $token);
        
        if ($stmt->execute()) {
            $success = "Password berhasil direset! <a href='login.php'>Login sekarang</a>";
            $valid_token = false;
        } else {
            $error = "Error updating password.";
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
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($valid_token): ?>
            <form method="POST" action="">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                
                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" required placeholder="Minimal 6 karakter">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" id="confirm_password" required placeholder="Ketik ulang password baru">
                </div>
                
                <button type="submit" class="btn">Reset Password</button>
            </form>
        <?php endif; ?>
        
        <div class="links">
            <p><a href="login.php">Kembali ke Login</a></p>
        </div>
    </div>
</body>
</html>