<?php
session_start();
include 'includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prodemail = trim($_POST['email']);
    $prodpass = $_POST['password'];

    $sql = "SELECT * FROM USERS WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $prodemail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if ($row['status'] !== 'ACTIVE') {
            $error = "Akun Anda belum diaktifkan. Silakan cek email Anda untuk link aktivasi.";
        } elseif (password_verify($prodpass, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah. <a href='forgot_password.php'>Lupa Password?</a>";
        }
    } else {
        $error = "Email tidak terdaftar. <a href='register.php'>Register di sini</a>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Gudang</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login Admin Gudang</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email (Username)</label>
                <input type="email" name="email" id="email" required placeholder="contoh@email.com">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="Masukkan password">
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="links">
            <p><a href="forgot_password.php">Lupa Password?</a></p>
            <p>Belum punya akun? <a href="register.php">Register di sini</a></p>
        </div>
    </div>
</body>
</html>