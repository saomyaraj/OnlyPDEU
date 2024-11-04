<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Fetch upcoming events from the database
$events = [];
$result = $conn->query("SELECT * FROM events ORDER BY date ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/events.css">
</head>
<body>
    <div class="events-container">
        <header class="events-header">
            <h1>Manage Events</h1>
            <a href="dashboard.php" class="back-link">Back to Dashboard</a>
        </header>

        <main class="events-main">
            <section class="create-event">
                <h2>Create a New Event</h2>
                <form method="POST" action="create_event.php" class="event-form">
                    <input type="text" name="title" placeholder="Event Title" required>
                    <textarea name="description" placeholder="Event Description" required></textarea>
                    <input type="date" name="date" required>
                    <input type="text" name="location" placeholder="Event Location" required>
                    <button type="submit">Create Event</button>
                </form>
            </section>

            <section class="event-list">
                <h2>Upcoming Events</h2>
                <div class="events-grid">
                    <?php if (empty($events)): ?>
                        <p>No upcoming events available.</p>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <div class="event-card">
                                <h3><?= htmlspecialchars($event['title']) ?></h3>
                                <p><strong>Date:</strong> <?= htmlspecialchars($event['date']) ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                                <form action="register_event.php" method="POST">
                                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                    <button type="submit">Register</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
