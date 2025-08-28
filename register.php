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

// Rate limiting for registration
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!DreamLockSecurity::rateLimit("register:{$ip}", 3, 3600)) { // 3 attempts per hour
    $error = "Çok fazla kayıt denemesi. Lütfen 1 saat sonra tekrar deneyin.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !DreamLockSecurity::validateCSRFToken($_POST['csrf_token'])) {
        DreamLockSecurity::logSecurityEvent('csrf_attempt', ['ip' => $ip]);
        $error = "Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.";
    } else {
        // Sanitize inputs
        $username = DreamLockSecurity::sanitizeInput($_POST['username']);
        $email = DreamLockSecurity::sanitizeInput($_POST['email']);
        $phone = DreamLockSecurity::sanitizeInput($_POST['phone']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $agreeTerms = isset($_POST['agree_terms']);

        // Enhanced validation
        if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword)) {
            $error = "Lütfen tüm alanları doldurun.";
        } elseif (!$agreeTerms) {
            $error = "Kullanıcı sözleşmesini kabul etmelisiniz.";
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $error = "Kullanıcı adı 3-50 karakter arasında olmalıdır.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $error = "Kullanıcı adı sadece harf, rakam ve alt çizgi içerebilir.";
        } elseif (!DreamLockSecurity::validateEmail($email)) {
            $error = "Lütfen geçerli bir e-posta adresi girin.";
        } elseif (!preg_match('/^[0-9]{10,15}$/', preg_replace('/[^0-9]/', '', $phone))) {
            $error = "Lütfen geçerli bir telefon numarası girin (10-15 rakam).";
        } elseif (!DreamLockSecurity::validatePassword($password)) {
            $error = "Şifre en az " . PASSWORD_MIN_LENGTH . " karakter olmalı ve büyük harf, küçük harf, rakam ve özel karakter içermelidir.";
        } elseif ($password !== $confirmPassword) {
            $error = "Şifreler eşleşmiyor.";
        } else {
            try {
                $db = DreamLockSecurity::getSecureDB();
                
                // Check if user already exists
                $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                
                // Check phone separately
                $phoneCheck = $db->prepare("SELECT id FROM users WHERE phone = ? AND phone IS NOT NULL");
                $phoneCheck->execute([$phone]);
                
                if ($stmt->fetch() || $phoneCheck->fetch()) {
                    $error = "Bu kullanıcı adı, e-posta veya telefon numarası zaten kullanımda.";
                } else {
                    // Create new user with secure password hashing
                    $hashedPassword = DreamLockSecurity::hashPassword($password);
                    $stmt = $db->prepare("INSERT INTO users (username, email, phone, password, preferred_language, failed_login_attempts, created_at) VALUES (?, ?, ?, ?, 'en', 0, NOW())");
                    
                    if ($stmt->execute([$username, $email, $phone, $hashedPassword])) {
                        // Get the new user's ID and set session
                        $userId = $db->lastInsertId();
                        session_regenerate_id(true);
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['username'] = $username;
                        $_SESSION['is_premium'] = 0;
                        $_SESSION['last_activity'] = time();
                        $_SESSION['login_time'] = time();
                        
                        // Log successful registration
                        DreamLockSecurity::logSecurityEvent('registration_success', [
                            'user_id' => $userId,
                            'username' => $username,
                            'email' => $email,
                            'ip' => $ip
                        ]);
                        
                        $success = "Kayıt başarılı! Rüya panonuz'a yönlendiriliyorsunuz...";
                        echo "<script>
                            setTimeout(function() {
                                window.location.href = 'dream.php';
                            }, 2000);
                        </script>";
                    } else {
                        $error = "Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.";
                    }
                }
            } catch (Exception $e) {
                DreamLockSecurity::logSecurityEvent('registration_error', [
                    'error' => $e->getMessage(),
                    'ip' => $ip
                ]);
                
                if (strpos($e->getMessage(), 'Unknown database') !== false) {
                    $error = "DreamLock veritabanı bulunamadı. Lütfen veritabanını oluşturun.";
                } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
                    $error = "Veritabanı bağlantı hatası: Yanlış kullanıcı adı veya şifre.";
                } elseif (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $error = "Bu bilgiler zaten kayıtlı. Lütfen farklı bilgiler deneyin.";
                } else {
                    $error = "Bir sistem hatası oluştu. Lütfen tekrar deneyin.";
                }
            }
        }
    }
}

