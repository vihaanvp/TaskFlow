<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/email.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (strlen($username) < 3 || strlen($username) > 64) {
        $error = 'Username must be between 3 and 64 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if username or email already exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = 'Username or email already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $verification_token = bin2hex(random_bytes(32));
            
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, verification_token) VALUES (?, ?, ?, ?)');
            $stmt->execute([$username, $email, $hash, $verification_token]);
            
            // Send verification email
            sendVerificationEmail($email, $verification_token, $username);
            
            // Redirect to login with token info
            header('Location: login.php?registered=1&verify_email=1');
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
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" maxlength="255" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Register</button>
        </form>
        <a class="auth-link" href="login.php">Already have an account? Login</a>
    </div>
</div>
</body>
</html>