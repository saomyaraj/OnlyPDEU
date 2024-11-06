<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$query = $_GET['query'];
$results = [];

// Search for events
$eventResult = $conn->query("SELECT * FROM events WHERE title LIKE '%$query%' OR description LIKE '%$query%'");
if ($eventResult) {
    while ($row = $eventResult->fetch_assoc()) {
        $results[] = $row;
    }
}

// Search for forum posts
$forumResult = $conn->query("SELECT * FROM forum_posts WHERE title LIKE '%$query%' OR content LIKE '%$query%'");
if ($forumResult) {
    while ($row = $forumResult->fetch_assoc()) {
        $results[] = $row;
    }
}

// Search for collaboration projects
$collabResult = $conn->query("SELECT * FROM collaboration_projects WHERE title LIKE '%$query%' OR description LIKE '%$query%'");
if ($collabResult) {
    while ($row = $collabResult->fetch_assoc()) {
        $results[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="../assets/css/search.css">
</head>
<body>
    <div class="search-results-container">
        <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>
        <ul>
            <?php foreach ($results as $result): ?>
                <li>
                    <h2><?php echo htmlspecialchars($result['title']); ?></h2>
                    <p><?php echo htmlspecialchars($result['description']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
