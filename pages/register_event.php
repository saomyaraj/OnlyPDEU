<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $user_full_name = $_SESSION['full_name'];
    $user_email = $_SESSION['email'];

    // Insert registration into the event_registrations table
    $stmt = $conn->prepare("INSERT INTO event_registrations (user_full_name, user_email, event_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $user_full_name, $user_email, $event_id);

    if ($stmt->execute()) {
        header("Location: events.php?registered=1");
        exit();
    } else {
        echo "Error registering for the event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register for Event - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/register_event.css">
</head>
<body>
    <div class="register-event-container">
        <header class="register-event-header">
            <h1>Register for Event</h1>
            <a href="events.php">Back to Events</a>
        </header>
        <main>
            <p>Thank you for registering! You will receive confirmation via email.</p>
        </main>
    </div>
</body>
</html>
