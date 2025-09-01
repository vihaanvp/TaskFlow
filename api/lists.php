<?php
require_once '../includes/auth.php';
require_login();
require_once '../includes/db.php';

header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add a list
    if (isset($_POST['action']) && $_POST['action'] === 'add' && !empty($_POST['title'])) {
        $title = trim($_POST['title']);
        $type = isset($_POST['type']) && in_array($_POST['type'], ['todo', 'note']) ? $_POST['type'] : 'todo';
        $description = trim($_POST['description'] ?? '');
        
        $stmt = $pdo->prepare("INSERT INTO lists (user_id, title, type, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $type, $description]);
        $list_id = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'id' => $list_id, 'title' => $title, 'type' => $type]);
        exit();
    }
    // Get all lists (including shared lists)
    if (isset($_POST['action']) && $_POST['action'] === 'all') {
        // Get user's own lists
        $stmt = $pdo->prepare("SELECT * FROM lists WHERE user_id = ? ORDER BY created_at ASC");
        $stmt->execute([$user_id]);
        $own_lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get shared lists
        $stmt = $pdo->prepare("
            SELECT l.*, u.username as owner_username, ls.permission
            FROM lists l 
            JOIN list_shares ls ON l.id = ls.list_id 
            JOIN users u ON l.user_id = u.id
            WHERE ls.shared_with_user_id = ? 
            ORDER BY l.created_at ASC
        ");
        $stmt->execute([$user_id]);
        $shared_lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Mark shared lists
        foreach ($shared_lists as &$list) {
            $list['is_shared'] = true;
        }
        
        $all_lists = array_merge($own_lists, $shared_lists);
        echo json_encode(['success' => true, 'lists' => $all_lists]);
        exit();
    }
    // Delete a list
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($_POST['id'])) {
        $list_id = (int)$_POST['id'];
        // Delete items in the list first to maintain FK constraints
        $pdo->prepare("DELETE FROM items WHERE list_id=?")->execute([$list_id]);
        $stmt = $pdo->prepare("DELETE FROM lists WHERE id=? AND user_id=?");
        $stmt->execute([$list_id, $user_id]);
        echo json_encode(['success' => true]);
        exit();
    }
    
    // Share a list
    if (isset($_POST['action']) && $_POST['action'] === 'share' && !empty($_POST['list_id']) && !empty($_POST['username'])) {
        $list_id = (int)$_POST['list_id'];
        $username = trim($_POST['username']);
        $permission = isset($_POST['permission']) && $_POST['permission'] === 'write' ? 'write' : 'read';
        
        // Check if the list belongs to the current user
        $stmt = $pdo->prepare("SELECT id FROM lists WHERE id = ? AND user_id = ?");
        $stmt->execute([$list_id, $user_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'List not found or access denied']);
            exit();
        }
        
        // Find the user to share with
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $share_user = $stmt->fetch();
        
        if (!$share_user) {
            echo json_encode(['success' => false, 'error' => 'User not found']);
            exit();
        }
        
        if ($share_user['id'] == $user_id) {
            echo json_encode(['success' => false, 'error' => 'Cannot share with yourself']);
            exit();
        }
        
        // Insert or update share
        $stmt = $pdo->prepare("
            INSERT INTO list_shares (list_id, shared_with_user_id, shared_by_user_id, permission) 
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE permission = VALUES(permission)
        ");
        $stmt->execute([$list_id, $share_user['id'], $user_id, $permission]);
        
        echo json_encode(['success' => true, 'message' => 'List shared successfully']);
        exit();
    }
}
echo json_encode(['success' => false]);