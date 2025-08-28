<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Error Test - DreamLock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
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
    </style>
</head>
<body>
    <h1>Login Error Test</h1>
    <p>Test the login.php error handling by clicking the links below:</p>
    
    <a href="login.php?error=invalid_session" class="test-link">Test Invalid Session Error</a>
    <a href="login.php?error=unauthorized" class="test-link">Test Unauthorized Error</a>
    <a href="login.php?error=unknown" class="test-link">Test Unknown Error</a>
    <a href="login.php" class="test-link">Test Normal Login (No Error)</a>
    
    <p><strong>Expected behavior:</strong></p>
    <ul>
        <li>Invalid Session: Should show "Oturum süresi dolmuş. Lütfen tekrar giriş yapın."</li>
        <li>Unauthorized: Should show "Bu sayfaya erişim için giriş yapmanız gerekiyor."</li>
        <li>Unknown: Should show "Bir hata oluştu. Lütfen tekrar deneyin."</li>
        <li>Normal: Should show no error message</li>
    </ul>
</body>
</html>














