<?php
require 'includes/db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("UPDATE USERS SET status = 'ACTIVE' WHERE activation_token = ?");
    $stmt->bind_param("s", $token);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Your account has been activated successfully! You can now log in. <a href='login.php'>Login di sini</a>";
        } else {
            echo "Invalid activation token or your account is already active.";
        }
    } else {
        echo "Error activating account: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No activation token provided.";
}

$conn->close();
?>