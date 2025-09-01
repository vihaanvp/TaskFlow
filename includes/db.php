<?php
// Load configuration
require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Log the error for debugging
    error_log('Database connection failed: ' . $e->getMessage());
    
    if (DEBUG) {
        die('<h1 style="color: #f87171; font-family: system-ui;">Database Connection Error</h1>
             <p style="font-family: system-ui; background: #232329; color: #f3f3f5; padding: 1em; border-radius: 8px;">
             <strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '<br><br>
             <strong>Solution:</strong> Check your database settings in <code>includes/config.php</code><br>
             • Ensure MySQL/MariaDB is running<br>
             • Verify database credentials<br>
             • Create database if it doesn\'t exist: <code>CREATE DATABASE taskflow;</code><br>
             • Import schema: <code>mysql -u root -p taskflow < database_migrations.sql</code>
             </p>');
    } else {
        die('<h1 style="color: #f87171; font-family: system-ui;">Service Temporarily Unavailable</h1>
             <p style="font-family: system-ui;">We\'re experiencing technical difficulties. Please try again later.</p>');
    }
}