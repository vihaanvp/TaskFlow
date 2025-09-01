<?php
/**
 * Database Connection Test
 * This script tests if the database configuration is working correctly
 */

require_once 'includes/config.php';

echo "<h1>TaskFlow Database Connection Test</h1>";

try {
    // Test database connection
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test tables exist
    $tables = ['users', 'lists', 'items', 'list_shares', 'tags', 'list_tags'];
    $missing_tables = [];
    
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        if (!$stmt->fetch()) {
            $missing_tables[] = $table;
        }
    }
    
    if (empty($missing_tables)) {
        echo "<p style='color: green;'>✅ All required tables exist!</p>";
        
        // Test demo data
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users");
        $stmt->execute();
        $user_count = $stmt->fetch()['count'];
        
        echo "<p>👥 Users in database: <strong>{$user_count}</strong></p>";
        
        if ($user_count > 0) {
            $stmt = $pdo->prepare("SELECT username FROM users LIMIT 5");
            $stmt->execute();
            $users = $stmt->fetchAll();
            echo "<p>Sample users: ";
            foreach ($users as $user) {
                echo "<code>" . htmlspecialchars($user['username']) . "</code> ";
            }
            echo "</p>";
        }
        
        echo "<p style='color: green;'>🎉 Database setup is complete and working!</p>";
        echo "<p><a href='index.php'>Go to TaskFlow</a></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Missing tables: " . implode(', ', $missing_tables) . "</p>";
        echo "<p>Please run the database setup script first.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database configuration in <code>includes/config.php</code></p>";
}
?>

<style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
</style>