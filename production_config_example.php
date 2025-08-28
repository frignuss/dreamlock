<?php
/**
 * Production Configuration Example for DreamLock
 * Copy this to config.php and update with your production values
 */

// Environment setting - CHANGE THIS
define('ENVIRONMENT', 'production'); // Changed from 'development'

// Production Security Settings
if (ENVIRONMENT === 'production') {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1); // Requires HTTPS
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    
    // Disable error display in production
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '/var/log/dreamlock/error.log'); // Update path
}

// Production API Keys - UPDATE THESE
define('OPENROUTER_API_KEY', $_ENV['OPENROUTER_API_KEY'] ?? 'your-production-api-key');

// Production Paddle Settings - UPDATE THESE
define('PADDLE_VENDOR_ID', $_ENV['PADDLE_VENDOR_ID'] ?? 'your-production-vendor-id');
define('PADDLE_CLIENT_SIDE_TOKEN', $_ENV['PADDLE_CLIENT_SIDE_TOKEN'] ?? 'your-production-client-token');
define('PADDLE_API_KEY', $_ENV['PADDLE_API_KEY'] ?? 'your-production-api-key');
define('PADDLE_ENVIRONMENT', 'production'); // Changed from 'sandbox'
define('PADDLE_PRODUCT_ID', $_ENV['PADDLE_PRODUCT_ID'] ?? 'your-production-product-id');

// Production Database Settings - UPDATE THESE
define('DB_HOST', $_ENV['DB_HOST'] ?? 'your-production-db-host');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'your-production-db-name');
define('DB_USER', $_ENV['DB_USER'] ?? 'your-production-db-user');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'your-production-db-password');

// Production Security Constants - MAY NEED UPDATES
define('SECURE_SESSION', true);
define('CSRF_TOKEN_NAME', 'dreamlock_csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes
define('PASSWORD_MIN_LENGTH', 8);
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Production Security Headers - UPDATE DOMAIN
define('SECURITY_HEADERS', [
    'X-Frame-Options' => 'DENY',
    'X-Content-Type-Options' => 'nosniff',
    'X-XSS-Protection' => '1; mode=block',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https://openrouter.ai;",
    'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload'
]);

// Production Email Settings - ADD THESE
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_PORT', $_ENV['SMTP_PORT'] ?? 587);
define('SMTP_USERNAME', $_ENV['SMTP_USERNAME'] ?? 'your-email@gmail.com');
define('SMTP_PASSWORD', $_ENV['SMTP_PASSWORD'] ?? 'your-app-password');
define('SMTP_FROM_EMAIL', $_ENV['SMTP_FROM_EMAIL'] ?? 'noreply@dreamlock.com');
define('SMTP_FROM_NAME', $_ENV['SMTP_FROM_NAME'] ?? 'DreamLock');

// Production URL Settings - UPDATE THESE
define('SITE_URL', $_ENV['SITE_URL'] ?? 'https://yourdomain.com');
define('SITE_NAME', 'DreamLock');

// Production Logging Settings
define('LOG_DIR', '/var/log/dreamlock/'); // Update path
define('SECURITY_LOG_FILE', LOG_DIR . 'security.log');
define('ERROR_LOG_FILE', LOG_DIR . 'error.log');
define('ACCESS_LOG_FILE', LOG_DIR . 'access.log');

// Production Cache Settings
define('CACHE_ENABLED', true);
define('CACHE_DIR', '/tmp/dreamlock_cache/'); // Update path
define('CACHE_DURATION', 3600); // 1 hour

// Production Rate Limiting
define('RATE_LIMIT_ENABLED', true);
define('RATE_LIMIT_STORAGE', 'redis'); // or 'file' or 'database'
define('REDIS_HOST', $_ENV['REDIS_HOST'] ?? 'localhost');
define('REDIS_PORT', $_ENV['REDIS_PORT'] ?? 6379);

// Production Backup Settings
define('BACKUP_ENABLED', true);
define('BACKUP_DIR', '/var/backups/dreamlock/'); // Update path
define('BACKUP_RETENTION_DAYS', 30);

// Production Monitoring
define('MONITORING_ENABLED', true);
define('SENTRY_DSN', $_ENV['SENTRY_DSN'] ?? ''); // For error tracking
define('GOOGLE_ANALYTICS_ID', $_ENV['GOOGLE_ANALYTICS_ID'] ?? '');

// Production Maintenance Mode
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_ALLOWED_IPS', ['127.0.0.1', '::1']); // Add your IP

// Production SSL Settings
define('FORCE_HTTPS', true);
define('SSL_CERT_PATH', '/path/to/ssl/certificate.crt');
define('SSL_KEY_PATH', '/path/to/ssl/private.key');

// Production Database Optimization
define('DB_PERSISTENT_CONNECTIONS', true);
define('DB_CONNECTION_POOL_SIZE', 10);
define('DB_QUERY_CACHE_ENABLED', true);

// Production File Upload Settings
define('UPLOAD_DIR', '/var/www/dreamlock/uploads/'); // Update path
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);

// Production Session Settings
define('SESSION_SAVE_PATH', '/tmp/sessions/'); // Update path
define('SESSION_GC_MAXLIFETIME', 1440); // 24 minutes
define('SESSION_GC_PROBABILITY', 1);
define('SESSION_GC_DIVISOR', 100);

// Production Timezone
date_default_timezone_set('Europe/Istanbul'); // Update to your timezone

// Production Error Handling
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Production Exception Handler
set_exception_handler(function($exception) {
    error_log("Uncaught Exception: " . $exception->getMessage());
    if (ENVIRONMENT === 'production') {
        http_response_code(500);
        include 'error/500.html';
    } else {
        throw $exception;
    }
});

// Production Shutdown Function
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && $error['type'] === E_ERROR) {
        error_log("Fatal Error: " . $error['message']);
        if (ENVIRONMENT === 'production') {
            http_response_code(500);
            include 'error/500.html';
        }
    }
});

// Production Security Functions
function secureHeaders() {
    foreach (SECURITY_HEADERS as $header => $value) {
        header("$header: $value");
    }
    
    // Force HTTPS in production
    if (FORCE_HTTPS && !isset($_SERVER['HTTPS'])) {
        $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $redirect_url");
        exit();
    }
}

// Production CSRF Functions
function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function validateCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

// Production Input Sanitization
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Production Email Validation
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && 
           preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}

// Production Password Validation
function validatePassword($password) {
    return strlen($password) >= PASSWORD_MIN_LENGTH && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password) &&
           preg_match('/[^A-Za-z0-9]/', $password);
}

// Production Logging Function
function logSecurityEvent($event, $details = []) {
    $log_entry = date('Y-m-d H:i:s') . " | " . $event . " | " . 
                 json_encode($details) . " | " . 
                 ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
    
    if (!is_dir(dirname(SECURITY_LOG_FILE))) {
        mkdir(dirname(SECURITY_LOG_FILE), 0755, true);
    }
    
    file_put_contents(SECURITY_LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);
}

// Production Maintenance Mode Check
function checkMaintenanceMode() {
    if (MAINTENANCE_MODE && !in_array($_SERVER['REMOTE_ADDR'], MAINTENANCE_ALLOWED_IPS)) {
        http_response_code(503);
        include 'error/503.html';
        exit();
    }
}

// Initialize production settings
if (ENVIRONMENT === 'production') {
    secureHeaders();
    checkMaintenanceMode();
}
?>








