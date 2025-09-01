#!/bin/bash

# TaskFlow Production Readiness Test Script
# This script validates that TaskFlow is ready for production use

echo "🧪 TaskFlow Production Readiness Test"
echo "====================================="
echo

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test counter
TESTS_PASSED=0
TESTS_FAILED=0

# Function to run a test
run_test() {
    local test_name="$1"
    local test_command="$2"
    echo -n "Testing $test_name... "
    
    if eval "$test_command" &>/dev/null; then
        echo -e "${GREEN}✅ PASS${NC}"
        ((TESTS_PASSED++))
    else
        echo -e "${RED}❌ FAIL${NC}"
        ((TESTS_FAILED++))
    fi
}

# Test 1: Check PHP requirements
echo "🔍 Checking PHP Requirements"
echo "----------------------------"
run_test "PHP Version (>= 7.4)" "php -v | grep -E 'PHP [7-9]\.[4-9]|PHP [8-9]'"
run_test "PDO Extension" "php -m | grep -i pdo"
run_test "MySQL Extension" "php -m | grep -i mysql"
run_test "mbstring Extension" "php -m | grep -i mbstring"
run_test "JSON Extension" "php -m | grep -i json"
echo

# Test 2: Check file structure
echo "📂 Checking File Structure"
echo "--------------------------"
run_test "Database SQL file exists" "[ -f taskflow_database.sql ]"
run_test "Configuration file exists" "[ -f includes/config.php ]"
run_test "Authentication file exists" "[ -f includes/auth.php ]"
run_test "Database connection file exists" "[ -f includes/db.php ]"
run_test "Main dashboard exists" "[ -f dashboard.php ]"
run_test "Login page exists" "[ -f login.php ]"
run_test "Registration page exists" "[ -f register.php ]"
run_test "CSS file exists" "[ -f assets/style.css ]"
echo

# Test 3: Check removed files
echo "🗑️  Checking Removed Files"
echo "--------------------------"
run_test "Email verification removed" "[ ! -f verify_email.php ]"
run_test "Email functions removed" "[ ! -f includes/email.php ]"
run_test "Old database file removed" "[ ! -f database_migrations.sql ]"
echo

# Test 4: Check configuration
echo "⚙️  Checking Configuration"
echo "-------------------------"
run_test "Config file is readable" "[ -r includes/config.php ]"
run_test "No email verification in config" "! grep -q 'ENABLE_EMAIL_VERIFICATION.*true' includes/config.php"
run_test "Database constants defined" "grep -q 'define.*DB_HOST' includes/config.php"
echo

# Test 5: Check code quality
echo "📝 Checking Code Quality" 
echo "-----------------------"
run_test "No PHP syntax errors in config" "php -l includes/config.php"
run_test "No PHP syntax errors in auth" "php -l includes/auth.php"
run_test "No PHP syntax errors in db" "php -l includes/db.php"
run_test "No PHP syntax errors in login" "php -l login.php"
run_test "No PHP syntax errors in register" "php -l register.php"
run_test "No PHP syntax errors in dashboard" "php -l dashboard.php"
echo

# Test 6: Check database schema
echo "🗄️  Checking Database Schema"
echo "----------------------------"
run_test "Database file has CREATE statements" "grep -q 'CREATE TABLE' taskflow_database.sql"
run_test "Users table definition exists" "grep -q 'CREATE TABLE.*users' taskflow_database.sql"
run_test "Lists table definition exists" "grep -q 'CREATE TABLE.*lists' taskflow_database.sql"
run_test "Items table definition exists" "grep -q 'CREATE TABLE.*items' taskflow_database.sql"
run_test "No email columns in users table" "! grep -A 20 'CREATE TABLE.*users' taskflow_database.sql | grep -i email"
run_test "Demo data included" "grep -q 'INSERT INTO users' taskflow_database.sql"
echo

# Test 7: Check security
echo "🔒 Checking Security"
echo "-------------------"
run_test "Password hashing used" "grep -q 'password_hash' register.php"
run_test "Password verification used" "grep -q 'password_verify' login.php"
run_test "SQL prepared statements used" "grep -q 'prepare.*SELECT' login.php"
run_test "No hardcoded passwords" "! grep -i 'password.*=' includes/config.php | grep -v '//' | grep -v password_hash"
echo

# Test 8: Check documentation
echo "📚 Checking Documentation"
echo "------------------------"
run_test "README.md exists" "[ -f README.md ]"
run_test "Database setup guide exists" "[ -f DATABASE_SETUP.md ]"
run_test "Development guide exists" "[ -f DEVELOPMENT.md ]"
run_test "README mentions new database file" "grep -q 'taskflow_database.sql' README.md"
run_test "No email verification required" "! grep -q 'email verification required\|with email verification\|Please check your email' README.md"
echo

# Final summary
echo "📊 Test Results Summary"
echo "======================"
echo -e "Tests Passed: ${GREEN}$TESTS_PASSED${NC}"
echo -e "Tests Failed: ${RED}$TESTS_FAILED${NC}"
echo

if [ $TESTS_FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 All tests passed! TaskFlow is production ready!${NC}"
    echo
    echo "✅ Next steps:"
    echo "1. Import taskflow_database.sql into your MySQL database"
    echo "2. Update includes/config.php with your database credentials"
    echo "3. Test with test_database.php"
    echo "4. Deploy to your web server"
    echo
    exit 0
else
    echo -e "${RED}❌ Some tests failed. Please fix the issues before deploying.${NC}"
    echo
    exit 1
fi