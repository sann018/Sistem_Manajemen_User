<?php
session_start();
require 'includes/db.php';
require 'includes/email_functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id, fullname FROM USERS WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50));
        $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $conn->prepare("UPDATE USERS SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $token_expiry, $email);

        if ($stmt->execute()) {
            $email_sent = sendPasswordResetEmail($email, $user['fullname'], $token);
            
            if ($email_sent) {
                $message = "Email reset password telah dikirim ke <strong>$email</strong>.<br>Silakan cek inbox atau folder spam Anda.";
            } else {
                $reset_link = BASE_URL . "/reset_password.php?token=" . $token;
                $message = "Email gagal dikirim. Klik link berikut untuk reset password:<br><a href='$reset_link' style='color: #000000; font-weight: bold;'>Reset Password</a>";
            }
        } else {
            $message = "Error generating reset link.";
        }
    } else {
        $message = "Email tidak ditemukan.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Lupa Password</h2>
        
        <?php if (!empty($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required placeholder="Masukkan email Anda">
                <small>Kami akan mengirimkan link reset password ke email Anda</small>
            </div>
            
            <button type="submit" class="btn">Kirim Link Reset Password</button>
        </form>
        
        <div class="links">
            <p><a href="login.php">Kembali ke Login</a></p>
        </div>
    </div>
</body>
</html>