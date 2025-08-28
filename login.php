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

// Clear any invalid session data to prevent issues
if (isset($_SESSION['user_id']) && !DreamLockSecurity::isAuthenticated()) {
    // Session exists but is invalid, clear it
    session_destroy();
    session_start();
}

$error = '';
$success = '';

// Handle error from URL parameters (e.g., from redirects)
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_session':
            $error = 'Oturum süresi dolmuş. Lütfen tekrar giriş yapın.';
            break;
        case 'unauthorized':
            $error = 'Bu sayfaya erişim için giriş yapmanız gerekiyor.';
            break;
        default:
            $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
    }
    
    // Clear the error parameter from URL to prevent it from showing on refresh
    if (!empty($error)) {
        echo "<script>
            // Remove error parameter from URL without page reload
            if (window.history.replaceState) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        </script>";
    }
}

// Rate limiting for login attempts - only apply on actual login attempts
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rate_limit_error = '';

// Only check rate limiting when there's a POST request (actual login attempt)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!DreamLockSecurity::rateLimit("login:{$ip}", MAX_LOGIN_ATTEMPTS, LOGIN_LOCKOUT_TIME)) {
        $rate_limit_error = "Çok fazla giriş denemesi. Lütfen " . (LOGIN_LOCKOUT_TIME / 60) . " dakika sonra tekrar deneyin.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error) && empty($rate_limit_error)) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !DreamLockSecurity::validateCSRFToken($_POST['csrf_token'])) {
        DreamLockSecurity::logSecurityEvent('csrf_attempt', ['ip' => $ip]);
        $error = "Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.";
    } else {
        // Sanitize inputs
        $input = DreamLockSecurity::sanitizeInput($_POST['identifier']);
        $password = $_POST['password'];
        $rememberMe = isset($_POST['remember_me']);

        if (empty($input) || empty($password)) {
            $error = "Lütfen tüm alanları doldurun.";
        } else {
            try {
                $db = DreamLockSecurity::getSecureDB();
                
                // Use prepared statement with proper validation
                $stmt = $db->prepare("SELECT id, username, email, password, is_premium, premium_expires_at FROM users WHERE username = ? OR email = ? OR phone = ?");
                $stmt->execute([$input, $input, $input]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && DreamLockSecurity::verifyPassword($password, $user['password'])) {
                    // Check if password needs rehashing
                    if (DreamLockSecurity::passwordNeedsRehash($user['password'])) {
                        $newHash = DreamLockSecurity::hashPassword($password);
                        $updateStmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $updateStmt->execute([$newHash, $user['id']]);
                    }
                    
                    // Successful login
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_premium'] = $user['is_premium'];
                    $_SESSION['last_activity'] = time();
                    $_SESSION['login_time'] = time();
                    
                    // Log successful login
                    DreamLockSecurity::logSecurityEvent('login_success', [
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'ip' => $ip
                    ]);
                    
                    // Remember me functionality with secure token
                    if ($rememberMe) {
                        $token = DreamLockSecurity::generateSecureToken(64);
                        $expires = time() + (86400 * 30); // 30 days
                        
                        // Store token in database (you'll need to create a remember_tokens table)
                        // For now, we'll use a secure cookie
                        setcookie('remember_token', $token, $expires, '/', '', true, true);
                    }
                    
                    $success = "Giriş başarılı! Yönlendiriliyorsunuz...";
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showSuccessModal();
                        });
                    </script>";
                } else {
                    // Failed login
                    DreamLockSecurity::logSecurityEvent('login_failed', [
                        'identifier' => $input,
                        'ip' => $ip
                    ]);
                    $error = "Hatalı kullanıcı adı, e-posta veya şifre.";
                }
            } catch (Exception $e) {
                DreamLockSecurity::logSecurityEvent('login_error', [
                    'error' => $e->getMessage(),
                    'ip' => $ip
                ]);
                $error = "Giriş sırasında bir hata oluştu. Lütfen tekrar deneyiniz.";
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

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Giriş Yap - DreamLock</title>

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

    /* Animated Background Particles */
    .bg-animation {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
    }

    .particle {
      position: absolute;
      background: radial-gradient(circle, #39FF14 0%, transparent 70%);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .particle:nth-child(1) { width: 4px; height: 4px; top: 20%; left: 15%; animation-delay: 0s; }
    .particle:nth-child(2) { width: 6px; height: 6px; top: 70%; left: 25%; animation-delay: 1s; }
    .particle:nth-child(3) { width: 3px; height: 3px; top: 45%; left: 75%; animation-delay: 2s; }
    .particle:nth-child(4) { width: 5px; height: 5px; top: 85%; left: 65%; animation-delay: 3s; }
    .particle:nth-child(5) { width: 4px; height: 4px; top: 15%; left: 85%; animation-delay: 4s; }
    .particle:nth-child(6) { width: 3px; height: 3px; top: 60%; left: 5%; animation-delay: 5s; }

    @keyframes float {
      0%, 100% { transform: translateY(0px) scale(1); opacity: 0.7; }
      50% { transform: translateY(-20px) scale(1.1); opacity: 1; }
    }

    /* Floating Elements */
    .floating-element {
      position: absolute;
      pointer-events: none;
      color: rgba(57, 255, 20, 0.1);
      font-size: 20px;
      animation: floatSlow 8s ease-in-out infinite;
    }

    .floating-element:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
    .floating-element:nth-child(2) { top: 20%; right: 15%; animation-delay: 2s; }
    .floating-element:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }
    .floating-element:nth-child(4) { bottom: 10%; right: 10%; animation-delay: 6s; }

    @keyframes floatSlow {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-15px) rotate(5deg); }
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

    .login-container {
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

    .login-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .login-header h2 {
      color: #39FF14;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
      text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
    }

    .login-header p {
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

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 25px 0;
      font-size: 14px;
    }

    .remember-me {
      display: flex;
      align-items: center;
      color: #ddd;
      cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin-right: 8px;
      accent-color: #39FF14;
      cursor: pointer;
    }

    .forgot-password {
      color: #39FF14;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .forgot-password:hover {
      color: #2ecc71;
      text-shadow: 0 0 5px #39FF14;
    }

    .login-btn {
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

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(57, 255, 20, 0.4);
    }

    .login-btn:active {
      transform: translateY(0);
    }

    .login-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .login-btn:hover::before {
      left: 100%;
    }

    .login-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
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
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .error i {
      margin-right: 8px;
      font-size: 16px;
    }

    @keyframes shake {
      0%, 20%, 40%, 60%, 80%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    }

    .register-link {
      text-align: center;
      margin-top: 30px;
      color: #aaa;
      padding-top: 20px;
      border-top: 1px solid rgba(57, 255, 20, 0.1);
    }

    .register-link a {
      color: #39FF14;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .register-link a:hover {
      color: #2ecc71;
      text-shadow: 0 0 5px #39FF14;
    }

    .social-login {
      margin: 30px 0;
      text-align: center;
    }

    .social-login p {
      color: #888;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .social-buttons {
      display: flex;
      gap: 10px;
      justify-content: center;
    }

    .social-btn {
      flex: 1;
      padding: 12px;
      border: 1px solid rgba(57, 255, 20, 0.2);
      border-radius: 8px;
      background: rgba(42, 42, 42, 0.5);
      color: #fff;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .social-btn i {
      margin-right: 6px;
    }

    .social-btn:hover {
      background: rgba(57, 255, 20, 0.1);
      border-color: #39FF14;
      transform: translateY(-1px);
    }

    /* Success Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.8);
      backdrop-filter: blur(5px);
    }

    .success-modal .modal-content {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
      margin: 15% auto;
      padding: 40px;
      border-radius: 20px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      border: 2px solid #39FF14;
      box-shadow: 0 0 50px rgba(57, 255, 20, 0.3);
      animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .success-icon {
      font-size: 60px;
      color: #39FF14;
      margin-bottom: 20px;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }

    .success-title {
      color: #39FF14;
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .success-message {
      color: #ddd;
      margin-bottom: 30px;
      line-height: 1.5;
    }

    .success-btn {
      background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%);
      color: #000;
      padding: 12px 30px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .success-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(57, 255, 20, 0.3);
    }

    /* Loading Animation */
    .loading {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .spinner {
      width: 20px;
      height: 20px;
      border: 2px solid rgba(0,0,0,0.3);
      border-top: 2px solid #000;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .navbar {
        padding: 15px 20px;
      }
      
      .navbar-brand a {
        font-size: 24px;
      }

      .login-container {
        margin: 20px;
        padding: 30px;
      }

      .login-header h2 {
        font-size: 24px;
      }

      .social-buttons {
        flex-direction: column;
      }

      .form-options {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }
    }

    /* Security Indicator */
    .security-info {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 20px;
      font-size: 12px;
      color: #888;
    }

    .security-info i {
      margin-right: 5px;
      color: #39FF14;
    }

    /* Forgot Password Modal */
    .forgot-password-modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.8);
      backdrop-filter: blur(5px);
    }

    .forgot-password-modal .modal-content {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
      margin: 15% auto;
      padding: 40px;
      border-radius: 20px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      border: 2px solid #39FF14;
      box-shadow: 0 0 50px rgba(57, 255, 20, 0.3);
      animation: modalSlideIn 0.3s ease-out;
    }

    .forgot-password-modal .modal-content h2 {
      color: #39FF14;
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 15px;
      text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
    }

    .forgot-password-modal .modal-content p {
      color: #aaa;
      font-size: 16px;
      margin-bottom: 25px;
    }

    .forgot-password-modal .input-group {
      margin-bottom: 20px;
    }

    .forgot-password-modal .input-group i {
      left: 15px;
    }

    .forgot-password-modal .input-group input {
      padding: 16px 16px 16px 50px;
      border: 2px solid rgba(57, 255, 20, 0.2);
      border-radius: 12px;
      background: rgba(42, 42, 42, 0.8);
      color: #fff;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .forgot-password-modal .input-group input:focus {
      outline: none;
      border-color: #39FF14;
      box-shadow: 0 0 20px rgba(57, 255, 20, 0.3);
      background: rgba(42, 42, 42, 1);
    }

    .forgot-password-modal .input-group input::placeholder {
      color: #888;
    }

    .forgot-password-modal .password-toggle {
      right: 15px;
    }

    .forgot-password-modal .form-options {
      margin-top: 15px;
    }

    .forgot-password-modal .remember-me {
      color: #ddd;
      font-size: 14px;
    }

    .forgot-password-modal .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin-right: 8px;
      accent-color: #39FF14;
      cursor: pointer;
    }

    .forgot-password-modal .forgot-password {
      color: #39FF14;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .forgot-password-modal .forgot-password:hover {
      color: #2ecc71;
      text-shadow: 0 0 5px #39FF14;
    }

    .forgot-password-modal .login-btn {
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

    .forgot-password-modal .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(57, 255, 20, 0.4);
    }

    .forgot-password-modal .login-btn:active {
      transform: translateY(0);
    }

    .forgot-password-modal .login-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .forgot-password-modal .login-btn:hover::before {
      left: 100%;
    }

    .forgot-password-modal .login-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .forgot-password-modal .error {
      background: linear-gradient(135deg, #ff4444 0%, #cc1f1f 100%);
      padding: 15px 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      text-align: center;
      font-weight: 500;
      box-shadow: 0 4px 15px rgba(255, 68, 68, 0.3);
      animation: shake 0.5s ease-in-out;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .forgot-password-modal .error i {
      margin-right: 8px;
      font-size: 16px;
    }

    .forgot-password-modal .register-link {
      text-align: center;
      margin-top: 30px;
      color: #aaa;
      padding-top: 20px;
      border-top: 1px solid rgba(57, 255, 20, 0.1);
    }

    .forgot-password-modal .register-link a {
      color: #39FF14;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .forgot-password-modal .register-link a:hover {
      color: #2ecc71;
      text-shadow: 0 0 5px #39FF14;
    }
  </style>
</head>

<body>
  <!-- Animated Background -->
  <div class="bg-animation">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>

  <!-- Floating Elements -->
  <div class="floating-element"><i class="fas fa-brain"></i></div>
  <div class="floating-element"><i class="fas fa-moon"></i></div>
  <div class="floating-element"><i class="fas fa-star"></i></div>
  <div class="floating-element"><i class="fas fa-cloud"></i></div>

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
    <div class="login-container">
      <div class="login-header">
        <h2>Return to Your Dream World</h2>
        <p>Log in to your account</p>
      </div>

      <?php if (!empty($error) || !empty($rate_limit_error)): ?>
        <div class="error">
          <i class="fas fa-exclamation-triangle"></i>
          <?= htmlspecialchars($error ?: $rate_limit_error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="" id="loginForm">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        
        <div class="input-group">
          <i class="fas fa-user"></i>
          <input type="text" name="identifier" placeholder="Username, Email or Phone" required 
                 value="<?= isset($_POST['identifier']) ? htmlspecialchars($_POST['identifier']) : '' ?>"
                 maxlength="100" pattern="[a-zA-Z0-9@._+\-\s]+" title="Geçerli karakterler: harfler, rakamlar, @, ., _, +, -">
        </div>

        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" id="password" placeholder="password" required
                 minlength="6" maxlength="128">
          <span class="password-toggle" onclick="togglePassword()">
            <i class="fas fa-eye" id="toggleIcon"></i>
          </span>
        </div>

        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" name="remember_me" id="remember_me">
            Remember me
          </label>
          <a href="#" class="forgot-password" onclick="showForgotPassword()">I forgot my password</a>
        </div>

        <button type="submit" class="login-btn" id="loginBtn">
          <span>Giriş Yap</span>
          <div class="loading">
            <div class="spinner"></div>
          </div>
        </button>
      </form>

      

      <div class="register-link">
       Don't have an account? <a href="register.php">Sign Up</a>
      </div>

      <div class="security-info">
        <i class="fas fa-shield-alt"></i>
You are protected with a secure connection      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div id="successModal" class="modal success-modal">
    <div class="modal-content">
      <div class="success-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="success-title">Login Successful!</div>
      <div class="success-message">
Welcome! You are being directed to...      </div>
      <button class="success-btn" onclick="window.location.href='dream.php'">Continue</button>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <div id="forgotPasswordModal" class="forgot-password-modal">
    <div class="modal-content">
      <h2>Password Reset</h2>
      <p>Enter your email address to receive a password reset link.</p>
      <form method="POST" action="forgot_password.php" id="forgotPasswordForm">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" id="resetEmail" placeholder="Email Address" required>
        </div>
        <button type="submit" class="login-btn" id="resetSubmitBtn">
          <span>Reset Password</span>
          <div class="loading">
            <div class="spinner"></div>
          </div>
        </button>
      </form>
      <button class="login-btn" onclick="closeForgotPassword()">Cancel</button>
    </div>
  </div>

  <script>
    // Password Toggle Function
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      
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

    // Success Modal Function
    function showSuccessModal() {
      document.getElementById('successModal').style.display = 'block';
      document.body.style.overflow = 'hidden';
      
      // Auto redirect after 2 seconds
      setTimeout(() => {
        window.location.href = 'dream.php';
      }, 2000);
    }

    // Forgot Password Function
    function showForgotPassword() {
      document.getElementById('forgotPasswordModal').style.display = 'block';
      document.body.style.overflow = 'hidden';
    }

    function closeForgotPassword() {
      document.getElementById('forgotPasswordModal').style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    function submitForgotPassword() {
      const email = document.getElementById('resetEmail').value.trim();
      const submitBtn = document.getElementById('resetSubmitBtn');
      const btnText = submitBtn.querySelector('span');
      const loading = submitBtn.querySelector('.loading');
      
      if (!email) {
        alert('Lütfen email adresinizi girin.');
        return;
      }
      
      if (!isValidEmail(email)) {
        alert('Lütfen geçerli bir email adresi girin.');
        return;
      }
      
      // Show loading
      btnText.style.opacity = '0';
      loading.style.display = 'block';
      submitBtn.disabled = true;
      
      // Submit form
      const form = document.getElementById('forgotPasswordForm');
      form.submit();
    }

    function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }

    // Form Submission with Loading
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const btn = document.getElementById('loginBtn');
      const btnText = btn.querySelector('span');
      const loading = btn.querySelector('.loading');
      
      // Show loading animation
      btnText.style.opacity = '0';
      loading.style.display = 'block';
      btn.disabled = true;
      
      // Remove loading if there's an error (will be restored on page reload)
      setTimeout(() => {
        if (document.querySelector('.error')) {
          btnText.style.opacity = '1';
          loading.style.display = 'none';
          btn.disabled = false;
        }
      }, 1000);
    });

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

    // Container hover effects
    document.querySelector('.login-container').addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-5px)';
      this.style.boxShadow = '0 0 60px rgba(57, 255, 20, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.1)';
    });

    document.querySelector('.login-container').addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '0 0 50px rgba(57, 255, 20, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.1)';
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && e.target.type !== 'submit') {
        e.preventDefault();
        const form = e.target.closest('form');
        if (form) {
          const inputs = Array.from(form.querySelectorAll('input'));
          const index = inputs.indexOf(e.target);
          if (index > -1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
          } else if (index === inputs.length - 1) {
            form.querySelector('button[type="submit"]').click();
          }
        }
      }
      
      if (e.key === 'Escape') {
        // Close any open modals
        document.getElementById('successModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('forgotPasswordModal').style.display = 'none';
      }
    });

    // Auto-focus first input on page load
    document.addEventListener('DOMContentLoaded', function() {
      const firstInput = document.querySelector('input[name="identifier"]');
      if (firstInput && !firstInput.value) {
        setTimeout(() => firstInput.focus(), 500);
      }
    });

    // Shake effect for wrong password
    function shakeContainer() {
      const container = document.querySelector('.login-container');
      container.style.animation = 'none';
      container.offsetHeight; // Trigger reflow
      container.style.animation = 'shake 0.5s ease-in-out';
    }

    // Check if there's an error and shake
    if (document.querySelector('.error')) {
      setTimeout(shakeContainer, 100);
      
      // Auto-hide error message after 5 seconds
      setTimeout(() => {
        const errorElement = document.querySelector('.error');
        if (errorElement) {
          errorElement.style.transition = 'opacity 0.5s ease';
          errorElement.style.opacity = '0';
          setTimeout(() => {
            errorElement.remove();
          }, 500);
        }
      }, 5000);
    }

    // Remember me persistence
    document.addEventListener('DOMContentLoaded', function() {
      const rememberCheckbox = document.getElementById('remember_me');
      const identifierInput = document.querySelector('input[name="identifier"]');
      
      // Load remembered identifier if exists
      if (localStorage.getItem('remembered_identifier')) {
        identifierInput.value = localStorage.getItem('remembered_identifier');
        rememberCheckbox.checked = true;
      }
      
      // Save identifier when remember me is checked
      rememberCheckbox.addEventListener('change', function() {
        if (this.checked && identifierInput.value) {
          localStorage.setItem('remembered_identifier', identifierInput.value);
        } else {
          localStorage.removeItem('remembered_identifier');
        }
      });
      
      // Update stored identifier when input changes
      identifierInput.addEventListener('input', function() {
        if (rememberCheckbox.checked) {
          localStorage.setItem('remembered_identifier', this.value);
        }
      });
    });

    // Caps Lock detection
    document.getElementById('password').addEventListener('keyup', function(e) {
      const capsLockOn = e.getModifierState && e.getModifierState('CapsLock');
      const warningDiv = document.getElementById('capsLockWarning');
      
      if (capsLockOn) {
        if (!warningDiv) {
          const warning = document.createElement('div');
          warning.id = 'capsLockWarning';
          warning.style.cssText = `
            position: absolute;
            top: 100%;
            left: 50px;
            background: rgba(255, 193, 7, 0.9);
            color: #000;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 5px;
            z-index: 10;
          `;
          warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Caps Lock açık';
          this.parentElement.appendChild(warning);
        }
      } else if (warningDiv) {
        warningDiv.remove();
      }
    });

    // Enhanced security visualization
    function showSecurityIndicator() {
      const securityInfo = document.querySelector('.security-info');
      securityInfo.style.color = '#39FF14';
      securityInfo.innerHTML = '<i class="fas fa-shield-alt"></i> Güvenli bağlantı aktif';
      
      setTimeout(() => {
        securityInfo.style.color = '#888';
        securityInfo.innerHTML = '<i class="fas fa-shield-alt"></i> Güvenli bağlantı ile korunuyorsunuz';
      }, 2000);
    }

    // Show security indicator on focus
    document.querySelector('input[name="password"]').addEventListener('focus', showSecurityIndicator);

    // Form validation with better UX
    function validateForm() {
      const identifier = document.querySelector('input[name="identifier"]').value.trim();
      const password = document.querySelector('input[name="password"]').value;
      const submitBtn = document.getElementById('loginBtn');
      
      if (identifier.length >= 3 && password.length >= 1) {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
      } else {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.6';
      }
    }

    // Real-time form validation
    document.querySelectorAll('input').forEach(input => {
      input.addEventListener('input', validateForm);
    });

    // Initial validation check
    validateForm();

    // Smooth transitions for all interactive elements
    document.querySelectorAll('a, button, input, .social-btn').forEach(element => {
      element.style.transition = 'all 0.3s ease';
    });

    // Add ripple effect to buttons
    function createRipple(event) {
      const button = event.currentTarget;
      const circle = document.createElement('span');
      const diameter = Math.max(button.clientWidth, button.clientHeight);
      const radius = diameter / 2;

      circle.style.width = circle.style.height = `${diameter}px`;
      circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
      circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
      circle.classList.add('ripple');

      const ripple = button.getElementsByClassName('ripple')[0];
      if (ripple) {
        ripple.remove();
      }

      button.appendChild(circle);
    }

    // Add ripple effect styles
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = `
      .ripple {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
      }
      
      @keyframes ripple-animation {
        to {
          transform: scale(4);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(rippleStyle);

    // Apply ripple to buttons
    document.querySelectorAll('.login-btn, .success-btn, .social-btn').forEach(button => {
      button.addEventListener('click', createRipple);
      button.style.position = 'relative';
      button.style.overflow = 'hidden';
    });

    // Easter egg - Konami code
    let konamiCode = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65];
    let konamiIndex = 0;

    document.addEventListener('keydown', function(e) {
      if (e.keyCode === konamiCode[konamiIndex]) {
        konamiIndex++;
        if (konamiIndex === konamiCode.length) {
          // Activate special mode
          document.body.style.filter = 'hue-rotate(180deg)';
          setTimeout(() => {
            document.body.style.filter = 'none';
          }, 3000);
          konamiIndex = 0;
        }
      } else {
        konamiIndex = 0;
      }
    });

    // Performance optimization - debounce resize events
    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }

    const handleResize = debounce(() => {
      // Responsive adjustments if needed
      const container = document.querySelector('.login-container');
      if (window.innerWidth < 768) {
        container.style.margin = '20px';
        container.style.padding = '30px';
      } else {
        container.style.margin = 'auto';
        container.style.padding = '50px';
      }
    }, 250);

    window.addEventListener('resize', handleResize);

    // Console log for debugging (remove in production)
    console.log('DreamLock Login Page Loaded Successfully');
    console.log('Version: 2.0 Enhanced Edition');
    
    // Check for WebGL support for future 3D effects
    function checkWebGLSupport() {
      try {
        const canvas = document.createElement('canvas');
        return !!(canvas.getContext('webgl') || canvas.getContext('experimental-webgl'));
      } catch (e) {
        return false;
      }
    }
    
    if (checkWebGLSupport()) {
      console.log('WebGL destekleniyor - Gelecekteki 3D efektler için hazır');
    }

    // Accessibility improvements
    document.addEventListener('DOMContentLoaded', function() {
      // Add ARIA labels
      document.querySelector('input[name="identifier"]').setAttribute('aria-label', 'Kullanıcı adı, email veya telefon');
      document.querySelector('input[name="password"]').setAttribute('aria-label', 'Şifre');
      document.getElementById('remember_me').setAttribute('aria-label', 'Beni hatırla seçeneği');
      
      // Add role attributes
      document.querySelector('.error')?.setAttribute('role', 'alert');
      document.querySelector('.success-modal')?.setAttribute('role', 'dialog');
      document.getElementById('forgotPasswordModal')?.setAttribute('role', 'dialog');
      
      // Focus management for modal
      const successModal = document.getElementById('successModal');
      const forgotPasswordModal = document.getElementById('forgotPasswordModal');
      const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
            if (successModal.style.display === 'block') {
              successModal.querySelector('.success-btn')?.focus();
            }
            if (forgotPasswordModal.style.display === 'block') {
              forgotPasswordModal.querySelector('.login-btn')?.focus(); // Focus on cancel button
            }
          }
        });
      });
      
      observer.observe(successModal, { attributes: true });
      observer.observe(forgotPasswordModal, { attributes: true });
    });
  </script>

</body>
</html>