<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../includes/db.php');

    $title = $_POST['title'];
    $message = $_POST['message'];
    $user_email = $_SESSION['email'];

    // Insert feedback into the feedback table
    $stmt = $conn->prepare("INSERT INTO feedback (user_email, title, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user_email, $title, $message);

    if ($stmt->execute()) {
        header("Location: feedback.php?success=1");
        exit();
    } else {
        echo "Error submitting feedback.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/feedback.css">
</head>
<body>
    <div class="feedback-container">
        <header class="feedback-header">
            <h1>Feedback and Suggestions</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </header>

        <main class="feedback-main">
            <?php if (isset($_GET['success'])): ?>
                <p class="success-message">Thank you for your feedback!</p>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="title" placeholder="Feedback Title" required>
                <textarea name="message" placeholder="Your Feedback" required></textarea>
                <button type="submit">Submit Feedback</button>
            </form>
        </main>
    </div>
</body>
</html>
