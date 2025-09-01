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
define('APP_URL', 'http://localhost'); // Update for production: https://yourdomain.com
define('DEBUG', true); // Set to false for production - IMPORTANT!

// Security Configuration
define('SESSION_TIMEOUT', 7200); // 2 hours in seconds
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour

// Email Configuration (Disabled - Email verification removed)
// define('MAIL_FROM_NAME', 'TaskFlow');
// define('MAIL_FROM_ADDRESS', 'noreply@taskflow.local');
// define('DEVELOPMENT_MODE', true);
// define('MAIL_DRIVER', 'mail');

// Performance Configuration
define('ENABLE_CACHING', false); // Set to true for production with proper cache setup
define('CACHE_LIFETIME', 3600); // 1 hour

// Feature Flags
define('ENABLE_REGISTRATION', true); // Set to false to disable new registrations
// Email verification removed - users can login immediately after registration
// define('ENABLE_EMAIL_VERIFICATION', false);
// define('ENABLE_PASSWORD_RESET', false);

/**
 * Get configuration value
 * @param string $key Configuration key
 * @param mixed $default Default value if key doesn't exist
 * @return mixed Configuration value
 */
function config($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}

/**
 * Check if application is in production mode
 * @return bool
 */
function isProduction() {
    return !DEBUG && !DEVELOPMENT_MODE;
}

/**
 * Get secure headers for production
 * @return array
 */
function getSecurityHeaders() {
    if (isProduction()) {
        return [
            'X-Content-Type-Options: nosniff',
            'X-Frame-Options: DENY',
            'X-XSS-Protection: 1; mode=block',
            'Referrer-Policy: strict-origin-when-cross-origin',
            'Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\'; style-src \'self\' \'unsafe-inline\';'
        ];
    }
    return [];
}

// Apply security headers if in production
if (isProduction()) {
    foreach (getSecurityHeaders() as $header) {
        header($header);
    }
}
?>