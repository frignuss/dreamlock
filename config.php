<?php
// Environment setting
define('ENVIRONMENT', $_ENV['ENVIRONMENT'] ?? 'development');

// Set session settings BEFORE any session is started
if (ENVIRONMENT === 'production') {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
}

// Security: Use environment variables for sensitive data
// In production, set these in your server environment or .env file
define('OPENROUTER_API_KEY', $_ENV['OPENROUTER_API_KEY'] ?? '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');

// Paddle ayarları - Gerçek değerlerle değiştirin
define('PADDLE_VENDOR_ID', $_ENV['PADDLE_VENDOR_ID'] ?? 'your_vendor_id'); // Paddle vendor ID'ni buraya ekle
define('PADDLE_CLIENT_SIDE_TOKEN', $_ENV['PADDLE_CLIENT_SIDE_TOKEN'] ?? 'your_client_side_token'); // Paddle client token
define('PADDLE_API_KEY', $_ENV['PADDLE_API_KEY'] ?? 'your_api_key'); // Paddle API key
define('PADDLE_ENVIRONMENT', $_ENV['PADDLE_ENVIRONMENT'] ?? 'sandbox'); // 'sandbox' veya 'production'
define('PADDLE_PRODUCT_ID', $_ENV['PADDLE_PRODUCT_ID'] ?? 'your_product_id'); // Premium abonelik ürün ID'si

// Veritabanı bağlantısı - Güvenli hale getirildi
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'dreamlock');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Security Constants
define('SECURE_SESSION', true);
define('CSRF_TOKEN_NAME', 'dreamlock_csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes
define('PASSWORD_MIN_LENGTH', 8);
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Security Headers
define('SECURITY_HEADERS', [
    'X-Frame-Options' => 'DENY',
    'X-Content-Type-Options' => 'nosniff',
    'X-XSS-Protection' => '1; mode=block',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https://openrouter.ai;",
    'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload'
]);

// Error Reporting - Production settings
if (ENVIRONMENT === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '/var/log/dreamlock/error.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Timezone
date_default_timezone_set('UTC');

// Security Functions
function secureHeaders() {
    foreach (SECURITY_HEADERS as $header => $value) {
        header("$header: $value");
    }
}

function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function validateCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}

function validatePassword($password) {
    return strlen($password) >= PASSWORD_MIN_LENGTH && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password);
}

// Language utilities
function sanitizeLangCode($lang) {
    $lang = strtolower(trim($lang));
    // Allow only known languages used in the app
    $allowed = ['en', 'tr', 'es', 'fr'];
    return in_array($lang, $allowed, true) ? $lang : 'en';
}

function loadLanguage() {
    // Priority: explicit query -> session -> cookie -> default
    if (isset($_GET['lang'])) {
        $lang = sanitizeLangCode($_GET['lang']);
        // Persist in session
        $_SESSION['lang'] = $lang;
        // Persist in cookie for 1 year
        $secure = (ENVIRONMENT === 'production');
        setcookie('lang', $lang, time() + 31536000, '/', '', $secure, true);
        return $lang;
    }

    if (isset($_SESSION['lang'])) {
        return sanitizeLangCode($_SESSION['lang']);
    }

    if (isset($_COOKIE['lang'])) {
        $lang = sanitizeLangCode($_COOKIE['lang']);
        $_SESSION['lang'] = $lang;
        return $lang;
    }

    // Default language
    $_SESSION['lang'] = 'en';
    return 'en';
}

function rateLimit($key, $max_attempts = 5, $time_window = 300) {
    // Use session-based rate limiting (no Redis required)
    if (!isset($_SESSION['rate_limit'][$key])) {
        $_SESSION['rate_limit'][$key] = ['count' => 0, 'time' => time()];
    }
    
    if (time() - $_SESSION['rate_limit'][$key]['time'] > $time_window) {
        $_SESSION['rate_limit'][$key] = ['count' => 1, 'time' => time()];
        return true;
    }
    
    if ($_SESSION['rate_limit'][$key]['count'] >= $max_attempts) {
        return false;
    }
    
    $_SESSION['rate_limit'][$key]['count']++;
    return true;
}

// Apply security headers
secureHeaders();
?>
