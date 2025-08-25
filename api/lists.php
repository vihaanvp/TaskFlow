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
        $stmt = $pdo->prepare("INSERT INTO lists (user_id, title) VALUES (?, ?)");
        $stmt->execute([$user_id, $title]);
        $list_id = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'id' => $list_id, 'title' => $title]);
        exit();
    }
    // Get all lists
    if (isset($_POST['action']) && $_POST['action'] === 'all') {
        $stmt = $pdo->prepare("SELECT * FROM lists WHERE user_id = ? ORDER BY created_at ASC");
        $stmt->execute([$user_id]);
        echo json_encode(['success' => true, 'lists' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
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
}
echo json_encode(['success' => false]);