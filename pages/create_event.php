<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $created_by_email = $_SESSION['email'];

    // Insert event into the events table
    $stmt = $conn->prepare("INSERT INTO events (title, description, date, location, created_by_email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $date, $location, $created_by_email);

    if ($stmt->execute()) {
        // Redirect back to events page
        header("Location: events.php?success=1");
        exit();
    } else {
        echo "Error creating event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/create_event.css">
</head>
<body>
    <div class="create-event-container">
        <header class="create-event-header">
            <h1>Create New Event</h1>
            <a href="events.php">Back to Events</a>
        </header>
        <main>
            <form method="POST" action="" class="event-form">
                <input type="text" name="title" placeholder="Event Title" required>
                <textarea name="description" placeholder="Event Description" required></textarea>
                <input type="date" name="date" required>
                <input type="text" name="location" placeholder="Event Location" required>
                <button type="submit">Create Event</button>
            </form>
        </main>
    </div>
</body>
</html>
