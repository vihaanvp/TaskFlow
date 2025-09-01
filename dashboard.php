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
        
        <!-- Search bar -->
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Search lists and items..." maxlength="100">
            <div id="search-results" style="display:none;"></div>
        </div>
        
        <nav class="lists-nav" id="lists-nav">
            <!-- Lists will be loaded here by JS -->
        </nav>
        <form id="add-list-form" autocomplete="off">
            <button type="button" id="add-list-btn" title="Add List">+ New List</button>
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

<!-- Add List Modal -->
<div id="add-list-modal" style="display:none;">
    <div class="modal-content">
        <h3>Create New List</h3>
        <form id="new-list-form">
            <label for="list-title">Title</label>
            <input type="text" id="list-title" maxlength="128" required>
            
            <label for="list-type">Type</label>
            <select id="list-type" required>
                <option value="todo">📋 To-Do List</option>
                <option value="note">📝 Notes Page</option>
            </select>
            
            <label for="list-description">Description (optional)</label>
            <textarea id="list-description" rows="3" maxlength="500"></textarea>
            
            <div class="modal-buttons">
                <button type="submit">Create List</button>
                <button type="button" id="cancel-add-list">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Share List Modal -->
<div id="share-list-modal" style="display:none;">
    <div class="modal-content">
        <h3>Share List</h3>
        <form id="share-list-form">
            <input type="hidden" id="share-list-id">
            
            <label for="share-username">Username</label>
            <input type="text" id="share-username" required>
            
            <label for="share-permission">Permission</label>
            <select id="share-permission">
                <option value="read">Read Only</option>
                <option value="write">Read & Write</option>
            </select>
            
            <div class="modal-buttons">
                <button type="submit">Share List</button>
                <button type="button" id="cancel-share-list">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast -->
<div id="gui-toast"></div>

<script src="assets/app.js"></script>
</body>
</html>