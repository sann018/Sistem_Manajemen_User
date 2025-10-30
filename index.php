<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Welcome to the User Management System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
    <main>
        <h2>Home</h2>
        <p>This is the homepage of the User Management System. Please log in or register to continue.</p>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> User Management System</p>
    </footer>
</body>
</html>