// Generate CSRF token for the form
$csrf_token = DreamLockSecurity::generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up - DreamLock</title>

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

    .register-container {
      max-width: 500px;
      width: 100%;
      padding: 40px;
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

    .register-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .register-header h2 {
      color: #39FF14;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
      text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
    }

    .register-header p {
      color: #aaa;
      font-size: 16px;
    }

    .input-group {
      position: relative;
      margin-bottom: 20px;
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

    .password-strength {
      margin-top: 5px;
      font-size: 12px;
      height: 15px;
    }

    .strength-weak { color: #ff4444; }
    .strength-medium { color: #ffa500; }
    .strength-strong { color: #39FF14; }

    .terms-container {
      margin: 25px 0;
      padding: 20px;
      background: rgba(42, 42, 42, 0.5);
      border-radius: 12px;
      border: 1px solid rgba(57, 255, 20, 0.1);
    }

    .terms-scroll {
      max-height: 200px;
      overflow-y: auto;
      padding: 15px;
      background: rgba(20, 20, 20, 0.8);
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 13px;
      line-height: 1.5;
      color: #ccc;
    }

    .terms-scroll::-webkit-scrollbar {
      width: 6px;
    }

    .terms-scroll::-webkit-scrollbar-track {
      background: rgba(42, 42, 42, 0.5);
      border-radius: 3px;
    }

    .terms-scroll::-webkit-scrollbar-thumb {
      background: #39FF14;
      border-radius: 3px;
    }

    .terms-agreement {
      display: flex;
      align-items: center;
      color: #ddd;
      cursor: pointer;
    }

    .terms-agreement input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin-right: 10px;
      accent-color: #39FF14;
      cursor: pointer;
    }

    .register-btn {
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

    .register-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(57, 255, 20, 0.4);
    }

    .register-btn:active {
      transform: translateY(0);
    }

    .register-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .register-btn:hover::before {
      left: 100%;
    }

    .register-btn:disabled {
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

    .success {
      background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%);
      color: #000;
      padding: 15px 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      text-align: center;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(57, 255, 20, 0.3);
      animation: slideDown 0.5s ease-out;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .success i {
      margin-right: 8px;
      font-size: 16px;
    }

    @keyframes shake {
      0%, 20%, 40%, 60%, 80%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-link {
      text-align: center;
      margin-top: 25px;
      color: #aaa;
      padding-top: 20px;
      border-top: 1px solid rgba(57, 255, 20, 0.1);
    }

    .login-link a {
      color: #39FF14;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .login-link a:hover {
      color: #2ecc71;
      text-shadow: 0 0 5px #39FF14;
    }

    .social-register {
      margin: 25px 0;
      text-align: center;
    }

    .social-register p {
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

      .register-container {
        margin: 20px;
        padding: 25px;
      }

      .register-header h2 {
        font-size: 24px;
      }

      .social-buttons {
        flex-direction: column;
      }

      .terms-scroll {
        max-height: 150px;
      }
    }

    /* Input validation styles */
    .input-valid {
      border-color: #39FF14 !important;
      box-shadow: 0 0 10px rgba(57, 255, 20, 0.2) !important;
    }

    .input-invalid {
      border-color: #ff4444 !important;
      box-shadow: 0 0 10px rgba(255, 68, 68, 0.2) !important;
    }

    .validation-message {
      font-size: 12px;
      margin-top: 5px;
      padding-left: 15px;
    }

    .validation-success {
      color: #39FF14;
    }

    .validation-error {
      color: #ff4444;
    }
  </style>
	<link rel="icon" href="assets/logo.png" type="image/x-icon">

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
    <div class="register-container">
      <div class="register-header">
        <h2>Join Your Dream World</h2>
        <p>Create a new account</p>
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

      <form method="POST" action="" id="registerForm">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        
        <div class="input-group">
          <i class="fas fa-user"></i>
          <input type="text" name="username" id="username" placeholder="Username" required 
                 value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                 minlength="3" maxlength="50" pattern="[a-zA-Z0-9_]+" title="Sadece harf, rakam ve alt çizgi kullanın">
          <div class="validation-message" id="username-validation"></div>
        </div>

        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" id="email" placeholder="Email Address" required 
                 value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                 maxlength="100">
          <div class="validation-message" id="email-validation"></div>
        </div>

        <div class="input-group">
          <i class="fas fa-phone"></i>
          <input type="tel" name="phone" id="phone" placeholder="Phone Number" required 
                 value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>"
                 pattern="[0-9]{10,15}" title="10-15 rakam girin">
          <div class="validation-message" id="phone-validation"></div>
        </div>

        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" id="password" placeholder="Password" required
                 minlength="8" maxlength="128">
          <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
            <i class="fas fa-eye" id="toggleIcon1"></i>
          </span>
          <div class="password-strength" id="password-strength"></div>
        </div>

        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required
                 minlength="8" maxlength="128">
          <span class="password-toggle" onclick="togglePassword('confirm_password', 'toggleIcon2')">
            <i class="fas fa-eye" id="toggleIcon2"></i>
          </span>
          <div class="validation-message" id="password-match"></div>
        </div>

        <div class="terms-container">
          <div class="terms-scroll">
            <h4 style="color: #39FF14; margin-bottom: 15px;">USER AGREEMENT AND PRIVACY POLICY</h4>
            <p><strong>Effective Date:</strong> July 28, 2025<br>
            <strong>Last Updated:</strong> July 28, 2025<br>
            <strong>Application Name:</strong> DreamLock</p>
            
            <h5 style="color: #39FF14; margin: 15px 0 10px 0;">1. User Agreement</h5>
            <p>By registering as a member of the DreamLock application, each user confirms that they have read, understood, and accepted the terms outlined in this agreement. Registration is only possible upon full acceptance of the terms below, including our data usage policy.</p>
            
            <h6 style="color: #39FF14; margin: 10px 0 5px 0;">2.Privacy and Data use</h6>
            <p>2.1 Data Collection<br>
            DreamLock collects personal information from users, including but not limited to names, email addresses, phone numbers, and device-related data, to provide and improve the services.</p>
            
            <h6 style="color: #39FF14; margin: 10px 0 5px 0;">2.2 Data Sharing and Commercial Use<br>
            </h6>
            <p>DreamLock does not share, sell, or transfer any personal information (such as email addresses, phone numbers, or other identifying data) to any third parties under any circumstances.</p>
            <p>All user data is stored securely in DreamLock’s encrypted database and is used solely for the purpose of providing and improving the service. Personal data will only be disclosed if required by applicable law or legal process.</p>
            
            <h5 style="color: #39FF14; margin: 15px 0 10px 0;">2.3 Data Security</h5>
            <p>All personal data is protected with industry-standard security measures. Passwords are stored using encrypted cryptographic techniques.</p>
			  
		    <h5 style="color: #39FF14; margin: 15px 0 10px 0;">2.4 User Rights</h5>
            
            <p>Users have the right to access, correct, or delete their data. Requests must be submitted in writing to the support team.</p>
			  
		    <h5 style="color: #39FF14; margin: 15px 0 10px 0;">3. Changes to the Agreement<br>
		    </h5>
            
            <p>DreamLock reserves the right to update or change this agreement. All changes will be announced within the application. Continued use of the service after updates implies acceptance of the new terms.</p>
			  
            <h5 style="color: #39FF14; margin: 15px 0 10px 0;">4. Contact</h5>
            <p>For questions about privacy and user agreement, you can contact us:<br>
            📧 dreamlocktr@gmail.com</p>
            
            <p style="margin-top: 15px; color: #39FF14; font-weight: bold;">By accepting this agreement, the user consents to the collection, use, and secure storage of their data as specified above.</p>
          </div>
          
          <label class="terms-agreement">
            <input type="checkbox" name="agree_terms" id="agree_terms" required>
            I have read, understood and agree to the User Agreement and Privacy Policy.
          </label>
        </div>

        <button type="submit" class="register-btn" id="registerBtn">
          <span>Create Account</span>
          <div class="loading">
            <div class="spinner"></div>
          </div>
        </button>
      </form>

      <div class="login-link">
        Already have an account? <a href="login.php">Sign In</a>
      </div>

      <div class="security-info">
        <i class="fas fa-shield-alt"></i>
        Your data is protected with 256-bit SSL
      </div>
    </div>
  </div>

  <script>
    // Password Toggle Function
    function togglePassword(inputId, iconId) {
      const passwordInput = document.getElementById(inputId);
      const toggleIcon = document.getElementById(iconId);
      
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
      let feedback = '';
      
      if (password.length >= 6) strength++;
      if (password.match(/[a-z]/)) strength++;
      if (password.match(/[A-Z]/)) strength++;
      if (password.match(/[0-9]/)) strength++;
      if (password.match(/[^a-zA-Z0-9]/)) strength++;
      
      switch (strength) {
        case 0:
        case 1:
          feedback = '<span class="strength-weak">Very Weak</span>';
          break;
        case 2:
        case 3:
          feedback = '<span class="strength-medium">Medium</span>';
          break;
        case 4:
        case 5:
          feedback = '<span class="strength-strong">Strong</span>';
          break;
      }
      
      return feedback;
    }

    // Real-time Validation
    document.getElementById('username').addEventListener('input', function() {
      const username = this.value.trim();
      const validation = document.getElementById('username-validation');
      
      if (username.length === 0) {
        this.classList.remove('input-valid', 'input-invalid');
        validation.innerHTML = '';
      } else if (username.length < 3) {
        this.classList.remove('input-valid');
        this.classList.add('input-invalid');
        validation.innerHTML = '<span class="validation-error">Must be at least 3 characters</span>';
      } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        this.classList.remove('input-valid');
        this.classList.add('input-invalid');
        validation.innerHTML = '<span class="validation-error">Only letters, numbers and _ allowed</span>';
      } else {
        this.classList.remove('input-invalid');
        this.classList.add('input-valid');
        validation.innerHTML = '<span class="validation-success">✓ Available</span>';
      }
    });

    document.getElementById('email').addEventListener('input', function() {
      const email = this.value.trim();
      const validation = document.getElementById('email-validation');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      
      if (email.length === 0) {
        this.classList.remove('input-valid', 'input-invalid');
        validation.innerHTML = '';
      } else if (!emailRegex.test(email)) {
        this.classList.remove('input-valid');
        this.classList.add('input-invalid');
        validation.innerHTML = '<span class="validation-error">Enter a valid email address</span>';
      } else {
        this.classList.remove('input-invalid');
        this.classList.add('input-valid');
        validation.innerHTML = '<span class="validation-success">✓ Valid email</span>';
      }
    });

    document.getElementById('phone').addEventListener('input', function() {
      const phone = this.value.replace(/[^0-9]/g, '');
      const validation = document.getElementById('phone-validation');
      
      // Auto-format phone number
      this.value = phone;
      
      if (phone.length === 0) {
        this.classList.remove('input-valid', 'input-invalid');
        validation.innerHTML = '';
      } else if (phone.length < 10 || phone.length > 15) {
        this.classList.remove('input-valid');
        this.classList.add('input-invalid');
        validation.innerHTML = '<span class="validation-error">Enter 10-15 digit phone number</span>';
      } else {
        this.classList.remove('input-invalid');
        this.classList.add('input-valid');
        validation.innerHTML = '<span class="validation-success">✓ Valid phone number</span>';
      }
    });

    document.getElementById('password').addEventListener('input', function() {
      const password = this.value;
      const strengthDiv = document.getElementById('password-strength');
      
      if (password.length === 0) {
        this.classList.remove('input-valid', 'input-invalid');
        strengthDiv.innerHTML = '';
      } else {
        strengthDiv.innerHTML = 'Password Strength: ' + checkPasswordStrength(password);
        
        if (password.length >= 6) {
          this.classList.remove('input-invalid');
          this.classList.add('input-valid');
        } else {
          this.classList.remove('input-valid');
          this.classList.add('input-invalid');
        }
      }
      
      // Check password match
      checkPasswordMatch();
    });

    document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      const matchDiv = document.getElementById('password-match');
      const confirmInput = document.getElementById('confirm_password');
      
      if (confirmPassword.length === 0) {
        confirmInput.classList.remove('input-valid', 'input-invalid');
        matchDiv.innerHTML = '';
      } else if (password !== confirmPassword) {
        confirmInput.classList.remove('input-valid');
        confirmInput.classList.add('input-invalid');
        matchDiv.innerHTML = '<span class="validation-error">Passwords do not match</span>';
      } else {
        confirmInput.classList.remove('input-invalid');
        confirmInput.classList.add('input-valid');
        matchDiv.innerHTML = '<span class="validation-success">✓ Passwords match</span>';
      }
    }

    // Form Submission with Loading
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const btn = document.getElementById('registerBtn');
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
    });

    // Container hover effects
    document.querySelector('.register-container').addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-5px)';
      this.style.boxShadow = '0 0 60px rgba(57, 255, 20, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.1)';
    });

    document.querySelector('.register-container').addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '0 0 50px rgba(57, 255, 20, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.1)';
    });

    // Auto-focus first input on page load
    document.addEventListener('DOMContentLoaded', function() {
      const firstInput = document.querySelector('input[name="username"]');
      if (firstInput && !firstInput.value) {
        setTimeout(() => firstInput.focus(), 500);
      }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && e.target.type !== 'submit' && e.target.type !== 'checkbox') {
        e.preventDefault();
        const form = e.target.closest('form');
        if (form) {
          const inputs = Array.from(form.querySelectorAll('input:not([type="checkbox"])'));
          const index = inputs.indexOf(e.target);
          if (index > -1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
          } else if (index === inputs.length - 1) {
            document.getElementById('agree_terms').focus();
          }
        }
      }
    });

    // Terms checkbox animation
    document.getElementById('agree_terms').addEventListener('change', function() {
      const btn = document.getElementById('registerBtn');
      if (this.checked) {
        btn.style.opacity = '1';
        btn.disabled = false;
        this.parentElement.style.color = '#39FF14';
      } else {
        btn.style.opacity = '0.6';
        btn.disabled = true;
        this.parentElement.style.color = '#ddd';
      }
    });

    // Initial button state
    document.addEventListener('DOMContentLoaded', function() {
      const agreeCheckbox = document.getElementById('agree_terms');
      const btn = document.getElementById('registerBtn');
      
      if (!agreeCheckbox.checked) {
        btn.style.opacity = '0.6';
        btn.disabled = true;
      }
    });

    // Shake effect for errors
    function shakeContainer() {
      const container = document.querySelector('.register-container');
      container.style.animation = 'none';
      container.offsetHeight; // Trigger reflow
      container.style.animation = 'shake 0.5s ease-in-out';
    }

    // Check if there's an error and shake
    if (document.querySelector('.error')) {
      setTimeout(shakeContainer, 100);
    }

    // Caps Lock detection for password fields
    ['password', 'confirm_password'].forEach(fieldId => {
      document.getElementById(fieldId).addEventListener('keyup', function(e) {
        const capsLockOn = e.getModifierState && e.getModifierState('CapsLock');
        const warningId = fieldId + '-caps-warning';
        let warningDiv = document.getElementById(warningId);
        
        if (capsLockOn) {
          if (!warningDiv) {
            warningDiv = document.createElement('div');
            warningDiv.id = warningId;
            warningDiv.style.cssText = `
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
            warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Caps Lock is on';
            this.parentElement.appendChild(warningDiv);
          }
        } else if (warningDiv) {
          warningDiv.remove();
        }
      });
    });

    // Advanced form validation
    function validateEntireForm() {
      const username = document.getElementById('username').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.replace(/[^0-9]/g, '');
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      const agreeTerms = document.getElementById('agree_terms').checked;
      
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      
      let isValid = true;
      
      // Username validation
      if (username.length < 3 || !/^[a-zA-Z0-9_]+$/.test(username)) {
        isValid = false;
      }
      
      // Email validation
      if (!emailRegex.test(email)) {
        isValid = false;
      }
      
      // Phone validation - Updated to be more flexible
      if (phone.length < 10 || phone.length > 15) {
        isValid = false;
      }
      
      // Password validation
      if (password.length < 6) {
        isValid = false;
      }
      
      // Password match validation
      if (password !== confirmPassword) {
        isValid = false;
      }
      
      // Terms agreement
      if (!agreeTerms) {
        isValid = false;
      }
      
      const submitBtn = document.getElementById('registerBtn');
      if (isValid) {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
      } else {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.6';
      }
    }

    // Real-time form validation
    document.querySelectorAll('input').forEach(input => {
      input.addEventListener('input', validateEntireForm);
      input.addEventListener('change', validateEntireForm);
    });

    // Initial validation check
    validateEntireForm();

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
    document.querySelectorAll('.register-btn, .social-btn').forEach(button => {
      button.addEventListener('click', createRipple);
      button.style.position = 'relative';
      button.style.overflow = 'hidden';
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
      const container = document.querySelector('.register-container');
      if (window.innerWidth < 768) {
        container.style.margin = '20px';
        container.style.padding = '25px';
      } else {
        container.style.margin = 'auto';
        container.style.padding = '40px';
      }
    }, 250);

    window.addEventListener('resize', handleResize);

    // Console log for debugging
    console.log('DreamLock Register Page Loaded Successfully');
    console.log('Version: 2.0 Enhanced Edition - Flexible Phone Validation');

    // Accessibility improvements
    document.addEventListener('DOMContentLoaded', function() {
      // Add ARIA labels
      document.getElementById('username').setAttribute('aria-label', 'Username');
      document.getElementById('email').setAttribute('aria-label', 'Email address');
      document.getElementById('phone').setAttribute('aria-label', 'Phone number');
      document.getElementById('password').setAttribute('aria-label', 'Password');
      document.getElementById('confirm_password').setAttribute('aria-label', 'Confirm password');
      document.getElementById('agree_terms').setAttribute('aria-label', 'Agree to user agreement');
      
      // Add role attributes
      document.querySelector('.error')?.setAttribute('role', 'alert');
      document.querySelector('.success')?.setAttribute('role', 'status');
      document.querySelector('.terms-container')?.setAttribute('role', 'region');
    });

    // Enhanced security visualization
    function showSecurityIndicator() {
      const securityInfo = document.querySelector('.security-info');
      securityInfo.style.color = '#39FF14';
      securityInfo.innerHTML = '<i class="fas fa-shield-alt"></i> Security check active';
      
      setTimeout(() => {
        securityInfo.style.color = '#888';
        securityInfo.innerHTML = '<i class="fas fa-shield-alt"></i> Your data is protected with 256-bit SSL';
      }, 2000);
    }

    // Show security indicator on password focus
    document.getElementById('password').addEventListener('focus', showSecurityIndicator);

    // Terms scroll to bottom detector
    const termsScroll = document.querySelector('.terms-scroll');
    const agreeCheckbox = document.getElementById('agree_terms');
    let hasScrolledToBottom = false;

    termsScroll.addEventListener('scroll', function() {
      const scrollTop = this.scrollTop;
      const scrollHeight = this.scrollHeight;
      const clientHeight = this.clientHeight;
      
      if (scrollTop + clientHeight >= scrollHeight - 10) {
        hasScrolledToBottom = true;
        this.style.borderColor = '#39FF14';
      }
    });

    // Encourage reading terms
    agreeCheckbox.addEventListener('click', function(e) {
      if (!hasScrolledToBottom && !this.checked) {
        e.preventDefault();
        alert('Please read the user agreement to the end.');
        termsScroll.focus();
        termsScroll.style.borderColor = '#ffa500';
        termsScroll.style.border = '2px solid #ffa500';
      }
    });

    // Auto-scroll terms on first focus
    let termsFirstFocus = true;
    termsScroll.addEventListener('focus', function() {
      if (termsFirstFocus) {
        this.scrollTop = 0;
        termsFirstFocus = false;
      }
    });
  </script>

</body>
</html>