<?php
// Test page for password reset functionality
define('SECURE_ACCESS', true);
require_once 'config.php';
require_once 'includes/security.php';

echo "<h1>DreamLock Password Reset Test</h1>";

// Test database connection
try {
    $db = DreamLockSecurity::getSecureDB();
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Check if password_resets table exists
    $stmt = $db->query("SHOW TABLES LIKE 'password_resets'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ password_resets table exists</p>";
    } else {
        echo "<p style='color: red;'>❌ password_resets table does not exist</p>";
    }
    
    // Check if users table exists
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ users table exists</p>";
        
        // Count users
        $stmt = $db->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>📊 Total users: " . $result['count'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ users table does not exist</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test security functions
echo "<h2>Security Functions Test</h2>";

try {
    $token = DreamLockSecurity::generateSecureToken(64);
    echo "<p style='color: green;'>✅ Token generation: " . substr($token, 0, 20) . "...</p>";
    
    $csrf_token = DreamLockSecurity::generateCSRFToken();
    echo "<p style='color: green;'>✅ CSRF token generation: " . substr($csrf_token, 0, 20) . "...</p>";
    
    $validation = DreamLockSecurity::validateCSRFToken($csrf_token);
    echo "<p style='color: green;'>✅ CSRF validation: " . ($validation ? 'PASS' : 'FAIL') . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Security function error: " . $e->getMessage() . "</p>";
}

// Test email functionality
echo "<h2>Email Test</h2>";

if (function_exists('mail')) {
    echo "<p style='color: green;'>✅ PHP mail() function is available</p>";
} else {
    echo "<p style='color: red;'>❌ PHP mail() function is not available</p>";
}

// Test file existence
echo "<h2>File Check</h2>";

$files = [
    'login.php' => 'Login page with forgot password modal',
    'forgot_password.php' => 'Password reset request page',
    'reset_password.php' => 'Password reset form page',
    'add_password_resets_table.sql' => 'Database table creation script'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file - $description</p>";
    } else {
        echo "<p style='color: red;'>❌ $file - $description (MISSING)</p>";
    }
}

// Test URL generation
echo "<h2>URL Test</h2>";
$test_url = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/reset_password.php?token=test123";
echo "<p>Reset URL format: <code>$test_url</code></p>";

echo "<h2>Next Steps</h2>";
echo "<ol>";
echo "<li>Make sure the password_resets table is created in your database</li>";
echo "<li>Configure email settings in php.ini for XAMPP</li>";
echo "<li>Test the forgot password functionality on login.php</li>";
echo "<li>Check if emails are being sent (check spam folder)</li>";
echo "</ol>";

echo "<h2>Quick Test</h2>";
echo "<p><a href='login.php' style='background: #39FF14; color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
echo "<p><a href='forgot_password.php' style='background: #39FF14; color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Forgot Password Page</a></p>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
}
h1, h2 {
    color: #333;
}
p {
    margin: 10px 0;
    padding: 5px;
}
code {
    background: #f0f0f0;
    padding: 2px 5px;
    border-radius: 3px;
}
</style>








