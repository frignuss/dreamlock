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
    'welcome' => 'Welcome',  
    'subconscious_analysis' => 'Subconscious Mind Analysis',
    'analyze_subconscious' => 'Analyze My Subconscious',
    'analyzing' => 'Analyzing Your Subconscious...',
    'dreams_required' => 'dreams required for analysis',
    'insufficient_dreams' => 'You need at least 5 dreams to perform subconscious analysis.',
    'add_more_dreams' => 'Add More Dreams',
    'analysis_complete' => 'Analysis Complete',
    'your_analysis' => 'Your Subconscious Analysis',
    'based_on_dreams' => 'Based on your',
    'dreams_text' => 'dreams',
    'home' => 'Home',
    'dreams' => 'Dreams',
    'logout' => 'Log Out',
    'analysis_error' => 'Analysis could not be completed. Please try again.',
    'personality_traits' => 'Personality Traits',
    'emotional_patterns' => 'Emotional Patterns',
    'recurring_themes' => 'Recurring Themes',
    'subconscious_insights' => 'Subconscious Insights',
    'recommendations' => 'Recommendations',
    'dream_symbols' => 'Common Dream Symbols',
    'new_analysis' => 'Generate New Analysis',
    'last_analysis' => 'Last Analysis',
    'no_analysis' => 'No subconscious analysis has been performed yet.',
    'perform_analysis' => 'Perform your first subconscious analysis to unlock the mysteries of your inner mind.',
    'analysis_date' => 'Analysis Date',
    'save_error' => 'Analysis could not be saved. Please try again.',
    'load_error' => 'Error loading analysis. Please try again later.',
    'subconscious' => 'SUBCONSCIOUS',
    'sleep_analysis' => 'SLEEP ANALYSIS'
  ],
  'tr' => [
    'welcome' => 'Hoş Geldiniz',
    'subconscious_analysis' => 'Bilinçaltı Analizi',
    'analyze_subconscious' => 'Bilinçaltımı Analiz Et',
    'analyzing' => 'Bilinçaltınız Analiz Ediliyor...',
    'dreams_required' => 'rüya gerekli',
    'insufficient_dreams' => 'Bilinçaltı analizi için en az 5 rüya gereklidir.',
    'add_more_dreams' => 'Daha Fazla Rüya Ekle',
    'analysis_complete' => 'Analiz Tamamlandı',
    'your_analysis' => 'Bilinçaltı Analiziniz',
    'based_on_dreams' => 'Girdiğiniz',
    'dreams_text' => 'rüya temel alınarak',
    'home' => 'Ana Sayfa',
    'dreams' => 'Rüyalar',
    'logout' => 'Çıkış Yap',
    'analysis_error' => 'Analiz tamamlanamadı. Lütfen tekrar deneyin.',
    'personality_traits' => 'Kişilik Özellikleri',
    'emotional_patterns' => 'Duygusal Desenler',
    'recurring_themes' => 'Tekrarlayan Temalar',
    'subconscious_insights' => 'Bilinçaltı İçgörüleri',
    'recommendations' => 'Öneriler',
    'dream_symbols' => 'Ortak Rüya Sembolleri',
    'new_analysis' => 'Yeni Analiz Oluştur',
    'last_analysis' => 'Son Analiz',
    'no_analysis' => 'Henüz hiç bilinçaltı analizi yapılmamış.',
    'perform_analysis' => 'İç dünyanızın gizemlerini açığa çıkarmak için ilk bilinçaltı analizinizi yapın.',
    'analysis_date' => 'Analiz Tarihi',
    'save_error' => 'Analiz kaydedilemedi. Lütfen tekrar deneyin.',
    'load_error' => 'Analiz yüklenirken hata oluştu. Lütfen daha sonra tekrar deneyin.',
    'subconscious' => 'BİLİNÇALTI',
    'sleep_analysis' => 'UYKU ANALİZİ'
  ],
  'es' => [
    'welcome' => 'Bienvenido',
    'subconscious_analysis' => 'Análisis del Subconsciente',
    'analyze_subconscious' => 'Analizar Mi Subconsciente',
    'analyzing' => 'Analizando Tu Subconsciente...',
    'dreams_required' => 'sueños requeridos para análisis',
    'insufficient_dreams' => 'Necesitas al menos 5 sueños para realizar análisis del subconsciente.',
    'add_more_dreams' => 'Agregar Más Sueños',
    'analysis_complete' => 'Análisis Completo',
    'your_analysis' => 'Tu Análisis del Subconsciente',
    'based_on_dreams' => 'Basado en tus',
    'dreams_text' => 'sueños',
    'home' => 'Inicio',
    'dreams' => 'Sueños',
    'logout' => 'Cerrar Sesión',
    'analysis_error' => 'El análisis no pudo completarse. Inténtalo de nuevo.',
    'personality_traits' => 'Rasgos de Personalidad',
    'emotional_patterns' => 'Patrones Emocionales',
    'recurring_themes' => 'Temas Recurrentes',
    'subconscious_insights' => 'Percepciones del Subconsciente',
    'recommendations' => 'Recomendaciones',
    'dream_symbols' => 'Símbolos Comunes de Sueños',
    'new_analysis' => 'Generar Nuevo Análisis',
    'last_analysis' => 'Último Análisis',
    'no_analysis' => 'Aún no se ha realizado ningún análisis del subconsciente.',
    'perform_analysis' => 'Realiza tu primer análisis del subconsciente para desbloquear los misterios de tu mente interior.',
    'analysis_date' => 'Fecha de Análisis',
    'save_error' => 'El análisis no pudo guardarse. Inténtalo de nuevo.',
    'load_error' => 'Error al cargar el análisis. Inténtalo más tarde.',
    'subconscious' => 'SUBCONSCIENTE',
    'sleep_analysis' => 'ANÁLISIS DEL SUEÑO'
  ],
  'fr' => [
    'welcome' => 'Bienvenue',
    'subconscious_analysis' => 'Analyse du Subconscient',
    'analyze_subconscious' => 'Analyser Mon Subconscient',
    'analyzing' => 'Analyse de Votre Subconscient...',
    'dreams_required' => 'rêves requis pour l\'analyse',
    'insufficient_dreams' => 'Vous avez besoin d\'au moins 5 rêves pour effectuer une analyse du subconscient.',
    'add_more_dreams' => 'Ajouter Plus de Rêves',
    'analysis_complete' => 'Analyse Terminée',
    'your_analysis' => 'Votre Analyse du Subconscient',
    'based_on_dreams' => 'Basé sur vos',
    'dreams_text' => 'rêves',
    'home' => 'Accueil',
    'dreams' => 'Rêves',
    'logout' => 'Se Déconnecter',
    'analysis_error' => 'L\'analyse n\'a pas pu être complétée. Veuillez réessayer.',
    'personality_traits' => 'Traits de Personnalité',
    'emotional_patterns' => 'Modèles Émotionnels',
    'recurring_themes' => 'Thèmes Récurrents',
    'subconscious_insights' => 'Aperçus du Subconscient',
    'recommendations' => 'Recommandations',
    'dream_symbols' => 'Symboles de Rêve Communs',
    'new_analysis' => 'Générer une Nouvelle Analyse',
    'last_analysis' => 'Dernière Analyse',
    'no_analysis' => 'Aucune analyse du subconscient n\'a encore été effectuée.',
    'perform_analysis' => 'Effectuez votre première analyse du subconscient pour débloquer les mystères de votre esprit intérieur.',
    'analysis_date' => 'Date d\'Analyse',
    'save_error' => 'L\'analyse n\'a pas pu être sauvegardée. Veuillez réessayer.',
    'load_error' => 'Erreur lors du chargement de l\'analyse. Veuillez réessayer plus tard.',
    'subconscious' => 'SUBCONSCIENT',
    'sleep_analysis' => 'ANALYSE DU SOMMEIL'
  ]
];

