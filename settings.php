<?php
require_once 'includes/auth.php';
require_login();
require_once 'includes/db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$stmt = $pdo->prepare('SELECT username FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Settings | TaskFlow</title>
    <link rel="stylesheet" href="assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dark">
<div class="settings-container">
    <h2>Settings</h2>
    <div class="user-info">Signed in as <b><?=htmlspecialchars($user['username'])?></b></div>
    <button type="button" class="danger-btn" id="delete-account-btn">Delete my account</button>
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>
<!-- Custom Modal & Toast for GUI confirm -->
<div id="modal-backdrop" style="display:none;">
    <div id="gui-modal">
        <div id="gui-modal-message"></div>
        <div id="gui-modal-buttons"></div>
    </div>
</div>
<div id="gui-toast"></div>
<script src="assets/app.js"></script>
<script>
    document.getElementById('delete-account-btn').onclick = function() {
        guiConfirmWithPassword("To confirm account deletion, enter your password:").then(({confirmed, password}) => {
            if (confirmed) {
                fetch('api/delete_account.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({password})
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            guiAlert('Your account has been deleted.').then(() => {
                                window.location = 'index.php';
                            });
                        } else {
                            guiToast(data.error || 'Error deleting account.');
                        }
                    });
            }
        });
    };
</script>
</body>
</html>