<?php
/**
 * DreamLock Security Module
 * Comprehensive security implementation for the DreamLock application
 */

// Prevent direct access
if (!defined('SECURE_ACCESS')) {
    http_response_code(403);
    exit('Direct access not allowed');
}

class DreamLockSecurity {
    
    /**
     * Initialize security settings
     */
    public static function init() {
        // Apply security headers
        self::applySecurityHeaders();
        
        // Start secure session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['last_regeneration']) || 
            time() - $_SESSION['last_regeneration'] > 300) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
    
    /**
     * Apply security headers
     */
    public static function applySecurityHeaders() {
        foreach (SECURITY_HEADERS as $header => $value) {
            header("$header: $value");
        }
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCSRFToken($token) {
        return isset($_SESSION[CSRF_TOKEN_NAME]) && 
               hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
    
    /**
     * Sanitize input data
     */
    public static function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate email address
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && 
               preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
    }
    
    /**
     * Validate password strength
     */
    public static function validatePassword($password) {
        return strlen($password) >= PASSWORD_MIN_LENGTH && 
               preg_match('/[A-Z]/', $password) && 
               preg_match('/[a-z]/', $password) && 
               preg_match('/[0-9]/', $password) &&
               preg_match('/[^A-Za-z0-9]/', $password);
    }
    
    /**
     * Rate limiting implementation (session-based)
     */
    public static function rateLimit($key, $max_attempts = 5, $time_window = 300) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $rate_key = "rate_limit:{$ip}:{$key}";
        
        // Use session-based rate limiting (no Redis required)
        if (!isset($_SESSION['rate_limit'][$rate_key])) {
            $_SESSION['rate_limit'][$rate_key] = ['count' => 0, 'time' => time()];
        }
        
        if (time() - $_SESSION['rate_limit'][$rate_key]['time'] > $time_window) {
            $_SESSION['rate_limit'][$rate_key] = ['count' => 1, 'time' => time()];
            return true;
        }
        
        if ($_SESSION['rate_limit'][$rate_key]['count'] >= $max_attempts) {
            return false;
        }
        
        $_SESSION['rate_limit'][$rate_key]['count']++;
        return true;
    }
    
    /**
     * Secure database connection
     */
    public static function getSecureDB() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            return new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection error");
        }
    }
    
    /**
     * Validate file upload
     */
    public static function validateFileUpload($file) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        
        $file_size = $file['size'];
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Check file size
        if ($file_size > MAX_FILE_SIZE) {
            return false;
        }
        
        // Check file type
        if (!in_array($file_type, ALLOWED_FILE_TYPES)) {
            return false;
        }
        
        // Validate file content
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowed_mimes = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif'
        ];
        
        return in_array($mime_type, $allowed_mimes);
    }
    
    /**
     * Secure file upload
     */
    public static function secureFileUpload($file, $upload_dir = 'uploads/') {
        if (!self::validateFileUpload($file)) {
            throw new Exception("Invalid file upload");
        }
        
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = bin2hex(random_bytes(16)) . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception("File upload failed");
        }
        
        return $new_filename;
    }
    
    /**
     * Log security events
     */
    public static function logSecurityEvent($event, $details = []) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'event' => $event,
            'details' => $details
        ];
        
        error_log("SECURITY: " . json_encode($log_entry));
    }
    
    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['last_activity']) && 
               (time() - $_SESSION['last_activity']) < SESSION_TIMEOUT;
    }
    
    /**
     * Update user activity
     */
    public static function updateUserActivity() {
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Secure logout
     */
    public static function secureLogout() {
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    /**
     * Validate user permissions
     */
    public static function checkPermission($user_id, $resource_id, $action = 'read') {
        // Implement permission checking logic here
        return true; // Placeholder
    }
    
    /**
     * Generate secure random string
     */
    public static function generateSecureToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Hash password securely
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }
    
    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Check if password needs rehashing
     */
    public static function passwordNeedsRehash($hash) {
        return password_needs_rehash($hash, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }
}

// Initialize security
DreamLockSecurity::init();
?>
