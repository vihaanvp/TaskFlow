#!/bin/bash

# TaskFlow Installation Script
# This script helps set up TaskFlow for development

echo "🚀 TaskFlow Installation Script"
echo "================================"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed or not in PATH"
    echo "Please install PHP 7.4 or higher"
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
MIN_VERSION="7.4"

if [ "$(printf '%s\n' "$MIN_VERSION" "$PHP_VERSION" | sort -V | head -n1)" != "$MIN_VERSION" ]; then
    echo "❌ PHP version $PHP_VERSION is too old. Minimum required: $MIN_VERSION"
    exit 1
fi

echo "✅ PHP $PHP_VERSION found"

# Check if MySQL/MariaDB is available
if ! command -v mysql &> /dev/null; then
    echo "⚠️  MySQL/MariaDB client not found in PATH"
    echo "Please ensure MySQL or MariaDB is installed"
fi

# Copy environment file if it doesn't exist
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "✅ Created .env file from .env.example"
        echo "📝 Please edit .env with your database credentials"
    else
        echo "❌ .env.example not found"
        exit 1
    fi
else
    echo "✅ .env file already exists"
fi

# Check if database_migrations.sql exists
if [ ! -f "database_migrations.sql" ]; then
    echo "❌ database_migrations.sql not found"
    echo "This file is required for database setup"
    exit 1
fi

echo ""
echo "📋 Next Steps:"
echo ""
echo "1. Edit .env file with your database credentials:"
echo "   nano .env"
echo ""
echo "2. Create database and run migrations:"
echo "   mysql -u your_username -p -e \"CREATE DATABASE taskflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\""
echo "   mysql -u your_username -p taskflow < database_migrations.sql"
echo ""
echo "3. Start development server:"
echo "   php -S localhost:8000"
echo ""
echo "4. Open browser and navigate to:"
echo "   http://localhost:8000"
echo ""

# Offer to start development server
read -p "🚀 Start development server now? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Starting PHP development server on localhost:8000..."
    echo "Press Ctrl+C to stop the server"
    echo ""
    php -S localhost:8000
fi

echo "✨ Installation script completed!"
echo "Visit https://github.com/vihaanvp/TaskFlow for more help"