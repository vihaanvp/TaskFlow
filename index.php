<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>TaskFlow</title>
    <link rel="stylesheet" href="assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dark">
<div class="home-container">
    <header>
        <h1>📝 TaskFlow</h1>
        <div class="subtitle">Your simple, modern to-do list manager.</div>
    </header>
    <div class="home-actions">
        <?php if($is_logged_in): ?>
            <a href="dashboard.php" class="home-btn">Go to Dashboard</a>
        <?php else: ?>
            <a href="login.php" class="home-btn">Login</a>
            <a href="register.php" class="home-btn secondary">Register</a>
        <?php endif; ?>
    </div>
    <div class="footer-text">Made with ❤️ for productivity</div>
</div>
</body>
</html>