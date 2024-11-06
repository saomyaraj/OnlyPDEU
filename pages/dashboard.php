<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
require '../includes/db.php'; // Ensure this connects to your database
// Fetch quick links from the database
$query = "SELECT * FROM quick_links";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - OnlyPDEU</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Welcome, <?php echo $_SESSION['full_name']; ?>!</h1>
            <form action="search.php" method="get" class="search-form">
                <input type="text" name="query" placeholder="Search..." required>
                <button type="submit">Search</button>
            </form>
            <button id="dark-mode-toggle">Dark Mode</button> <!-- Dark Mode Toggle Button -->
            <nav>
                <a href="profile.php">Profile</a>
                <a href="forum.php">Forum</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <main class="dashboard-main">
            <!-- Existing card sections -->
            <section class="card">
                <h2>Community Feed</h2>
                <p>Stay updated with campus news and announcements!</p>
                <form action="community_feed.php" method="get">
                    <button type="submit">View Feed</button>
                </form>
            </section>

            <section class="card">
                <h2>Events</h2>
                <p>Check out upcoming events and gatherings.</p>
                <form action="events.php" method="get">
                    <button type="submit">Explore Events</button>
                </form>
            </section>

            <section class="card">
                <h2>Collaborate</h2>
                <p>Connect with peers and start new projects.</p>
                <form action="collaborate.php" method="get">
                    <button type="submit">Start Collaborating</button>
                </form>
            </section>

            <section class="card">
                <h2>Feedback and Suggestions</h2>
                <p>We value your feedback! Let us know your thoughts.</p>
                <form action="feedback.php" method="get">
                    <button type="submit">Give Feedback</button>
                </form>
            </section>

            <section class="card">
                <h2>Quick Links</h2>
                <ul class="quick-links">
                    <?php 
                    $query = "SELECT * FROM quick_links";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) { 
                    ?>
                        <li><a href="<?php echo $row['url']; ?>" target="_blank" class="quick-link-item">
                            <?php echo $row['title']; ?>
                        </a></li>
                    <?php } ?>
                </ul>
            </section>
        </main>
    </div>

    <script>
    const toggleButton = document.getElementById('dark-mode-toggle');
    const body = document.body;

    toggleButton.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        
        // Toggle dark mode for cards
        document.querySelectorAll('.card').forEach(card => {
            card.classList.toggle('dark-mode');
        });

        // Toggle dark mode for the header
        document.querySelector('.dashboard-header').classList.toggle('dark-mode');
    });
    </script>

</body>
</html>