$t = $translations[$lang] ?? $translations['en'];

// Database connection
$db = new PDO('mysql:host=localhost;dbname=dreamlock;charset=utf8', 'root', '');

// Get valid user ID function
function getValidUserId($db) {
    if (isset($_SESSION['user_id'])) {
        $stmt = $db->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        if ($stmt->fetch()) {
            return $_SESSION['user_id'];
        }
    }
    
    if (isset($_SESSION['username'])) {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            return $user['id'];
        }
    }
    
    session_destroy();
    header("Location: login.php?error=invalid_session");
    exit();
}

$current_user_id = getValidUserId($db);

// Logout functionality
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: index.php");
  exit();
}

// Get user's dream count
$stmt = $db->prepare("SELECT COUNT(*) as dream_count FROM dreams WHERE user_id = ?");
$stmt->execute([$current_user_id]);
$dream_count = $stmt->fetch(PDO::FETCH_ASSOC)['dream_count'];

// Process subconscious analysis
$analysis_result = null;
$is_analyzing = false;
$error_message = null;

if (isset($_POST['analyze_subconscious']) && $dream_count >= 5) {
    $is_analyzing = true;
    
    // Get all user dreams
    $stmt = $db->prepare("SELECT dream_text, analysis FROM dreams WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$current_user_id]);
    $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Prepare dreams for analysis
    $dream_texts = array_map(function($dream) { return $dream['dream_text']; }, $dreams);
    $dream_analyses = array_map(function($dream) { return $dream['analysis']; }, $dreams);
    
    $combined_dreams = implode("\n\n---DREAM SEPARATOR---\n\n", $dream_texts);
    $combined_analyses = implode("\n\n", $dream_analyses);
    
    // Multi-language analysis prompts
    $analysis_prompts = [
        'en' => "Analyze the subconscious mind based on these dreams and their analyses. Provide a comprehensive psychological profile with the following sections:

1. PERSONALITY TRAITS: Core personality characteristics
2. EMOTIONAL PATTERNS: Recurring emotional themes and states
3. RECURRING THEMES: Common symbols, situations, and narratives
4. SUBCONSCIOUS INSIGHTS: Deep psychological insights and hidden meanings
5. RECOMMENDATIONS: Practical advice for personal growth and self-understanding
6. DREAM SYMBOLS: Most significant symbols and their meanings

Dreams and Analyses:
{$combined_dreams}

Previous Analyses:
{$combined_analyses}

Please provide a detailed, professional psychological analysis in clear sections.",
        'tr' => "Bu rüyalar ve analizler temel alınarak bilinçaltını analiz et. Aşağıdaki bölümlerle kapsamlı bir psikolojik profil sun:

1. KİŞİLİK ÖZELLİKLERİ: Temel kişilik karakteristikleri
2. DUYGUSAL DESENLER: Tekrarlayan duygusal temalar ve durumlar
3. TEKRARLAYAN TEMALAR: Ortak semboller, durumlar ve anlatılar
4. BİLİNÇALTI İÇGÖRÜLERİ: Derin psikolojik içgörüler ve gizli anlamlar
5. ÖNERİLER: Kişisel gelişim ve öz anlayış için pratik tavsiyeler
6. RÜYA SEMBOLLERİ: En önemli semboller ve anlamları

Rüyalar ve Analizler:
{$combined_dreams}

Önceki Analizler:
{$combined_analyses}

Lütfen net bölümlerle detaylı, profesyonel psikolojik analiz yap.",
        'es' => "Analiza el subconsciente basándote en estos sueños y sus análisis. Proporciona un perfil psicológico integral con las siguientes secciones:

1. RASGOS DE PERSONALIDAD: Características centrales de personalidad
2. PATRONES EMOCIONALES: Temas y estados emocionales recurrentes
3. TEMAS RECURRENTES: Símbolos, situaciones y narrativas comunes
4. PERCEPCIONES DEL SUBCONSCIENTE: Insights psicológicos profundos y significados ocultos
5. RECOMENDACIONES: Consejos prácticos para crecimiento personal y autocomprensión
6. SÍMBOLOS DE SUEÑOS: Símbolos más significativos y sus significados

Sueños y Análisis:
{$combined_dreams}

Análisis Previos:
{$combined_analyses}

Por favor proporciona un análisis psicológico detallado y profesional en secciones claras.",
        'fr' => "Analysez le subconscient basé sur ces rêves et leurs analyses. Fournissez un profil psychologique complet avec les sections suivantes:

1. TRAITS DE PERSONNALITÉ: Caractéristiques centrales de personnalité
2. MODÈLES ÉMOTIONNELS: Thèmes et états émotionnels récurrents
3. THÈMES RÉCURRENTS: Symboles, situations et narratifs communs
4. APERÇUS DU SUBCONSCIENT: Insights psychologiques profonds et significations cachées
5. RECOMMANDATIONS: Conseils pratiques pour croissance personnelle et compréhension de soi
6. SYMBOLES DE RÊVE: Symboles les plus significatifs et leurs significations

Rêves et Analyses:
{$combined_dreams}

Analyses Précédentes:
{$combined_analyses}

Veuillez fournir une analyse psychologique détaillée et professionnelle en sections claires."
    ];
    
    $system_prompts = [
        'en' => 'You are a professional psychologist and dream analyst specializing in subconscious mind analysis. Provide detailed, insightful, and professional analysis.',
        'tr' => 'Sen bilinçaltı analizi konusunda uzman profesyonel bir psikolog ve rüya analistisin. Detaylı, içgörülü ve profesyonel analiz yap.',
        'es' => 'Eres un psicólogo profesional y analista de sueños especializado en análisis del subconsciente. Proporciona análisis detallado, perspicaz y profesional.',
        'fr' => 'Vous êtes un psychologue professionnel et analyste de rêves spécialisé dans l\'analyse du subconscient. Fournissez une analyse détaillée, perspicace et professionnelle.'
    ];

    $prompt = $analysis_prompts[$lang] ?? $analysis_prompts['en'];

    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENROUTER_API_KEY,
        'HTTP-Referer: http://localhost',
        'X-Title: DreamLock Subconscious Analyzer'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'deepseek/deepseek-chat-v3-0324',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompts[$lang] ?? $system_prompts['en']],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.8,
        'max_tokens' => 2000
    ]));

    $response = curl_exec($ch);
    
    if (curl_error($ch)) {
        $analysis_result = $t['analysis_error'];
        $error_message = $t['analysis_error'];
    } else {
        $result = json_decode($response, true);
        $analysis_result = $result['choices'][0]['message']['content'] ?? $t['analysis_error'];
        
        // Save analysis to database
        try {
            // Create table if not exists
            $db->exec("CREATE TABLE IF NOT EXISTS subconscious_analyses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                analysis_text LONGTEXT NOT NULL,
                dream_count INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
            
            $stmt = $db->prepare("INSERT INTO subconscious_analyses (user_id, analysis_text, dream_count) VALUES (?, ?, ?)");
            $stmt->execute([$current_user_id, $analysis_result, $dream_count]);
        } catch (PDOException $e) {
            error_log("Subconscious analysis save error: " . $e->getMessage());
            $error_message = $t['save_error'];
        }
    }
    
    curl_close($ch);
}

