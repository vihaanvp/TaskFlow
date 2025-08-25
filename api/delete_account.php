<?php
require_once '../includes/auth.php';
require_login();
require_once '../includes/db.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        echo json_encode(['success'=>false, 'error'=>'Incorrect password.']);
        exit();
    }
    $pdo->prepare("DELETE FROM items WHERE list_id IN (SELECT id FROM lists WHERE user_id = ?)")->execute([$user_id]);
    $pdo->prepare("DELETE FROM lists WHERE user_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
    session_destroy();
    echo json_encode(['success' => true]);
    exit();
}
echo json_encode(['success' => false, 'error' => 'Invalid request']);