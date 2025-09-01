# 🚀 TaskFlow Development Setup

A comprehensive guide to setting up TaskFlow for development.

## 📋 Prerequisites

Before you begin, ensure you have the following installed on your system:

- **PHP 7.4 or higher** with the following extensions:
  - PDO
  - MySQL/MariaDB driver
  - mbstring
  - json
- **MySQL 5.7+ or MariaDB 10.2+**
- **Web server** (Apache, Nginx, or PHP built-in server for development)
- **Git** (for version control)

### Quick Check
```bash
# Check PHP version and extensions
php --version
php -m | grep -E "(pdo|mysql|mbstring|json)"

# Check MySQL/MariaDB
mysql --version
```

## 🛠️ Installation

### 1. Clone the Repository
```bash
git clone https://github.com/vihaanvp/TaskFlow.git
cd TaskFlow
```

### 2. Configuration Setup
Configure your application settings by editing `includes/config.php`:

```bash
nano includes/config.php  # or use your preferred editor
```

**Key settings to configure:**
```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'taskflow');
define('DB_USER', 'root');
define('DB_PASS', ''); // Set your MySQL password if needed

// Application Configuration
define('APP_NAME', 'TaskFlow');
define('APP_URL', 'http://localhost:8000'); // Update to your local URL
define('DEBUG', true); // Enable debug mode for development

// Email Configuration
define('DEVELOPMENT_MODE', true); // Logs emails instead of sending
```

### 3. Database Setup

#### Option A: Using Command Line
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE taskflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import schema
mysql -u root -p taskflow < database_migrations.sql
```

#### Option B: Using phpMyAdmin
1. Open http://localhost/phpmyadmin
2. Create a new database named `taskflow`
3. Import the `database_migrations.sql` file

### 4. Start Development Server

#### Option A: PHP Built-in Server (Recommended for development)
```bash
php -S localhost:8000
```

#### Option B: XAMPP/WAMP/MAMP
1. Copy the TaskFlow folder to your web server directory (`htdocs`, `www`, etc.)
2. Open http://localhost/TaskFlow in your browser

## 📁 Project Structure

```
TaskFlow/
├── api/                     # REST API endpoints
│   ├── items.php           # Task/note management
│   ├── lists.php           # List management
│   └── search.php          # Search functionality
├── assets/                  # Frontend assets
│   ├── app.js              # Main JavaScript application
│   └── style.css           # Complete CSS styling
├── includes/                # PHP backend utilities
│   ├── auth.php            # Authentication and session management
│   ├── config.php          # Application configuration
│   ├── db.php              # Database connection
│   └── email.php           # Email utilities (verification, etc.)
├── dashboard.php            # Main application interface
├── database_migrations.sql # Database schema and initial data
├── index.php               # Homepage and landing page
├── login.php               # User authentication
├── logout.php              # Session termination
├── register.php            # User registration with email verification
├── settings.php            # User account settings
└── verify_email.php        # Email verification handler
```

## 🧪 Development Workflow

### Testing Changes
1. **Frontend Changes**: Refresh browser to see CSS/JS updates
2. **Backend Changes**: May require server restart for config changes
3. **Database Changes**: Run SQL scripts manually or use migrations

### Debugging
- Enable debug mode in `includes/config.php`: `define('DEBUG', true);`
- Check PHP error logs: `tail -f /var/log/apache2/error.log`
- Use browser developer tools for frontend debugging

### Best Practices
- Test with multiple browsers (Chrome, Firefox, Safari)
- Test responsive design on different screen sizes
- Validate all user inputs
- Test email functionality in development mode (check logs)

## 🐛 Troubleshooting

### Common Development Issues

**Database Connection Failed**
- **Solution:** Check your database credentials in `includes/config.php` and ensure MySQL/MariaDB is running.

**500 Internal Server Error**
- **Cause:** Usually PHP syntax errors or missing files
- **Solution:** Check PHP error logs and enable debug mode

**Styles Not Loading**
- **Cause:** Incorrect file paths or server configuration
- **Solution:** Verify web server is serving static files and check browser console

**Email Verification Not Working**
- **Solution:** In development mode, emails are logged instead of sent. Check PHP error logs for verification URLs.

Enable debug mode in `includes/config.php`:
```php
define('DEBUG', true);
```

## 🔧 Development Tools

### Recommended Tools
- **IDE:** VS Code, PhpStorm, or Sublime Text
- **Database:** phpMyAdmin, MySQL Workbench, or DBeaver
- **Browser Extensions:** React Developer Tools, Vue.js devtools
- **Version Control:** Git with GitHub Desktop or command line

### Code Quality
- Follow PSR-12 coding standards for PHP
- Use meaningful variable and function names
- Comment complex logic
- Test thoroughly before committing

## 📊 Performance Optimization

### Development Tips
- Use browser caching for static assets
- Optimize database queries with proper indexing
- Minimize HTTP requests
- Use compression for production deployment

### Database Optimization
- Index frequently queried columns
- Use prepared statements (already implemented)
- Optimize JOIN queries
- Regular database maintenance

## 🚀 Deployment

### Preparation for Production
1. Set `define('DEBUG', false);` in `includes/config.php`
2. Set `define('DEVELOPMENT_MODE', false);` for real email sending
3. Configure proper email service (SMTP) in `includes/email.php`
4. Set secure database credentials
5. Enable HTTPS in production
6. Configure proper file permissions

### Production Environment Variables
```php
// Production Configuration Example
define('DEBUG', false);
define('DEVELOPMENT_MODE', false);
define('APP_URL', 'https://your-domain.com');
define('DB_HOST', 'your-db-host');
define('DB_PASS', 'secure-password');
```

## 🤝 Contributing

### Development Process
1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Make your changes
4. Test thoroughly
5. Commit with clear messages: `git commit -m "Add feature X"`
6. Push to your fork: `git push origin feature-name`
7. Submit a pull request

### Code Standards
- Follow PHP PSR-12 coding standards
- Write clear, descriptive commit messages
- Include tests for new features
- Update documentation as needed

### Feature Development
- Test all new features thoroughly
- Ensure responsive design works
- Validate all user inputs
- Follow security best practices

## 📝 API Documentation

### Authentication
All API endpoints require authentication via PHP sessions.

### Endpoints
- `POST /api/lists.php` - List management (create, read, update, delete)
- `POST /api/items.php` - Item management (tasks and notes)
- `POST /api/search.php` - Search across lists and items

### Response Format
```json
{
    "success": true,
    "data": {},
    "message": "Operation completed successfully"
}
```

## 🎯 Getting Help

1. **Documentation**: Check this file and README.md first
2. **Issues**: Search existing GitHub issues
3. **Community**: Open a new issue with detailed information
4. **Debug**: Enable debug mode and check error logs

Happy coding! 🚀