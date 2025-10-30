<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$user = getUserByEmail($conn, $email);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $gender = trim($_POST['gender']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    
    if (empty($fullname) || empty($username) || empty($gender) || empty($phone) || empty($role)) {
        $error = "Semua field harus diisi.";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $error = "Nomor HP harus berupa angka 10-15 digit.";
    } else {
        $sql = "UPDATE USERS SET fullname = ?, username = ?, gender = ?, phone = ?, role = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $fullname, $username, $gender, $phone, $role, $email);
        
        if ($stmt->execute()) {
            $success = "Profile berhasil diupdate!";
            $_SESSION['fullname'] = $fullname;
            $_SESSION['username'] = $username;
            $user['fullname'] = $fullname;
            $user['username'] = $username;
            $user['gender'] = $gender;
            $user['phone'] = $phone;
            $user['role'] = $role;
        } else {
            $error = "Error: " . $conn->error;
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
    <title>Profile Pengguna</title>
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
        <h2>Profile Pengguna</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-row-full">
                <div class="form-group">
                    <label for="email">Email (Username)</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    <small>Email tidak dapat diubah</small>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required placeholder="Masukkan username">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    <select name="gender" id="gender" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" <?php echo (isset($user['gender']) && $user['gender'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo (isset($user['gender']) && $user['gender'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor HP</label>
                    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required placeholder="Contoh: 081234567890" pattern="[0-9]{10,15}">
                    <small>Format: 10-15 digit angka</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" required>
                        <option value="">Pilih Role</option>
                        <option value="Admin Gudang" <?php echo ($user['role'] == 'Admin Gudang') ? 'selected' : ''; ?>>Admin Gudang</option>
                        <option value="Supervisor" <?php echo ($user['role'] == 'Supervisor') ? 'selected' : ''; ?>>Supervisor</option>
                        <option value="Staff" <?php echo ($user['role'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                        <option value="Manager" <?php echo ($user['role'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" id="status" value="<?php echo htmlspecialchars($user['status']); ?>" disabled>
                    <small>Status tidak dapat diubah</small>
                </div>
            </div>
            
            <button type="submit" class="btn">Update Profile</button>
        </form>
        
        <div class="links">
            <p><a href="dashboard.php">Kembali ke Dashboard</a> | <a href="change_password.php">Ubah Password</a></p>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>