// Get latest analysis
$latest_analysis = null;
try {
    $stmt = $db->prepare("SELECT analysis_text, dream_count, created_at FROM subconscious_analyses WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$current_user_id]);
    $latest_analysis = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Analysis fetch error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="assets/logo.png" type="image/x-icon">

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DreamLock - Subconscious Analysis</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <style>
    :root {
      --primary-orange: #FF8C00;
      --secondary-orange: #FFA500;
      --dark-bg: #0a0a0a;
      --card-bg: #1a1a1a;
      --text-light: #ffffff;
      --text-muted: #888888;
      --border-color: #2a2a2a;
      --success-bg: #2a1f0f;
      --success-text: #ffd700;
      --danger-bg: #2a0f0f;
      --danger-text: #ff6b6b;
      --warning-bg: #2a1f0f;
      --warning-text: #ffd700;
      --info-bg: #2a1f0f;
      --info-text: #ffd700;
    }
    
    * { 
      font-family: 'Inter', 'Manrope', sans-serif; 
      font-weight: 400;
    }
    
    /* ANIMATED ORANGE BACKGROUND SYSTEM - START */
    
    /* Main Animated Background */
    .animated-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -10;
      background: 
          radial-gradient(circle at 20% 30%, rgba(255, 140, 0, 0.08) 0%, transparent 50%),
          radial-gradient(circle at 80% 70%, rgba(255, 165, 0, 0.06) 0%, transparent 45%),
          radial-gradient(circle at 60% 20%, rgba(255, 140, 0, 0.04) 0%, transparent 60%),
          radial-gradient(circle at 30% 80%, rgba(255, 165, 0, 0.05) 0%, transparent 55%),
          linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #0f0f0f 100%);
      animation: backgroundPulse 12s ease-in-out infinite alternate;
    }

    @keyframes backgroundPulse {
      0% {
          filter: brightness(1) contrast(1) hue-rotate(0deg);
          opacity: 1;
      }
      100% {
          filter: brightness(1.02) contrast(1.01) hue-rotate(1deg);
          opacity: 1;
      }
    }

    /* Animated Gradient Overlay */
    .gradient-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -9;
      background: linear-gradient(45deg, 
          rgba(255, 140, 0, 0.1) 0%, 
          rgba(255, 165, 0, 0.05) 25%, 
          rgba(255, 140, 0, 0.08) 50%, 
          rgba(255, 165, 0, 0.03) 75%, 
          rgba(255, 140, 0, 0.1) 100%);
      animation: gradientShift 8s ease-in-out infinite;
    }

    @keyframes gradientShift {
      0%, 100% {
          transform: scale(1) rotate(0deg);
          opacity: 0.4;
      }
      50% {
          transform: scale(1.02) rotate(45deg);
          opacity: 0.6;
      }
    }

    /* Floating Geometric Shapes */
    .geometric-shape {
      position: fixed;
      pointer-events: none;
      z-index: -8;
      animation: floatShape 15s ease-in-out infinite;
    }

    .shape-1 {
      width: 80px;
      height: 80px;
      background: linear-gradient(45deg, rgba(255, 140, 0, 0.2), rgba(255, 165, 0, 0.1));
      clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
      top: 15%;
      left: 10%;
      animation-delay: -2s;
      animation-duration: 18s;
    }

    .shape-2 {
      width: 60px;
      height: 60px;
      background: linear-gradient(45deg, rgba(255, 165, 0, 0.15), rgba(255, 140, 0, 0.08));
      clip-path: polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%);
      top: 60%;
      right: 15%;
      animation-delay: -5s;
      animation-duration: 20s;
    }

    .shape-3 {
      width: 100px;
      height: 100px;
      background: linear-gradient(45deg, rgba(255, 140, 0, 0.12), rgba(255, 165, 0, 0.06));
      clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
      bottom: 25%;
      left: 20%;
      animation-delay: -8s;
      animation-duration: 16s;
    }

    .shape-4 {
      width: 70px;
      height: 70px;
      background: linear-gradient(45deg, rgba(255, 165, 0, 0.18), rgba(255, 140, 0, 0.09));
      clip-path: polygon(0% 0%, 100% 0%, 50% 100%);
      top: 35%;
      right: 30%;
      animation-delay: -3s;
      animation-duration: 22s;
    }

    @keyframes floatShape {
      0%, 100% {
          transform: translateY(0px) translateX(0px) rotate(0deg) scale(1);
          opacity: 0.3;
      }
      25% {
          transform: translateY(-15px) translateX(10px) rotate(30deg) scale(1.02);
          opacity: 0.4;
      }
      50% {
          transform: translateY(-8px) translateX(-8px) rotate(60deg) scale(0.98);
          opacity: 0.35;
      }
      75% {
          transform: translateY(-18px) translateX(8px) rotate(90deg) scale(1.01);
          opacity: 0.45;
      }
    }

    /* Glowing Orbs */
    .glow-orb {
      position: fixed;
      border-radius: 50%;
      pointer-events: none;
      z-index: -7;
      animation: glowPulse 6s ease-in-out infinite;
    }

    .orb-1 {
      width: 120px;
      height: 120px;
      background: radial-gradient(circle, rgba(255, 140, 0, 0.15) 0%, rgba(255, 140, 0, 0.02) 70%, transparent 100%);
      top: 10%;
      left: 25%;
      animation-delay: -1s;
    }

    .orb-2 {
      width: 90px;
      height: 90px;
      background: radial-gradient(circle, rgba(255, 165, 0, 0.12) 0%, rgba(255, 165, 0, 0.01) 70%, transparent 100%);
      top: 70%;
      right: 20%;
      animation-delay: -3s;
    }

    .orb-3 {
      width: 110px;
      height: 110px;
      background: radial-gradient(circle, rgba(255, 140, 0, 0.1) 0%, rgba(255, 140, 0, 0.03) 70%, transparent 100%);
      bottom: 15%;
      left: 35%;
      animation-delay: -5s;
    }

    @keyframes glowPulse {
      0%, 100% {
          transform: scale(1);
          opacity: 0.3;
          filter: blur(0.5px);
      }
      50% {
          transform: scale(1.05);
          opacity: 0.5;
          filter: blur(1px);
      }
    }

    /* Animated Grid */
    .grid-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -6;
      background-image: 
          linear-gradient(rgba(255, 140, 0, 0.03) 1px, transparent 1px),
          linear-gradient(90deg, rgba(255, 140, 0, 0.03) 1px, transparent 1px);
      background-size: 80px 80px;
      animation: gridMove 12s linear infinite;
      opacity: 0.1;
    }

    @keyframes gridMove {
      0% {
          transform: translateX(0) translateY(0);
      }
      100% {
          transform: translateX(80px) translateY(80px);
      }
    }

    /* Light Rays */
    .light-ray {
      position: fixed;
      width: 3px;
      height: 100%;
      background: linear-gradient(to bottom, 
          transparent 0%, 
          rgba(255, 140, 0, 0.1) 30%, 
          rgba(255, 165, 0, 0.05) 50%, 
          rgba(255, 140, 0, 0.1) 70%, 
          transparent 100%);
      pointer-events: none;
      z-index: -5;
      animation: raySweep 20s linear infinite;
      filter: blur(1px);
    }

    .ray-1 {
      left: 15%;
      animation-delay: -2s;
      animation-duration: 25s;
    }

    .ray-2 {
      left: 40%;
      animation-delay: -8s;
      animation-duration: 30s;
    }

    .ray-3 {
      right: 25%;
      animation-delay: -5s;
      animation-duration: 22s;
    }

    .ray-4 {
      right: 50%;
      animation-delay: -12s;
      animation-duration: 28s;
    }

    @keyframes raySweep {
      0% {
          opacity: 0;
          transform: translateY(-100%) skewX(-15deg);
      }
      10% {
          opacity: 1;
      }
      90% {
          opacity: 1;
      }
      100% {
          opacity: 0;
          transform: translateY(100%) skewX(-15deg);
      }
    }

    /* Floating Dots */
    .floating-dot {
      position: fixed;
      width: 4px;
      height: 4px;
      background: #FF8C00;
      border-radius: 50%;
      pointer-events: none;
      z-index: -4;
      box-shadow: 0 0 8px rgba(255, 140, 0, 0.6);
      animation: floatDot 10s ease-in-out infinite;
    }

    .dot-1 {
      top: 20%;
      left: 15%;
      animation-delay: -1s;
      animation-duration: 12s;
    }

    .dot-2 {
      top: 65%;
      right: 25%;
      animation-delay: -4s;
      animation-duration: 15s;
    }

    .dot-3 {
      bottom: 30%;
      left: 30%;
      animation-delay: -7s;
      animation-duration: 18s;
    }

    .dot-4 {
      top: 45%;
      right: 40%;
      animation-delay: -2s;
      animation-duration: 14s;
    }

    @keyframes floatDot {
      0%, 100% {
          transform: translateY(0px) translateX(0px) scale(1);
          opacity: 0.5;
      }
      25% {
          transform: translateY(-25px) translateX(15px) scale(1.3);
          opacity: 1;
      }
      50% {
          transform: translateY(-15px) translateX(-20px) scale(0.8);
          opacity: 0.7;
      }
      75% {
          transform: translateY(-35px) translateX(10px) scale(1.1);
          opacity: 0.9;
      }
    }

    /* Animated Waves */
    .wave-container {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 200px;
      pointer-events: none;
      z-index: -3;
      overflow: hidden;
    }

    .wave {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 200%;
      height: 100%;
      background: linear-gradient(45deg, 
          rgba(255, 140, 0, 0.1) 0%, 
          rgba(255, 165, 0, 0.05) 50%, 
          rgba(255, 140, 0, 0.1) 100%);
      border-radius: 50% 50% 0 0;
      animation: waveMove 15s linear infinite;
    }

    .wave:nth-child(2) {
      animation-delay: -5s;
      animation-duration: 20s;
      opacity: 0.6;
    }

    .wave:nth-child(3) {
      animation-delay: -10s;
      animation-duration: 25s;
      opacity: 0.4;
    }

    @keyframes waveMove {
      0% {
          transform: translateX(-50%) translateY(0);
      }
      100% {
          transform: translateX(0%) translateY(-20px);
      }
    }

    /* ANIMATED ORANGE BACKGROUND SYSTEM - END */
    
    /* Loading Screen */
    #loading-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    #loading-screen.hidden {
      opacity: 0;
      visibility: hidden;
      transform: scale(1.1);
    }
    
    .loading-content {
      text-align: center;
      color: var(--text-light);
    }
    
    .loading-spinner {
      width: 60px;
      height: 60px;
      border: 3px solid rgba(255, 140, 0, 0.1);
      border-top: 3px solid var(--primary-orange);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 30px auto;
      box-shadow: 0 0 20px rgba(255, 140, 0, 0.3);
    }
    
    .loading-text {
      font-size: 24px;
      font-weight: 800;
      color: var(--primary-orange);
      letter-spacing: 4px;
      text-shadow: 0 0 10px rgba(255, 140, 0, 0.5);
      font-family: 'Manrope', sans-serif;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    body {
      margin: 0;
      padding-top: 100px;
      padding-bottom: 100px; /* Add padding for bottom navigation */
      color: var(--text-light);
      background: transparent;
      min-height: 100vh;
      overflow-x: hidden;
    }
    
    /* Top Navbar */
    .top-navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(10, 10, 10, 0.95);
      backdrop-filter: blur(20px);
      padding: 15px 0;
      z-index: 1000;
      border-bottom: 1px solid rgba(255, 140, 0, 0.1);
    }

    .top-navbar .container-fluid {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .brand-logo {
      font-family: 'Manrope', sans-serif;
      font-size: 24px;
      font-weight: 800;
      color: var(--text-light);
      text-decoration: none;
    }

    .brand-logo .dream {
      color: rgba(255, 255, 255, 0.75);
    }

    .brand-logo .lock {
      color: var(--primary-orange);
    }

    .premium-btn {
      background: linear-gradient(135deg, #FFD700, #FFA500);
      color: #000;
      padding: 8px 20px;
      border-radius: 20px;
      text-decoration: none;
      font-weight: 700;
      font-size: 12px;
      letter-spacing: 1px;
      transition: all 0.3s ease;
    }

    .premium-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
      color: #000;
      text-decoration: none;
    }

    .language-select {
      background: transparent;
      color: var(--text-light);
      border: 1px solid rgba(255, 140, 0, 0.3);
      border-radius: 8px;
      padding: 5px 10px;
      font-size: 12px;
    }

    .nav-link {
      color: var(--text-light) !important;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 10px;
      transition: all 0.3s ease;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .nav-link:hover {
      color: var(--primary-orange) !important;
      background: rgba(255, 140, 0, 0.1);
      transform: translateY(-2px);
    }

    .nav-link.active {
      color: var(--primary-orange) !important;
      background: rgba(255, 140, 0, 0.15);
      border: 1px solid rgba(255, 140, 0, 0.3);
    }

    /* MODERN MOBILE BOTTOM NAVIGATION */
    .bottom-navigation {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 80px;
      background: rgba(20, 20, 20, 0.98);
      backdrop-filter: blur(25px);
      border-top: 2px solid rgba(255, 140, 0, 0.2);
      box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.5);
      z-index: 10000;
      display: flex;
      align-items: center;
      justify-content: space-around;
      animation: slideUpNav 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideUpNav {
      from { 
        opacity: 0;
        transform: translateY(100%); 
      }
      to { 
        opacity: 1;
        transform: translateY(0); 
      }
    }

    .nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 8px 12px;
      text-decoration: none;
      color: var(--text-muted);
      border-radius: 20px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      min-width: 60px;
      height: 55px;
      overflow: hidden;
    }

    .nav-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
      border-radius: 20px;
      opacity: 0;
      transform: scale(0.3);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: -1;
    }

    .nav-item:hover::before {
      opacity: 1;
      transform: scale(1);
    }

    .nav-item:hover {
      color: var(--dark-bg);
      transform: translateY(-5px) scale(1.1);
      box-shadow: 0 10px 25px rgba(255, 140, 0, 0.3);
    }

    .nav-item.active {
      color: var(--primary-orange);
      background: rgba(255, 140, 0, 0.1);
      border: 1px solid rgba(255, 140, 0, 0.3);
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255, 140, 0, 0.2);
    }

    .nav-item.active::before {
      opacity: 0.2;
      transform: scale(1);
    }

    .nav-icon {
      font-size: 22px;
      margin-bottom: 4px;
      transition: all 0.3s ease;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .nav-text {
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      text-align: center;
    }
    
    /* Enhanced Navbar */
    .navbar {
      background: rgba(26, 26, 26, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255, 140, 0, 0.1);
      padding: 20px 0;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
      animation: slideDown 1s cubic-bezier(0.4, 0, 0.2, 1);
      display: none; /* Hide the old navbar */
    }
    
    /* Container */
    .container {
      max-width: 1000px;
      margin: auto;
      padding: 40px 20px;
      animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Welcome Header */
    .welcome-header {
      text-align: center;
      margin-bottom: 50px;
      padding: 40px;
      background: linear-gradient(135deg, rgba(255, 140, 0, 0.1) 0%, rgba(255, 140, 0, 0.05) 100%);
      border-radius: 20px;
      border: 1px solid rgba(255, 140, 0, 0.2);
      position: relative;
      overflow: hidden;
    }
    
    .welcome-header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent, rgba(255, 140, 0, 0.05), transparent);
      animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
      0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
      100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }
    
    .welcome-header h1 {
      color: var(--primary-orange);
      font-weight: 800;
      font-size: 2.5rem;
      margin: 0;
      text-shadow: 0 0 20px rgba(255, 140, 0, 0.3);
      font-family: 'Manrope', sans-serif;
      position: relative;
      z-index: 1;
    }
    
    .welcome-subtitle {
      color: var(--text-muted);
      font-size: 1.1rem;
      margin-top: 10px;
      font-weight: 300;
      position: relative;
      z-index: 1;
    }
    
    /* Sections */
    .section {
      background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
      backdrop-filter: blur(20px);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      margin-bottom: 40px;
      border: 1px solid rgba(255, 140, 0, 0.1);
      position: relative;
      overflow: hidden;
      animation: fadeInUp 0.8s ease forwards;
      opacity: 0;
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--primary-orange), transparent);
      animation: borderGlow 2s infinite;
    }
    
    @keyframes borderGlow {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }
    
    .section:hover {
      transform: translateY(-5px);
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
      border-color: rgba(255, 140, 0, 0.3);
    }
    
    .section h2 {
      color: var(--primary-orange);
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 30px;
      text-align: center;
      font-family: 'Manrope', sans-serif;
      position: relative;
    }
    
    .section h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-orange), var(--secondary-orange));
      border-radius: 2px;
    }
    
    /* Dream Count Display */
    .dream-count-display {
      text-align: center;
      padding: 30px;
      margin-bottom: 30px;
      background: linear-gradient(135deg, rgba(255, 140, 0, 0.1) 0%, rgba(255, 140, 0, 0.05) 100%);
      border-radius: 15px;
      border: 2px solid rgba(255, 140, 0, 0.2);
    }
    
    .dream-count-number {
      font-size: 3rem;
      font-weight: 800;
      color: var(--primary-orange);
      margin-bottom: 10px;
      text-shadow: 0 0 20px rgba(255, 140, 0, 0.3);
      font-family: 'Manrope', sans-serif;
    }
    
    .dream-count-text {
      color: var(--text-muted);
      font-size: 1.1rem;
      font-weight: 500;
    }
    
    /* Insufficient Dreams Warning */
    .insufficient-dreams {
      background: linear-gradient(135deg, var(--warning-bg) 0%, rgba(42, 31, 15, 0.9) 100%);
      border: 2px solid rgba(255, 217, 61, 0.3);
      color: var(--warning-text);
      padding: 30px;
      border-radius: 15px;
      text-align: center;
      margin-bottom: 30px;
      position: relative;
      overflow: hidden;
    }
    
    .insufficient-dreams::before {
      content: '⚠️';
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 24px;
      opacity: 0.7;
    }
    
    .insufficient-dreams h3 {
      color: var(--warning-text);
      margin-bottom: 15px;
      font-weight: 700;
    }
    
    .insufficient-dreams p {
      margin-bottom: 20px;
      font-size: 1.1rem;
    }
    
    /* Buttons */
    .btn-analyze {
      background: linear-gradient(135deg, var(--primary-orange) 0%, var(--secondary-orange) 100%);
      color: var(--dark-bg);
      border: none;
      padding: 15px 40px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(255, 140, 0, 0.3);
      text-decoration: none;
      display: inline-block;
    }
    
    .btn-analyze::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn-analyze:hover::before {
      left: 100%;
    }
    
    .btn-analyze:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 15px 40px rgba(255, 140, 0, 0.4);
      color: var(--dark-bg);
      text-decoration: none;
    }
    
    .btn-analyze:active {
      transform: translateY(-1px) scale(1.02);
    }
    
    .btn-secondary {
      background: linear-gradient(135deg, rgba(255, 217, 61, 0.1) 0%, rgba(255, 217, 61, 0.2) 100%);
      color: var(--warning-text);
      border: 2px solid var(--warning-text);
    }
    
    .btn-secondary:hover {
      background: linear-gradient(135deg, var(--warning-text) 0%, #ffd93d 100%);
      color: var(--dark-bg);
    }
    
    /* Analysis Display */
    .analysis-container {
      background: linear-gradient(135deg, rgba(26, 26, 26, 0.98) 0%, rgba(20, 20, 20, 0.98) 100%);
      border: 2px solid var(--primary-orange);
      border-radius: 20px;
      padding: 40px;
      margin-top: 30px;
      position: relative;
      overflow: hidden;
    }
    
    .analysis-container::before {
      content: '';
      position: absolute;
      top: -1px;
      left: -1px;
      right: -1px;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-orange), var(--secondary-orange), var(--primary-orange));
      border-radius: 20px 20px 0 0;
    }
    
    .analysis-header {
      text-align: center;
      margin-bottom: 40px;
      padding-bottom: 20px;
      border-bottom: 2px solid rgba(255, 140, 0, 0.2);
    }
    
    .analysis-title {
      color: var(--primary-orange);
      font-size: 2.2rem;
      font-weight: 800;
      margin-bottom: 10px;
      font-family: 'Manrope', sans-serif;
      text-shadow: 0 0 15px rgba(255, 140, 0, 0.3);
    }
    
    .analysis-meta {
      color: var(--text-muted);
      font-size: 1rem;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }
    
    .analysis-meta-item {
      background: rgba(255, 140, 0, 0.1);
      padding: 8px 15px;
      border-radius: 20px;
      border: 1px solid rgba(255, 140, 0, 0.2);
      font-weight: 500;
    }
    
    .analysis-content {
      color: var(--text-light);
      font-size: 16px;
      line-height: 1.8;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
      padding: 30px;
      border-radius: 15px;
      border-left: 4px solid var(--primary-orange);
      white-space: pre-wrap;
      font-family: 'Inter', sans-serif;
    }
    
    /* Analyzing State */
    .analyzing-state {
      text-align: center;
      padding: 60px 20px;
      background: linear-gradient(135deg, rgba(255, 140, 0, 0.05) 0%, rgba(255, 140, 0, 0.02) 100%);
      border-radius: 20px;
      border: 2px solid rgba(255, 140, 0, 0.2);
      margin-top: 30px;
    }
    
    .analyzing-spinner {
      width: 80px;
      height: 80px;
      border: 4px solid rgba(255, 140, 0, 0.1);
      border-top: 4px solid var(--primary-orange);
      border-radius: 50%;
      animation: analyzingSpin 1.5s linear infinite;
      margin: 0 auto 30px auto;
      box-shadow: 0 0 30px rgba(255, 140, 0, 0.3);
    }
    
    @keyframes analyzingSpin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .analyzing-text {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-orange);
      margin-bottom: 15px;
      text-shadow: 0 0 10px rgba(255, 140, 0, 0.3);
    }
    
    .analyzing-description {
      color: var(--text-muted);
      font-size: 1rem;
      max-width: 400px;
      margin: 0 auto;
    }
    
    /* No Analysis State */
    .no-analysis {
      text-align: center;
      padding: 60px 20px;
      color: var(--text-muted);
      font-size: 18px;
      background: linear-gradient(135deg, rgba(255, 140, 0, 0.03) 0%, rgba(255, 140, 0, 0.01) 100%);
      border-radius: 15px;
      border: 2px dashed rgba(255, 140, 0, 0.2);
      position: relative;
    }
    
    .no-analysis::before {
      content: '🧠';
      font-size: 60px;
      display: block;
      margin-bottom: 20px;
      opacity: 0.5;
    }
    
    /* Alert */
    .alert {
      padding: 20px 25px;
      margin-bottom: 30px;
      border-radius: 15px;
      background: linear-gradient(135deg, var(--danger-bg) 0%, rgba(42, 15, 15, 0.9) 100%);
      color: var(--danger-text);
      border: 1px solid rgba(255, 107, 107, 0.3);
      font-weight: 500;
      position: relative;
      overflow: hidden;
    }
    
    .alert::before {
      content: '⚠️';
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 20px;
      opacity: 0.7;
    }
    
    /* Animations */
    @keyframes fadeInUp {
      from { 
        opacity: 0; 
        transform: translateY(40px); 
      }
      to { 
        opacity: 1; 
        transform: translateY(0); 
      }
    }
    
    @keyframes slideDown {
      from { 
        opacity: 0;
        transform: translateY(-100%); 
      }
      to { 
        opacity: 1;
        transform: translateY(0); 
      }
    }
    
    /* Mobile Responsiveness */
    @media (max-width: 991.98px) {
      .top-navbar .container-fluid {
        padding: 0 15px;
      }
      
      .brand-logo {
        font-size: 20px;
      }
      
      .premium-btn {
        padding: 6px 16px;
        font-size: 11px;
      }
      
      .container {
        padding: 20px 15px;
      }
      
      .welcome-header {
        padding: 30px 20px;
        margin-bottom: 30px;
      }
      
      .welcome-header h1 {
        font-size: 2rem;
      }
      
      .section {
        padding: 25px 20px;
        margin-bottom: 25px;
      }
      
      .section h2 {
        font-size: 1.5rem;
      }
      
      .analysis-container {
        padding: 30px 20px;
      }

      
      .analysis-title {
        font-size: 1.8rem;
      }
      
      .analysis-meta {
        flex-direction: column;
        gap: 10px;
      }
      
      .dream-count-number {
        font-size: 2.5rem;
      }
      
      .dream-orb {
        transform: scale(0.7);
      }

      .bottom-navigation {
        height: 70px;
      }

      .nav-item {
        min-width: 50px;
        height: 50px;
      }

      .nav-icon {
        font-size: 20px;
      }

      .nav-text {
        font-size: 10px;
      }
    }
    
    @media (max-width: 576px) {
      body {
        padding-top: 80px;
        padding-bottom: 70px;
      }
      
      .welcome-header h1 {
        font-size: 1.8rem;
      }
      
      .btn-analyze {
        padding: 12px 30px;
        font-size: 14px;
      }
      
      .analysis-content {
        padding: 20px;
        font-size: 15px;
      }
      
      .dream-count-number {
        font-size: 2rem;
      }

      .top-navbar {
        padding: 12px 0;
      }

      .bottom-navigation {
        height: 65px;
      }
    }
    

  </style>
