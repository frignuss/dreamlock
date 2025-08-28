<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DreamLock | AI-Powered Dream Analysis & Subconscious Insights</title>
  <meta name="description" content="Discover the meaning of your dreams and get lost in your dream world">
  <meta name="keywords" content="dream, lock, dream analysis, dream interpretation">

	<!-- Canonical Link -->
  <link rel="canonical" href="https://www.seninsiten.com/" />


  <!-- Meta Açıklama -->
  <meta name="description" content="Analyze your dreams with AI. DreamLock helps you unlock the secrets of your subconscious with secure dream logging and detailed interpretations." />

  <!-- Anahtar Kelimeler -->
  <meta name="keywords" content="dream analysis, AI dream interpretation, subconscious analysis, dream diary, dream journal, lucid dreams, sleep insights, mental wellness" />
	
  <!-- Yazar -->
  <meta name="author" content="DreamLock Team" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.seninsiten.com/" />
  <meta property="og:title" content="DreamLock | AI-Powered Dream Analysis" />
  <meta property="og:description" content="Analyze your dreams using cutting-edge AI. Unlock hidden meanings and explore your subconscious." />
  <meta property="og:image" content="https://www.seninsiten.com/assets/logo.png" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:url" content="https://www.seninsiten.com/" />
  <meta name="twitter:title" content="DreamLock | AI-Powered Dream Analysis" />
  <meta name="twitter:description" content="DreamLock provides secure, AI-driven dream logging and analysis. Understand your subconscious through smart tools." />
  <meta name="twitter:image" content="https://www.seninsiten.com/assets/logo.png" />

	
  <!-- Favicon -->
  <link href="assets/logo.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <!-- Emoji Font Support -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Color+Emoji&display=swap" rel="stylesheet">

  <style>
    /* Emoji Font Support */
    body {
      font-family: 'Manrope', 'Noto Color Emoji', sans-serif;
    }
    
    /* Ensure emojis display properly */
    .emoji, [data-emoji] {
      font-family: 'Noto Color Emoji', 'Segoe UI Emoji', 'Apple Color Emoji', sans-serif;
      font-size: 1.2em;
      line-height: 1;
    }
    
	  .rain-drop {
  position: absolute;
  width: 2px;
  height: 10px;
  background: linear-gradient(transparent, #4a90e2);
  animation: rainFall 1.5s linear infinite;
}

@keyframes rainFall {
  0% { transform: translateY(-100vh); opacity: 1; }
  100% { transform: translateY(100vh); opacity: 0; }
}
	  /* Cookie Consent Styles */
.cookie-consent {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(10, 10, 10, 0.98);
  backdrop-filter: blur(20px);
  border-top: 2px solid #39FF14;
  padding: 25px;
  z-index: 10000;
  transform: translateY(100%);
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.5);
}

.cookie-consent.show {
  transform: translateY(0);
}

.cookie-consent-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 30px;
  flex-wrap: wrap;
}

.cookie-info {
  flex: 1;
  min-width: 300px;
}

.cookie-info h3 {
  color: #39FF14;
  font-size: 1.3rem;
  font-weight: 700;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.cookie-info p {
  color: #ddd;
  line-height: 1.6;
  font-size: 0.95rem;
  margin-bottom: 15px;
}

.cookie-info a {
  color: #39FF14;
  text-decoration: underline;
  transition: all 0.3s ease;
}

.cookie-info a:hover {
  color: #2ecc71;
  text-shadow: 0 0 8px #39FF14;
}

.cookie-buttons {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  align-items: center;
}

.cookie-btn {
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-family: 'Manrope', sans-serif;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  white-space: nowrap;
}

.cookie-btn-accept {
  background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%);
  color: #000;
  box-shadow: 0 4px 15px rgba(57, 255, 20, 0.3);
}

.cookie-btn-accept:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(57, 255, 20, 0.4);
  color: #000;
}

.cookie-btn-reject {
  background: transparent;
  color: #ddd;
  border: 1px solid #666;
}

.cookie-btn-reject:hover {
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  border-color: #999;
}

.cookie-btn-settings {
  background: transparent;
  color: #39FF14;
  border: 1px solid #39FF14;
}

.cookie-btn-settings:hover {
  background: rgba(57, 255, 20, 0.1);
  color: #39FF14;
}

/* Cookie Settings Modal */
.cookie-settings-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(10px);
  z-index: 10001;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.cookie-settings-modal.show {
  opacity: 1;
  visibility: visible;
}

.cookie-settings-content {
  background: rgba(20, 20, 20, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 15px;
  border: 1px solid rgba(57, 255, 20, 0.3);
  padding: 30px;
  max-width: 600px;
  width: 100%;
  max-height: 80vh;
  overflow-y: auto;
  transform: scale(0.9);
  transition: transform 0.3s ease;
}

.cookie-settings-modal.show .cookie-settings-content {
  transform: scale(1);
}

.cookie-settings-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 25px;
  padding-bottom: 15px;
  border-bottom: 1px solid rgba(57, 255, 20, 0.2);
}

.cookie-settings-header h3 {
  color: #39FF14;
  font-size: 1.5rem;
  font-weight: 700;
}

.cookie-close-btn {
  background: none;
  border: none;
  color: #ddd;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 5px;
  border-radius: 5px;
  transition: all 0.3s ease;
}

.cookie-close-btn:hover {
  color: #39FF14;
  background: rgba(57, 255, 20, 0.1);
}

.cookie-category {
  margin-bottom: 25px;
  padding: 20px;
  background: rgba(30, 30, 30, 0.5);
  border-radius: 10px;
  border: 1px solid rgba(57, 255, 20, 0.1);
}

.cookie-category-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 15px;
}

.cookie-category h4 {
  color: #fff;
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
}

.cookie-category p {
  color: #bbb;
  font-size: 0.9rem;
  line-height: 1.5;
  margin: 0;
}

.cookie-toggle {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.cookie-toggle input {
  opacity: 0;
  width: 0;
  height: 0;
}

.cookie-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #666;
  transition: 0.3s;
  border-radius: 24px;
}

.cookie-slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
}

input:checked + .cookie-slider {
  background-color: #39FF14;
}

input:checked + .cookie-slider:before {
  transform: translateX(26px);
}

.cookie-toggle.disabled {
  opacity: 0.5;
  pointer-events: none;
}

.cookie-settings-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  padding-top: 20px;
  border-top: 1px solid rgba(57, 255, 20, 0.2);
}

/* Responsive Design for Cookie Consent */
@media (max-width: 768px) {
  .cookie-consent {
    padding: 20px;
  }

  .cookie-consent-content {
    flex-direction: column;
    align-items: stretch;
    gap: 20px;
  }

  .cookie-info {
    min-width: unset;
  }

  .cookie-info h3 {
    font-size: 1.2rem;
  }

  .cookie-info p {
    font-size: 0.9rem;
  }

  .cookie-buttons {
    justify-content: center;
    gap: 10px;
  }

  .cookie-btn {
    flex: 1;
    justify-content: center;
    min-width: 100px;
  }

  .cookie-settings-content {
    padding: 20px;
    margin: 10px;
  }

  .cookie-category {
    padding: 15px;
  }

  .cookie-settings-actions {
    flex-direction: column;
  }
}

