<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

require 'config.php';

$lang = loadLanguage();

$t = [
  'en' => [
    'success_title' => 'Welcome to Premium!',
    'success_message' => 'Your premium subscription has been activated successfully.',
    'go_to_dreams' => 'Go to Dreams',
    'home' => 'Home'
  ],
  'tr' => [
    'success_title' => 'Premium\'a Hoş Geldiniz!',
    'success_message' => 'Premium aboneliğiniz başarıyla aktifleştirildi.',
    'go_to_dreams' => 'Rüyalara Git',
    'home' => 'Ana Sayfa'
  ]
][$lang] ?? [
  'success_title' => 'Welcome to Premium!',
  'success_message' => 'Your premium subscription has been activated successfully.',
  'go_to_dreams' => 'Go to Dreams',
  'home' => 'Home'
];

$checkout_id = $_GET['checkout'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="assets/logo.png" type="image/x-icon">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Premium Success - DreamLock</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-green: #39FF14;
      --premium-gold: #FFD700;
      --dark-bg: #0a0a0a;
      --text-light: #ffffff;
    }
    
    body {
      background: linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #0f0f0f 100%);
      color: var(--text-light);
      padding-top: 100px;
      min-height: 100vh;
    }
    
    .success-card {
      background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
      border: 2px solid var(--premium-gold);
      border-radius: 20px;
      padding: 60px 40px;
      text-align: center;
      margin: 50px auto;
      max-width: 600px;
    }
    
    .success-icon {
      font-size: 80px;
      margin-bottom: 30px;
    }
    
    .success-title {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 20px;
      color: var(--premium-gold);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
      color: var(--dark-bg);
      border: none;
      padding: 15px 30px;
      border-radius: 50px;
      font-weight: 700;
      margin: 10px;
    }
  </style>
</head>

<body>
<div class="container">
  <div class="success-card">
    <div class="success-icon">👑</div>
    <h1 class="success-title"><?php echo $t['success_title']; ?></h1>
    <p><?php echo $t['success_message']; ?></p>
    
    <?php if ($checkout_id): ?>
    <p><strong>Checkout ID:</strong> <?php echo htmlspecialchars($checkout_id); ?></p>
    <?php endif; ?>
    
    <div style="margin-top: 40px;">
      <a href="dream.php" class="btn btn-primary"><?php echo $t['go_to_dreams']; ?></a>
      <a href="index.php" class="btn btn-secondary"><?php echo $t['home']; ?></a>
    </div>
  </div>
</div>

<script>
setTimeout(function() {
  window.location.href = 'dream.php';
}, 5000);
</script>

</body>
</html>

