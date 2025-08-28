<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Error Handling Test - DreamLock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-section {
            background: white;
            margin: 20px 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .test-link {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .test-link:hover {
            background: #0056b3;
        }
        .test-link.danger {
            background: #dc3545;
        }
        .test-link.danger:hover {
            background: #c82333;
        }
        .test-link.success {
            background: #28a745;
        }
        .test-link.success:hover {
            background: #218838;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .code-block {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Login Error Handling Test</h1>
    
    <div class="test-section">
        <h2>Test Login Error Scenarios</h2>
        <p>Click the links below to test different error scenarios on login.php:</p>
        
        <a href="login.php?error=invalid_session" class="test-link danger">Test Invalid Session Error</a>
        <a href="login.php?error=unauthorized" class="test-link danger">Test Unauthorized Error</a>
        <a href="login.php?error=unknown" class="test-link danger">Test Unknown Error</a>
        <a href="login.php" class="test-link success">Test Normal Login (No Error)</a>
    </div>

    <div class="test-section">
        <h2>Test Session Management</h2>
        <p>Test session validation and redirects:</p>
        
        <a href="dream.php" class="test-link">Test Dream Page (Should redirect to login if not authenticated)</a>
        <a href="sleep_analysis.php" class="test-link">Test Sleep Analysis (Should redirect to login if not authenticated)</a>
        <a href="subconscious.php" class="test-link">Test Subconscious (Should redirect to login if not authenticated)</a>
        <a href="dream-sharing.php" class="test-link">Test Dream Sharing (Should redirect to login if not authenticated)</a>
    </div>

    <div class="test-section">
        <h2>Expected Behavior</h2>
        
        <div class="info-box">
            <h3>Error Messages:</h3>
            <ul>
                <li><strong>Invalid Session:</strong> "Oturum süresi dolmuş. Lütfen tekrar giriş yapın."</li>
                <li><strong>Unauthorized:</strong> "Bu sayfaya erişim için giriş yapmanız gerekiyor."</li>
                <li><strong>Unknown:</strong> "Bir hata oluştu. Lütfen tekrar deneyin."</li>
            </ul>
        </div>

        <div class="warning-box">
            <h3>Auto-Clear Features:</h3>
            <ul>
                <li>Error parameter should be removed from URL after display</li>
                <li>Error message should auto-hide after 5 seconds</li>
                <li>Page refresh should not show the error again</li>
            </ul>
        </div>

        <div class="success-box">
            <h3>Session Management:</h3>
            <ul>
                <li>Invalid sessions should be cleared automatically</li>
                <li>Protected pages should redirect to login with appropriate error</li>
                <li>Valid sessions should allow access to protected pages</li>
            </ul>
        </div>
    </div>

    <div class="test-section">
        <h2>Debug Information</h2>
        <p>Current session status:</p>
        <div class="code-block">
            <?php
            session_start();
            echo "Session ID: " . session_id() . "\n";
            echo "Session Data: " . print_r($_SESSION, true) . "\n";
            echo "Session Status: " . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive') . "\n";
            ?>
        </div>
    </div>

    <div class="test-section">
        <h2>Manual Test Steps</h2>
        <ol>
            <li>Click "Test Invalid Session Error" - should show red error message</li>
            <li>Notice the URL changes to remove the error parameter</li>
            <li>Wait 5 seconds - error message should fade out</li>
            <li>Refresh the page - no error should appear</li>
            <li>Try accessing a protected page without login - should redirect to login with error</li>
            <li>Login successfully - should redirect to dream.php</li>
        </ol>
    </div>

    <div class="test-section">
        <h2>Technical Details</h2>
        <p>The fixes implemented include:</p>
        <ul>
            <li><strong>URL Parameter Cleanup:</strong> JavaScript removes error parameters from URL</li>
            <li><strong>Auto-Hide Error:</strong> Error messages fade out after 5 seconds</li>
            <li><strong>Session Validation:</strong> Invalid sessions are cleared automatically</li>
            <li><strong>Graceful Handling:</strong> Better error message management</li>
        </ul>
    </div>

    <script>
        // Add some interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Add click tracking
            document.querySelectorAll('.test-link').forEach(link => {
                link.addEventListener('click', function() {
                    console.log('Testing:', this.textContent);
                });
            });

            // Show current URL info
            console.log('Current URL:', window.location.href);
            console.log('URL Parameters:', new URLSearchParams(window.location.search));
        });
    </script>
</body>
</html>














