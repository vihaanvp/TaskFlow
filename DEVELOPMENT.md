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

### 2. Environment Configuration
Copy the environment template and configure your settings:

```bash
cp .env.example .env
```

Edit `.env` file with your configuration:
```bash
nano .env  # or use your preferred editor
```

**Key settings to configure:**
```env
# Database Configuration
DB_HOST=localhost
DB_NAME=taskflow
DB_USER=your_db_user
DB_PASS=your_db_password

# Application Configuration
APP_URL=http://localhost:8000  # Adjust for your setup

# Email Configuration (for development)
MAIL_FROM_ADDRESS=noreply@taskflow.local
DEVELOPMENT_MODE=true
```

### 3. Database Setup

#### Create Database
```bash
# Connect to MySQL
mysql -u your_username -p

# Create database
CREATE DATABASE taskflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit
```

#### Run Migrations
```bash
# Import the database schema
mysql -u your_username -p taskflow < database_migrations.sql
```

### 4. Web Server Setup

#### Option A: PHP Built-in Server (Development)
```bash
# Start the development server
php -S localhost:8000

# Access the application
# Open http://localhost:8000 in your browser
```

#### Option B: Apache/Nginx
Configure your web server to serve files from the TaskFlow directory.

**Apache Virtual Host Example:**
```apache
<VirtualHost *:80>
    ServerName taskflow.local
    DocumentRoot /path/to/TaskFlow
    <Directory /path/to/TaskFlow>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `/etc/hosts`:
```
127.0.0.1 taskflow.local
```

## 🧪 Testing the Installation

### 1. Access the Application
Open your browser and navigate to your configured URL (e.g., `http://localhost:8000`)

### 2. Create Test Account
1. Click "Register" on the homepage
2. Fill in the registration form
3. Check your PHP error log for the verification email (in development mode)
4. Copy the verification URL from the log and open it in your browser

### 3. Test Features
- Create different types of lists (Todo and Notes)
- Add items to your lists
- Test the search functionality
- Try sharing a list (create another user account first)

## 📁 Project Structure

```
TaskFlow/
├── .env.example          # Environment configuration template
├── .env                  # Your local environment configuration
├── .gitignore           # Git ignore rules
├── README.md            # Main documentation
├── DEVELOPMENT.md       # This development guide
├── LICENSE              # License information
├── database_migrations.sql  # Database schema
├── features_demo.html   # Feature demonstration page
├── index.php           # Homepage
├── login.php           # User login
├── register.php        # User registration
├── dashboard.php       # Main application dashboard
├── settings.php        # User settings
├── verify_email.php    # Email verification handler
├── logout.php          # Logout handler
├── api/                # API endpoints
│   ├── delete_account.php
│   ├── items.php       # Item management
│   ├── lists.php       # List management
│   ├── search.php      # Search functionality
│   └── tags.php        # Tag management (future)
├── assets/             # Static assets
│   ├── app.js          # JavaScript functionality
│   └── style.css       # Application styles
└── includes/           # PHP includes
    ├── auth.php        # Authentication helpers
    ├── config.php      # Environment configuration loader
    ├── db.php          # Database connection
    └── email.php       # Email utilities
```

## 🔧 Configuration Details

### Environment Variables

| Variable | Description | Default | Required |
|----------|-------------|---------|----------|
| `DB_HOST` | Database host | `localhost` | Yes |
| `DB_NAME` | Database name | `taskflow` | Yes |
| `DB_USER` | Database user | `root` | Yes |
| `DB_PASS` | Database password | _(empty)_ | No |
| `APP_NAME` | Application name | `TaskFlow` | No |
| `APP_URL` | Base application URL | `http://localhost` | No |
| `APP_ENV` | Environment (development/production) | `development` | No |
| `DEVELOPMENT_MODE` | Enable development features | `true` | No |
| `DEBUG` | Enable debug mode | `true` | No |
| `MAIL_FROM_ADDRESS` | Default sender email | `noreply@taskflow.local` | No |
| `MAIL_FROM_NAME` | Default sender name | `TaskFlow` | No |

### Email Configuration

In **development mode**, emails are logged to PHP error log instead of being sent. 

For **production**, configure:
```env
DEVELOPMENT_MODE=false
MAIL_DRIVER=smtp
MAIL_HOST=your.smtp.host
MAIL_PORT=587
MAIL_USERNAME=your_smtp_user
MAIL_PASSWORD=your_smtp_password
```

## 🐛 Troubleshooting

### Common Issues

#### Database Connection Failed
```
Error: Database connection failed
```
**Solution:** Check your database credentials in `.env` and ensure MySQL/MariaDB is running.

#### Permission Denied
```
Error: Permission denied
```
**Solution:** Ensure your web server has read permissions to the TaskFlow directory.

#### Email Not Working
In development mode, emails are logged. Check your PHP error log:
```bash
tail -f /var/log/php_errors.log
# or
tail -f /usr/local/var/log/php_error_log
```

#### 404 Errors
Ensure your web server is configured correctly and serving files from the TaskFlow directory.

### Debug Mode

Enable debug mode in `.env`:
```env
DEBUG=true
```

This provides more detailed error messages.

## 🔄 Development Workflow

### Making Changes

1. **Frontend Changes**: Edit files in `assets/` (CSS/JS)
2. **Backend Changes**: Edit PHP files and API endpoints
3. **Database Changes**: Update `database_migrations.sql`

### Testing

Always test your changes with:
- Different list types (Todo vs Notes)
- User registration and email verification
- List sharing functionality
- Search across different content types

### Version Control

Follow standard Git practices:
```bash
git add .
git commit -m "Description of changes"
git push origin your-branch
```

## 📚 Additional Resources

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [TaskFlow Features Demo](features_demo.html)

## 🆘 Getting Help

If you encounter issues:

1. Check this development guide
2. Review the error logs
3. Ensure your environment configuration is correct
4. Create an issue on the GitHub repository with:
   - Error messages
   - Your environment details
   - Steps to reproduce the issue