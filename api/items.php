<?php
require_once '../includes/auth.php';
require_login();
require_once '../includes/db.php';

header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;

// Check if user has access to list (owns it or has shared access)
function has_list_access($list_id, $pdo, $user_id, $required_permission = 'read') {
    // Check if user owns the list
    $stmt = $pdo->prepare("SELECT 1 FROM lists WHERE id = ? AND user_id = ?");
    $stmt->execute([$list_id, $user_id]);
    if ($stmt->fetch()) return true;
    
    // Check if list is shared with user
    $stmt = $pdo->prepare("SELECT permission FROM list_shares WHERE list_id = ? AND shared_with_user_id = ?");
    $stmt->execute([$list_id, $user_id]);
    $share = $stmt->fetch();
    
    if ($share) {
        if ($required_permission === 'read') return true;
        if ($required_permission === 'write' && $share['permission'] === 'write') return true;
    }
    
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    // All items
    if ($action === 'all' && !empty($_POST['list_id'])) {
        $list_id = (int)$_POST['list_id'];
        if (!has_list_access($list_id, $pdo, $user_id)) exit(json_encode(['success'=>false]));
        $stmt = $pdo->prepare("SELECT * FROM items WHERE list_id = ? ORDER BY created_at ASC");
        $stmt->execute([$list_id]);
        echo json_encode(['success' => true, 'items' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit();
    }
    // Add item
    if ($action === 'add' && !empty($_POST['list_id']) && isset($_POST['content'])) {
        $list_id = (int)$_POST['list_id'];
        $content = trim($_POST['content']);
        $item_type = isset($_POST['item_type']) && $_POST['item_type'] === 'content' ? 'content' : 'task';
        $rich_content = trim($_POST['rich_content'] ?? '');
        
        if (!has_list_access($list_id, $pdo, $user_id, 'write')) exit(json_encode(['success'=>false]));
        
        $stmt = $pdo->prepare("INSERT INTO items (list_id, content, item_type, rich_content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$list_id, $content, $item_type, $rich_content]);
        echo json_encode(['success'=>true]);
        exit();
    }
    // Toggle done (only for tasks)
    if ($action === 'toggle' && isset($_POST['id'], $_POST['is_done'])) {
        $id = (int)$_POST['id'];
        $is_done = (int)$_POST['is_done'];
        // Confirm the user has write access to the item
        $stmt = $pdo->prepare("SELECT i.list_id FROM items i WHERE i.id=?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        if (!$item || !has_list_access($item['list_id'], $pdo, $user_id, 'write')) exit(json_encode(['success'=>false]));
        
        $stmt = $pdo->prepare("UPDATE items SET is_done=? WHERE id=?");
        $stmt->execute([$is_done, $id]);
        echo json_encode(['success'=>true]);
        exit();
    }
    // Delete item
    if ($action === 'delete' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        // Confirm the user has write access to the item
        $stmt = $pdo->prepare("SELECT i.list_id FROM items i WHERE i.id=?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        if (!$item || !has_list_access($item['list_id'], $pdo, $user_id, 'write')) exit(json_encode(['success'=>false]));
        
        $stmt = $pdo->prepare("DELETE FROM items WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['success'=>true]);
        exit();
    }
}
echo json_encode(['success' => false]);