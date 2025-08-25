<?php
require_once 'includes/auth.php';
require_login();
require_once 'includes/db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Fetch all lists for this user
$stmt = $pdo->prepare("SELECT * FROM lists WHERE user_id = ? ORDER BY created_at ASC");
$stmt->execute([$user_id]);
$lists = $stmt->fetchAll();

$selected_list_id = $lists[0]['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard | TaskFlow</title>
    <link rel="stylesheet" href="assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dark">
<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <span class="app-logo">📝</span>
            <span class="app-name">TaskFlow</span>
        </div>
        <nav class="lists-nav" id="lists-nav">
            <!-- Lists will be loaded here by JS -->
        </nav>
        <form id="add-list-form" autocomplete="off">
            <input type="text" id="new-list-title" placeholder="New List..." maxlength="128" required>
            <button type="submit" title="Add List">+</button>
        </form>
        <div class="sidebar-options">
            <a href="settings.php" class="sidebar-settings-link">Settings</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </aside>
    <!-- Main content -->
    <main class="main-panel">
        <div id="main-content">
            <!-- List title and tasks will be loaded here by JS -->
        </div>
    </main>
</div>

<!-- Custom Modal -->
<div id="modal-backdrop" style="display:none;">
    <div id="gui-modal">
        <div id="gui-modal-message"></div>
        <div id="gui-modal-buttons">
            <!-- Buttons will be added dynamically -->
        </div>
    </div>
</div>
<!-- Toast -->
<div id="gui-toast"></div>

<script src="assets/app.js"></script>
</body>
</html>