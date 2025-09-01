-- ============================================
-- TaskFlow Complete Database Schema
-- ============================================
-- This file creates a complete database for TaskFlow
-- Compatible with phpMyAdmin and MySQL/MariaDB

-- Create database (optional - you can create this manually in phpMyAdmin)
-- CREATE DATABASE IF NOT EXISTS taskflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE taskflow;

-- ============================================
-- Users Table
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Lists Table
-- ============================================
CREATE TABLE IF NOT EXISTS lists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    type ENUM('todo', 'note') DEFAULT 'todo',
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_lists_user_type (user_id, type),
    INDEX idx_lists_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Items Table
-- ============================================
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    list_id INT NOT NULL,
    content TEXT NOT NULL,
    item_type ENUM('task', 'content') DEFAULT 'task',
    rich_content TEXT NULL,
    is_done BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (list_id) REFERENCES lists(id) ON DELETE CASCADE,
    INDEX idx_items_list_type (list_id, item_type),
    INDEX idx_items_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- List Shares Table (for collaboration)
-- ============================================
CREATE TABLE IF NOT EXISTS list_shares (
    id INT AUTO_INCREMENT PRIMARY KEY,
    list_id INT NOT NULL,
    shared_with_user_id INT NOT NULL,
    shared_by_user_id INT NOT NULL,
    permission ENUM('read', 'write') DEFAULT 'read',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (list_id) REFERENCES lists(id) ON DELETE CASCADE,
    FOREIGN KEY (shared_with_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (shared_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_share (list_id, shared_with_user_id),
    INDEX idx_list_shares_user (shared_with_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tags Table (for organizing lists and items)
-- ============================================
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    color VARCHAR(7) DEFAULT '#6366f1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_tag (user_id, name),
    INDEX idx_tags_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- List Tags Table (many-to-many relationship)
-- ============================================
CREATE TABLE IF NOT EXISTS list_tags (
    list_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (list_id, tag_id),
    FOREIGN KEY (list_id) REFERENCES lists(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Sample Data (Optional - Remove if not needed)
-- ============================================

-- Create a demo user (password: 'demo123')
INSERT INTO users (username, password_hash) VALUES 
('demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Get the demo user ID for sample data
SET @demo_user_id = LAST_INSERT_ID();

-- Create sample lists
INSERT INTO lists (user_id, title, type, description) VALUES 
(@demo_user_id, 'My First Todo List', 'todo', 'Getting started with TaskFlow'),
(@demo_user_id, 'Project Notes', 'note', 'Important notes and ideas');

-- Get list IDs for sample items
SET @todo_list_id = (SELECT id FROM lists WHERE title = 'My First Todo List' AND user_id = @demo_user_id);
SET @note_list_id = (SELECT id FROM lists WHERE title = 'Project Notes' AND user_id = @demo_user_id);

-- Create sample todo items
INSERT INTO items (list_id, content, item_type, is_done) VALUES 
(@todo_list_id, 'Welcome to TaskFlow!', 'task', 1),
(@todo_list_id, 'Create your first list', 'task', 0),
(@todo_list_id, 'Add some tasks', 'task', 0),
(@todo_list_id, 'Try the note feature', 'task', 0);

-- Create sample note content
INSERT INTO items (list_id, content, item_type, rich_content) VALUES 
(@note_list_id, 'TaskFlow Features', 'content', 'TaskFlow is a powerful task and note management system with:\n\n• Todo lists with checkboxes\n• Freeform note pages\n• List sharing and collaboration\n• Search functionality\n• Tag organization\n\nGet started by creating your own lists!');

-- Create sample tags
INSERT INTO tags (user_id, name, color) VALUES 
(@demo_user_id, 'Important', '#ef4444'),
(@demo_user_id, 'Work', '#3b82f6'),
(@demo_user_id, 'Personal', '#10b981');

-- ============================================
-- Database Setup Complete
-- ============================================
-- Your TaskFlow database is now ready to use!
-- 
-- Demo account credentials:
-- Username: demo
-- Password: demo123
-- 
-- You can delete the demo data by running:
-- DELETE FROM users WHERE username = 'demo';
-- ============================================