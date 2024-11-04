<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
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
            <nav>
                <a href="profile.php">Profile</a>
                <a href="forum.php">Forum</a>
                <a href="events.php">Events</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <main class="dashboard-main">
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
        </main>
    </div>
</body>
</html>
