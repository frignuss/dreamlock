<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

require 'config.php';

// Multi-language support
$lang = loadLanguage();

$translations = [
  'en' => [
    'premium_title' => 'Upgrade to Premium',
    'unlock_dreams' => 'Unlock Unlimited Dreams',
    'free_limit' => 'Free users are limited to 4 dreams',
    'premium_features' => 'Premium Features',
    'unlimited_dreams' => 'Unlimited dream entries',
    'advanced_analysis' => 'Advanced AI analysis',
    'dream_history' => 'Complete dream history',
    'priority_support' => 'Priority support',
    'monthly_plan' => 'Monthly Plan',
    'yearly_plan' => 'Yearly Plan',
    'per_month' => 'per month',
    'per_year' => 'per year',
    'save_percent' => 'Save 20%',
    'upgrade_now' => 'Upgrade Now',
    'already_premium' => 'You are already a Premium member!',
    'premium_expires' => 'Your Premium membership expires on',
    'manage_subscription' => 'Manage Subscription',
    'home' => 'Home',
    'logout' => 'Log Out'
  ],
  'tr' => [
    'premium_title' => 'Premium\'a Yükselt',
    'unlock_dreams' => 'Sınırsız Rüya Kilidi Aç',
    'free_limit' => 'Ücretsiz kullanıcılar 4 rüya ile sınırlıdır',
    'premium_features' => 'Premium Özellikler',
    'unlimited_dreams' => 'Sınırsız rüya girişi',
    'advanced_analysis' => 'Gelişmiş AI analizi',
    'dream_history' => 'Tam rüya geçmişi',
    'priority_support' => 'Öncelikli destek',
    'monthly_plan' => 'Aylık Plan',
    'yearly_plan' => 'Yıllık Plan',
    'per_month' => 'aylık',
    'per_year' => 'yıllık',
    'save_percent' => '%20 Tasarruf',
    'upgrade_now' => 'Şimdi Yükselt',
    'already_premium' => 'Zaten Premium üyesiniz!',
    'premium_expires' => 'Premium üyeliğiniz şu tarihte sona eriyor',
    'manage_subscription' => 'Aboneliği Yönet',
    'home' => 'Ana Sayfa',
    'logout' => 'Çıkış Yap'
  ]
];

$t = $translations[$lang] ?? $translations['en'];

