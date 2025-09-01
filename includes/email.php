<?php
// Load configuration
require_once __DIR__ . '/config.php';

/**
 * Email configuration and utility functions
 * Supports both development and production environments
 */

function sendVerificationEmail($email, $token, $username) {
    $verification_url = APP_URL . "/verify_email.php?token=" . $token;
    
    $subject = APP_NAME . " - Verify Your Email";
    $from_name = MAIL_FROM_NAME;
    $from_email = MAIL_FROM_ADDRESS;
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #6366f1; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .button { display: inline-block; background: #6366f1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>📝 " . APP_NAME . "</h1>
            </div>
            <div class='content'>
                <h2>Welcome to " . APP_NAME . ", {$username}!</h2>
                <p>Thank you for signing up. Please verify your email address to complete your registration.</p>
                <p><a href='{$verification_url}' class='button'>Verify Email Address</a></p>
                <p>Or copy and paste this link: <br>{$verification_url}</p>
                <p>If you didn't create this account, you can safely ignore this email.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = array(
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        "From: {$from_name} <{$from_email}>",
        'X-Mailer: PHP/' . phpversion()
    );
    
    // In development mode, log emails instead of sending
    if (DEVELOPMENT_MODE) {
        $log_message = "=== EMAIL LOG ===\n";
        $log_message .= "To: {$email}\n";
        $log_message .= "Subject: {$subject}\n";
        $log_message .= "Verification URL: {$verification_url}\n";
        $log_message .= "Time: " . date('Y-m-d H:i:s') . "\n";
        $log_message .= "==================\n\n";
        
        error_log($log_message);
        return true; // Simulate success in development
    }
    
    // Production email sending
    return mail($email, $subject, $message, implode("\r\n", $headers));
}

function sendPasswordResetEmail($email, $token, $username) {
    $reset_url = APP_URL . "/reset_password.php?token=" . $token;
    
    $subject = APP_NAME . " - Password Reset";
    $from_name = MAIL_FROM_NAME;
    $from_email = MAIL_FROM_ADDRESS;
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #6366f1; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .button { display: inline-block; background: #6366f1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>📝 " . APP_NAME . "</h1>
            </div>
            <div class='content'>
                <h2>Password Reset Request</h2>
                <p>Hi {$username},</p>
                <p>You requested a password reset for your " . APP_NAME . " account.</p>
                <p><a href='{$reset_url}' class='button'>Reset Password</a></p>
                <p>Or copy and paste this link: <br>{$reset_url}</p>
                <p>This link will expire in 24 hours.</p>
                <p>If you didn't request this, you can safely ignore this email.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = array(
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        "From: {$from_name} <{$from_email}>",
        'X-Mailer: PHP/' . phpversion()
    );
    
    // In development mode, log emails instead of sending
    if (DEVELOPMENT_MODE) {
        $log_message = "=== EMAIL LOG ===\n";
        $log_message .= "To: {$email}\n";
        $log_message .= "Subject: {$subject}\n";
        $log_message .= "Reset URL: {$reset_url}\n";
        $log_message .= "Time: " . date('Y-m-d H:i:s') . "\n";
        $log_message .= "==================\n\n";
        
        error_log($log_message);
        return true; // Simulate success in development
    }
    
    return mail($email, $subject, $message, implode("\r\n", $headers));
}
?>