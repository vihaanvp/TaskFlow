# Database Setup Guide

This guide provides multiple ways to set up the TaskFlow database for both development and production environments.

## Quick Setup (Recommended)

### Option 1: Direct Import with phpMyAdmin

1. **Open phpMyAdmin** in your web browser
2. **Create a new database:**
   - Click "New" in the left sidebar
   - Enter database name: `taskflow`
   - Choose `utf8mb4_unicode_ci` collation
   - Click "Create"

3. **Import the complete database:**
   - Select the `taskflow` database
   - Click the "Import" tab
   - Click "Choose File" and select `taskflow_database.sql`
   - Click "Go" to import

4. **Done!** Your database is ready with:
   - All required tables created
   - A demo account (username: `demo`, password: `demo123`)
   - Sample data to help you get started

### Option 2: MySQL Command Line

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE taskflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import the complete schema
mysql -u root -p taskflow < taskflow_database.sql
```

## Manual Setup (Step by Step)

If you prefer to create the database manually or want to understand the structure:

### Step 1: Create Database

```sql
CREATE DATABASE taskflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE taskflow;
```

### Step 2: Create Tables

#### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Lists Table
```sql
CREATE TABLE lists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    type ENUM('todo', 'note') DEFAULT 'todo',
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_lists_user_type (user_id, type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Items Table
```sql
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    list_id INT NOT NULL,
    content TEXT NOT NULL,
    item_type ENUM('task', 'content') DEFAULT 'task',
    rich_content TEXT NULL,
    is_done BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (list_id) REFERENCES lists(id) ON DELETE CASCADE,
    INDEX idx_items_list_type (list_id, item_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### List Shares Table (for collaboration)
```sql
CREATE TABLE list_shares (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tags Table
```sql
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    color VARCHAR(7) DEFAULT '#6366f1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_tag (user_id, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### List Tags Table
```sql
CREATE TABLE list_tags (
    list_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (list_id, tag_id),
    FOREIGN KEY (list_id) REFERENCES lists(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Configuration

After setting up the database, update your configuration:

1. **Edit `includes/config.php`:**
   ```php
   define('DB_HOST', 'localhost');     // Your MySQL host
   define('DB_NAME', 'taskflow');      // Database name
   define('DB_USER', 'root');          // Your MySQL username
   define('DB_PASS', '');              // Your MySQL password
   ```

2. **For production, also update:**
   ```php
   define('APP_URL', 'https://yourdomain.com');
   define('DEBUG', false);
   ```

## Testing the Setup

1. **Test database connection:**
   - Navigate to your TaskFlow installation
   - If you see a database error, check your configuration

2. **Create a test account:**
   - Go to the registration page
   - Create a new account
   - Login and test creating lists/tasks

3. **Use the demo account (if imported):**
   - Username: `demo`
   - Password: `demo123`

## Removing Demo Data

If you imported the complete database with demo data and want to remove it:

```sql
DELETE FROM users WHERE username = 'demo';
```

This will automatically remove all associated lists, items, and other data due to foreign key constraints.

## Troubleshooting

### phpMyAdmin Import Issues

**Error: "No data was received to import"**
- Make sure the `taskflow_database.sql` file is selected
- Check file size limits in phpMyAdmin settings

**Error: "SQL syntax error"**
- Ensure you're using MySQL 5.7+ or MariaDB 10.2+
- Check that foreign key constraints are enabled

### Connection Issues

**Error: "Connection refused"**
- Verify MySQL/MariaDB is running
- Check host, port, username, and password in config.php

**Error: "Database does not exist"**
- Create the database first before importing
- Ensure the database name in config.php matches

### Permission Issues

**Error: "Access denied"**
- Ensure the MySQL user has proper permissions
- Grant permissions: `GRANT ALL PRIVILEGES ON taskflow.* TO 'username'@'localhost';`

## Database Schema Overview

The TaskFlow database consists of 6 main tables:

- **`users`**: User accounts and authentication
- **`lists`**: Todo lists and note pages
- **`items`**: Individual tasks and note content
- **`list_shares`**: List sharing and collaboration
- **`tags`**: Organization and categorization
- **`list_tags`**: Many-to-many relationship for list tagging

All tables use foreign key constraints to maintain data integrity and automatic cleanup when users or lists are deleted.