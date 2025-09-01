<?php
require_once '../includes/auth.php';
require_login();
require_once '../includes/db.php';

header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Get all tags for user
    if ($action === 'all') {
        $stmt = $pdo->prepare("SELECT * FROM tags WHERE user_id = ? ORDER BY name ASC");
        $stmt->execute([$user_id]);
        echo json_encode(['success' => true, 'tags' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit();
    }
    
    // Create new tag
    if ($action === 'create' && !empty($_POST['name'])) {
        $name = trim($_POST['name']);
        $color = $_POST['color'] ?? '#6366f1';
        
        if (strlen($name) > 50) {
            echo json_encode(['success' => false, 'error' => 'Tag name too long']);
            exit();
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO tags (user_id, name, color) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $name, $color]);
            $tag_id = $pdo->lastInsertId();
            echo json_encode(['success' => true, 'id' => $tag_id, 'name' => $name, 'color' => $color]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                echo json_encode(['success' => false, 'error' => 'Tag already exists']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database error']);
            }
        }
        exit();
    }
    
    // Delete tag
    if ($action === 'delete' && !empty($_POST['id'])) {
        $tag_id = (int)$_POST['id'];
        
        // Remove tag from all lists first
        $pdo->prepare("DELETE FROM list_tags WHERE tag_id = ?")->execute([$tag_id]);
        
        // Delete the tag
        $stmt = $pdo->prepare("DELETE FROM tags WHERE id = ? AND user_id = ?");
        $stmt->execute([$tag_id, $user_id]);
        
        echo json_encode(['success' => true]);
        exit();
    }
    
    // Add tag to list
    if ($action === 'add_to_list' && !empty($_POST['list_id']) && !empty($_POST['tag_id'])) {
        $list_id = (int)$_POST['list_id'];
        $tag_id = (int)$_POST['tag_id'];
        
        // Verify user owns the list
        $stmt = $pdo->prepare("SELECT 1 FROM lists WHERE id = ? AND user_id = ?");
        $stmt->execute([$list_id, $user_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Access denied']);
            exit();
        }
        
        // Verify user owns the tag
        $stmt = $pdo->prepare("SELECT 1 FROM tags WHERE id = ? AND user_id = ?");
        $stmt->execute([$tag_id, $user_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Tag not found']);
            exit();
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO list_tags (list_id, tag_id) VALUES (?, ?)");
            $stmt->execute([$list_id, $tag_id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                echo json_encode(['success' => false, 'error' => 'Tag already added to list']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database error']);
            }
        }
        exit();
    }
    
    // Remove tag from list
    if ($action === 'remove_from_list' && !empty($_POST['list_id']) && !empty($_POST['tag_id'])) {
        $list_id = (int)$_POST['list_id'];
        $tag_id = (int)$_POST['tag_id'];
        
        $stmt = $pdo->prepare("
            DELETE lt FROM list_tags lt 
            JOIN lists l ON lt.list_id = l.id 
            WHERE lt.list_id = ? AND lt.tag_id = ? AND l.user_id = ?
        ");
        $stmt->execute([$list_id, $tag_id, $user_id]);
        
        echo json_encode(['success' => true]);
        exit();
    }
    
    // Get tags for a list
    if ($action === 'list_tags' && !empty($_POST['list_id'])) {
        $list_id = (int)$_POST['list_id'];
        
        $stmt = $pdo->prepare("
            SELECT t.* FROM tags t 
            JOIN list_tags lt ON t.id = lt.tag_id 
            WHERE lt.list_id = ? AND t.user_id = ?
            ORDER BY t.name ASC
        ");
        $stmt->execute([$list_id, $user_id]);
        
        echo json_encode(['success' => true, 'tags' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit();
    }
}

echo json_encode(['success' => false]);
?>