</head>

<body>

<!-- ANIMATED ORANGE BACKGROUND SYSTEM START -->
<div class="animated-background"></div>
<div class="gradient-overlay"></div>
<div class="grid-overlay"></div>
<div class="geometric-shape shape-1"></div>
<div class="geometric-shape shape-2"></div>
<div class="geometric-shape shape-3"></div>
<div class="geometric-shape shape-4"></div>
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>
<div class="light-ray ray-1"></div>
<div class="light-ray ray-2"></div>
<div class="light-ray ray-3"></div>
<div class="light-ray ray-4"></div>
<div class="floating-dot dot-1"></div>
<div class="floating-dot dot-2"></div>
<div class="floating-dot dot-3"></div>
<div class="floating-dot dot-4"></div>
<div class="wave-container">
  <div class="wave"></div>
  <div class="wave"></div>
  <div class="wave"></div>
</div>
<!-- ANIMATED ORANGE BACKGROUND SYSTEM END -->

<!-- Loading Screen -->
<div id="loading-screen">
  <div class="loading-content">
    <div class="loading-spinner"></div>
    <div class="loading-text">DREAMLOCK</div>
  </div>
</div>

<!-- Top Navbar -->
<nav class="top-navbar">
  <div class="container-fluid">
    <a href="dream.php" class="brand-logo">
      <i class="bi bi-moon-stars-fill me-2"></i>
      <span class="dream">DREAM</span><span class="lock">LOCK</span>
    </a>
      <a href="premium.php" class="premium-btn">
        <i class="bi bi-star-fill me-1"></i>PREMIUM
      </a>
      <select class="language-select" onchange="changeLanguage(this.value)">
        <option value="en" <?php echo $lang === 'en' ? 'selected' : ''; ?>>🇺🇸 EN</option>
        <option value="tr" <?php echo $lang === 'tr' ? 'selected' : ''; ?>>🇹🇷 TR</option>
        <option value="es" <?php echo $lang === 'es' ? 'selected' : ''; ?>>🇪🇸 ES</option>
        <option value="fr" <?php echo $lang === 'fr' ? 'selected' : ''; ?>>🇫🇷 FR</option>
      </select>
    </div>
  </div>
