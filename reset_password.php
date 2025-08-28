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
$token = '';
$tokenValid = false;
$user = null;

// Get token from URL
if (isset($_GET['token'])) {
    $token = DreamLockSecurity::sanitizeInput($_GET['token']);
    
    if (!empty($token)) {
        try {
            $db = DreamLockSecurity::getSecureDB();
            
            // Check if token exists and is valid
            $stmt = $db->prepare("
                SELECT pr.*, u.username, u.email 
                FROM password_resets pr 
                JOIN users u ON pr.user_id = u.id 
                WHERE pr.token = ? AND pr.expires_at > NOW() AND pr.used = 0
            ");
            $stmt->execute([$token]);
            $resetData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resetData) {
                $tokenValid = true;
                $user = [
                    'id' => $resetData['user_id'],
                    'username' => $resetData['username'],
                    'email' => $resetData['email']
                ];
            } else {
                $error = "Geçersiz veya süresi dolmuş şifre sıfırlama linki.";
            }
        } catch (Exception $e) {
            DreamLockSecurity::logSecurityEvent('reset_token_error', [
                'error' => $e->getMessage(),
                'token' => $token,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $error = "Bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
        }
    } else {
        $error = "Geçersiz şifre sıfırlama linki.";
    }
} else {
    $error = "Şifre sıfırlama linki bulunamadı.";
}

// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValid) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !DreamLockSecurity::validateCSRFToken($_POST['csrf_token'])) {
        DreamLockSecurity::logSecurityEvent('csrf_attempt', ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
        $error = "Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.";
    } else {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if (empty($password)) {
            $error = "Lütfen yeni şifrenizi girin.";
        } elseif (strlen($password) < 6) {
            $error = "Şifre en az 6 karakter olmalıdır.";
        } elseif (strlen($password) > 128) {
            $error = "Şifre en fazla 128 karakter olabilir.";
        } elseif ($password !== $confirmPassword) {
            $error = "Şifreler eşleşmiyor.";
        } else {
            try {
                $db = DreamLockSecurity::getSecureDB();
                
                // Hash the new password
                $hashedPassword = DreamLockSecurity::hashPassword($password);
                
                // Update user password
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $user['id']]);
                
                // Mark reset token as used
                $stmt = $db->prepare("UPDATE password_resets SET used = 1, used_at = NOW() WHERE token = ?");
                $stmt->execute([$token]);
                
                // Log the password reset
                DreamLockSecurity::logSecurityEvent('password_reset_completed', [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                
                $success = "Şifreniz başarıyla güncellendi. Şimdi giriş yapabilirsiniz.";
                
            } catch (Exception $e) {
                DreamLockSecurity::logSecurityEvent('password_reset_error', [
                    'error' => $e->getMessage(),
                    'user_id' => $user['id'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $error = "Şifre güncellenirken bir hata oluştu. Lütfen tekrar deneyin.";
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
    <title>Yeni Şifre Belirleme - DreamLock</title>
    
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

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #39FF14;
            font-size: 16px;
            z-index: 2;
        }

        .input-group input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 2px solid rgba(57, 255, 20, 0.2);
            border-radius: 12px;
            background: rgba(42, 42, 42, 0.8);
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: #39FF14;
            box-shadow: 0 0 20px rgba(57, 255, 20, 0.3);
            background: rgba(42, 42, 42, 1);
        }

        .input-group input::placeholder {
            color: #888;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: #39FF14;
        }

        .reset-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            color: #000;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(57, 255, 20, 0.3);
            position: relative;
            overflow: hidden;
        }

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(57, 255, 20, 0.4);
        }

        .reset-btn:active {
            transform: translateY(0);
        }

        .reset-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        .password-strength {
            margin-top: 10px;
            font-size: 12px;
            color: #888;
        }

        .strength-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #ff4444; width: 25%; }
        .strength-fair { background: #ffaa00; width: 50%; }
        .strength-good { background: #39FF14; width: 75%; }
        .strength-strong { background: #2ecc71; width: 100%; }

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
                <h2>Yeni Şifre Belirleme</h2>
                <p><?= $tokenValid ? "Merhaba {$user['username']}, yeni şifrenizi belirleyin" : "Şifre sıfırlama" ?></p>
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

            <?php if ($tokenValid && empty($success)): ?>
                <form method="POST" action="" id="resetForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Yeni şifre" required
                               minlength="6" maxlength="128">
                        <span class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </span>
                    </div>
                    
                    <div class="password-strength">
                        <span id="strengthText">Şifre gücü</span>
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                    </div>

                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" id="confirmPassword" placeholder="Şifreyi tekrar girin" required
                               minlength="6" maxlength="128">
                        <span class="password-toggle" onclick="togglePassword('confirmPassword')">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </span>
                    </div>

                    <button type="submit" class="reset-btn" id="resetBtn">
                        <span>Şifreyi Güncelle</span>
                        <div class="loading" style="display: none;">
                            <div class="spinner" style="width: 20px; height: 20px; border: 2px solid rgba(0,0,0,0.3); border-top: 2px solid #000; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                        </div>
                    </button>
                </form>
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
        // Password Toggle Function
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId === 'password' ? 'toggleIcon1' : 'toggleIcon2');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Password Strength Checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const strengthText = document.getElementById('strengthText');
            const strengthFill = document.getElementById('strengthFill');
            
            if (password.length >= 6) strength += 25;
            if (password.length >= 8) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            strength = Math.min(strength, 100);
            
            // Update strength bar
            strengthFill.className = 'strength-fill';
            if (strength <= 25) {
                strengthFill.classList.add('strength-weak');
                strengthText.textContent = 'Zayıf';
                strengthText.style.color = '#ff4444';
            } else if (strength <= 50) {
                strengthFill.classList.add('strength-fair');
                strengthText.textContent = 'Orta';
                strengthText.style.color = '#ffaa00';
            } else if (strength <= 75) {
                strengthFill.classList.add('strength-good');
                strengthText.textContent = 'İyi';
                strengthText.style.color = '#39FF14';
            } else {
                strengthFill.classList.add('strength-strong');
                strengthText.textContent = 'Güçlü';
                strengthText.style.color = '#2ecc71';
            }
        }

        // Form validation
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const submitBtn = document.getElementById('resetBtn');
            
            if (password.length >= 6 && password === confirmPassword) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            validateForm();
        });

        document.getElementById('confirmPassword').addEventListener('input', validateForm);

        // Form submission with loading
        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const btn = document.getElementById('resetBtn');
            const btnText = btn.querySelector('span');
            const loading = btn.querySelector('.loading');
            
            // Show loading animation
            btnText.style.opacity = '0';
            loading.style.display = 'block';
            btn.disabled = true;
        });

        // Auto redirect after 3 seconds if success
        <?php if (!empty($success)): ?>
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 3000);
        <?php endif; ?>

        // Enhanced input effects
        document.querySelectorAll('.input-group input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.style.transition = 'all 0.3s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });

            // Real-time validation feedback
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.style.borderColor = '#39FF14';
                } else {
                    this.style.borderColor = 'rgba(57, 255, 20, 0.2)';
                }
            });
        });

        // Initial validation check
        validateForm();
    </script>
</body>
</html>








