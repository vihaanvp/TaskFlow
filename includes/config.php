<?php
/**
 * TaskFlow Configuration
 * Central configuration for the application
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'taskflow');
define('DB_USER', 'root');
define('DB_PASS', ''); // Set your MySQL password if needed

// Application Configuration
define('APP_NAME', 'TaskFlow');
define('APP_URL', 'http://localhost');
define('DEBUG', false); // Set to true for development debugging

// Email Configuration
define('MAIL_FROM_NAME', 'TaskFlow');
define('MAIL_FROM_ADDRESS', 'noreply@taskflow.local');
define('DEVELOPMENT_MODE', true); // Set to false in production
define('MAIL_DRIVER', 'mail'); // mail, smtp

// Session Configuration
define('SESSION_TIMEOUT', 7200); // 2 hours in seconds

/**
 * Get configuration value
 * @param string $key Configuration key
 * @param mixed $default Default value if key doesn't exist
 * @return mixed Configuration value
 */
function config($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}
?>