</nav>

<div class="container" data-aos="fade-up">
  <!-- Welcome Header -->
  <div class="welcome-header">
    <h1><?php echo $t['subconscious_analysis']; ?></h1>
    <div class="welcome-subtitle">Discover the hidden patterns in your subconscious mind</div>
  </div>

  <?php if (isset($error_message)): ?>
    <div class="alert"><?php echo $error_message; ?></div>
  <?php endif; ?>

  <!-- Dream Count Section -->
  <div class="section">
    <h2>Your Dream Collection</h2>
    <div class="dream-count-display">
      <div class="dream-count-number"><?php echo $dream_count; ?></div>
      <div class="dream-count-text"><?php echo $dream_count; ?> / 5 <?php echo $t['dreams_required']; ?></div>
    </div>
    
    <?php if ($dream_count < 5): ?>
      <div class="insufficient-dreams">
        <h3><?php echo $t['insufficient_dreams']; ?></h3>
        <p>You currently have <?php echo $dream_count; ?> dream<?php echo $dream_count !== 1 ? 's' : ''; ?>. Add <?php echo 5 - $dream_count; ?> more to unlock your subconscious analysis.</p>
        <a href="dream.php" class="btn-analyze btn-secondary"><?php echo $t['add_more_dreams']; ?></a>
      </div>
    <?php else: ?>
      <!-- Analysis Section -->
      <div style="text-align: center; margin-top: 30px;">
        <?php if (!$is_analyzing): ?>
          <form method="POST" action="">
            <button type="submit" name="analyze_subconscious" class="btn-analyze">
              🧠 <?php echo $t['analyze_subconscious']; ?>
            </button>
          </form>
        <?php else: ?>
          <div class="analyzing-state">
            <div class="analyzing-spinner"></div>
            <div class="analyzing-text"><?php echo $t['analyzing']; ?></div>
            <div class="analyzing-description">Please wait while we analyze your dreams and uncover your subconscious patterns...</div>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Analysis Results Section -->
  <?php if ($analysis_result && !$error_message): ?>
    <div class="section">
      <div class="analysis-container">
        <div class="analysis-header">
          <div class="analysis-title"><?php echo $t['your_analysis']; ?></div>
          <div class="analysis-meta">
            <span class="analysis-meta-item"><?php echo $t['based_on_dreams']; ?> <?php echo $dream_count; ?> <?php echo $t['dreams_text']; ?></span>
            <span class="analysis-meta-item"><?php echo $t['analysis_date']; ?>: <?php echo date('M d, Y - H:i'); ?></span>
          </div>
        </div>
        <div class="analysis-content"><?php echo htmlspecialchars($analysis_result); ?></div>
      </div>
    </div>
  <?php elseif ($latest_analysis && !$is_analyzing): ?>
    <!-- Show Latest Analysis -->
    <div class="section">
      <h2><?php echo $t['last_analysis']; ?></h2>
      <div class="analysis-container">
        <div class="analysis-header">
          <div class="analysis-title"><?php echo $t['your_analysis']; ?></div>
          <div class="analysis-meta">
            <span class="analysis-meta-item"><?php echo $t['based_on_dreams']; ?> <?php echo $latest_analysis['dream_count']; ?> <?php echo $t['dreams_text']; ?></span>
            <span class="analysis-meta-item"><?php echo $t['analysis_date']; ?>: <?php echo date('M d, Y - H:i', strtotime($latest_analysis['created_at'])); ?></span>
          </div>
        </div>
        <div class="analysis-content"><?php echo htmlspecialchars($latest_analysis['analysis_text']); ?></div>
      </div>
      
      <?php if ($dream_count >= 5): ?>
        <div style="text-align: center; margin-top: 30px;">
          <form method="POST" action="">
            <button type="submit" name="analyze_subconscious" class="btn-analyze">
              🔄 <?php echo $t['new_analysis']; ?>
            </button>
          </form>
        </div>
      <?php endif; ?>
    </div>
  <?php elseif (!$is_analyzing && $dream_count >= 5): ?>
    <!-- No Analysis Yet -->
    <div class="section">
      <div class="no-analysis">
        <?php echo $t['no_analysis']; ?>
        <br><br>
        <?php echo $t['perform_analysis']; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<!-- Bottom Mobile Navigation -->
