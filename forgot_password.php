<?php
// Security initialization
define('SECURE_ACCESS', true);
require_once 'config.php';
require_once 'includes/security.php';

// Check if already logged in
if (DreamLockSecurity::isAuthenticated()) {
    header("Location: dream.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !DreamLockSecurity::validateCSRFToken($_POST['csrf_token'])) {
        DreamLockSecurity::logSecurityEvent('csrf_attempt', ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
        $error = "Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.";
    } else {
        $email = DreamLockSecurity::sanitizeInput($_POST['email']);
        
        if (empty($email)) {
            $error = "Lütfen email adresinizi girin.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Lütfen geçerli bir email adresi girin.";
        } else {
            try {
                $db = DreamLockSecurity::getSecureDB();
                
                // Check if user exists
                $stmt = $db->prepare("SELECT id, username, email FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    // Generate secure reset token
                    $token = DreamLockSecurity::generateSecureToken(64);
                    $expires = time() + (3600 * 24); // 24 hours
                    
                    // Store reset token in database
                    $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$user['id'], $token, date('Y-m-d H:i:s', $expires), date('Y-m-d H:i:s')]);
                    
                    // Send reset email
                    $resetLink = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/reset_password.php?token=" . urlencode($token);
                    
                    $to = $user['email'];
                    $subject = "DreamLock - Şifre Sıfırlama";
                    $message = "
                    <html>
                    <head>
                        <title>Şifre Sıfırlama</title>
                    </head>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                            <div style='text-align: center; margin-bottom: 30px;'>
                                <h1 style='color: #39FF14; margin: 0;'>DREAMLOCK</h1>
                                <p style='color: #666; margin: 10px 0;'>Şifre Sıfırlama İsteği</p>
                            </div>
                            
                            <div style='background: #f9f9f9; padding: 30px; border-radius: 10px; border-left: 4px solid #39FF14;'>
                                <h2 style='color: #333; margin-top: 0;'>Merhaba {$user['username']},</h2>
                                
                                <p>DreamLock hesabınız için şifre sıfırlama talebinde bulundunuz.</p>
                                
                                <p>Şifrenizi sıfırlamak için aşağıdaki butona tıklayın:</p>
                                
                                <div style='text-align: center; margin: 30px 0;'>
                                    <a href='{$resetLink}' style='background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%); color: #000; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;'>Şifremi Sıfırla</a>
                                </div>
                                
                                <p style='font-size: 14px; color: #666;'>Bu link 24 saat geçerlidir.</p>
                                
                                <p style='font-size: 14px; color: #666;'>Eğer bu talebi siz yapmadıysanız, bu emaili görmezden gelebilirsiniz.</p>
                            </div>
                            
                            <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
                                <p style='color: #666; font-size: 12px;'>
                                    Bu email DreamLock sisteminden otomatik olarak gönderilmiştir.<br>
                                    Lütfen bu emaili yanıtlamayın.
                                </p>
                            </div>
                        </div>
                    </body>
                    </html>";
                    
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: DreamLock <noreply@dreamlock.com>" . "\r\n";
                    
                    if (mail($to, $subject, $message, $headers)) {
                        $success = "Şifre sıfırlama linki email adresinize gönderildi. Lütfen email kutunuzu kontrol edin.";
                        
                        // Log the password reset request
                        DreamLockSecurity::logSecurityEvent('password_reset_requested', [
                            'user_id' => $user['id'],
                            'email' => $email,
                            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                        ]);
                    } else {
                        $error = "Email gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
                    }
                } else {
                    // Don't reveal if email exists or not for security
                    $success = "Şifre sıfırlama linki email adresinize gönderildi. Lütfen email kutunuzu kontrol edin.";
                }
            } catch (Exception $e) {
                DreamLockSecurity::logSecurityEvent('password_reset_error', [
                    'error' => $e->getMessage(),
                    'email' => $email,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $error = "Bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
            }
        }
    }
}

// Generate CSRF token for the form
$csrf_token = DreamLockSecurity::generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="icon" href="assets/logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırlama - DreamLock</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Manrope', sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 40px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(57, 255, 20, 0.2);
        }

        .navbar-brand a {
            font-size: 32px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navbar-brand a:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 10px #39FF14);
        }

        .navbar-brand .logo-icon {
            margin-right: 10px;
            font-size: 28px;
            color: #39FF14;
        }

        .navbar-brand span.white {
            color: #ffffff;
        }

        .navbar-brand span.green {
            color: #39FF14;
            text-shadow: 0 0 10px #39FF14;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 20px 40px;
            position: relative;
            z-index: 1;
        }

        .reset-container {
            max-width: 450px;
            width: 100%;
            padding: 50px;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 
                0 0 50px rgba(57, 255, 20, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(57, 255, 20, 0.2);
            position: relative;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reset-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .reset-header h2 {
            color: #39FF14;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
        }

        .reset-header p {
            color: #aaa;
            font-size: 16px;
        }

        .success {
            background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%);
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(57, 255, 20, 0.3);
            color: #000;
        }

        .error {
            background: linear-gradient(135deg, #ff4444 0%, #cc1f1f 100%);
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(255, 68, 68, 0.3);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 20%, 40%, 60%, 80%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        .back-link a {
            color: #39FF14;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            color: #2ecc71;
            text-shadow: 0 0 5px #39FF14;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            
            .navbar-brand a {
                font-size: 24px;
            }

            .reset-container {
                margin: 20px;
                padding: 30px;
            }

            .reset-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-brand">
            <a href="index.php">
                <i class="fas fa-brain logo-icon"></i>
                <span class="white">DREAM</span><span class="green">LOCK</span>
            </a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <div class="reset-container">
            <div class="reset-header">
                <h2>Şifre Sıfırlama</h2>
                <p>Email adresinizi girin</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <div class="back-link">
                <a href="login.php">
                    <i class="fas fa-arrow-left"></i>
                    Giriş sayfasına dön
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto redirect after 5 seconds if success
        <?php if (!empty($success)): ?>
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>