@media (max-width: 480px) {
  .cookie-consent {
    padding: 15px;
  }

  .cookie-buttons {
    flex-direction: column;
    gap: 10px;
  }

  .cookie-btn {
    width: 100%;
  }

  .cookie-info h3 {
    font-size: 1.1rem;
  }

  .cookie-info p {
    font-size: 0.85rem;
  }
}
    * {
      font-family: 'Manrope', sans-serif;
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
      color: #fff;
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
      animation: float 8s ease-in-out infinite;
    }

    .particle:nth-child(1) { width: 6px; height: 6px; top: 10%; left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { width: 4px; height: 4px; top: 20%; left: 80%; animation-delay: 1s; }
    .particle:nth-child(3) { width: 8px; height: 8px; top: 60%; left: 15%; animation-delay: 2s; }
    .particle:nth-child(4) { width: 5px; height: 5px; top: 80%; left: 70%; animation-delay: 3s; }
    .particle:nth-child(5) { width: 3px; height: 3px; top: 40%; left: 90%; animation-delay: 4s; }
    .particle:nth-child(6) { width: 7px; height: 7px; top: 70%; left: 30%; animation-delay: 5s; }
    .particle:nth-child(7) { width: 4px; height: 4px; top: 30%; left: 60%; animation-delay: 6s; }
    .particle:nth-child(8) { width: 5px; height: 5px; top: 90%; left: 20%; animation-delay: 7s; }

    @keyframes float {
      0%, 100% { transform: translateY(0px) scale(1); opacity: 0.7; }
      50% { transform: translateY(-30px) scale(1.2); opacity: 1; }
    }

    /* Floating Dream Icons */
    .floating-element {
      position: absolute;
      pointer-events: none;
      color: rgba(57, 255, 20, 0.1);
      font-size: 24px;
      animation: floatSlow 12s ease-in-out infinite;
    }

    .floating-element:nth-child(1) { top: 15%; left: 5%; animation-delay: 0s; }
    .floating-element:nth-child(2) { top: 25%; right: 10%; animation-delay: 3s; }
    .floating-element:nth-child(3) { bottom: 40%; left: 8%; animation-delay: 6s; }
    .floating-element:nth-child(4) { bottom: 20%; right: 15%; animation-delay: 9s; }
    .floating-element:nth-child(5) { top: 50%; left: 3%; animation-delay: 2s; }
    .floating-element:nth-child(6) { top: 70%; right: 5%; animation-delay: 8s; }

    @keyframes floatSlow {
      0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.1; }
      50% { transform: translateY(-25px) rotate(10deg); opacity: 0.3; }
    }

    /* Navigation */
    .navbar {
      background: rgba(10, 10, 10, 0.95);
      backdrop-filter: blur(20px);
      padding: 20px 40px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(57, 255, 20, 0.1);
      transition: all 0.3s ease;
    }

    .navbar.scrolled {
      background: rgba(10, 10, 10, 0.98);
      padding: 15px 40px;
      box-shadow: 0 5px 30px rgba(57, 255, 20, 0.1);
    }

    .navbar-brand a {
      font-size: 32px;
      font-weight: 800;
      text-decoration: none;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .navbar-brand a:hover {
      transform: scale(1.05);
      filter: drop-shadow(0 0 15px #39FF14);
    }

    .navbar-brand .logo-icon {
      margin-right: 12px;
      font-size: 28px;
      color: #39FF14;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }

    .navbar-brand .white {
      color: #ffffff;
    }

    .navbar-brand .green {
      color: #39FF14;
      text-shadow: 0 0 20px #39FF14;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    .nav-link {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      padding: 12px 24px;
      border-radius: 8px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .nav-link:hover {
      color: #39FF14;
      background: rgba(57, 255, 20, 0.1);
      transform: translateY(-2px);
    }

    .nav-link.login-btn {
      background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%);
      color: #000;
      font-weight: 700;
      box-shadow: 0 4px 15px rgba(57, 255, 20, 0.3);
    }

    .nav-link.login-btn:hover {
      color: #000;
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(57, 255, 20, 0.4);
    }

    /* Mobile Menu */
    .mobile-menu-toggle {
      display: none;
      background: none;
      border: none;
      color: #fff;
      font-size: 24px;
      cursor: pointer;
    }

    /* Hero Section */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      position: relative;
      background: url('background.jpg') center/cover;
      background-attachment: fixed;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(10, 10, 10, 0.8) 0%, rgba(26, 26, 46, 0.9) 100%);
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      padding: 0 20px;
      animation: heroSlideUp 1s ease-out;
    }

    @keyframes heroSlideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .hero h1 {
      font-size: 4rem;
      font-weight: 800;
      margin-bottom: 20px;
      background: linear-gradient(135deg, #39FF14 0%, #2ecc71 50%, #39FF14 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: textGlow 3s ease-in-out infinite alternate;
    }

    @keyframes textGlow {
      from { filter: drop-shadow(0 0 10px #39FF14); }
      to { filter: drop-shadow(0 0 30px #39FF14); }
    }

    .hero p {
      font-size: 1.5rem;
      color: #ddd;
      margin-bottom: 40px;
      font-weight: 400;
      line-height: 1.6;
    }

    .hero-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn-primary {
      background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%);
      color: #000;
      padding: 18px 40px;
      border: none;
      border-radius: 12px;
      font-weight: 700;
      font-size: 18px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 25px rgba(57, 255, 20, 0.3);
      position: relative;
      overflow: hidden;
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(57, 255, 20, 0.4);
      color: #000;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn-primary:hover::before {
      left: 100%;
    }

    .btn-secondary {
      background: transparent;
      color: #39FF14;
      padding: 18px 40px;
      border: 2px solid #39FF14;
      border-radius: 12px;
      font-weight: 600;
      font-size: 18px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-secondary:hover {
      background: rgba(57, 255, 20, 0.1);
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(57, 255, 20, 0.2);
      color: #39FF14;
    }

    /* Features Section */
    .features {
      padding: 100px 0;
      position: relative;
      z-index: 1;
      background: linear-gradient(180deg, transparent 0%, rgba(26, 26, 46, 0.5) 100%);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .section-title {
      text-align: center;
      margin-bottom: 80px;
    }

    .section-title h2 {
      font-size: 3rem;
      font-weight: 700;
      color: #39FF14;
      margin-bottom: 20px;
      text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
    }

    .section-title p {
      font-size: 1.2rem;
      color: #aaa;
      line-height: 1.6;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
    }

    .feature-card {
      background: rgba(30, 30, 30, 0.9);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 40px;
      text-align: center;
      border: 1px solid rgba(57, 255, 20, 0.2);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #39FF14, #2ecc71, #39FF14);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .feature-card:hover::before {
      transform: scaleX(1);
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 50px rgba(57, 255, 20, 0.15);
      border-color: #39FF14;
    }

    .feature-icon {
      font-size: 3rem;
      color: #39FF14;
      margin-bottom: 20px;
      display: inline-block;
      transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon {
      transform: scale(1.2) rotate(5deg);
      filter: drop-shadow(0 0 20px #39FF14);
    }

    .feature-card h3 {
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 15px;
    }

    .feature-card p {
      color: #bbb;
      line-height: 1.6;
      font-size: 1rem;
    }

    /* Stats Section */
    .stats {
      padding: 80px 0;
      background: rgba(26, 26, 46, 0.3);
      backdrop-filter: blur(10px);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 40px;
      text-align: center;
    }

    .stat-item {
      padding: 20px;
    }

    .stat-number {
      font-size: 3rem;
      font-weight: 800;
      color: #39FF14;
      display: block;
      margin-bottom: 10px;
      text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
    }

    .stat-label {
      font-size: 1.1rem;
      color: #ddd;
      font-weight: 600;
    }

    /* CTA Section */
    .cta {
      padding: 100px 0;
      text-align: center;
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.1) 0%, rgba(46, 204, 113, 0.1) 100%);
    }

    .cta h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: #39FF14;
      margin-bottom: 20px;
    }

    .cta p {
      font-size: 1.2rem;
      color: #ddd;
      margin-bottom: 40px;
      line-height: 1.6;
    }

    /* Footer */
    .footer {
      background: rgba(10, 10, 10, 0.95);
      padding: 60px 0 30px;
      border-top: 1px solid rgba(57, 255, 20, 0.2);
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-section h3 {
      color: #39FF14;
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .footer-section p, .footer-section a {
      color: #bbb;
      line-height: 1.6;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-section a:hover {
      color: #39FF14;
    }

    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .social-link {
      width: 45px;
      height: 45px;
      background: rgba(57, 255, 20, 0.1);
      border: 1px solid rgba(57, 255, 20, 0.3);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #39FF14;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .social-link:hover {
      background: #39FF14;
      color: #000;
      transform: translateY(-3px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 30px;
      border-top: 1px solid rgba(57, 255, 20, 0.1);
      color: #888;
    }

    .footer-bottom strong {
      color: #39FF14;
    }

    /* Scroll to Top */
    .scroll-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #000;
      font-size: 20px;
      text-decoration: none;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .scroll-top.visible {
      opacity: 1;
      visibility: visible;
    }

    .scroll-top:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(57, 255, 20, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
      .container {
        padding: 0 30px;
      }
      
      .features-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
      }
    }

    @media (max-width: 992px) {
      .navbar {
        padding: 15px 30px;
      }

      .hero h1 {
        font-size: 3rem;
      }

      .hero p {
        font-size: 1.3rem;
      }

      .section-title h2 {
        font-size: 2.5rem;
      }

      .features-grid {
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .mobile-menu-toggle {
        display: block;
      }

      .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: rgba(10, 10, 10, 0.98);
        backdrop-filter: blur(20px);
        flex-direction: column;
        padding: 20px;
        border-top: 1px solid rgba(57, 255, 20, 0.2);
        gap: 15px;
      }

      .nav-links.active {
        display: flex;
      }

      .navbar {
        padding: 15px 20px;
      }

      .navbar-brand a {
        font-size: 28px;
      }

      .hero {
        padding: 100px 0 50px;
      }

      .hero h1 {
        font-size: 2.5rem;
      }

      .hero p {
        font-size: 1.2rem;
        margin-bottom: 30px;
      }

      .hero-buttons {
        flex-direction: column;
        align-items: center;
      }

      .btn-primary, .btn-secondary {
        width: 100%;
        max-width: 300px;
        justify-content: center;
      }

      .section-title h2 {
        font-size: 2rem;
      }

      .section-title p {

        font-size: 1.1rem;
      }

      .features {
        padding: 80px 0;
      }

      .features-grid {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      .feature-card {
        padding: 30px 20px;
      }

      .stats {
        padding: 60px 0;
      }

      .stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 30px;
      }

      .stat-number {
        font-size: 2.5rem;
      }

      .cta {
        padding: 80px 0;
      }

      .cta h2 {
        font-size: 2rem;
      }

      .footer {
        padding: 50px 0 20px;
      }

      .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      .social-links {
        justify-content: center;
      }
    }

    @media (max-width: 576px) {
      .hero h1 {
        font-size: 2rem;
      }

      .hero p {
        font-size: 1.1rem;
      }

      .section-title h2 {
        font-size: 1.8rem;
      }

      .features-grid {
        gap: 20px;
      }

      .feature-card {
        padding: 25px 15px;
      }

      .feature-icon {
        font-size: 2.5rem;
      }

      .stats-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .stat-number {
        font-size: 2rem;
      }

      .cta h2 {
        font-size: 1.8rem;
      }

      .btn-primary, .btn-secondary {
        font-size: 16px;
        padding: 15px 30px;
      }

      .scroll-top {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
        font-size: 18px;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 0 15px;
      }

      .navbar {
        padding: 12px 15px;
      }

      .navbar-brand a {
        font-size: 24px;
      }

      .logo-icon {
        font-size: 22px !important;
      }

      .hero {
        padding: 80px 0 40px;
      }

      .hero-content {
        padding: 0 15px;
      }

      .features {
        padding: 60px 0;
      }

      .section-title {
        margin-bottom: 50px;
      }

      .stats {
        padding: 50px 0;
      }

      .cta {
        padding: 60px 0;
      }

      .footer {
        padding: 40px 0 15px;
      }
    }

    /* Loading Animation */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.5s ease;
    }

    .loading-overlay.hidden {
      opacity: 0;
      pointer-events: none;
    }

    .loader {
      width: 60px;
      height: 60px;
      border: 3px solid rgba(57, 255, 20, 0.3);
      border-top: 3px solid #39FF14;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Smooth Animations */
    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }
	  /* Nisa Easter Egg Styles */
.nisa-easter-egg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.9);
  backdrop-filter: blur(10px);
  z-index: 10005;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  visibility: hidden;
  transition: all 0.5s ease;
  pointer-events: none;
}

.nisa-easter-egg.show {
  opacity: 1;
  visibility: visible;
  pointer-events: all;
}

.nisa-text {
  font-size: 8rem;
  font-weight: 900;
  background: linear-gradient(45deg, #ff1493, #ff69b4, #ffc0cb, #ff1493);
  background-size: 400% 400%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: nisaGradient 2s ease-in-out infinite, nisaPulse 1s ease-in-out infinite;
  text-shadow: 0 0 50px rgba(255, 20, 147, 0.8);
  transform: scale(0);
  animation-delay: 0.3s;
}

.nisa-easter-egg.show .nisa-text {
  animation: nisaGradient 2s ease-in-out infinite, nisaPulse 1s ease-in-out infinite, nisaZoomIn 0.8s ease-out forwards;
}

@keyframes nisaGradient {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

@keyframes nisaPulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

@keyframes nisaZoomIn {
  0% { transform: scale(0) rotate(-180deg); opacity: 0; }
  50% { transform: scale(1.2) rotate(0deg); opacity: 1; }
  100% { transform: scale(1) rotate(0deg); opacity: 1; }
}

.firework {
  position: absolute;
  width: 6px;
  height: 6px;
  background: #ff1493;
  border-radius: 50%;
  animation: fireworkExplode 2s ease-out forwards;
}

@keyframes fireworkExplode {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  100% {
    transform: scale(0);
    opacity: 0;
  }
}

.heart-particle {
  position: absolute;
  font-size: 2rem;
  color: #ff1493;
  animation: heartFloat 3s ease-out forwards;
  pointer-events: none;
}

@keyframes heartFloat {
  0% {
    transform: translateY(0) scale(0) rotate(0deg);
    opacity: 1;
  }
  50% {
    transform: translateY(-100px) scale(1) rotate(180deg);
    opacity: 1;
  }
  100% {
    transform: translateY(-200px) scale(0) rotate(360deg);
    opacity: 0;
  }
}

/* Responsive */
@media (max-width: 768px) {
  .nisa-text {
    font-size: 4rem;
  }
}

@media (max-width: 480px) {
  .nisa-text {
    font-size: 3rem;
  }
}
  </style>
</head>
<script>
console.log(`
███╗   ██╗██╗███████╗ █████╗     ♥
████╗  ██║██║██╔════╝██╔══██╗   ♥♥♥
██╔██╗ ██║██║███████╗███████║  ♥♥♥♥♥
██║╚██╗██║██║╚════██║██╔══██║   ♥♥♥
██║ ╚████║██║███████║██║  ██║    ♥
╚═╝  ╚═══╝╚═╝╚══════╝╚═╝  ╚═╝
);
</script>
<body>
	<!-- Cookie Consent Banner -->
<div class="cookie-consent" id="cookieConsent">
  <div class="cookie-consent-content">
    <div class="cookie-info">
      <h3>
        <i class="fas fa-cookie-bite"></i>
        Çerez ve Veri Toplama Politikası
      </h3>
      <p>
        Deneyiminizi geliştirmek için çerezler kullanıyor ve kişisel verilerinizi (e-posta, telefon) pazarlama amaçlı üçüncü taraflarla paylaşabiliriz. 
        <a href="#" onclick="openPrivacyPolicy()">Gizlilik Politikamızı</a> detaylar için okuyun.
      </p>
    </div>
    <div class="cookie-buttons">
      <button class="cookie-btn cookie-btn-accept" onclick="acceptAllCookies()">
        <i class="fas fa-check"></i>
        Tümünü Kabul Et
      </button>
      <button class="cookie-btn cookie-btn-reject" onclick="rejectCookies()">
        <i class="fas fa-times"></i>
        Reddet
      </button>
    </div>
  </div>
</div>
<!-- Cookie Settings Modal -->
<div class="cookie-settings-modal" id="cookieSettingsModal">
  <div class="cookie-settings-content">
    <div class="cookie-settings-header">
      <h3>Çerez Tercihleri</h3>
      <button class="cookie-close-btn" onclick="closeCookieSettings()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="cookie-category">
      <div class="cookie-category-header">
        <h4>Zorunlu Çerezler</h4>
        <div class="cookie-toggle disabled">
          <input type="checkbox" checked disabled>
          <span class="cookie-slider"></span>
        </div>
      </div>
      <p>Bu çerezler web sitesinin çalışması için gereklidir ve kapatılamaz.</p>
    </div>

    <div class="cookie-category">
      <div class="cookie-category-header">
        <h4>Analitik & Performans</h4>
        <div class="cookie-toggle">
          <input type="checkbox" id="analytics-toggle">
          <span class="cookie-slider"></span>
        </div>
      </div>
      <p>Bu çerezler ziyaretçilerin web sitesi ile nasıl etkileşim kurduğunu anlamamıza yardımcı olur.</p>
    </div>

    <div class="cookie-category">
      <div class="cookie-category-header">
        <h4>Pazarlama & Üçüncü Taraf Paylaşımı</h4>
        <div class="cookie-toggle">
          <input type="checkbox" id="marketing-toggle">
          <span class="cookie-slider"></span>
        </div>
      </div>
      <p>Bu çerezler kişiselleştirilmiş reklamları ve verilerinizi iş ortaklarımızla paylaşmamızı sağlar.</p>
    </div>

    <div class="cookie-category">
      <div class="cookie-category-header">
        <h4>İşlevsel Çerezler</h4>
        <div class="cookie-toggle">
          <input type="checkbox" id="functional-toggle">
          <span class="cookie-slider"></span>
        </div>
      </div>
      <p>Bu çerezler videolar ve canlı sohbet gibi gelişmiş işlevleri etkinleştirir.</p>
    </div>

    <div class="cookie-settings-actions">
      <button class="cookie-btn cookie-btn-reject" onclick="rejectCookies()">
        Tümünü Reddet
      </button>
      <button class="cookie-btn cookie-btn-accept" onclick="saveCustomSettings()">
        Tercihleri Kaydet
      </button>
    </div>
  </div>
</div>
  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loader"></div>
  </div>

  <!-- Animated Background -->
  <div class="bg-animation">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>

  <!-- Floating Dream Elements -->
  <div class="floating-element"><i class="fas fa-brain"></i></div>
  <div class="floating-element"><i class="fas fa-moon"></i></div>
  <div class="floating-element"><i class="fas fa-star"></i></div>
  <div class="floating-element"><i class="fas fa-cloud"></i></div>
  <div class="floating-element"><i class="fas fa-eye"></i></div>
  <div class="floating-element"><i class="fas fa-magic"></i></div>

  <!-- Navigation -->
  <nav class="navbar" id="navbar">
    <div class="navbar-brand">
      <a href="#hero">
        <i class="fas fa-brain logo-icon"></i>
        <span class="white">DREAM</span><span class="green">LOCK</span>
      </a>
    </div>
    <div class="nav-links" id="navLinks">
      <a href="#features" class="nav-link">Features</a>
      <a href="#contact" class="nav-link">Contact</a>
      <a href="login.php" class="nav-link login-btn">
        <i class="fas fa-sign-in-alt"></i>
        Login
      </a>
    </div>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
      <i class="fas fa-bars"></i>
    </button>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div class="hero-content">
      <h1>DreamLock</h1>
      <p>Discover the depths of your dreams and unlock their hidden meanings</p>
      <div class="hero-buttons">
        <a href="register.php" class="btn-primary">
          <i class="fas fa-rocket"></i>
          Get Started
        </a>
        <a href="#features" class="btn-secondary">
          <i class="fas fa-info-circle"></i>
          Learn More
        </a>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" id="features">
    <div class="container">
      <div class="section-title fade-in">
        <h2>Get Lost in Your Dream World</h2>
        <p>Discover the hidden meanings of your dreams with DreamLock and dive into the depths of your subconscious</p>
      </div>
      <div class="features-grid">
        <div class="feature-card fade-in">
          <div class="feature-icon">
            <i class="fas fa-brain"></i>
          </div>
          <h3>Dream Analysis</h3>
          <p>Our AI-powered algorithms analyze your dreams in detail, revealing their hidden meanings and insights.</p>
        </div>
        <div class="feature-card fade-in">
          <div class="feature-icon">
            <i class="fas fa-moon"></i>
          </div>
          <h3>Sleep Tracking</h3>
          <p>Monitor your sleep quality and identify which sleep stages your dreams occur in for better understanding.</p>
        </div>
        <div class="feature-card fade-in">
          <div class="feature-icon">
            <i class="fas fa-chart-line"></i>
          </div>
          <h3>Statistics</h3>
          <p>Visualize your dream data to analyze long-term changes and patterns in your subconscious mind.</p>
        </div>
        <div class="feature-card fade-in">
          <div class="feature-icon">
            <i class="fas fa-users"></i>
          </div>
          <h3>Community</h3>
          <p>Share your dreams with other users, exchange experiences, and discover together in our supportive community.</p>
        </div>
        <div class="feature-card fade-in">
          <div class="feature-icon">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h3>Security</h3>
          <p>All your data is protected with end-to-end encryption. Your privacy is our top priority.</p>
        </div>
        <div class="feature-card fade-in">
          <div class="feature-icon">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <h3>Mobile Support</h3>
          <p>Access from anywhere with our mobile app - record and analyze your dreams instantly upon waking.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  

  <!-- CTA Section -->
  <section class="cta" id="about">
    <div class="container">
      <div class="fade-in">
        <h2>Begin Your Dream Journey</h2>
        <p>Sign up now and start exploring the mysterious world of your dreams.</p>
        <a href="register.php" class="btn-primary">
          <i class="fas fa-user-plus"></i>
          Sign Up Free
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer" id="contact">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h3>DreamLock</h3>
          <p>Discover the meaning of your dreams and dive into the depths of your subconscious. Every dream is a story, every story is a discovery.</p>
          <div class="social-links">
            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
        <div class="footer-section">
          <h3>Quick Links</h3>
          <p><a href="#features">Features</a></p>
          <p><a href="#about">About</a></p>
          <p><a href="register.php">Sign Up</a></p>
          <p><a href="login.php">Login</a></p>
        </div>
        <div class="footer-section">
          <h3>Support</h3>
          <p><a href="#">Help Center</a></p>
          <p><a href="#">FAQ</a></p>
          <p><a href="#">Privacy Policy</a></p>
          <p><a href="#">Terms of Service</a></p>
        </div>
        <div class="footer-section">
          <h3>Contact</h3>
          <p><i class="fas fa-envelope"></i> dreamlocktr@gmail.com</p>
          <p><i class="fas fa-map-marker-alt"></i> Istanbul, Turkey</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 <strong>DreamLock</strong>. All rights reserved.</p>
        <p>Design: <strong>@sadri.k_</strong></p>
      </div>
    </div>
  </footer>

  <!-- Scroll to Top -->
  <a href="#hero" class="scroll-top" id="scrollTop">
    <i class="fas fa-arrow-up"></i>
  </a>

  <script>
	  // Cookie Consent Management
class CookieConsent {
  constructor() {
    this.consentGiven = false;
    this.preferences = {
      essential: true,
      analytics: false,
      marketing: false,
      functional: false
    };
    this.init();
  }

  init() {
    const savedConsent = localStorage.getItem('dreamlock-cookie-consent');
    if (savedConsent) {
      this.preferences = JSON.parse(savedConsent);
      this.consentGiven = true;
      this.applyCookieSettings();
    } else {
      setTimeout(() => {
        this.showCookieBanner();
      }, 2000);
    }
  }

  showCookieBanner() {
    const banner = document.getElementById('cookieConsent');
    banner.classList.add('show');
  }

  hideCookieBanner() {
    const banner = document.getElementById('cookieConsent');
    banner.classList.remove('show');
  }

  saveConsent() {
    localStorage.setItem('dreamlock-cookie-consent', JSON.stringify(this.preferences));
    this.consentGiven = true;
    this.hideCookieBanner();
    this.applyCookieSettings();
    this.showConsentThankYou();
  }

  showConsentThankYou() {
    const successMsg = document.createElement('div');
    successMsg.innerHTML = `
      <div style="position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #39FF14 0%, #2ecc71 100%); 
                  color: #000; padding: 15px 25px; border-radius: 10px; z-index: 10002; 
                  box-shadow: 0 5px 20px rgba(57, 255, 20, 0.3); font-weight: 600;">
        <i class="fas fa-check-circle"></i> Çerez tercihleri kaydedildi!
      </div>
    `;
    document.body.appendChild(successMsg);
    
    setTimeout(() => {
      document.body.removeChild(successMsg);
    }, 3000);
  }

  applyCookieSettings() {
    if (this.preferences.analytics) {
      this.enableAnalytics();
    }
    if (this.preferences.marketing) {
      this.enableMarketing();
    }
    if (this.preferences.functional) {
      this.enableFunctional();
    }
    console.log('Cookie preferences applied:', this.preferences);
  }

  enableAnalytics() {
    console.log('Analytics enabled');
    // Google Analytics kodunuz buraya gelecek
  }

  enableMarketing() {
    console.log('Marketing cookies enabled');
    // Pazarlama scriptleri buraya gelecek
  }

  enableFunctional() {
    console.log('Functional cookies enabled');
    // İşlevsel çerezler buraya gelecek
  }

  acceptAll() {
    this.preferences = {
      essential: true,
      analytics: true,
      marketing: true,
      functional: true
    };
    this.saveConsent();
  }

  rejectAll() {
    this.preferences = {
      essential: true,
      analytics: false,
      marketing: false,
      functional: false
    };
    this.saveConsent();
  }

  openSettings() {
    document.getElementById('analytics-toggle').checked = this.preferences.analytics;
    document.getElementById('marketing-toggle').checked = this.preferences.marketing;
    document.getElementById('functional-toggle').checked = this.preferences.functional;

    const modal = document.getElementById('cookieSettingsModal');
    modal.classList.add('show');
  }

  closeSettings() {
    const modal = document.getElementById('cookieSettingsModal');
    modal.classList.remove('show');
  }

  saveCustomSettings() {
    this.preferences.analytics = document.getElementById('analytics-toggle').checked;
    this.preferences.marketing = document.getElementById('marketing-toggle').checked;
    this.preferences.functional = document.getElementById('functional-toggle').checked;
    
    this.closeSettings();
    this.saveConsent();
  }

  isAllowed(type) {
    return this.preferences[type] || false;
  }
}

// Initialize cookie consent
const cookieManager = new CookieConsent();

// Global functions
function acceptAllCookies() {
  cookieManager.acceptAll();
}

function rejectCookies() {
  cookieManager.rejectAll();
}

function openCookieSettings() {
  cookieManager.openSettings();
}

function closeCookieSettings() {
  cookieManager.closeSettings();
}

function saveCustomSettings() {
  cookieManager.saveCustomSettings();
}

function openPrivacyPolicy() {
  const modal = document.createElement('div');
  modal.innerHTML = `
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.9); backdrop-filter: blur(10px); z-index: 10003; 
                display: flex; align-items: center; justify-content: center; padding: 20px;">
      <div style="background: rgba(20, 20, 20, 0.95); border-radius: 15px; 
                  border: 1px solid rgba(57, 255, 20, 0.3); padding: 30px; 
                  max-width: 700px; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; 
                    margin-bottom: 20px; padding-bottom: 15px; 
                    border-bottom: 1px solid rgba(57, 255, 20, 0.2);">
          <h3 style="color: #39FF14; margin: 0;">Gizlilik Politikası ve Kullanıcı Sözleşmesi</h3>
          <button onclick="this.closest('div').parentNode.remove()" 
                  style="background: none; border: none; color: #ddd; font-size: 1.5rem; 
                         cursor: pointer; padding: 5px; border-radius: 5px;">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div style="color: #ddd; line-height: 1.6; font-size: 0.9rem;">
          <p><strong style="color: #39FF14;">Yürürlük Tarihi:</strong> 28 Temmuz 2025</p>
          <p><strong style="color: #39FF14;">Uygulama Adı:</strong> DreamLock</p>
          
          <h4 style="color: #39FF14; margin: 20px 0 10px;">1. Kullanıcı Sözleşmesi</h4>
          <p>DreamLock uygulamasına üye olan her kullanıcı, bu sözleşmede belirtilen şartları okuduğunu, anladığını ve kabul ettiğini onaylar.</p>
          
          <h4 style="color: #39FF14; margin: 20px 0 10px;">2. Gizlilik ve Veri Kullanımı</h4>
          <p><strong>2.1 Veri Toplama:</strong> DreamLock, kullanıcılardan ad, e-posta adresi, telefon numarası ve cihaz verilerini toplar.</p>
          <p><strong>2.2 Veri Paylaşımı:</strong> DreamLock, toplanan kişisel verileri pazarlama, analiz veya ticari amaçlarla üçüncü taraf iş ortaklarıyla paylaşabilir.</p>
          <p><strong>2.3 Veri Güvenliği:</strong> Tüm kişisel veriler endüstri standardı güvenlik önlemleri ile korunur.</p>
          <p><strong>2.4 Kullanıcı Hakları:</strong> Kullanıcılar verilerine erişme, düzeltme veya silme hakkına sahiptir.</p>
          
          <h4 style="color: #39FF14; margin: 20px 0 10px;">3. İletişim</h4>
          <p>📧 dreamlocktr@gmail.com</p>
          
          <p style="margin-top: 20px; padding: 15px; background: rgba(57, 255, 20, 0.1); 
                    border-radius: 8px; border-left: 3px solid #39FF14;">
            <strong>Kayıt işlemini tamamlayarak, kişisel verilerinizin yukarıda açıklandığı şekilde toplanması, kullanılması ve üçüncü taraflarla paylaşılmasını kabul etmiş olursunuz.</strong>
          </p>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

// Close settings when clicking outside
document.getElementById('cookieSettingsModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeCookieSettings();
  }
});

// Escape key closes modals
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeCookieSettings();
    const privacyModal = document.querySelector('div[style*="z-index: 10003"]');
    if (privacyModal) {
      privacyModal.remove();
    }
  }
});
    // Loading Screen
    window.addEventListener('load', function() {
      const loadingOverlay = document.getElementById('loadingOverlay');
      setTimeout(() => {
        loadingOverlay.classList.add('hidden');
      }, 1000);
    });

    // Navbar Scroll Effect
    window.addEventListener('scroll', function() {
      const navbar = document.getElementById('navbar');
      const scrollTop = document.getElementById('scrollTop');
      
      if (window.scrollY > 100) {
        navbar.classList.add('scrolled');
        scrollTop.classList.add('visible');
      } else {
        navbar.classList.remove('scrolled');
        scrollTop.classList.remove('visible');
      }
    });

    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const navLinks = document.getElementById('navLinks');

    mobileMenuToggle.addEventListener('click', function() {
      navLinks.classList.toggle('active');
      const icon = this.querySelector('i');
      if (navLinks.classList.contains('active')) {
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-times');
      } else {
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
      }
    });

    // Smooth Scrolling for Navigation Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          const offsetTop = target.offsetTop - 80;
          window.scrollTo({
            top: offsetTop,
            behavior: 'smooth'
          });
          
          // Close mobile menu if open
          navLinks.classList.remove('active');
          const icon = mobileMenuToggle.querySelector('i');
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      });
    });

    // Intersection Observer for Fade-in Animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, observerOptions);

    // Observe all fade-in elements
    document.querySelectorAll('.fade-in').forEach(el => {
      observer.observe(el);
    });

    // Counter Animation for Stats
    function animateCounter(element, start, end, duration) {
      let startTime = null;
      const step = (timestamp) => {
        if (!startTime) startTime = timestamp;
        const progress = Math.min((timestamp - startTime) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = value.toLocaleString('en-US');
        if (progress < 1) {
          requestAnimationFrame(step);
        }
      };
      requestAnimationFrame(step);
    }

    // Stats Counter Observer
    const statsObserver = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const counters = entry.target.querySelectorAll('.stat-number');
          counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            animateCounter(counter, 0, target, 2000);
          });
          statsObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    const statsSection = document.getElementById('stats');
    if (statsSection) {
      statsObserver.observe(statsSection);
    }

    // Parallax Effect for Hero Background
    window.addEventListener('scroll', function() {
      const scrolled = window.pageYOffset;
      const parallax = document.querySelector('.hero');
      if (parallax) {
        const speed = scrolled * 0.5;
        parallax.style.transform = `translateY(${speed}px)`;
      }
    });

    // Enhanced Button Hover Effects
    document.querySelectorAll('.btn-primary, .btn-secondary').forEach(button => {
      button.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-3px) scale(1.05)';
      });
      
      button.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });

    // Dynamic Particle Animation
    function createDynamicParticle() {
      const particle = document.createElement('div');
      particle.className = 'particle';
      particle.style.width = Math.random() * 8 + 2 + 'px';
      particle.style.height = particle.style.width;
      particle.style.left = Math.random() * 100 + '%';
      particle.style.top = Math.random() * 100 + '%';
      particle.style.animationDelay = Math.random() * 8 + 's';
      particle.style.animationDuration = Math.random() * 10 + 8 + 's';
      
      document.querySelector('.bg-animation').appendChild(particle);
      
      // Remove particle after animation
      setTimeout(() => {
        if (particle.parentNode) {
          particle.parentNode.removeChild(particle);
        }
      }, 18000);
    }

    // Add new particles periodically
    setInterval(createDynamicParticle, 3000);

    // Enhanced Feature Card Interactions
    document.querySelectorAll('.feature-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        // Add glow effect to neighboring cards
        const cards = Array.from(document.querySelectorAll('.feature-card'));
        const currentIndex = cards.indexOf(this);
        
        cards.forEach((otherCard, index) => {
          if (Math.abs(index - currentIndex) === 1) {
            otherCard.style.boxShadow = '0 10px 30px rgba(57, 255, 20, 0.1)';
          }
        });
      });
      
      card.addEventListener('mouseleave', function() {
        document.querySelectorAll('.feature-card').forEach(otherCard => {
          otherCard.style.boxShadow = '';
        });
      });
    });

    // Easter Egg - Konami Code
    let konamiCode = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65];
    let konamiIndex = 0;

    document.addEventListener('keydown', function(e) {
      if (e.keyCode === konamiCode[konamiIndex]) {
        konamiIndex++;
        if (konamiIndex === konamiCode.length) {
          // Activate matrix mode
          document.body.style.filter = 'hue-rotate(120deg) contrast(1.2)';
          document.querySelectorAll('.particle').forEach(particle => {
            particle.style.background = 'radial-gradient(circle, #ff0066 0%, transparent 70%)';
          });
          
          setTimeout(() => {
            document.body.style.filter = 'none';
            document.querySelectorAll('.particle').forEach(particle => {
              particle.style.background = 'radial-gradient(circle, #39FF14 0%, transparent 70%)';
            });
          }, 5000);
          
          konamiIndex = 0;
        }
      } else {
        konamiIndex = 0;
      }
    });

    // Auto-hide mobile menu on scroll
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
      const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
      
      if (currentScroll > lastScrollTop && currentScroll > 100) {
        // Scrolling down
        if (navLinks.classList.contains('active')) {
          navLinks.classList.remove('active');
          const icon = mobileMenuToggle.querySelector('i');
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      }
      
      lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });

    // Enhanced Social Links Animation
    document.querySelectorAll('.social-link').forEach(link => {
      link.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) rotate(5deg)';
        this.style.boxShadow = '0 8px 25px rgba(57, 255, 20, 0.3)';
      });
      
      link.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) rotate(0deg)';
        this.style.boxShadow = '';
      });
    });

    // Performance Optimization - Debounce Scroll Events
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

    // Optimized scroll handler
    const optimizedScroll = debounce(() => {
      // Scroll-based animations go here
    }, 10);

    window.addEventListener('scroll', optimizedScroll);

    // Accessibility Improvements
    document.addEventListener('DOMContentLoaded', function() {
      // Add ARIA labels
      document.querySelector('#mobileMenuToggle').setAttribute('aria-label', 'Toggle mobile menu');
      document.querySelector('#scrollTop').setAttribute('aria-label', 'Scroll to top');
      
      // Add role attributes
      document.querySelector('.features').setAttribute('role', 'region');
      document.querySelector('.stats').setAttribute('role', 'region');
      
      // Focus management
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
          document.body.classList.add('keyboard-navigation');
        }
      });
      
      document.addEventListener('mousedown', function() {
        document.body.classList.remove('keyboard-navigation');
      });
    });

    // Add keyboard navigation styles
    const keyboardStyles = document.createElement('style');
    keyboardStyles.textContent = `
      .keyboard-navigation *:focus {
        outline: 2px solid #39FF14 !important;
        outline-offset: 2px !important;
      }
    `;
    document.head.appendChild(keyboardStyles);

    // Console Easter Egg

	 console.log(`    
                                      
████╗  ██║██║██╔════╝██╔══██╗   
██╔██╗ ██║██║███████╗███████║  
██║╚██╗██║██║╚════██║██╔══██║  
    `);
