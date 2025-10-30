<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistemmanajemenuser";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// echo "Koneksi berhasil<br>"; // Comment ini agar tidak mengganggu header redirect
$conn->set_charset('utf8mb4');
?>