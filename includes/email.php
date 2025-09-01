<?php
// Load environment configuration
require_once __DIR__ . '/config.php';

/**
 * Email configuration and utility functions
 * Supports both development and production environments
 */

function sendVerificationEmail($email, $token, $username) {
    $app_url = env('APP_URL', 'http://localhost');
    $verification_url = $app_url . "/verify_email.php?token=" . $token;
    
    $subject = env('APP_NAME', 'TaskFlow') . " - Verify Your Email";
    $from_name = env('MAIL_FROM_NAME', 'TaskFlow');
    $from_email = env('MAIL_FROM_ADDRESS', 'noreply@taskflow.local');
    
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
                <h1>📝 " . env('APP_NAME', 'TaskFlow') . "</h1>
            </div>
            <div class='content'>
                <h2>Welcome to " . env('APP_NAME', 'TaskFlow') . ", {$username}!</h2>
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
    if (env('DEVELOPMENT_MODE', true) || env('APP_ENV') === 'development') {
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
    if (env('MAIL_DRIVER') === 'smtp') {
        // For production, integrate with your preferred email service
        // Examples: SendGrid, Mailgun, AWS SES, etc.
        return mail($email, $subject, $message, implode("\r\n", $headers));
    }
    
    return mail($email, $subject, $message, implode("\r\n", $headers));
}

function sendPasswordResetEmail($email, $token, $username) {
    $app_url = env('APP_URL', 'http://localhost');
    $reset_url = $app_url . "/reset_password.php?token=" . $token;
    
    $subject = env('APP_NAME', 'TaskFlow') . " - Password Reset";
    $from_name = env('MAIL_FROM_NAME', 'TaskFlow');
    $from_email = env('MAIL_FROM_ADDRESS', 'noreply@taskflow.local');
    
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
                <h1>📝 " . env('APP_NAME', 'TaskFlow') . "</h1>
            </div>
            <div class='content'>
                <h2>Password Reset Request</h2>
                <p>Hi {$username},</p>
                <p>You requested a password reset for your " . env('APP_NAME', 'TaskFlow') . " account.</p>
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
    if (env('DEVELOPMENT_MODE', true) || env('APP_ENV') === 'development') {
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