// Nisa Easter Egg
let nisaSequence = ['n', 'i', 's', 'a'];
let nisaIndex = 0;
let nisaActive = false;

document.addEventListener('keydown', function(e) {
  if (nisaActive) return;
  
  const key = e.key.toLowerCase();
  
  if (key === nisaSequence[nisaIndex]) {
    nisaIndex++;
    console.log(`Nisa sequence: ${nisaIndex}/${nisaSequence.length}`);
    
    if (nisaIndex === nisaSequence.length) {
      activateNisaEasterEgg();
      nisaIndex = 0;
    }
  } else {
    nisaIndex = 0;
  }
});

function activateNisaEasterEgg() {
  nisaActive = true;
  const easterEgg = document.getElementById('nisaEasterEgg');
  
  // Show the easter egg
  easterEgg.classList.add('show');
  
  // Create fireworks
  createFireworks();
  
  // Create floating hearts
  createFloatingHearts();
  
  // Play celebration sound (if you want to add sound later)
  console.log('🎉 NISA EASTER EGG ACTIVATED! 💖');
  
  // Hide after 5 seconds
  setTimeout(() => {
    easterEgg.classList.remove('show');
    nisaActive = false;
  }, 5000);
}

function createFireworks() {
  const colors = ['#4a90e2', '#5dade2', '#85c1e9', '#aed6f1'];
  
  for (let i = 0; i < 15; i++) {
    setTimeout(() => {
      for (let j = 0; j < 8; j++) {
        const firework = document.createElement('div');
        firework.className = 'firework';
        firework.style.background = colors[Math.floor(Math.random() * colors.length)];
        firework.style.left = Math.random() * 100 + '%';
        firework.style.top = Math.random() * 100 + '%';
        
        const angle = (j * 45) * (Math.PI / 180);
        const distance = 100 + Math.random() * 100;
        const x = Math.cos(angle) * distance;
        const y = Math.sin(angle) * distance;
        
        firework.style.setProperty('--end-x', x + 'px');
        firework.style.setProperty('--end-y', y + 'px');
        
        firework.style.animation = `
          fireworkExplode 2s ease-out forwards,
          fireworkMove 2s ease-out forwards
        `;
        
        document.getElementById('nisaEasterEgg').appendChild(firework);
        
        // Remove firework after animation
        setTimeout(() => {
          if (firework.parentNode) {
            firework.parentNode.removeChild(firework);
          }
        }, 2000);
      }
    }, i * 200);
  }
}

