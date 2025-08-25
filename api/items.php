<?php
require_once '../includes/auth.php';
require_login();
require_once '../includes/db.php';

header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;

// Check if list_id belongs to the user
function owns_list($list_id, $pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT 1 FROM lists WHERE id = ? AND user_id = ?");
    $stmt->execute([$list_id, $user_id]);
    return $stmt->fetch() !== false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    // All items
    if ($action === 'all' && !empty($_POST['list_id'])) {
        $list_id = (int)$_POST['list_id'];
        if (!owns_list($list_id, $pdo, $user_id)) exit(json_encode(['success'=>false]));
        $stmt = $pdo->prepare("SELECT * FROM items WHERE list_id = ? ORDER BY created_at ASC");
        $stmt->execute([$list_id]);
        echo json_encode(['success' => true, 'items' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit();
    }
    // Add item
    if ($action === 'add' && !empty($_POST['list_id']) && isset($_POST['content'])) {
        $list_id = (int)$_POST['list_id'];
        $content = trim($_POST['content']);
        if (!owns_list($list_id, $pdo, $user_id)) exit(json_encode(['success'=>false]));
        $stmt = $pdo->prepare("INSERT INTO items (list_id, content) VALUES (?, ?)");
        $stmt->execute([$list_id, $content]);
        echo json_encode(['success'=>true]);
        exit();
    }
    // Toggle done
    if ($action === 'toggle' && isset($_POST['id'], $_POST['is_done'])) {
        $id = (int)$_POST['id'];
        $is_done = (int)$_POST['is_done'];
        // Confirm the user owns the item
        $stmt = $pdo->prepare("SELECT i.id FROM items i JOIN lists l ON i.list_id = l.id WHERE i.id=? AND l.user_id=?");
        $stmt->execute([$id, $user_id]);
        if (!$stmt->fetch()) exit(json_encode(['success'=>false]));
        $stmt = $pdo->prepare("UPDATE items SET is_done=? WHERE id=?");
        $stmt->execute([$is_done, $id]);
        echo json_encode(['success'=>true]);
        exit();
    }
    // Delete item
    if ($action === 'delete' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        // Confirm the user owns the item
        $stmt = $pdo->prepare("SELECT i.id FROM items i JOIN lists l ON i.list_id = l.id WHERE i.id=? AND l.user_id=?");
        $stmt->execute([$id, $user_id]);
        if (!$stmt->fetch()) exit(json_encode(['success'=>false]));
        $stmt = $pdo->prepare("DELETE FROM items WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['success'=>true]);
        exit();
    }
}
echo json_encode(['success' => false]);