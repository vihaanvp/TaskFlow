<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = '';
$registered = isset($_GET['registered']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        login($user['id']);
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | TaskFlow</title>
    <link rel="stylesheet" href="assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dark">
<div class="auth-container">
    <div class="auth-panel">
        <h2>Login to TaskFlow</h2>
        <?php if($registered): ?>
            <div class="auth-success">Registration successful! Please log in.</div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="auth-error"><?=htmlspecialchars($error)?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" maxlength="64" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Login</button>
        </form>
        <a class="auth-link" href="register.php">Don't have an account? Register</a>
    </div>
</div>
</body>
</html>