// Check if user is premium
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
$stmt = $db->prepare("SELECT is_premium, premium_expires_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$is_premium = $user['is_premium'] && (!$user['premium_expires_at'] || strtotime($user['premium_expires_at']) > time());
?>

<!DOCTYPE html>
<html lang="en">
<head>
		<link rel="icon" href="assets/logo.png" type="image/x-icon">

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DreamLock Premium</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Paddle Checkout -->
  <script src="https://cdn.paddle.com/paddle/paddle.js"></script>
  
  <style>
    /* Premium styling with dream.php compatibility */
    :root {
      --primary-green: #39FF14;
      --secondary-green: #2ecc71;
      --dark-bg: #0a0a0a;
      --card-bg: #1a1a1a;
      --text-light: #ffffff;
      --text-muted: #888888;
      --border-color: #2a2a2a;
      --premium-gold: #FFD700;
      --premium-gradient: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
      --success-bg: #0f2a0f;
      --success-text: #b6fcb6;
      --danger-bg: #2a0f0f;
      --danger-text: #ff6b6b;
    }
    
    * { 
      font-family: 'Inter', 'Manrope', sans-serif; 
      font-weight: 400;
    }
    
    body {
      margin: 0;
      padding-top: 100px;
      color: var(--text-light);
      background: linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #0f0f0f 100%);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    /* PREMIUM BACKGROUND SYSTEM - START */
    
    /* Advanced Premium Background */
    .premium-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -10;
      background: 
          radial-gradient(circle at 15% 25%, rgba(255, 215, 0, 0.08) 0%, transparent 40%),
          radial-gradient(circle at 85% 75%, rgba(255, 165, 0, 0.06) 0%, transparent 35%),
          radial-gradient(circle at 60% 15%, rgba(255, 215, 0, 0.04) 0%, transparent 50%),
          radial-gradient(circle at 30% 85%, rgba(255, 215, 0, 0.05) 0%, transparent 45%),
          linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #0f0f0f 100%);
      animation: premiumPulse 8s ease-in-out infinite alternate;
    }

    @keyframes premiumPulse {
      0% {
          filter: brightness(1) contrast(1);
      }
      100% {
          filter: brightness(1.1) contrast(1.05);
      }
    }

    /* Floating Premium Orbs */
    .premium-orb {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(1px);
      animation: floatPremium 12s infinite ease-in-out;
    }

    .premium-orb:nth-child(1) {
      width: 120px;
      height: 120px;
      background: radial-gradient(circle, rgba(255, 215, 0, 0.1) 0%, rgba(255, 215, 0, 0.02) 70%, transparent 100%);
      top: 10%;
      left: 15%;
      animation-delay: -2s;
      animation-duration: 15s;
    }

    .premium-orb:nth-child(2) {
      width: 80px;
      height: 80px;
      background: radial-gradient(circle, rgba(255, 165, 0, 0.08) 0%, rgba(255, 165, 0, 0.01) 70%, transparent 100%);
      top: 60%;
      right: 20%;
      animation-delay: -5s;
      animation-duration: 18s;
    }

    .premium-orb:nth-child(3) {
      width: 100px;
      height: 100px;
      background: radial-gradient(circle, rgba(255, 215, 0, 0.06) 0%, rgba(255, 215, 0, 0.01) 70%, transparent 100%);
      bottom: 20%;
      left: 25%;
      animation-delay: -8s;
      animation-duration: 20s;
    }

    .premium-orb:nth-child(4) {
      width: 60px;
      height: 60px;
      background: radial-gradient(circle, rgba(255, 215, 0, 0.12) 0%, rgba(255, 215, 0, 0.03) 70%, transparent 100%);
      top: 30%;
      right: 35%;
      animation-delay: -3s;
      animation-duration: 14s;
    }

    .premium-orb:nth-child(5) {
      width: 90px;
      height: 90px;
      background: radial-gradient(circle, rgba(255, 165, 0, 0.07) 0%, rgba(255, 165, 0, 0.02) 70%, transparent 100%);
      bottom: 40%;
      right: 15%;
      animation-delay: -6s;
      animation-duration: 16s;
    }

    @keyframes floatPremium {
      0%, 100% {
          transform: translateY(0px) translateX(0px) scale(1);
          opacity: 0.7;
      }
      25% {
          transform: translateY(-30px) translateX(20px) scale(1.1);
          opacity: 1;
      }
      50% {
          transform: translateY(-10px) translateX(-15px) scale(0.9);
          opacity: 0.8;
      }
      75% {
          transform: translateY(-40px) translateX(10px) scale(1.05);
          opacity: 0.9;
      }
    }

    /* Premium Particles */
    .premium-particle-system {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -8;
    }

    .premium-particle {
      position: absolute;
      background: #FFD700;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(0.5px);
      box-shadow: 0 0 10px rgba(255, 215, 0, 0.6);
    }

    /* Premium Waves */
    .premium-wave {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -9;
      background: 
          linear-gradient(90deg, transparent 0%, rgba(255, 215, 0, 0.02) 50%, transparent 100%);
      animation: premiumWaveMotion 25s linear infinite;
    }

    @keyframes premiumWaveMotion {
      0% {
          transform: translateX(-100%) skewX(-15deg);
      }
      100% {
          transform: translateX(100%) skewX(-15deg);
      }
    }

    /* Premium Grid */
    .premium-grid {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -7;
      background-image: 
          linear-gradient(rgba(255, 215, 0, 0.03) 1px, transparent 1px),
          linear-gradient(90deg, rgba(255, 215, 0, 0.03) 1px, transparent 1px);
      background-size: 100px 100px;
      animation: premiumGridPulse 10s ease-in-out infinite;
      opacity: 0.3;
    }

    @keyframes premiumGridPulse {
      0%, 100% {
          opacity: 0.2;
          transform: scale(1);
      }
      50% {
          opacity: 0.4;
          transform: scale(1.02);
      }
    }

    /* Premium Nebula */
    .premium-nebula {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -6;
      background: 
          radial-gradient(ellipse at 20% 30%, rgba(255, 215, 0, 0.05) 0%, transparent 60%),
          radial-gradient(ellipse at 80% 70%, rgba(255, 165, 0, 0.04) 0%, transparent 50%),
          radial-gradient(ellipse at 60% 20%, rgba(255, 215, 0, 0.03) 0%, transparent 70%);
      animation: premiumNebulaSwirl 30s linear infinite;
    }

    @keyframes premiumNebulaSwirl {
      0% {
          transform: rotate(0deg) scale(1);
      }
      100% {
          transform: rotate(360deg) scale(1.1);
      }
    }

    /* Premium Starfield */
    .premium-starfield {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -5;
    }

    .premium-star {
      position: absolute;
      background: #FFD700;
      border-radius: 50%;
      animation: premiumTwinkle 3s ease-in-out infinite;
    }

    @keyframes premiumTwinkle {
      0%, 100% {
          opacity: 0.3;
          transform: scale(1);
      }
      50% {
          opacity: 1;
          transform: scale(1.2);
      }
    }

    /* Premium Ripples */
    .premium-ripple {
      position: fixed;
      border: 2px solid rgba(255, 215, 0, 0.1);
      border-radius: 50%;
      pointer-events: none;
      z-index: -4;
      animation: premiumRippleExpand 6s linear infinite;
    }

    @keyframes premiumRippleExpand {
      0% {
          width: 0;
          height: 0;
          opacity: 1;
          border-width: 3px;
      }
      100% {
          width: 300px;
          height: 300px;
          opacity: 0;
          border-width: 0px;
      }
    }

    /* Premium Light Rays */
    .premium-light-ray {
      position: fixed;
      width: 2px;
      height: 100%;
      background: linear-gradient(to bottom, 
          transparent 0%, 
          rgba(255, 215, 0, 0.1) 30%, 
          rgba(255, 215, 0, 0.05) 50%, 
          rgba(255, 215, 0, 0.1) 70%, 
          transparent 100%);
      pointer-events: none;
      z-index: -3;
      animation: premiumRayMove 15s linear infinite;
      filter: blur(1px);
    }

    .premium-light-ray:nth-child(1) {
      left: 10%;
      animation-delay: -2s;
      animation-duration: 18s;
    }

    .premium-light-ray:nth-child(2) {
      left: 35%;
      animation-delay: -7s;
      animation-duration: 22s;
    }

    .premium-light-ray:nth-child(3) {
      right: 20%;
      animation-delay: -4s;
      animation-duration: 16s;
    }

    .premium-light-ray:nth-child(4) {
      right: 45%;
      animation-delay: -9s;
      animation-duration: 20s;
    }

    @keyframes premiumRayMove {
      0% {
          opacity: 0;
          transform: translateY(-100%) skewX(-10deg);
      }
      10% {
          opacity: 1;
      }
      90% {
          opacity: 1;
      }
      100% {
          opacity: 0;
          transform: translateY(100%) skewX(-10deg);
      }
    }
    
    /* PREMIUM BACKGROUND SYSTEM - END */
    
    .navbar {
      background: rgba(26, 26, 26, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255, 215, 0, 0.1);
      padding: 20px 0;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }
    
    .navbar-brand {
      font-size: 32px;
      font-weight: 800;
      color: var(--text-light);
      font-family: 'Manrope', sans-serif;
      text-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
      transition: all 0.3s ease;
    }
    
    .navbar-brand:hover {
      text-shadow: 0 0 30px rgba(255, 215, 0, 0.5);
      transform: scale(1.05);
    }
    
    .navbar-brand span:last-child {
      color: var(--premium-gold);
    }
    
    .nav-link {
      color: var(--text-light) !important;
      margin: 0 15px;
      font-size: 16px;
      font-weight: 600;
      padding: 10px 20px !important;
      border-radius: 25px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.2), transparent);
      transition: left 0.5s;
    }

    .nav-link:hover::before {
      left: 100%;
    }

    .nav-link:hover {
      background: rgba(255, 215, 0, 0.1) !important;
      color: var(--premium-gold) !important;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    }
    
    .container {
      max-width: 1000px;
      margin: auto;
      padding: 40px 20px;
    }
    
    .premium-header {
      text-align: center;
      margin-bottom: 50px;
      padding: 80px 40px;
      background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 165, 0, 0.05) 100%);
      border: 2px solid var(--premium-gold);
      border-radius: 25px;
      color: var(--text-light);
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(20px);
      box-shadow: 0 20px 60px rgba(255, 215, 0, 0.2);
      animation: headerGlow 3s ease-in-out infinite alternate;
    }

    @keyframes headerGlow {
      0% {
        box-shadow: 0 20px 60px rgba(255, 215, 0, 0.2);
        border-color: var(--premium-gold);
      }
      100% {
        box-shadow: 0 30px 80px rgba(255, 215, 0, 0.4), 0 0 40px rgba(255, 215, 0, 0.2);
        border-color: #FFA500;
      }
    }
    
    .premium-header::before {
      content: '👑';
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 50px;
      opacity: 0.4;
      animation: crownFloat 4s ease-in-out infinite;
    }

    @keyframes crownFloat {
      0%, 100% {
        transform: translateY(0px) rotate(0deg);
      }
      50% {
        transform: translateY(-10px) rotate(5deg);
      }
    }

    .premium-header::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent 30%, rgba(255, 215, 0, 0.1) 50%, transparent 70%);
      animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
      0% {
        transform: translateX(-100%);
      }
      100% {
        transform: translateX(100%);
      }
    }
    
    .premium-header h1 {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 20px;
      text-shadow: 0 0 30px rgba(255, 215, 0, 0.5);
      color: var(--premium-gold);
      position: relative;
      z-index: 2;
    }
    
    .premium-subtitle {
      font-size: 1.4rem;
      font-weight: 500;
      opacity: 0.9;
      position: relative;
      z-index: 2;
    }
    
    .pricing-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      margin: 50px 0;
    }
    
    .pricing-card {
      background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
      border: 2px solid var(--premium-gold);
      border-radius: 25px;
      padding: 50px 30px;
      text-align: center;
      position: relative;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      backdrop-filter: blur(20px);
      overflow: hidden;
    }

    .pricing-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
      transition: left 0.6s;
    }

    .pricing-card:hover::before {
      left: 100%;
    }
    
    .pricing-card:hover {
      transform: translateY(-15px) scale(1.02);
      box-shadow: 0 30px 80px rgba(255, 215, 0, 0.3);
      border-color: #FFA500;
    }
    
    .pricing-card.popular {
      border-color: var(--premium-gold);
      background: linear-gradient(135deg, rgba(255, 215, 0, 0.15) 0%, rgba(26, 26, 26, 0.95) 100%);
      animation: popularGlow 2s ease-in-out infinite alternate;
    }

    @keyframes popularGlow {
      0% {
        box-shadow: 0 20px 60px rgba(255, 215, 0, 0.2);
      }
      100% {
        box-shadow: 0 30px 80px rgba(255, 215, 0, 0.4), 0 0 40px rgba(255, 215, 0, 0.2);
      }
    }
    
    .pricing-card.popular::after {
      content: 'Most Popular';
      position: absolute;
      top: -15px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--premium-gradient);
      color: var(--dark-bg);
      padding: 10px 25px;
      border-radius: 25px;
      font-weight: 700;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 5px 20px rgba(255, 215, 0, 0.4);
      animation: popularBadge 2s ease-in-out infinite;
    }

    @keyframes popularBadge {
      0%, 100% {
        transform: translateX(-50%) scale(1);
      }
      50% {
        transform: translateX(-50%) scale(1.05);
      }
    }
    
    .plan-name {
      font-size: 28px;
      font-weight: 800;
      color: var(--premium-gold);
      margin-bottom: 20px;
      text-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
    }
    
    .plan-price {
      font-size: 48px;
      font-weight: 900;
      color: var(--text-light);
      margin-bottom: 8px;
      text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    }
    
    .plan-period {
      color: var(--text-muted);
      margin-bottom: 35px;
      font-size: 16px;
      font-weight: 500;
    }
    
    .plan-features {
      list-style: none;
      padding: 0;
      margin: 35px 0;
    }
    
    .plan-features li {
      padding: 15px 0;
      color: var(--text-light);
      border-bottom: 1px solid rgba(255, 215, 0, 0.1);
      font-size: 16px;
      font-weight: 500;
      transition: all 0.3s ease;
      position: relative;
    }

    .plan-features li:hover {
      color: var(--premium-gold);
      transform: translateX(5px);
    }
    
    .plan-features li:before {
      content: '✓';
      color: var(--premium-gold);
      font-weight: bold;
      margin-right: 15px;
      font-size: 18px;
      text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }
    
    .upgrade-btn {
      background: var(--premium-gradient);
      color: var(--dark-bg);
      border: none;
      padding: 18px 35px;
      border-radius: 50px;
      font-weight: 800;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      width: 100%;
      text-transform: uppercase;
      letter-spacing: 2px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
    }

    .upgrade-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .upgrade-btn:hover::before {
      left: 100%;
    }
    
    .upgrade-btn:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 15px 40px rgba(255, 215, 0, 0.5);
      background: linear-gradient(135deg, #FFA500 0%, #FFD700 100%);
    }

    .upgrade-btn:active {
      transform: translateY(-1px) scale(1.02);
    }
    
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      margin: 50px 0;
    }
    
    .feature-card {
      background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(20, 20, 20, 0.9) 100%);
      padding: 40px 30px;
      border-radius: 20px;
      border: 2px solid rgba(255, 215, 0, 0.2);
      text-align: center;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      backdrop-filter: blur(15px);
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255, 215, 0, 0.05) 0%, transparent 50%, rgba(255, 215, 0, 0.05) 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .feature-card:hover::before {
      opacity: 1;
    }
    
    .feature-card:hover {
      transform: translateY(-10px) scale(1.02);
      border-color: var(--premium-gold);
      box-shadow: 0 20px 50px rgba(255, 215, 0, 0.2);
    }
    
    .feature-icon {
      font-size: 50px;
      margin-bottom: 25px;
      animation: featureIconFloat 3s ease-in-out infinite;
      position: relative;
      z-index: 2;
    }

    @keyframes featureIconFloat {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-8px);
      }
    }
    
    .feature-title {
      color: var(--premium-gold);
      font-weight: 700;
      font-size: 20px;
      margin-bottom: 20px;
      position: relative;
      z-index: 2;
    }
    
    .feature-desc {
      color: var(--text-light);
      line-height: 1.7;
      font-size: 15px;
      position: relative;
      z-index: 2;
    }
    
    .already-premium {
      background: linear-gradient(135deg, rgba(255, 215, 0, 0.15) 0%, rgba(255, 165, 0, 0.1) 100%);
      color: var(--text-light);
      padding: 60px 40px;
      border-radius: 25px;
      text-align: center;
      margin: 30px 0;
      border: 2px solid var(--premium-gold);
      backdrop-filter: blur(20px);
      box-shadow: 0 20px 60px rgba(255, 215, 0, 0.2);
      animation: alreadyPremiumGlow 3s ease-in-out infinite alternate;
      position: relative;
      overflow: hidden;
    }

    @keyframes alreadyPremiumGlow {
      0% {
        box-shadow: 0 20px 60px rgba(255, 215, 0, 0.2);
      }
      100% {
        box-shadow: 0 30px 80px rgba(255, 215, 0, 0.4), 0 0 40px rgba(255, 215, 0, 0.2);
      }
    }

    .already-premium::before {
      content: '👑';
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 40px;
      opacity: 0.3;
      animation: crownSpin 4s linear infinite;
    }

    @keyframes crownSpin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
    
    .already-premium h2 {
      margin-bottom: 20px;
      font-weight: 800;
      font-size: 2.5rem;
      color: var(--premium-gold);
      text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
    }

    .already-premium p {
      font-size: 1.2rem;
      margin-bottom: 30px;
      opacity: 0.9;
    }
    
    .save-badge {
      background: linear-gradient(135deg, #ff4757 0%, #ff6b6b 100%);
      color: white;
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 700;
      position: absolute;
      top: 15px;
      right: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 5px 15px rgba(255, 71, 87, 0.4);
      animation: saveBadgePulse 2s ease-in-out infinite;
    }

    @keyframes saveBadgePulse {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 5px 15px rgba(255, 71, 87, 0.4);
      }
      50% {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(255, 71, 87, 0.6);
      }
    }
  </style>
