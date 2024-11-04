<?php
require_once('../includes/db.php');


$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the inputs
    $full_name = $_POST['full_name']; // This is the full name input
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    // Prepare the statement without the username field
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $full_name, $email, $password);


    if ($stmt->execute()) {
        $message = "Registration successful!";
    } else {
        $message = "Error: Could not register. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlyPDEU - Register</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required> <!-- Full Name field -->
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>