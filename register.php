<?php
session_start();
include 'includes/db.php';
include 'includes/email_functions.php';

$error = '';
$success = '';
$fullName = '';
$email = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produser = trim($_POST['username']);
    $prodpass = $_POST['password'];
    $prodconfirm = $_POST['confirmPassword'];
    $prodfullname = trim($_POST['fullName']);
    $prodemail = trim($_POST['email']);

    // Simpan ke variabel untuk pre-fill form
    $fullName = $prodfullname;
    $email = $prodemail;
    $username = $produser;

    if (empty($produser) || empty($prodpass) || empty($prodfullname) || empty($prodemail)) {
        $error = "Semua field harus diisi.";
    } elseif (!filter_var($prodemail, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($prodpass) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($prodpass !== $prodconfirm) {
        $error = "Password tidak cocok.";
    } else {
        // Cek apakah email sudah terdaftar
        $check_email = $conn->prepare("SELECT id FROM USERS WHERE email = ?");
        $check_email->bind_param("s", $prodemail);
        $check_email->execute();
        $result_email = $check_email->get_result();
        
        if ($result_email->num_rows > 0) {
            $error = "Email '<strong>" . htmlspecialchars($prodemail) . "</strong>' sudah terdaftar. <a href='login.php'>Login di sini</a>";
        } else {
            // Cek apakah username sudah terdaftar
            $check_username = $conn->prepare("SELECT id FROM USERS WHERE username = ?");
            $check_username->bind_param("s", $produser);
            $check_username->execute();
            $result_username = $check_username->get_result();

            if ($result_username->num_rows > 0) {
                $error = "Username '<strong>" . htmlspecialchars($produser) . "</strong>' sudah terdaftar. Gunakan username yang berbeda!";
            } else {
                // Email dan username belum ada, lakukan register
                $hashedPassword = password_hash($prodpass, PASSWORD_DEFAULT);
                $activation_token = bin2hex(random_bytes(32));

                $sql = "INSERT INTO USERS (username, password, fullname, email, activation_token, status, role) 
                        VALUES (?, ?, ?, ?, ?, 'INACTIVE', 'Admin Gudang')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $produser, $hashedPassword, $prodfullname, $prodemail, $activation_token);

                if ($stmt->execute()) {
                    $email_sent = sendActivationEmail($prodemail, $prodfullname, $activation_token);
                    
                    if ($email_sent) {
                        $success = "Registrasi berhasil sebagai <strong>Admin Gudang</strong>!<br>
                                   Email aktivasi telah dikirim ke <strong>" . htmlspecialchars($prodemail) . "</strong>.<br>
                                   Silakan cek inbox atau folder spam Anda.";
                    } else {
                        $activation_link = (defined('BASE_URL') ? BASE_URL : 'http://localhost/UTS_WEBPRO_Praktikum/Sistem_Manajemen_User') . "/activate.php?token=" . $activation_token;
                        $success = "Registrasi berhasil sebagai <strong>Admin Gudang</strong>!<br>
                                   Email gagal dikirim. Klik link berikut untuk aktivasi:<br>
                                   <a href='" . htmlspecialchars($activation_link) . "' style='color: #667eea; font-weight: bold;'>Aktivasi Akun</a>";
                    }
                    
                    // Clear form setelah berhasil
                    $fullName = '';
                    $email = '';
                    $username = '';
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close();
            }

            $check_username->close();
        }
        $check_email->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Admin Gudang</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Register Admin Gudang</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="fullName">Nama Lengkap</label>
                <input type="text" name="fullName" id="fullName" required placeholder="Masukkan nama lengkap" value="<?php echo htmlspecialchars($fullName); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email (sebagai Username)</label>
                <input type="email" name="email" id="email" required placeholder="contoh@email.com" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required placeholder="Masukan username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter">
            </div>
            
            <div class="form-group">
                <label for="confirmPassword">Konfirmasi Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" required placeholder="Ketik ulang password">
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <div class="links">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>