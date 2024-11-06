<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$collaborations = [];

// Fetch collaboration projects from the database
$result = $conn->query("SELECT * FROM collaborations ORDER BY created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $collaborations[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_title = $_POST['project_title'];
    $description = $_POST['description'];
    $created_by_email = $_SESSION['email'];

    // Insert new collaboration into the collaborations table
    $stmt = $conn->prepare("INSERT INTO collaborations (project_title, description, created_by_email, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $project_title, $description, $created_by_email);

    if ($stmt->execute()) {
        header("Location: collaborate.php");
        exit();
    } else {
        echo "Error creating collaboration.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collaborate - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/collaborate.css">
</head>
<body>
    <div class="collaborate-container">
        <header class="collaborate-header">
            <h1>Collaborate on Projects</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </header>

        <main class="collaborate-main">
            <section class="new-collaboration">
                <h2>Create New Collaboration</h2>
                <form method="POST">
                    <input type="text" name="project_title" placeholder="Project Title" required>
                    <textarea name="description" placeholder="Project Description" required></textarea>
                    <button type="submit">Create Collaboration</button>
                </form>
            </section>

            <section class="collaboration-list">
                <h2>Existing Collaborations</h2>
                <?php foreach ($collaborations as $collaboration): ?>
                    <div class="collaboration">
                        <h3><?= htmlspecialchars($collaboration['project_title']) ?></h3>
                        <p><strong>Created by:</strong> <?= htmlspecialchars($collaboration['created_by_email']) ?></p>
                        <p><?= htmlspecialchars($collaboration['description']) ?></p>
                        <p class="collaboration-time"><?= htmlspecialchars($collaboration['created_at']) ?></p>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>
    </div>
</body>
</html>
