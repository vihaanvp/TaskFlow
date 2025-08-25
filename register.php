<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strlen($username) < 3 || strlen($username) > 64) {
        $error = 'Username must be between 3 and 64 characters.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$username, $hash]);
            // Redirect to login with success message
            header('Location: login.php?registered=1');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | TaskFlow</title>
    <link rel="stylesheet" href="assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dark">
<div class="auth-container">
    <div class="auth-panel">
        <h2>Create Your Account</h2>
        <?php if($error): ?>
            <div class="auth-error"><?=htmlspecialchars($error)?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" maxlength="64" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Register</button>
        </form>
        <a class="auth-link" href="login.php">Already have an account? Login</a>
    </div>
</div>
</body>
</html>