function createFloatingHearts() {
  const hearts = ['💔', '😢', '💧', '🌧️'];
  
  for (let i = 0; i < 20; i++) {
    setTimeout(() => {
      const heart = document.createElement('div');
      heart.className = 'heart-particle';
      heart.textContent = hearts[Math.floor(Math.random() * hearts.length)];
      heart.style.left = Math.random() * 100 + '%';
      heart.style.top = '100%';
      heart.style.animationDelay = Math.random() * 2 + 's';
      
      document.getElementById('nisaEasterEgg').appendChild(heart);
      
      // Remove heart after animation
      setTimeout(() => {
        if (heart.parentNode) {
          heart.parentNode.removeChild(heart);
        }
      }, 3000);
    }, i * 100);
  }
}
	  function createRainDrops() {
  for (let i = 0; i < 50; i++) {
    setTimeout(() => {
      const drop = document.createElement('div');
      drop.className = 'rain-drop';
      drop.style.left = Math.random() * 100 + '%';
      drop.style.animationDelay = Math.random() * 2 + 's';
      document.getElementById('nisaEasterEgg').appendChild(drop);
      
      setTimeout(() => {
        if (drop.parentNode) drop.parentNode.removeChild(drop);
      }, 1500);
    }, i * 50);
  }
}

// Additional CSS animation for firework movement
const nisaStyles = document.createElement('style');
nisaStyles.textContent = `
  @keyframes fireworkMove {
    0% { transform: translate(0, 0); }
    100% { transform: translate(var(--end-x, 0), var(--end-y, 0)); }
  }
`;
document.head.appendChild(nisaStyles);
    // Performance Monitor
    if ('performance' in window) {
      window.addEventListener('load', function() {
        setTimeout(() => {
          const perfData = performance.timing;
          const loadTime = perfData.loadEventEnd - perfData.navigationStart;
          console.log(`Page load time: ${loadTime}ms`);
        }, 0);
      });
    }

    // WebGL Support Check for Future Features
    function checkWebGLSupport() {
      try {
        const canvas = document.createElement('canvas');
        return !!(canvas.getContext('webgl') || canvas.getContext('experimental-webgl'));
      } catch (e) {
        return false;
      }
    }

    if (checkWebGLSupport()) {
      console.log('WebGL supported - Ready for future 3D effects! 🚀');
    }

    // Service Worker Registration (for future PWA features)
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        // Service worker will be registered here in the future
        console.log('PWA support ready! 📱');
      });
    }

    // 🎉 EASTER EGG: "irem" Konami Code (Mavi Tema)
    let iremCode = [];
    const iremSequence = ['i', 'r', 'e', 'm'];
    
    document.addEventListener('keydown', function(e) {
      iremCode.push(e.key.toLowerCase());
      
      // Keep only the last 4 keys
      if (iremCode.length > 4) {
        iremCode.shift();
      }
      
      // Check if the sequence matches "irem"
      if (iremCode.join('') === 'irem') {
        triggerIremEasterEgg();
        iremCode = []; // Reset the sequence
      }
    });
    
    function triggerIremEasterEgg() {
      // Create the main easter egg container
      const easterEgg = document.createElement('div');
      easterEgg.id = 'irem-easter-egg';
      easterEgg.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 100000;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Manrope', sans-serif;
        background: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(10px);
      `;
      
      // Create the main text
      const mainText = document.createElement('div');
      mainText.textContent = 'irem <3';
      mainText.style.cssText = `
        font-size: 8rem;
        font-weight: 900;
        color: #4a90e2;
        text-shadow: 
          0 0 20px #4a90e2,
          0 0 40px #4a90e2,
          0 0 60px #4a90e2,
          0 0 80px #4a90e2;
        animation: iremGlow 2s ease-in-out infinite alternate;
        position: relative;
        z-index: 100001;
      `;
      
      // Add CSS animation for the glow effect
      const style = document.createElement('style');
      style.textContent = `
        @keyframes iremGlow {
          0% {
            text-shadow: 
              0 0 20px #4a90e2,
              0 0 40px #4a90e2,
              0 0 60px #4a90e2,
              0 0 80px #4a90e2;
            transform: scale(1) rotate(0deg);
          }
          100% {
            text-shadow: 
              0 0 30px #1e90ff,
              0 0 60px #1e90ff,
              0 0 90px #1e90ff,
              0 0 120px #1e90ff;
            transform: scale(1.1) rotate(2deg);
          }
        }
        
        @keyframes blueFirework {
          0% {
            transform: translateY(0) scale(0);
            opacity: 1;
          }
          100% {
            transform: translateY(-100vh) scale(1);
            opacity: 0;
          }
        }
        
        @keyframes blueHeartBeat {
          0%, 100% { transform: scale(1); }
          50% { transform: scale(1.2); }
        }
        
        @keyframes blueSparkle {
          0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
          50% { opacity: 1; transform: scale(1) rotate(180deg); }
        }
        
        @keyframes blueRain {
          0% { transform: translateY(-100vh); opacity: 1; }
          100% { transform: translateY(100vh); opacity: 0; }
        }
      `;
      
      document.head.appendChild(style);
      easterEgg.appendChild(mainText);
      document.body.appendChild(easterEgg);
      
      // Create blue fireworks
      createBlueFireworks();
      
      // Create floating blue hearts
      createBlueHearts();
      
      // Create blue sparkles
      createBlueSparkles();
      
      // Create blue rain
      createBlueRain();
      
      // Add sound effect (optional - browser might block autoplay)
      try {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
        audio.volume = 0.3;
        audio.play().catch(() => {}); // Ignore autoplay errors
      } catch (e) {}
      
      // Remove easter egg after 5 seconds
      setTimeout(() => {
        if (easterEgg.parentNode) {
          easterEgg.parentNode.removeChild(easterEgg);
        }
      }, 5000);
    }
    
    function createBlueFireworks() {
      const colors = ['#4a90e2', '#1e90ff', '#87ceeb', '#00bfff', '#4169e1'];
      
      for (let i = 0; i < 20; i++) {
        setTimeout(() => {
          const firework = document.createElement('div');
          firework.style.cssText = `
            position: fixed;
            left: ${Math.random() * 100}%;
            bottom: 0;
            width: 4px;
            height: 4px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            border-radius: 50%;
            animation: blueFirework ${2 + Math.random() * 2}s ease-out forwards;
            z-index: 99998;
          `;
          
          document.body.appendChild(firework);
          
          // Remove firework after animation
          setTimeout(() => {
            if (firework.parentNode) {
              firework.parentNode.removeChild(firework);
            }
          }, 4000);
        }, i * 100);
      }
    }
    
    function createBlueHearts() {
      const hearts = ['💙', '💎', '💧', '🌊', '💠', '🔷'];
      
      for (let i = 0; i < 15; i++) {
        setTimeout(() => {
          const heart = document.createElement('div');
          heart.textContent = hearts[Math.floor(Math.random() * hearts.length)];
          heart.style.cssText = `
            position: fixed;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
            font-size: ${2 + Math.random() * 3}rem;
            animation: blueHeartBeat ${1 + Math.random()}s ease-in-out infinite;
            z-index: 99997;
            pointer-events: none;
          `;
          
          document.body.appendChild(heart);
          
          // Remove heart after 5 seconds
          setTimeout(() => {
            if (heart.parentNode) {
              heart.parentNode.removeChild(heart);
            }
          }, 5000);
        }, i * 200);
      }
    }
    
    function createBlueSparkles() {
      const sparkles = ['✨', '💫', '⭐', '🌟', '💎', '💍'];
      
      for (let i = 0; i < 25; i++) {
        setTimeout(() => {
          const sparkle = document.createElement('div');
          sparkle.textContent = sparkles[Math.floor(Math.random() * sparkles.length)];
          sparkle.style.cssText = `
            position: fixed;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
            font-size: ${1 + Math.random() * 2}rem;
            animation: blueSparkle ${2 + Math.random() * 2}s ease-in-out infinite;
            z-index: 99996;
            pointer-events: none;
            color: #4a90e2;
          `;
          
          document.body.appendChild(sparkle);
          
          // Remove sparkle after 5 seconds
          setTimeout(() => {
            if (sparkle.parentNode) {
              sparkle.parentNode.removeChild(sparkle);
            }
          }, 5000);
        }, i * 150);
      }
    }
    
    function createBlueRain() {
      for (let i = 0; i < 50; i++) {
        setTimeout(() => {
          const drop = document.createElement('div');
          drop.style.cssText = `
            position: fixed;
            left: ${Math.random() * 100}%;
            top: -10px;
            width: 2px;
            height: 20px;
            background: linear-gradient(to bottom, #4a90e2, #1e90ff);
            border-radius: 1px;
            animation: blueRain ${1 + Math.random()}s linear infinite;
            z-index: 99995;
            pointer-events: none;
          `;
          
          document.body.appendChild(drop);
          
          // Remove drop after animation
          setTimeout(() => {
            if (drop.parentNode) {
              drop.parentNode.removeChild(drop);
            }
          }, 2000);
        }, i * 50);
      }
    }
  </script>
	<!-- Nisa Easter Egg -->
<div class="nisa-easter-egg" id="nisaEasterEgg">
  <div class="nisa-text">NİSA 💔</div>
</div>
</body>
</html>