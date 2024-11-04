<?php
require_once('../includes/db.php');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT full_name, password FROM users WHERE email = ?");
   
    // Check for SQL errors
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }


    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($full_name, $hashed_password);
    $stmt->fetch();


    // Check if a user was found and verify password
    if (password_verify($password, $hashed_password)) {
        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $full_name;
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Login failed! Incorrect email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlyPDEU - Login</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
