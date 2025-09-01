<?php
require_once '../includes/auth.php';
require_login();
require_once '../includes/db.php';

header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'search' && !empty($_POST['query'])) {
        $query = trim($_POST['query']);
        $search_term = '%' . $query . '%';
        
        // Search in user's own lists and items
        $stmt = $pdo->prepare("
            SELECT 
                l.id as list_id, 
                l.title as list_title, 
                l.type as list_type,
                i.id as item_id, 
                i.content as item_content,
                i.item_type,
                'own' as source
            FROM lists l 
            LEFT JOIN items i ON l.id = i.list_id 
            WHERE l.user_id = ? AND (
                l.title LIKE ? OR 
                l.description LIKE ? OR 
                i.content LIKE ?
            )
            
            UNION ALL
            
            SELECT 
                l.id as list_id, 
                l.title as list_title, 
                l.type as list_type,
                i.id as item_id, 
                i.content as item_content,
                i.item_type,
                'shared' as source
            FROM lists l 
            JOIN list_shares ls ON l.id = ls.list_id
            LEFT JOIN items i ON l.id = i.list_id 
            WHERE ls.shared_with_user_id = ? AND (
                l.title LIKE ? OR 
                l.description LIKE ? OR 
                i.content LIKE ?
            )
            
            ORDER BY list_title, item_content
            LIMIT 50
        ");
        
        $stmt->execute([
            $user_id, $search_term, $search_term, $search_term,
            $user_id, $search_term, $search_term, $search_term
        ]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Group results by list
        $grouped = [];
        foreach ($results as $result) {
            $list_id = $result['list_id'];
            if (!isset($grouped[$list_id])) {
                $grouped[$list_id] = [
                    'list_id' => $list_id,
                    'list_title' => $result['list_title'],
                    'list_type' => $result['list_type'],
                    'source' => $result['source'],
                    'items' => []
                ];
            }
            
            if ($result['item_id']) {
                $grouped[$list_id]['items'][] = [
                    'item_id' => $result['item_id'],
                    'content' => $result['item_content'],
                    'item_type' => $result['item_type']
                ];
            }
        }
        
        echo json_encode(['success' => true, 'results' => array_values($grouped)]);
        exit();
    }
}

echo json_encode(['success' => false]);
?>