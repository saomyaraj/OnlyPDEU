<?php
session_start();
require_once('../includes/db.php'); // Include database connection

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// Handle form submission for updating email/password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];

    // Prepare SQL statement to update email and/or password
    $sql = "UPDATE users SET ";
    $params = [];
    if (!empty($new_email)) {
        $sql .= "email = ?";
        $params[] = $new_email;
    }
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        if (!empty($params)) {
            $sql .= ", "; // Add comma if there are already parameters
        }
        $sql .= "password = ?";
        $params[] = $hashed_password;
    }
    $sql .= " WHERE email = ?"; // Use current email for updating
    $params[] = $_SESSION['email'];

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    if (count($params) === 2) {
        $stmt->bind_param("ss", $params[0], $params[1]);
    } elseif (count($params) === 1) {
        if (empty($new_password)) {
            $stmt->bind_param("s", $params[0]); // Only email
        } else {
            $stmt->bind_param("ss", $params[0], $params[1]); // Email and password
        }
    } else {
        // If no fields are updated, don't execute
        $message = "No changes made.";
        $stmt->close();
        $conn->close();
        echo $message;
        exit();
    }

    // Execute the update
    if ($stmt->execute()) {
        // If email was changed, update the session variable
        if (!empty($new_email)) {
            $_SESSION['email'] = $new_email; 
        }
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile.";
    }

    // Close the statement
    $stmt->close();
}

// Get current user's details for display (optional)
$current_email = $_SESSION['email'];
// Fetch user's full_name from the session if it's stored there
$current_full_name = $_SESSION['full_name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
    <div class="profile-container">
        <header class="profile-header">
            <h1>Welcome to Your Profile, <?php echo htmlspecialchars($current_full_name); ?></h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </header>

        <main class="profile-main">
            <section class="profile-card">
                <img src="../assets/images/profile-placeholder.png" alt="Profile Picture" class="profile-picture">
                <h2><?php echo htmlspecialchars($current_email); ?></h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($current_email); ?></p>
                <p><strong>Joined:</strong> January 2024</p>
                <?php if ($message): ?>
                    <p class="message"><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
            </section>

            <section class="edit-profile">
                <h2>Edit Profile</h2>
                <form method="POST" action="">
                    <input type="email" name="email" placeholder="Update Email">
                    <input type="password" name="password" placeholder="New Password">
                    <button type="submit">Save Changes</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
