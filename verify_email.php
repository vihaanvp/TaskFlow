<?php
require_once 'includes/db.php';

$message = '';
$success = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $pdo->prepare('SELECT id, email FROM users WHERE verification_token = ? AND email_verified = FALSE');
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Verify the user
        $stmt = $pdo->prepare('UPDATE users SET email_verified = TRUE, verification_token = NULL WHERE id = ?');
        $stmt->execute([$user['id']]);
        
        $message = 'Email verified successfully! You can now log in.';
        $success = true;
    } else {
        $message = 'Invalid or expired verification token.';
    }
} else {
    $message = 'No verification token provided.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Email Verification | TaskFlow</title>
    <link rel="stylesheet" href="assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dark">
<div class="auth-container">
    <div class="auth-panel">
        <h2>Email Verification</h2>
        <?php if($success): ?>
            <div class="auth-success"><?=htmlspecialchars($message)?></div>
            <a class="auth-link" href="login.php">Go to Login</a>
        <?php else: ?>
            <div class="auth-error"><?=htmlspecialchars($message)?></div>
            <a class="auth-link" href="register.php">Try registering again</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>