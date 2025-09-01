<?php
// Email configuration and utility functions
// This is a basic implementation. In production, use a service like SendGrid, Mailgun, or AWS SES

function sendVerificationEmail($email, $token, $username) {
    $verification_url = "http://" . $_SERVER['HTTP_HOST'] . "/verify_email.php?token=" . $token;
    
    $subject = "TaskFlow - Verify Your Email";
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
                <h1>📝 TaskFlow</h1>
            </div>
            <div class='content'>
                <h2>Welcome to TaskFlow, {$username}!</h2>
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
        'From: TaskFlow <noreply@taskflow.local>',
        'X-Mailer: PHP/' . phpversion()
    );
    
    // In development, just log the email instead of sending
    if (defined('DEVELOPMENT_MODE') || true) {
        error_log("Would send verification email to {$email}:");
        error_log("Subject: {$subject}");
        error_log("Verification URL: {$verification_url}");
        return true; // Simulate success
    }
    
    // In production, uncomment this:
    // return mail($email, $subject, $message, implode("\r\n", $headers));
    
    return true;
}

function sendPasswordResetEmail($email, $token, $username) {
    $reset_url = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $token;
    
    $subject = "TaskFlow - Password Reset";
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
                <h1>📝 TaskFlow</h1>
            </div>
            <div class='content'>
                <h2>Password Reset Request</h2>
                <p>Hi {$username},</p>
                <p>You requested a password reset for your TaskFlow account.</p>
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
        'From: TaskFlow <noreply@taskflow.local>',
        'X-Mailer: PHP/' . phpversion()
    );
    
    // In development, just log the email instead of sending
    if (defined('DEVELOPMENT_MODE') || true) {
        error_log("Would send password reset email to {$email}:");
        error_log("Subject: {$subject}");
        error_log("Reset URL: {$reset_url}");
        return true;
    }
    
    return true;
}
?>