<?php
// Test configuration
echo "<h1>DreamLock Configuration Test</h1>";

// Test environment
echo "<p><strong>Environment:</strong> " . (defined('ENVIRONMENT') ? ENVIRONMENT : 'NOT DEFINED') . "</p>";

// Test database connection
try {
    require_once 'config.php';
    require_once 'includes/security.php';
    
    $db = DreamLockSecurity::getSecureDB();
    echo "<p><strong>Database Connection:</strong> ✅ SUCCESS</p>";
    
    // Test a simple query
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p><strong>Users Count:</strong> " . $result['count'] . "</p>";
    
} catch (Exception $e) {
    echo "<p><strong>Database Connection:</strong> ❌ FAILED - " . $e->getMessage() . "</p>";
}

// Test security functions
echo "<h2>Security Tests</h2>";

// Test CSRF token
$token = DreamLockSecurity::generateCSRFToken();
echo "<p><strong>CSRF Token Generated:</strong> " . (strlen($token) > 0 ? "✅ SUCCESS" : "❌ FAILED") . "</p>";

// Test input sanitization
$test_input = "<script>alert('test')</script>";
$sanitized = DreamLockSecurity::sanitizeInput($test_input);
echo "<p><strong>Input Sanitization:</strong> " . (strpos($sanitized, '<script>') === false ? "✅ SUCCESS" : "❌ FAILED") . "</p>";

// Test rate limiting
$rate_limit_result = DreamLockSecurity::rateLimit('test', 5, 300);
echo "<p><strong>Rate Limiting:</strong> " . ($rate_limit_result ? "✅ SUCCESS" : "❌ FAILED") . "</p>";

echo "<h2>Configuration Complete!</h2>";
echo "<p>If you see all ✅ SUCCESS messages above, your configuration is working correctly.</p>";
echo "<p><a href='index.php'>Go to Homepage</a></p>";
?>

