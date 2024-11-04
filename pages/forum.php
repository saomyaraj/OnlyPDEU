<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Get the user ID from the session
    $user_email = $_SESSION['email'];

    // Fetch the user ID based on the logged-in email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // Prepare the SQL statement to insert the new thread
    $stmt = $conn->prepare("INSERT INTO threads (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $content);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Thread posted successfully!";
    } else {
        $message = "Error posting thread. Please try again.";
    }
    $stmt->close();
}

// Fetch existing threads from the database
$threads = [];
$result = $conn->query("SELECT t.title, t.content, u.email, t.created_at FROM threads t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $threads[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forum - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/forum.css">
</head>
<body>
    <div class="forum-container">
        <header class="forum-header">
            <h1>Community Forum</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </header>

        <main class="forum-main">
            <section class="create-thread">
                <h2>Start a New Discussion</h2>
                <form method="POST" action="">
                    <input type="text" name="title" placeholder="Thread Title" required>
                    <textarea name="content" placeholder="What's on your mind?" required></textarea>
                    <button type="submit">Post</button>
                </form>
                <?php if ($message): ?>
                    <p class="message"><?= $message ?></p>
                <?php endif; ?>
            </section>

            <section class="threads">
                <h2>Recent Threads</h2>
                <?php foreach ($threads as $thread): ?>
                    <article class="thread">
                        <h3><?= htmlspecialchars($thread['title']) ?></h3>
                        <p>Started by <?= htmlspecialchars($thread['email']) ?> | <?= $thread['created_at'] ?></p>
                        <p><?= htmlspecialchars($thread['content']) ?></p>
                        <button class="reply">Reply</button>
                    </article>
                <?php endforeach; ?>
            </section>
        </main>
    </div>
</body>
</html>