</head>

<body>
<!-- Premium Background Elements -->
<div class="premium-background"></div>
<div class="premium-orb"></div>
<div class="premium-orb"></div>
<div class="premium-orb"></div>
<div class="premium-orb"></div>
<div class="premium-orb"></div>
<div class="premium-particle-system"></div>
<div class="premium-wave"></div>
<div class="premium-grid"></div>
<div class="premium-nebula"></div>
<div class="premium-starfield"></div>
<div class="premium-ripple" style="top: 20%; left: 30%;"></div>
<div class="premium-ripple" style="top: 60%; right: 20%;"></div>
<div class="premium-light-ray"></div>
<div class="premium-light-ray"></div>
<div class="premium-light-ray"></div>
<div class="premium-light-ray"></div>

<!-- Navbar -->
<nav class="navbar fixed-top navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="dream.php">
      <i class="bi bi-moon-stars-fill me-2"></i>
      <span style="color: rgba(255,255,255,0.75)">DREAM</span><span style="color: #39FF14;">LOCK</span>
    </a>
    
    <div class="ms-auto">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="dream.php">
            <i class="bi bi-house-door me-1"></i><?php echo $t['home']; ?>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="dream-sharing.php">
            <i class="bi bi-share me-1"></i>Dream Sharing
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="subconscious.php">
            <i class="bi bi-brain me-1"></i>Subconscious
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="premium.php">
            <i class="bi bi-star-fill me-1"></i>Premium
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?logout=1">
            <i class="bi bi-box-arrow-right me-1"></i><?php echo $t['logout']; ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <?php if ($is_premium): ?>
    <div class="already-premium">
      <h2>👑 <?php echo $t['already_premium']; ?></h2>
      <?php if ($user['premium_expires_at']): ?>
        <p><?php echo $t['premium_expires']; ?>: <?php echo date('M d, Y', strtotime($user['premium_expires_at'])); ?></p>
      <?php endif; ?>
      <button class="upgrade-btn" onclick="managePaddleSubscription()"><?php echo $t['manage_subscription']; ?></button>
    </div>
  <?php else: ?>
    
    <div class="premium-header">
      <h1><?php echo $t['premium_title']; ?></h1>
      <p class="premium-subtitle"><?php echo $t['unlock_dreams']; ?></p>
      <p style="opacity: 0.8; margin-top: 20px;"><?php echo $t['free_limit']; ?></p>
    </div>

    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">🚀</div>
        <div class="feature-title"><?php echo $t['unlimited_dreams']; ?></div>
        <div class="feature-desc">No limits on dream entries</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🧠</div>
        <div class="feature-title"><?php echo $t['advanced_analysis']; ?></div>
        <div class="feature-desc">Deeper AI insights</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📚</div>
        <div class="feature-title"><?php echo $t['dream_history']; ?></div>
        <div class="feature-desc">Access all your dreams</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon">⭐</div>
        <div class="feature-title"><?php echo $t['priority_support']; ?></div>
        <div class="feature-desc">Get help when you need it</div>
      </div>
    </div>

    <?php
      // --- GEO-BASED PRICING START ---
      function detectCountryCode(): string {
        // Dev/test override via query in non-production
        if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') {
          if (!empty($_GET['test_country']) && preg_match('/^[A-Za-z]{2}$/', $_GET['test_country'])) {
            $_SESSION['test_country'] = strtoupper($_GET['test_country']);
          }
          if (!empty($_SESSION['test_country'])) {
            return strtoupper($_SESSION['test_country']);
          }
        }

        // Prefer Cloudflare header if present
        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
          return strtoupper(trim($_SERVER['HTTP_CF_IPCOUNTRY']));
        }
        // Try common proxy headers for country if any (some CDNs set this)
        if (!empty($_SERVER['HTTP_X_COUNTRY_CODE'])) {
          return strtoupper(trim($_SERVER['HTTP_X_COUNTRY_CODE']));
        }
        // Fallback: best-effort lookup via ipapi.co (server-side)
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip) {
          $ctx = stream_context_create(['http' => ['timeout' => 1.5]]);
          $resp = @file_get_contents("https://ipapi.co/{$ip}/country/", false, $ctx);
          if ($resp && preg_match('/^[A-Z]{2}$/', trim($resp))) {
            return strtoupper(trim($resp));
          }
        }
        return 'US';
      }

      function isEUCountry(string $code): bool {
        // EU countries list (as of 2024)
        $eu = [
          'AT','BE','BG','HR','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES','SE'
        ];
        return in_array(strtoupper($code), $eu, true);
      }

      function formatPriceByCurrency(string $currency, float $amount): string {
        switch ($currency) {
          case 'EUR':
            // Use comma decimal, no thousands, show 2 decimals
            return '€' . number_format($amount, 2, ',', '');
          case 'GBP':
            return '£' . number_format($amount, 2, '.', '');
          case 'TRY':
            // No decimals as provided
            return '₺' . number_format($amount, 0, ',', '.');
          case 'USD':
          default:
            return '$' . number_format($amount, 2, '.', '');
        }
      }

      $countryCode = detectCountryCode();
      $currencyCode = 'USD';
      $monthlyAmount = 9.99;
      $yearlyAmount = 99.99;

      if ($countryCode === 'TR') {
        $currencyCode = 'TRY';
        $monthlyAmount = 100;
        $yearlyAmount = 600;
      } elseif ($countryCode === 'GB') {
        $currencyCode = 'GBP';
        $monthlyAmount = 3.99;
        $yearlyAmount = 40.00;
      } elseif ($countryCode === 'US') {
        $currencyCode = 'USD';
        $monthlyAmount = 4.00;
        $yearlyAmount = 40.00;
      } elseif (isEUCountry($countryCode)) {
        $currencyCode = 'EUR';
        $monthlyAmount = 4.49;
        $yearlyAmount = 45.00;
      }

      $monthlyPriceFormatted = formatPriceByCurrency($currencyCode, $monthlyAmount);
      $yearlyPriceFormatted = formatPriceByCurrency($currencyCode, $yearlyAmount);
      // --- GEO-BASED PRICING END ---
    ?>

    <div class="pricing-cards">
      <div class="pricing-card">
        <div class="plan-name"><?php echo $t['monthly_plan']; ?></div>
        <div class="plan-price"><?php echo $monthlyPriceFormatted; ?></div>
        <div class="plan-period"><?php echo $t['per_month']; ?></div>
        <ul class="plan-features">
          <li><?php echo $t['unlimited_dreams']; ?></li>
          <li><?php echo $t['advanced_analysis']; ?></li>
          <li><?php echo $t['priority_support']; ?></li>
        </ul>
        <button class="upgrade-btn" onclick="upgradeToPremium('monthly')"><?php echo $t['upgrade_now']; ?></button>
      </div>
      
      <div class="pricing-card popular">
        <div class="save-badge"><?php echo $t['save_percent']; ?></div>
        <div class="plan-name"><?php echo $t['yearly_plan']; ?></div>
        <div class="plan-price"><?php echo $yearlyPriceFormatted; ?></div>
        <div class="plan-period"><?php echo $t['per_year']; ?></div>
        <ul class="plan-features">
          <li><?php echo $t['unlimited_dreams']; ?></li>
          <li><?php echo $t['advanced_analysis']; ?></li>
          <li><?php echo $t['priority_support']; ?></li>
          <li>Priority features</li>
        </ul>
        <button class="upgrade-btn" onclick="upgradeToPremium('yearly')"><?php echo $t['upgrade_now']; ?></button>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