<div class="bottom-navigation">
  <a href="index.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-house-fill"></i></div>
    <div class="nav-text"><?php echo $t['home']; ?></div>
  </a>
  <a href="dream.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-cloud-moon-fill"></i></div>
    <div class="nav-text">Dreams</div>
  </a>
  <a href="subconscious.php" class="nav-item active">
    <div class="nav-icon"><i class="bi bi-cpu-fill"></i></div>
    <div class="nav-text"><?php echo $t['subconscious']; ?></div>
  </a>
  <a href="sleep_analysis.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-moon-stars-fill"></i></div>
    <div class="nav-text"><?php echo $t['sleep_analysis']; ?></div>
  </a>
  <a href="dream-sharing.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-share-fill"></i></div>
    <div class="nav-text">Dream Sharing</div>
  </a>
  <a href="?logout=1" class="nav-item">
    <div class="nav-icon"><i class="bi bi-box-arrow-right"></i></div>
    <div class="nav-text"><?php echo $t['logout']; ?></div>
  </a>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script>
  // ANIMATED ORANGE BACKGROUND JAVASCRIPT START
  
  // Mouse parallax effect for animated background
  let mouseX = 0;
  let mouseY = 0;
  
  document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    
    // Create subtle parallax effect on background elements
    const animatedBackground = document.querySelector('.animated-background');
    if (animatedBackground) {
      const x = (mouseX / window.innerWidth - 0.5) * 20;
      const y = (mouseY / window.innerHeight - 0.5) * 20;
      animatedBackground.style.transform = `translate(${x}px, ${y}px)`;
    }
    
    // Randomly create mouse trail particles
    if (Math.random() < 0.05) {
      createMouseTrail(mouseX, mouseY);
    }
  });
  
  function createMouseTrail(x, y) {
    const trail = document.createElement('div');
    trail.style.position = 'fixed';
    trail.style.left = x + 'px';
    trail.style.top = y + 'px';
    trail.style.width = '2px';
    trail.style.height = '2px';
    trail.style.background = '#FF8C00';
    trail.style.borderRadius = '50%';
    trail.style.pointerEvents = 'none';
    trail.style.zIndex = '1';
    trail.style.boxShadow = '0 0 6px rgba(255, 140, 0, 0.6)';
    
    document.body.appendChild(trail);
    
    // Animate trail fade out
    trail.animate([
      {
        transform: 'scale(1)',
        opacity: 1
      },
      {
        transform: 'scale(0)',
        opacity: 0
      }
    ], {
      duration: 1000,
      easing: 'ease-out'
    }).addEventListener('finish', () => {
      trail.remove();
    });
  }
  
  // Create additional floating elements periodically
  function createFloatingElement() {
    const element = document.createElement('div');
    element.style.position = 'fixed';
    element.style.width = Math.random() * 4 + 2 + 'px';
    element.style.height = element.style.width;
    element.style.background = '#FF8C00';
    element.style.borderRadius = '50%';
    element.style.pointerEvents = 'none';
    element.style.zIndex = '0';
    element.style.boxShadow = '0 0 8px rgba(255, 140, 0, 0.4)';
    element.style.left = Math.random() * 100 + '%';
    element.style.top = Math.random() * 100 + '%';
    element.style.opacity = '0.6';
    
    document.body.appendChild(element);
    
    // Animate floating
    const duration = Math.random() * 8000 + 4000;
    const keyframes = [
      { transform: 'translateY(0px) translateX(0px) scale(1)', opacity: 0.6 },
      { transform: `translateY(${-30 - Math.random() * 20}px) translateX(${Math.random() * 40 - 20}px) scale(1.2)`, opacity: 1 },
      { transform: `translateY(${-60 - Math.random() * 30}px) translateX(${Math.random() * 60 - 30}px) scale(0.8)`, opacity: 0.3 }
    ];
    
    element.animate(keyframes, {
      duration: duration,
      easing: 'ease-in-out'
    }).addEventListener('finish', () => {
      element.remove();
    });
  }
  
  // ANIMATED ORANGE BACKGROUND JAVASCRIPT END
  
  // Loading screen functionality
  window.addEventListener('load', function() {
    const loadingScreen = document.getElementById('loading-screen');
    setTimeout(() => {
      loadingScreen.classList.add('hidden');
      // Initialize animated orange background after loading
      setTimeout(() => {
        // Create additional floating elements periodically
        setInterval(createFloatingElement, 3000);
        
        // Add extra sparkle effects
        setInterval(() => {
          if (Math.random() < 0.2) {
            const x = Math.random() * window.innerWidth;
            const y = Math.random() * window.innerHeight;
            createMouseTrail(x, y);
          }
        }, 2000);
      }, 500);
    }, 800);
  });
  
  // Show loading screen on page navigation
  window.addEventListener('beforeunload', function() {
    const loadingScreen = document.getElementById('loading-screen');
    loadingScreen.classList.remove('hidden');
  });
  
  // Initialize AOS with custom settings
  AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true,
    offset: 100
  });
  
  // Animate sections on scroll
  const sections = document.querySelectorAll('.section');
  sections.forEach((section, index) => {
    section.style.animationDelay = (index * 0.2) + 's';
  });
  
  function changeLanguage(lang) {
    const loadingScreen = document.getElementById('loading-screen');
    loadingScreen.classList.remove('hidden');
    
    const url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location.href = url.toString();
  }
  
  // Add smooth hover effects to sections
  document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
      section.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) scale(1.01)';
      });
      
      section.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
  });
  
  // Auto-refresh for analyzing state
  <?php if ($is_analyzing): ?>
  setTimeout(() => {
    window.location.reload();
  }, 3000);
  <?php endif; ?>
  
  // Responsive adjustments
  window.addEventListener('resize', () => {
    // Reset background parallax on resize
    const animatedBackground = document.querySelector('.animated-background');
    if (animatedBackground) {
      animatedBackground.style.transform = 'translate(0px, 0px)';
    }
  });
  
  // Enhanced brain animation for analysis
  function createBrainWave() {
    const brainWave = document.createElement('div');
    brainWave.style.position = 'fixed';
    brainWave.style.width = '200px';
    brainWave.style.height = '2px';
    brainWave.style.background = 'linear-gradient(90deg, transparent, #FF8C00, transparent)';
    brainWave.style.top = Math.random() * window.innerHeight + 'px';
    brainWave.style.left = '-200px';
    brainWave.style.pointerEvents = 'none';
    brainWave.style.zIndex = '1';
    brainWave.style.borderRadius = '1px';
    brainWave.style.opacity = '0.7';
    
    document.body.appendChild(brainWave);
    
    // Animate across screen
    brainWave.animate([
      { transform: 'translateX(0px)' },
      { transform: `translateX(${window.innerWidth + 200}px)` }
    ], {
      duration: 2000,
      easing: 'ease-out'
    }).addEventListener('finish', () => {
      brainWave.remove();
    });
  }
  
  // Create brain waves during analysis
  <?php if ($is_analyzing): ?>
  setInterval(createBrainWave, 500);
  <?php endif; ?>
  
  // Navigation ripple effect
  function createNavRipple(event) {
    const ripple = document.createElement('div');
    ripple.classList.add('nav-ripple');
    
    const rect = event.currentTarget.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    
    event.currentTarget.appendChild(ripple);
    
    setTimeout(() => {
      ripple.remove();
    }, 600);
  }
  
  // Add ripple effect to navigation items
  document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
      item.addEventListener('click', createNavRipple);
    });
  });
  
</script>

</body>
</html>