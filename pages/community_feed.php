<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$posts = [];

// Fetch community feed posts from the database
$result = $conn->query("SELECT * FROM community_feed ORDER BY created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_content = $_POST['post_content'];
    $user_email = $_SESSION['email'];

    // Insert new post into the community_feed table
    $stmt = $conn->prepare("INSERT INTO community_feed (user_email, post_content, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $user_email, $post_content);

    if ($stmt->execute()) {
        header("Location: community_feed.php");
        exit();
    } else {
        echo "Error posting your message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Community Feed - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/community_feed.css">
</head>
<body>
    <div class="feed-container">
        <header class="feed-header">
            <h1>Community Feed</h1>
            <form action="dashboard.php" method="get">
                <button type="submit">Back to Dashboard</button>
            </form>
        </header>

        <main class="feed-main">
            <section class="new-post">
                <h2>New Post</h2>
                <form method="POST">
                    <textarea name="post_content" placeholder="Share your thoughts..." required></textarea>
                    <button type="submit">Post</button>
                </form>
            </section>

            <section class="post-list">
                <h2>Posts</h2>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <p><strong><?= htmlspecialchars($post['user_email']) ?>:</strong> <?= htmlspecialchars($post['post_content']) ?></p>
                        <p class="post-time"><?= htmlspecialchars($post['created_at']) ?></p>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>
    </div>
</body>
</html>
