<?php
/**
 * GiggaDev E-commerce Platform
 * Configuration Settings
 */

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'giggadev');
define('DB_USER', 'giggadev_user'); // Replace with your database username
define('DB_PASS', 'strong_password'); // Replace with your secure database password

// Site configuration
define('SITE_TITLE', 'GiggaDev');
define('SITE_DESCRIPTION', 'Premium Digital Products E-commerce Platform');
define('SITE_URL', 'https://giggadev.com'); // No trailing slash
define('ADMIN_EMAIL', 'admin@giggadev.com');

// Timezone settings
date_default_timezone_set('America/Chicago');

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1); // For HTTPS connections
session_start();

// Error reporting (disable in production)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Logging settings
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/php-errors.log');

// Stripe API settings - IMPORTANT: Replace with your actual keys from Stripe dashboard
define('STRIPE_PUBLIC_KEY', 'pk_test_XXXXXXXXXXXXXXXXXXXXXXXX'); // Replace with your actual public key
define('STRIPE_SECRET_KEY', 'sk_test_XXXXXXXXXXXXXXXXXXXXXXXX'); // Replace with your actual secret key
define('STRIPE_WEBHOOK_SECRET', 'whsec_XXXXXXXXXXXXXXXXXXXXXXXX'); // Replace with your webhook signing secret

// Security settings
define('HASH_SALT', '85c25d5ef5a9cd33b46c936bd0c066f9'); // Generate a random salt for password hashing
define('TOKEN_EXPIRY', 86400); // Token expiry in seconds (24 hours)

// File upload settings
define('UPLOAD_DIR', __DIR__ . '/../../uploads');
define('MAX_UPLOAD_SIZE', 20 * 1024 * 1024); // 20MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'zip']);

// Database connection
try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    error_log('Database connection error: ' . $e->getMessage());
    // Don't display the error to users in production
    die('A database error occurred. Please try again later.');
}
