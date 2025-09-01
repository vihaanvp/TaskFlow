-- TaskFlow Database Schema Migrations
-- Run these SQL commands to update your database for new features

-- 1. Add email and verification to users table
ALTER TABLE users 
ADD COLUMN email VARCHAR(255) UNIQUE NULL,
ADD COLUMN email_verified BOOLEAN DEFAULT FALSE,
ADD COLUMN verification_token VARCHAR(255) NULL,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- 2. Add list type to lists table
ALTER TABLE lists 
ADD COLUMN type ENUM('todo', 'note') DEFAULT 'todo',
ADD COLUMN description TEXT NULL;

-- 3. Create list_shares table for collaboration
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
    UNIQUE KEY unique_share (list_id, shared_with_user_id)
);

-- 4. Update items table to support rich content for notes
ALTER TABLE items 
ADD COLUMN item_type ENUM('task', 'content') DEFAULT 'task',
ADD COLUMN rich_content TEXT NULL;

-- 5. Create tags table for organizing lists and items
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    color VARCHAR(7) DEFAULT '#6366f1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_tag (user_id, name)
);

-- 6. Create list_tags table for many-to-many relationship
CREATE TABLE IF NOT EXISTS list_tags (
    list_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (list_id, tag_id),
    FOREIGN KEY (list_id) REFERENCES lists(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- 7. Add indexes for better performance
CREATE INDEX idx_lists_user_type ON lists(user_id, type);
CREATE INDEX idx_items_list_type ON items(list_id, item_type);
CREATE INDEX idx_list_shares_user ON list_shares(shared_with_user_id);
CREATE INDEX idx_users_email ON users(email);