// Initialize Paddle
Paddle.Setup({ 
  vendor: <?php echo PADDLE_VENDOR_ID; ?>,
  environment: '<?php echo PADDLE_ENVIRONMENT; ?>'
});

function upgradeToPremium(plan) {
  const productId = plan === 'yearly' ? '<?php echo PADDLE_PRODUCT_ID; ?>_yearly' : '<?php echo PADDLE_PRODUCT_ID; ?>_monthly';
  
  Paddle.Checkout.open({
    product: productId,
    email: '<?php echo $_SESSION['username']; ?>@dreamlock.com', // You might want to store actual email
    country: '<?php echo $countryCode; ?>',
    currency: '<?php echo $currencyCode; ?>',
    passthrough: JSON.stringify({
      user_id: <?php echo $_SESSION['user_id']; ?>,
      plan: plan
    }),
    successCallback: function(data) {
      // Redirect to success page
      window.location.href = 'premium-success.php?checkout=' + data.checkout.id;
    },
    closeCallback: function() {
      console.log('Checkout closed');
    }
  });
}

function managePaddleSubscription() {
  // Redirect to Paddle subscription management
  window.open('https://vendors.paddle.com/subscription-management', '_blank');
}

// Premium Particle System
function createPremiumParticles() {
  const particleSystem = document.querySelector('.premium-particle-system');
  const starfield = document.querySelector('.premium-starfield');
  
  // Create particles
  for (let i = 0; i < 50; i++) {
    const particle = document.createElement('div');
    particle.className = 'premium-particle';
    particle.style.width = Math.random() * 3 + 1 + 'px';
    particle.style.height = particle.style.width;
    particle.style.left = Math.random() * 100 + '%';
    particle.style.top = Math.random() * 100 + '%';
    particle.style.animationDelay = Math.random() * 3 + 's';
    particle.style.animationDuration = (Math.random() * 3 + 2) + 's';
    particleSystem.appendChild(particle);
  }
  
  // Create stars
  for (let i = 0; i < 30; i++) {
    const star = document.createElement('div');
    star.className = 'premium-star';
    star.style.width = Math.random() * 2 + 1 + 'px';
    star.style.height = star.style.width;
    star.style.left = Math.random() * 100 + '%';
    star.style.top = Math.random() * 100 + '%';
    star.style.animationDelay = Math.random() * 3 + 's';
    starfield.appendChild(star);
  }
}

// Initialize particles when page loads
document.addEventListener('DOMContentLoaded', function() {
  createPremiumParticles();
});

// Add premium ripple effect on click
document.addEventListener('click', function(e) {
  const ripple = document.createElement('div');
  ripple.className = 'premium-ripple';
  ripple.style.left = e.clientX + 'px';
  ripple.style.top = e.clientY + 'px';
  document.body.appendChild(ripple);
  
  setTimeout(() => {
    ripple.remove();
  }, 6000);
});
</script>

</body>
</html>