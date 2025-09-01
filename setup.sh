#!/bin/bash

# TaskFlow Setup Script
# This script helps set up TaskFlow for development

echo "🚀 TaskFlow Setup"
echo "================="

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 7.4 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "✅ PHP version: $PHP_VERSION"

# Check if MySQL is available
if ! command -v mysql &> /dev/null; then
    echo "⚠️  MySQL command not found. Make sure MySQL/MariaDB is installed and running."
else
    echo "✅ MySQL found"
fi

# Check if database exists
echo ""
echo "📋 Setup Steps:"
echo "1. Configure database settings in includes/config.php"
echo "2. Create database: CREATE DATABASE taskflow;"
echo "3. Import database: mysql -u root -p taskflow < database_migrations.sql"
echo "4. Start development server: php -S localhost:8000"
echo ""

# Check if config file exists
if [ -f "includes/config.php" ]; then
    echo "✅ Configuration file exists"
else
    echo "❌ Configuration file missing. Please check includes/config.php"
    exit 1
fi

# Check if database schema exists
if [ -f "database_migrations.sql" ]; then
    echo "✅ Database schema file exists"
else
    echo "❌ Database schema file missing"
    exit 1
fi

echo ""
echo "🎉 Ready to start! Run:"
echo "   php -S localhost:8000"
echo ""
echo "Then open http://localhost:8000 in your browser"