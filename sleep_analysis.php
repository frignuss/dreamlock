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
    'sleep_analysis' => 'AI Sleep Pattern Analysis',
    'welcome' => 'Welcome',
    'sleep_tracking' => 'Advanced Sleep Intelligence',
    'track_sleep' => 'Track Your Sleep Pattern',
    'average_sleep' => 'Average Sleep Duration',
    'sleep_score' => 'Sleep Intelligence Score',
    'dream_analysis' => 'Dream-Based Analysis',
    'recommendations' => 'AI Insights & Recommendations',
    'no_sleep_data' => 'No sleep data recorded yet. Start tracking your sleep!',
    'insufficient_dreams' => 'You need at least 5 dreams recorded to access advanced AI analysis.',
    'home' => 'Home',
    'dreams' => 'Dreams',
    'subconscious' => 'Subconscious',
    'sleep' => 'Sleep Analysis',
    'logout' => 'Log Out',
    'save_error' => 'Sleep data could not be saved. Please try again.',
    'hours' => 'hours',
    'excellent' => 'Excellent',
    'good' => 'Good',
    'fair' => 'Fair',
    'poor' => 'Poor',
    'sleep_insights' => 'AI Sleep Intelligence Dashboard',
    'stress_analysis' => 'Stress Level Analysis',
    'dream_quality' => 'Dream Quality Impact',
    'sleep_pattern' => 'Sleep Pattern Recognition',
    'emotional_state' => 'Emotional Sleep State',
    'cognitive_performance' => 'Cognitive Performance',
    'enter_sleep_hours' => 'Enter your average sleep hours',
    'analyze_dreams' => 'Analyze Dream Patterns',
    'dream_count' => 'Total Dreams',
    'avg_dream_length' => 'Avg Dream Length',
    'emotional_score' => 'Emotional Score',
    'stress_level' => 'Stress Level',
    'sleep_efficiency' => 'Sleep Efficiency',
    'rem_quality' => 'REM Quality',
    'analyzing' => 'Analyzing Your Dreams',
    'please_wait' => 'AI is processing your sleep patterns...',
    'processing_dreams' => 'Processing dream data',
    'calculating_metrics' => 'Calculating sleep metrics',
    'generating_insights' => 'Generating AI insights',
    'finalizing_analysis' => 'Finalizing analysis',
    'intelligence_trend' => 'Sleep Intelligence Progress',
    'dream_profile' => 'Dream Intelligence Profile',
    'sleep_correlation' => 'Sleep Duration vs Intelligence Score',
    'emotional_analysis' => 'Emotional State vs Stress Management',
    'monday' => 'Monday',
    'tuesday' => 'Tuesday',
    'wednesday' => 'Wednesday',
    'thursday' => 'Thursday',
    'friday' => 'Friday',
    'saturday' => 'Saturday',
    'sunday' => 'Sunday',
    'emotional_score_desc' => 'How positive your dreams are',
    'rem_quality_desc' => 'Dream vividness and recall',
    'cognitive_desc' => 'Problem-solving in dreams',
    'efficiency_desc' => 'Overall sleep quality',
    'stress_mgmt_desc' => 'Stress management ability',
    'dream_richness_desc' => 'Dream complexity and detail',
	  'weekly_pattern' => 'Weekly Sleep Pattern',
'overall_score' => 'Overall Sleep Score',
    'premium' => 'Premium',
    'upgrade_premium' => 'Upgrade to Premium',
  ],
  'tr' => [
    'sleep_analysis' => 'AI Uyku Düzeni Analizi',
    'welcome' => 'Hoş Geldiniz',
    'sleep_tracking' => 'Gelişmiş Uyku Zekası',
    'track_sleep' => 'Uyku Düzeninizi Takip Edin',
    'average_sleep' => 'Ortalama Uyku Süresi',
    'sleep_score' => 'Uyku Zeka Puanı',
    'dream_analysis' => 'Rüya Tabanlı Analiz',
    'recommendations' => 'AI Öngörüleri ve Öneriler',
    'no_sleep_data' => 'Henüz uyku verisi kaydedilmemiş. Uykunu takip etmeye başla!',
    'insufficient_dreams' => 'Gelişmiş AI analizi için en az 5 rüya kaydetmiş olmanız gerekiyor.',
    'home' => 'Ana Sayfa',
    'dreams' => 'Rüyalar',
    'subconscious' => 'Bilinçaltı',
    'sleep' => 'Uyku Analizi',
    'logout' => 'Çıkış Yap',
    'save_error' => 'Uyku verisi kaydedilemedi. Lütfen tekrar deneyin.',
    'hours' => 'saat',
    'excellent' => 'Mükemmel',
    'good' => 'İyi',
    'fair' => 'Orta',
    'poor' => 'Kötü',
    'sleep_insights' => 'AI Uyku Zekası Kontrol Paneli',
    'stress_analysis' => 'Stres Seviyesi Analizi',
    'dream_quality' => 'Rüya Kalitesi Etkisi',
    'sleep_pattern' => 'Uyku Düzeni Tanıma',
    'emotional_state' => 'Duygusal Uyku Durumu',
    'cognitive_performance' => 'Bilişsel Performans',
    'enter_sleep_hours' => 'Ortalama uyku saatinizi girin',
    'analyze_dreams' => 'Rüya Desenlerini Analiz Et',
    'dream_count' => 'Toplam Rüya',
    'avg_dream_length' => 'Ort. Rüya Uzunluğu',
    'emotional_score' => 'Duygusal Puan',
    'stress_level' => 'Stres Seviyesi',
    'sleep_efficiency' => 'Uyku Verimliliği',
    'rem_quality' => 'REM Kalitesi',
    'analyzing' => 'Rüyalarınız Analiz Ediliyor',
    'please_wait' => 'AI uyku desenlerinizi işliyor...',
    'processing_dreams' => 'Rüya verisi işleniyor',
    'calculating_metrics' => 'Uyku metrikleri hesaplanıyor',
    'generating_insights' => 'AI öngörüleri oluşturuluyor',
    'finalizing_analysis' => 'Analiz tamamlanıyor',
    'intelligence_trend' => 'Uyku Zekası İlerlemesi',
    'dream_profile' => 'Rüya Zeka Profili',
    'sleep_correlation' => 'Uyku Süresi vs Zeka Puanı',
    'emotional_analysis' => 'Duygusal Durum vs Stres Yönetimi',
    'monday' => 'Pazartesi',
    'tuesday' => 'Salı',
    'wednesday' => 'Çarşamba',
    'thursday' => 'Perşembe',
    'friday' => 'Cuma',
    'saturday' => 'Cumartesi',
    'sunday' => 'Pazar',
    'emotional_score_desc' => 'Rüyalarınızın ne kadar pozitif olduğu',
    'rem_quality_desc' => 'Rüya canlılığı ve hatırlama',
    'cognitive_desc' => 'Rüyalarda problem çözme',
    'efficiency_desc' => 'Genel uyku kalitesi',
    'stress_mgmt_desc' => 'Stres yönetimi becerisi',
    'dream_richness_desc' => 'Rüya karmaşıklığı ve detayı',
	  'weekly_pattern' => 'Haftalık Uyku Düzeni', 
'overall_score' => 'Genel Uyku Skorunuz',
    'premium' => 'Premium',
    'upgrade_premium' => 'Premium\'a Yükselt',
  ],

'es' => [
  'sleep_analysis' => 'Análisis de Patrón de Sueño IA',
  'welcome' => 'Bienvenido',
  'sleep_tracking' => 'Inteligencia Avanzada del Sueño',
  'track_sleep' => 'Rastrea tu Patrón de Sueño',
  'average_sleep' => 'Duración Promedio de Sueño',
  'sleep_score' => 'Puntuación de Inteligencia del Sueño',
  'dream_analysis' => 'Análisis Basado en Sueños',
  'recommendations' => 'Perspectivas y Recomendaciones IA',
  'no_sleep_data' => 'Aún no hay datos de sueño registrados. ¡Comienza a rastrear tu sueño!',
  'insufficient_dreams' => 'Necesitas al menos 5 sueños registrados para acceder al análisis avanzado de IA.',
  'home' => 'Inicio',
  'dreams' => 'Sueños',
  'subconscious' => 'Subconsciente',
  'sleep' => 'Análisis del sueño',
  'logout' => 'Cerrar Sesión',
  'save_error' => 'Los datos de sueño no se pudieron guardar. Inténtalo de nuevo.',
  'hours' => 'horas',
  'excellent' => 'Excelente',
  'good' => 'Bueno',
  'fair' => 'Regular',
  'poor' => 'Pobre',
  'sleep_insights' => 'Panel de Inteligencia del Sueño IA',
  'stress_analysis' => 'Análisis de Nivel de Estrés',
  'dream_quality' => 'Impacto de Calidad de Sueños',
  'sleep_pattern' => 'Reconocimiento de Patrón de Sueño',
  'emotional_state' => 'Estado Emocional del Sueño',
  'cognitive_performance' => 'Rendimiento Cognitivo',
  'enter_sleep_hours' => 'Ingresa tus horas promedio de sueño',
  'analyze_dreams' => 'Analizar Patrones de Sueños',
  'dream_count' => 'Total de Sueños',
  'avg_dream_length' => 'Longitud Prom. de Sueño',
  'emotional_score' => 'Puntuación Emocional',
  'stress_level' => 'Nivel de Estrés',
  'sleep_efficiency' => 'Eficiencia del Sueño',
  'rem_quality' => 'Calidad REM',
  'analyzing' => 'Analizando Tus Sueños',
  'please_wait' => 'IA está procesando tus patrones de sueño...',
  'processing_dreams' => 'Procesando datos de sueños',
  'calculating_metrics' => 'Calculando métricas de sueño',
  'generating_insights' => 'Generando perspectivas IA',
  'finalizing_analysis' => 'Finalizando análisis',
  'intelligence_trend' => 'Progreso de Inteligencia del Sueño',
  'dream_profile' => 'Perfil de Inteligencia de Sueños',
  'sleep_correlation' => 'Duración de Sueño vs Puntuación de Inteligencia',
  'emotional_analysis' => 'Estado Emocional vs Manejo del Estrés',
  'monday' => 'Lunes',
  'tuesday' => 'Martes',
  'wednesday' => 'Miércoles',
  'thursday' => 'Jueves',
  'friday' => 'Viernes',
  'saturday' => 'Sábado',
  'sunday' => 'Domingo',
  'emotional_score_desc' => 'Qué tan positivos son tus sueños',
  'rem_quality_desc' => 'Vivacidad y recuerdo de sueños',
  'cognitive_desc' => 'Resolución de problemas en sueños',
  'efficiency_desc' => 'Calidad general del sueño',
  'stress_mgmt_desc' => 'Capacidad de manejo del estrés',
  'dream_richness_desc' => 'Complejidad y detalle de sueños',
	'weekly_pattern' => 'Patrón de sueño semanal', 
'overall_score' => 'Su puntuación general del sueño',
  'premium' => 'Premium',
  'upgrade_premium' => 'Actualizar a Premium',
],

  'fr' => [
    'sleep_analysis' => 'Analyse IA des habitudes de sommeil',
    'welcome' => 'Bienvenue',
    'sleep_tracking' => 'Intelligence avancée du sommeil',
    'track_sleep' => 'Suivez votre schéma de sommeil',
    'average_sleep' => 'Durée moyenne de sommeil',
    'sleep_score' => 'Score d\'intelligence du sommeil',
    'dream_analysis' => 'Analyse basée sur les rêves',
    'recommendations' => 'Aperçus et recommandations IA',
    'no_sleep_data' => 'Aucune donnée de sommeil enregistrée pour le moment. Commencez à suivre votre sommeil !',
    'insufficient_dreams' => 'Vous avez besoin d\'au moins 5 rêves enregistrés pour accéder à l\'analyse IA avancée.',
    'home' => 'Accueil',
    'dreams' => 'Rêves',
    'subconscious' => 'Subconscient',
    'sleep' => 'Analyse du sommeil',
    'logout' => 'Se déconnecter',
    'save_error' => 'Les données de sommeil n\'ont pas pu être enregistrées. Veuillez réessayer.',
    'hours' => 'heures',
    'excellent' => 'Excellent',
    'good' => 'Bon',
    'fair' => 'Moyen',
    'poor' => 'Faible',
    'sleep_insights' => 'Tableau de bord IA du sommeil',
    'stress_analysis' => 'Analyse du niveau de stress',
    'dream_quality' => 'Impact de la qualité des rêves',
    'sleep_pattern' => 'Reconnaissance des schémas de sommeil',
    'emotional_state' => 'État émotionnel du sommeil',
    'cognitive_performance' => 'Performance cognitive',
    'enter_sleep_hours' => 'Entrez vos heures de sommeil moyennes',
    'analyze_dreams' => 'Analyser les schémas de rêves',
    'dream_count' => 'Total des rêves',
    'avg_dream_length' => 'Longueur moy. des rêves',
    'emotional_score' => 'Score émotionnel',
    'stress_level' => 'Niveau de stress',
    'sleep_efficiency' => 'Efficacité du sommeil',
    'rem_quality' => 'Qualité REM',
    'analyzing' => 'Analyse de vos rêves',
    'please_wait' => 'L\'IA traite vos schémas de sommeil...',
    'processing_dreams' => 'Traitement des données de rêves',
    'calculating_metrics' => 'Calcul des métriques de sommeil',
    'generating_insights' => 'Génération d\'aperçus IA',
    'finalizing_analysis' => 'Finalisation de l\'analyse',
    'intelligence_trend' => 'Progression de l\'intelligence du sommeil',
    'dream_profile' => 'Profil d\'intelligence des rêves',
    'sleep_correlation' => 'Durée de sommeil vs score d\'intelligence',
    'emotional_analysis' => 'État émotionnel vs gestion du stress',
    'monday' => 'Lundi',
    'tuesday' => 'Mardi',
    'wednesday' => 'Mercredi',
    'thursday' => 'Jeudi',
    'friday' => 'Vendredi',
    'saturday' => 'Samedi',
    'sunday' => 'Dimanche',
    'emotional_score_desc' => 'À quel point vos rêves sont positifs',
    'rem_quality_desc' => 'Vivacité et rappel des rêves',
    'cognitive_desc' => 'Résolution de problèmes dans les rêves',
    'efficiency_desc' => 'Qualité globale du sommeil',
    'stress_mgmt_desc' => 'Capacité de gestion du stress',
    'dream_richness_desc' => 'Complexité et détail des rêves',
    'weekly_pattern' => 'Schéma de sommeil hebdomadaire',
    'overall_score' => 'Score global du sommeil',
    'premium' => 'Premium',
    'upgrade_premium' => 'Passer à Premium',
  ],

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

// Create sleep_data table if not exists
try {
    $db->exec("CREATE TABLE IF NOT EXISTS sleep_intelligence (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        average_sleep_hours DECIMAL(3,1) NOT NULL,
        analysis_date DATE NOT NULL,
        dream_count INT NOT NULL,
        avg_dream_length DECIMAL(6,2) NOT NULL,
        emotional_score DECIMAL(4,2) NOT NULL,
        stress_level DECIMAL(4,2) NOT NULL,
        sleep_efficiency DECIMAL(4,2) NOT NULL,
        rem_quality DECIMAL(4,2) NOT NULL,
        cognitive_performance DECIMAL(4,2) NOT NULL,
        sleep_intelligence_score INT NOT NULL,
        ai_comprehensive_analysis TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        UNIQUE KEY unique_user_date (user_id, analysis_date)
    )");
} catch (PDOException $e) {
    error_log("Sleep intelligence table creation error: " . $e->getMessage());
}

// Check if user has sufficient dreams
$stmt = $db->prepare("SELECT COUNT(*) as dream_count FROM dreams WHERE user_id = ?");
$stmt->execute([$current_user_id]);
$dream_check = $stmt->fetch(PDO::FETCH_ASSOC);
$has_sufficient_dreams = $dream_check['dream_count'] >= 5;

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: index.php");
  exit();
}

if (isset($_POST['analyze_sleep']) && $has_sufficient_dreams) {
    $average_sleep_hours = $_POST['average_sleep_hours'];
    $analysis_date = date('Y-m-d');
    
    // Get comprehensive dream analysis
    $stmt = $db->prepare("
        SELECT 
            COUNT(*) as total_dreams,
            AVG(LENGTH(dream_text)) as avg_dream_length,
            AVG(CASE 
                WHEN LOWER(dream_text) REGEXP '(happy|joy|love|peace|beautiful|wonderful|amazing|great|good|success|win|achieve)' THEN 8
                WHEN LOWER(dream_text) REGEXP '(sad|fear|angry|worry|stress|bad|terrible|awful|fail|lose|death|nightmare)' THEN 3
                ELSE 5.5
            END) as emotional_score,
            AVG(CASE 
                WHEN LOWER(dream_text) REGEXP '(chase|run|escape|hide|fight|danger|afraid|panic|stress|pressure|exam|work|deadline)' THEN 8
                WHEN LOWER(dream_text) REGEXP '(calm|relax|peace|quiet|nature|beach|home|family|friend|love)' THEN 2
                ELSE 5
            END) as stress_indicators,
            AVG(CASE 
                WHEN LENGTH(dream_text) > 200 AND dream_text REGEXP '(detail|remember|clear|vivid|color|people|place|conversation)' THEN 9
                WHEN LENGTH(dream_text) < 50 THEN 4
                ELSE 6.5
            END) as rem_quality,
            AVG(CASE 
                WHEN LOWER(dream_text) REGEXP '(solve|think|idea|create|invent|discover|learn|understand|realize|decision)' THEN 8.5
                WHEN LOWER(dream_text) REGEXP '(confuse|forget|lost|unclear|chaos|mess|strange|weird)' THEN 4
                ELSE 6
            END) as cognitive_indicators
        FROM dreams 
        WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ");
    $stmt->execute([$current_user_id]);
    $dream_analysis = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Calculate metrics
    $dream_count = $dream_analysis['total_dreams'];
    $avg_dream_length = $dream_analysis['avg_dream_length'];
    $emotional_score = $dream_analysis['emotional_score'];
    $stress_level = $dream_analysis['stress_indicators'];
    $rem_quality = $dream_analysis['rem_quality'];
    $cognitive_performance = $dream_analysis['cognitive_indicators'];
    
    // Calculate sleep efficiency based on multiple factors
    $sleep_efficiency = min(100, max(0, 
        ($average_sleep_hours >= 7 && $average_sleep_hours <= 9 ? 25 : 15) +
        ($emotional_score >= 6 ? 20 : 10) +
        ($stress_level <= 5 ? 20 : 10) +
        ($rem_quality >= 7 ? 20 : 10) +
        ($cognitive_performance >= 6 ? 15 : 5)
    ));
    
    // Calculate overall sleep intelligence score
    $sleep_intelligence_score = round(
        ($average_sleep_hours / 8 * 20) +
        ($emotional_score / 10 * 25) +
        ((10 - $stress_level) / 10 * 20) +
        ($rem_quality / 10 * 20) +
        ($cognitive_performance / 10 * 15)
    );
    
    // Advanced AI Analysis
    $analysis_prompts = [
    'en' => "Sleep analysis: {$average_sleep_hours}h avg, {$dream_count} dreams. Emotional: {$emotional_score}/10, Stress: {$stress_level}/10, REM: {$rem_quality}/10, Cognitive: {$cognitive_performance}/10, Efficiency: {$sleep_efficiency}%. Provide 2-3 sentences with key insights and 1-2 specific recommendations.",
    'tr' => "Uyku analizi: Ort {$average_sleep_hours}s, {$dream_count} rüya. Duygusal: {$emotional_score}/10, Stres: {$stress_level}/10, REM: {$rem_quality}/10, Bilişsel: {$cognitive_performance}/10, Verim: {$sleep_efficiency}%. 2-3 cümle ana bulgular ve 1-2 spesifik öneri ver.",
    'es' => "Análisis de sueño: {$average_sleep_hours}h prom, {$dream_count} sueños. Emocional: {$emotional_score}/10, Estrés: {$stress_level}/10, REM: {$rem_quality}/10, Cognitivo: {$cognitive_performance}/10, Eficiencia: {$sleep_efficiency}%. Proporciona 2-3 oraciones con hallazgos clave y 1-2 recomendaciones específicas.",
    'fr' => "Analyse du sommeil: {$average_sleep_hours}h moy, {$dream_count} rêves. Émotionnel: {$emotional_score}/10, Stress: {$stress_level}/10, REM: {$rem_quality}/10, Cognitif: {$cognitive_performance}/10, Efficacité: {$sleep_efficiency}%. Fournir 2-3 phrases avec des insights clés et 1-2 recommandations spécifiques."
];

$system_prompts = [
    'en' => 'You are a sleep analysis AI. Provide concise, actionable insights in 2-3 sentences max.',
    'tr' => 'Uyku analizi AI uzmanısın. Maksimum 2-3 cümle ile kısa, uygulanabilir öngörüler ver.',
    'es' => 'Eres una IA de análisis del sueño. Proporciona insights concisos y accionables en máximo 2-3 oraciones.',
    'fr' => 'Vous êtes une IA d\'analyse du sommeil. Fournissez des insights concis et actionnables en maximum 2-3 phrases.'
];
    
    $prompt = $analysis_prompts[$lang] ?? $analysis_prompts['en'];
    
    // OpenRouter API call
    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENROUTER_API_KEY,
        'HTTP-Referer: http://localhost',
        'X-Title: DreamLock Sleep Intelligence'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'deepseek/deepseek-chat-v3-0324',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompts[$lang] ?? $system_prompts['en']],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.7,
        'max_tokens' => 300
    ]));
    
    $response = curl_exec($ch);
    
    if (curl_error($ch)) {
        $comprehensive_analysis = $lang === 'tr' ? 'Bağlantı hatası nedeniyle analiz tamamlanamadı.' : 'Analysis could not be completed due to connection error.';
    } else {
        $result = json_decode($response, true);
        $comprehensive_analysis = $result['choices'][0]['message']['content'] ?? ($lang === 'tr' ? 'Analiz mevcut değil.' : 'Analysis not available.');
    }
    
    curl_close($ch);
    
    // Save to database
    try {
        $stmt = $db->prepare("INSERT INTO sleep_intelligence (user_id, average_sleep_hours, analysis_date, dream_count, avg_dream_length, emotional_score, stress_level, sleep_efficiency, rem_quality, cognitive_performance, sleep_intelligence_score, ai_comprehensive_analysis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE average_sleep_hours = VALUES(average_sleep_hours), dream_count = VALUES(dream_count), avg_dream_length = VALUES(avg_dream_length), emotional_score = VALUES(emotional_score), stress_level = VALUES(stress_level), sleep_efficiency = VALUES(sleep_efficiency), rem_quality = VALUES(rem_quality), cognitive_performance = VALUES(cognitive_performance), sleep_intelligence_score = VALUES(sleep_intelligence_score), ai_comprehensive_analysis = VALUES(ai_comprehensive_analysis)");
        $stmt->execute([$current_user_id, $average_sleep_hours, $analysis_date, $dream_count, $avg_dream_length, $emotional_score, $stress_level, $sleep_efficiency, $rem_quality, $cognitive_performance, $sleep_intelligence_score, $comprehensive_analysis]);
        header("Location: sleep_analysis.php");
        exit();
    } catch (PDOException $e) {
        $error_message = $t['save_error'];
        error_log("Sleep intelligence save error: " . $e->getMessage());
    }
}

// Get existing analysis data
$sleep_intelligence_data = [];
if ($has_sufficient_dreams) {
    try {
        $stmt = $db->prepare("SELECT * FROM sleep_intelligence WHERE user_id = ? ORDER BY analysis_date DESC LIMIT 10");
        $stmt->execute([$current_user_id]);
        $sleep_intelligence_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Sleep intelligence fetch error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DreamLock - <?php echo $t['sleep_analysis']; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
  <!-- Fallback Chart.js CDN -->
  <script>
    if (typeof Chart === 'undefined') {
      document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"><\/script>');
    }
  </script>
  <style>
    :root {
      --primary-pink: #ee819f;
      --secondary-pink: #f198a8;
      --dark-pink: #d4647a;
      --deep-pink: #b8485e;
      --dark-bg: #0a0a0a;
      --darker-bg: #050505;
      --card-bg: #111111;
      --card-hover: #1a1a1a;
      --text-light: #ffffff;
      --text-muted: #888888;
      --text-soft: #cccccc;
      --border-color: #2a2a2a;
      --success-bg: #0f2a0f;
      --success-text: #b6fcb6;
      --gradient-primary: linear-gradient(135deg, #ee819f 0%, #f198a8 50%, #d4647a 100%);
      --gradient-secondary: linear-gradient(135deg, #b8485e 0%, #ee819f 50%, #f198a8 100%);
      --gradient-radial: radial-gradient(circle, #ee819f 0%, #d4647a 50%, #b8485e 100%);
      --glow-pink: rgba(238, 129, 159, 0.4);
      --glow-soft: rgba(238, 129, 159, 0.15);
      --shadow-pink: 0 0 30px rgba(238, 129, 159, 0.3);
    }
    
    * { 
      font-family: 'Inter', 'Manrope', sans-serif; 
      font-weight: 400;
    }

    /* Premium Button Styling */
    .premium-btn {
      background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
      color: #000000;
      border: 2px solid #ffd700;
      border-radius: 12px;
      padding: 8px 15px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
      backdrop-filter: blur(10px);
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
      animation: premiumGlow 2s ease-in-out infinite;
    }

    .premium-btn:hover {
      background: linear-gradient(135deg, #ffed4e 0%, #ffd700 100%);
      transform: translateY(-2px) scale(1.05);
      box-shadow: 0 8px 25px rgba(255, 215, 0, 0.5);
      color: #000000;
      text-decoration: none;
    }

    @keyframes premiumGlow {
      0%, 100% {
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
      }
      50% {
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.5), 0 0 15px rgba(255, 215, 0, 0.3);
      }
    }

    .top-header-actions {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    /* Top Header with Logo and Language Selector */
    .top-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 60px;
      background: rgba(20, 20, 20, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(238, 129, 159, 0.1);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      animation: slideDown 0.8s cubic-bezier(0.4, 0, 0.2, 1);
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

    .app-logo {
      font-size: 24px;
      font-weight: 800;
      color: var(--text-light);
      font-family: 'Manrope', sans-serif;
      text-shadow: 0 0 15px rgba(238, 129, 159, 0.3);
    }

    .app-logo span:last-child {
      color: var(--primary-pink);
    }

    .language-selector select {
      background: linear-gradient(135deg, rgba(42, 42, 42, 0.9) 0%, rgba(35, 35, 35, 0.9) 100%) !important;
      color: var(--text-light) !important;
      border: 2px solid rgba(238, 129, 159, 0.3);
      border-radius: 12px;
      padding: 8px 15px;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.3s ease;
      cursor: pointer;
      backdrop-filter: blur(10px);
    }

    .language-selector select option {
      background: var(--card-bg) !important;
      color: var(--text-light) !important;
      padding: 8px;
    }
    
    .language-selector select:hover {
      background: linear-gradient(135deg, rgba(35, 35, 35, 0.9) 0%, rgba(42, 42, 42, 0.9) 100%) !important;
      box-shadow: 0 0 15px rgba(238, 129, 159, 0.3);
      transform: translateY(-2px);
      border-color: rgba(238, 129, 159, 0.5);
    }
    
    .language-selector select:focus {
      outline: none;
      box-shadow: 0 0 20px rgba(238, 129, 159, 0.4);
      border-color: var(--primary-pink);
    }

    /* Bottom Mobile Navigation */
    .bottom-navigation {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 80px;
      background: rgba(20, 20, 20, 0.98);
      backdrop-filter: blur(25px);
      border-top: 2px solid rgba(238, 129, 159, 0.2);
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
      background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
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
      box-shadow: 0 10px 25px rgba(238, 129, 159, 0.3);
    }

    .nav-item.active {
      color: var(--primary-pink);
      background: rgba(238, 129, 159, 0.1);
      border: 1px solid rgba(238, 129, 159, 0.3);
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(238, 129, 159, 0.2);
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
      opacity: 0.9;
      transition: all 0.3s ease;
    }

    .nav-item:hover .nav-icon {
      transform: scale(1.2) rotate(5deg);
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.4));
    }

    .nav-item:hover .nav-text {
      opacity: 1;
      font-weight: 700;
    }

    /* Mobile Navigation Active State Enhancements */
    .nav-item.active .nav-icon {
      animation: activeIconPulse 2s ease-in-out infinite;
    }

    @keyframes activeIconPulse {
      0%, 100% {
        transform: scale(1);
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
      }
      50% {
        transform: scale(1.1);
        filter: drop-shadow(0 4px 12px rgba(238, 129, 159, 0.6));
      }
    }

    /* Navigation Ripple Effect */
    .nav-item .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(238, 129, 159, 0.3);
      transform: scale(0);
      animation: rippleEffect 0.6s linear;
    }

    @keyframes rippleEffect {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    /* Body padding adjustments */
    body {
      margin: 0;
      padding-top: 80px;
      padding-bottom: 100px;
      color: var(--text-light);
      background: transparent;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Mobile responsive adjustments */
    @media (max-width: 576px) {
      .premium-btn {
        padding: 6px 10px;
        font-size: 11px;
      }
      
      .premium-btn i {
        font-size: 12px;
      }
      
      .top-header-actions {
        gap: 10px;
      }

      .top-header {
        height: 55px;
        padding: 0 15px;
      }
      
      .app-logo {
        font-size: 20px;
      }
      
      .language-selector select {
        padding: 6px 12px;
        font-size: 12px;
      }
      
      .bottom-navigation {
        height: 75px;
        padding: 0 10px;
      }
      
      .nav-item {
        min-width: 50px;
        height: 50px;
        padding: 6px 10px;
      }
      
      .nav-icon {
        font-size: 20px;
        margin-bottom: 3px;
      }
      
      .nav-text {
        font-size: 10px;
      }
    }

    /* Premium Button Styling */
    .premium-btn {
      background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
      color: #000000;
      border: 2px solid #ffd700;
      border-radius: 12px;
      padding: 8px 15px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
      backdrop-filter: blur(10px);
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
      animation: premiumGlow 2s ease-in-out infinite;
    }

    .premium-btn:hover {
      background: linear-gradient(135deg, #ffed4e 0%, #ffd700 100%);
      transform: translateY(-2px) scale(1.05);
      box-shadow: 0 8px 25px rgba(255, 215, 0, 0.5);
      color: #000000;
      text-decoration: none;
    }

    @keyframes premiumGlow {
      0%, 100% {
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
      }
      50% {
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.5), 0 0 15px rgba(255, 215, 0, 0.3);
      }
    }

    .top-header-actions {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    /* Top Header with Logo and Language Selector */
    .top-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 60px;
      background: rgba(20, 20, 20, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(238, 129, 159, 0.1);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      animation: slideDown 0.8s cubic-bezier(0.4, 0, 0.2, 1);
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

    .app-logo {
      font-size: 24px;
      font-weight: 800;
      color: var(--text-light);
      font-family: 'Manrope', sans-serif;
      text-shadow: 0 0 15px rgba(238, 129, 159, 0.3);
    }

    .app-logo span:last-child {
      color: var(--primary-pink);
    }

    .language-selector select {
      background: linear-gradient(135deg, rgba(42, 42, 42, 0.9) 0%, rgba(35, 35, 35, 0.9) 100%) !important;
      color: var(--text-light) !important;
      border: 2px solid rgba(238, 129, 159, 0.3);
      border-radius: 12px;
      padding: 8px 15px;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.3s ease;
      cursor: pointer;
      backdrop-filter: blur(10px);
    }

    .language-selector select option {
      background: var(--card-bg) !important;
      color: var(--text-light) !important;
      padding: 8px;
    }
    
    .language-selector select:hover {
      background: linear-gradient(135deg, rgba(35, 35, 35, 0.9) 0%, rgba(42, 42, 42, 0.9) 100%) !important;
      box-shadow: 0 0 15px rgba(238, 129, 159, 0.3);
      transform: translateY(-2px);
      border-color: rgba(238, 129, 159, 0.5);
    }
    
    .language-selector select:focus {
      outline: none;
      box-shadow: 0 0 20px rgba(238, 129, 159, 0.4);
      border-color: var(--primary-pink);
    }

    /* AI Insight Modal */
.ai-modal {
  display: none;
  position: fixed;
  z-index: 10000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.95);
  backdrop-filter: blur(10px);
  animation: fadeIn 0.3s ease;
}

.ai-modal-content {
  background: linear-gradient(135deg, rgba(17, 17, 17, 0.98) 0%, rgba(26, 26, 26, 0.95) 100%);
  margin: 5% auto;
  padding: 40px;
  border: 3px solid var(--glow-soft);
  border-radius: 25px;
  width: 90%;
  max-width: 800px;
  max-height: 80vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 
    0 30px 100px rgba(0, 0, 0, 0.8),
    0 0 50px var(--glow-pink);
  animation: modalSlideIn 0.4s ease;
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

.ai-modal-close {
  color: var(--text-muted);
  float: right;
  font-size: 32px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  position: absolute;
  top: 15px;
  right: 25px;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: rgba(238, 129, 159, 0.1);
}

.ai-modal-close:hover {
  color: var(--primary-pink);
  background: rgba(238, 129, 159, 0.2);
  transform: scale(1.1);
}

.ai-modal-title {
  color: var(--primary-pink);
  font-size: 1.6rem;
  font-weight: 700;
  margin-bottom: 25px;
  padding-right: 60px;
  text-shadow: var(--shadow-pink);
  display: flex;
  align-items: center;
  gap: 15px;
}

.ai-modal-text {
  color: var(--text-light);
  font-size: 1.2rem;
  line-height: 1.8;
  text-align: justify;
  margin-bottom: 20px;
}

.ai-modal-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 2px solid var(--glow-soft);
  flex-wrap: wrap;
  gap: 15px;
}

.ai-modal-score {
  background: var(--gradient-primary);
  color: white;
  padding: 8px 20px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.ai-modal-date {
  color: var(--text-muted);
  font-size: 0.9rem;
  font-style: italic;
}

/* Clickable indicator */
.ai-insight-card {
  cursor: pointer;
  position: relative;
}

.ai-insight-card::after {
  content: '🔍';
  position: absolute;
  top: 15px;
  right: 15px;
  font-size: 16px;
  opacity: 0.6;
  transition: all 0.3s ease;
}

.ai-insight-card:hover::after {
  opacity: 1;
  transform: scale(1.2);
}

.ai-insight-clickable {
  position: relative;
}

.ai-insight-clickable::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.05) 50%, transparent 70%);
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.ai-insight-clickable:hover::before {
  opacity: 1;
  animation: shimmer 1s ease-in-out;
}

/* Mobile responsive */
@media (max-width: 768px) {
  .ai-modal-content {
    margin: 10% auto;
    padding: 30px 25px;
    width: 95%;
    max-height: 85vh;
  }
  
  .ai-modal-title {
    font-size: 1.4rem;
    padding-right: 50px;
  }
  
  .ai-modal-text {
    font-size: 1.1rem;
  }
  
  .ai-modal-close {
    font-size: 28px;
    top: 10px;
    right: 15px;
  }
  
  .ai-modal-meta {
    flex-direction: column;
    align-items: flex-start;
  }
}
    
    /* Bottom Mobile Navigation */
    .bottom-navigation {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 80px;
      background: rgba(20, 20, 20, 0.98);
      backdrop-filter: blur(25px);
      border-top: 2px solid rgba(238, 129, 159, 0.2);
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
      background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
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
      box-shadow: 0 10px 25px rgba(238, 129, 159, 0.3);
    }

    .nav-item.active {
      color: var(--primary-pink);
      background: rgba(238, 129, 159, 0.1);
      border: 1px solid rgba(238, 129, 159, 0.3);
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(238, 129, 159, 0.2);
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
      opacity: 0.9;
      transition: all 0.3s ease;
    }

    .nav-item:hover .nav-icon {
      transform: scale(1.2) rotate(5deg);
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.4));
    }

    .nav-item:hover .nav-text {
      opacity: 1;
      font-weight: 700;
    }

    /* Mobile Navigation Active State Enhancements */
    .nav-item.active .nav-icon {
      animation: activeIconPulse 2s ease-in-out infinite;
    }

    @keyframes activeIconPulse {
      0%, 100% {
        transform: scale(1);
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
      }
      50% {
        transform: scale(1.1);
        filter: drop-shadow(0 4px 12px rgba(238, 129, 159, 0.6));
      }
    }

    /* Navigation Ripple Effect */
    .nav-item .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(238, 129, 159, 0.3);
      transform: scale(0);
      animation: rippleEffect 0.6s linear;
    }

    @keyframes rippleEffect {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    /* Enhanced Animated Background */
    .sleep-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -10;
      background: 
          radial-gradient(circle at 15% 25%, rgba(238, 129, 159, 0.12) 0%, transparent 45%),
          radial-gradient(circle at 85% 15%, rgba(241, 152, 168, 0.08) 0%, transparent 40%),
          radial-gradient(circle at 65% 85%, rgba(212, 100, 122, 0.06) 0%, transparent 55%),
          radial-gradient(circle at 25% 75%, rgba(184, 72, 94, 0.07) 0%, transparent 50%),
          radial-gradient(circle at 50% 50%, rgba(238, 129, 159, 0.03) 0%, transparent 80%),
          linear-gradient(135deg, #0a0a0a 0%, #111111 25%, #0f0f0f 50%, #161616 75%, #0a0a0a 100%);
      animation: dreamPulse 20s ease-in-out infinite alternate;
    }

    @keyframes dreamPulse {
      0% {
          filter: brightness(1) contrast(1) saturate(1) hue-rotate(0deg);
      }
      25% {
          filter: brightness(1.05) contrast(1.02) saturate(1.1) hue-rotate(5deg);
      }
      50% {
          filter: brightness(1.02) contrast(1.01) saturate(1.05) hue-rotate(-3deg);
      }
      75% {
          filter: brightness(1.07) contrast(1.03) saturate(1.15) hue-rotate(8deg);
      }
      100% {
          filter: brightness(1.03) contrast(1.01) saturate(1.08) hue-rotate(2deg);
      }
    }

    /* Enhanced Floating Dream Orbs */
    .dream-orb {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(2px);
      animation: dreamFloat 25s infinite ease-in-out;
      opacity: 0.8;
    }

    .dream-orb:nth-child(1) {
      width: 150px;
      height: 150px;
      background: radial-gradient(circle, var(--glow-pink) 0%, rgba(238, 129, 159, 0.05) 60%, transparent 100%);
      top: 8%;
      left: 5%;
      animation-delay: -5s;
      animation-duration: 28s;
    }

    .dream-orb:nth-child(2) {
      width: 110px;
      height: 110px;
      background: radial-gradient(circle, rgba(241, 152, 168, 0.15) 0%, rgba(241, 152, 168, 0.03) 60%, transparent 100%);
      top: 55%;
      right: 8%;
      animation-delay: -15s;
      animation-duration: 22s;
    }

    .dream-orb:nth-child(3) {
      width: 180px;
      height: 180px;
      background: radial-gradient(circle, rgba(212, 100, 122, 0.12) 0%, rgba(212, 100, 122, 0.02) 60%, transparent 100%);
      bottom: 15%;
      left: 10%;
      animation-delay: -10s;
      animation-duration: 35s;
    }

    .dream-orb:nth-child(4) {
      width: 90px;
      height: 90px;
      background: radial-gradient(circle, rgba(184, 72, 94, 0.18) 0%, rgba(184, 72, 94, 0.04) 60%, transparent 100%);
      top: 75%;
      right: 20%;
      animation-delay: -20s;
      animation-duration: 18s;
    }

    .dream-orb:nth-child(5) {
      width: 130px;
      height: 130px;
      background: radial-gradient(circle, var(--glow-soft) 0%, rgba(238, 129, 159, 0.02) 60%, transparent 100%);
      top: 30%;
      right: 35%;
      animation-delay: -25s;
      animation-duration: 30s;
    }

    @keyframes dreamFloat {
      0%, 100% {
          transform: translateY(0px) translateX(0px) scale(1) rotate(0deg);
          opacity: 0.6;
      }
      20% {
          transform: translateY(-40px) translateX(30px) scale(1.2) rotate(72deg);
          opacity: 0.9;
      }
      40% {
          transform: translateY(-25px) translateX(-35px) scale(0.8) rotate(144deg);
          opacity: 0.7;
      }
      60% {
          transform: translateY(-50px) translateX(20px) scale(1.1) rotate(216deg);
          opacity: 0.8;
      }
      80% {
          transform: translateY(-15px) translateX(-25px) scale(0.9) rotate(288deg);
          opacity: 0.75;
      }
    }

    /* Particle Effect */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -5;
      overflow: hidden;
    }

    .particle {
      position: absolute;
      width: 3px;
      height: 3px;
      background: var(--primary-pink);
      border-radius: 50%;
      animation: particleFloat 15s infinite linear;
      opacity: 0.3;
    }

    @keyframes particleFloat {
      0% {
        transform: translateY(100vh) translateX(0px);
        opacity: 0;
      }
      10% {
        opacity: 0.3;
      }
      90% {
        opacity: 0.3;
      }
      100% {
        transform: translateY(-10px) translateX(100px);
        opacity: 0;
      }
    }
    
    body {
      margin: 0;
      padding-top: 80px;
      padding-bottom: 100px;
      color: var(--text-light);
      background: transparent;
      min-height: 100vh;
      overflow-x: hidden;
    }
    

    
    /* Container */
    .container {
      max-width: 1400px;
      margin: auto;
      padding: 40px 25px;
      animation: fadeInUp 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Enhanced Welcome Header */
    .welcome-header {
      text-align: center;
      margin-bottom: 50px;
      padding: 40px 35px;
      background: linear-gradient(135deg, rgba(238, 129, 159, 0.15) 0%, rgba(184, 72, 94, 0.1) 100%);
      border-radius: 30px;
      border: 3px solid var(--glow-soft);
      position: relative;
      overflow: hidden;
      box-shadow: 
        0 30px 80px rgba(0, 0, 0, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.15);
      animation: headerFloat 6s ease-in-out infinite alternate;
    }

    @keyframes headerFloat {
      0% { transform: translateY(0px); }
      100% { transform: translateY(-10px); }
    }
    
    .welcome-header::before {
      content: '';
      position: absolute;
      top: -60%;
      left: -60%;
      width: 220%;
      height: 220%;
      background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
      animation: rotate 40s linear infinite;
      z-index: 0;
    }

    .welcome-header::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent 30%, var(--glow-soft) 50%, transparent 70%);
      animation: shimmer 4s infinite;
      opacity: 0.3;
    }

    @keyframes shimmer {
      0% { transform: translateX(-100%) translateY(-100%); }
      100% { transform: translateX(100%) translateY(100%); }
    }
    
    .welcome-header > * {
      position: relative;
      z-index: 1;
    }
    
    @keyframes rotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .welcome-header h1 {
      color: var(--primary-pink);
      font-weight: 800;
      font-size: 2.8rem;
      margin: 0;
      text-shadow: var(--shadow-pink);
      font-family: 'Manrope', sans-serif;
      animation: titleGlow 3s ease-in-out infinite alternate;
    }

    @keyframes titleGlow {
      0% { 
        text-shadow: 0 0 30px var(--glow-pink);
        transform: scale(1);
      }
      100% { 
        text-shadow: 0 0 40px var(--glow-pink), 0 0 60px var(--glow-pink);
        transform: scale(1.02);
      }
    }
    
    .welcome-subtitle {
      color: var(--text-soft);
      font-size: 1.1rem;
      margin-top: 20px;
      font-weight: 400;
      text-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
      animation: subtitleFloat 4s ease-in-out infinite alternate;
    }

    @keyframes subtitleFloat {
      0% { transform: translateY(0px); opacity: 0.8; }
      100% { transform: translateY(-5px); opacity: 1; }
    }
    
    /* Enhanced Sections */
    .section {
      background: linear-gradient(135deg, rgba(17, 17, 17, 0.98) 0%, rgba(26, 26, 26, 0.95) 100%);
      backdrop-filter: blur(30px);
      padding: 30px 35px;
      border-radius: 30px;
      box-shadow: 
        0 30px 100px rgba(0, 0, 0, 0.5),
        0 0 0 2px rgba(238, 129, 159, 0.15),
        inset 0 2px 0 rgba(255, 255, 255, 0.08);
      margin-bottom: 40px;
      border: 2px solid var(--glow-soft);
      position: relative;
      overflow: hidden;
      transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
      animation: sectionEntry 1s ease-out forwards;
    }

    @keyframes sectionEntry {
      0% {
        opacity: 0;
        transform: translateY(50px) scale(0.95);
      }
      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
    
    .section::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(238, 129, 159, 0.15), transparent);
      transition: left 1s ease;
    }

    .section::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: var(--gradient-primary);
      opacity: 0.8;
    }
    
    .section:hover {
      transform: translateY(-12px) scale(1.02);
      box-shadow: 
        0 40px 120px rgba(0, 0, 0, 0.6),
        0 0 0 3px rgba(238, 129, 159, 0.25),
        inset 0 2px 0 rgba(255, 255, 255, 0.12);
      border-color: var(--glow-pink);
    }
    
    .section:hover::before {
      left: 100%;
    }
    
    .section h2 {
      color: var(--primary-pink);
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 40px;
      text-align: center;
      font-family: 'Manrope', sans-serif;
      text-shadow: var(--shadow-pink);
      animation: headingPulse 2s ease-in-out infinite alternate;
    }

    @keyframes headingPulse {
      0% { text-shadow: 0 0 20px var(--glow-pink); }
      100% { text-shadow: 0 0 30px var(--glow-pink), 0 0 50px var(--glow-pink); }
    }
    
    /* Loading Screen */
    .loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  /* Mevcut background satırını şununla değiştir: */
  background: 
    radial-gradient(circle at 20% 30%, rgba(238, 129, 159, 0.15) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(241, 152, 168, 0.12) 0%, transparent 45%),
    radial-gradient(circle at 60% 80%, rgba(212, 100, 122, 0.08) 0%, transparent 55%),
    linear-gradient(135deg, rgba(10, 10, 10, 0.98) 0%, rgba(17, 17, 17, 0.96) 50%, rgba(5, 5, 5, 0.98) 100%);
  backdrop-filter: blur(25px);
  -webkit-backdrop-filter: blur(25px);
  z-index: 9999;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  animation: fadeIn 0.5s ease;
}

.loading-content {
  text-align: center;
  max-width: 500px;
  padding: 0 20px;
}

.loading-title {
  font-size: 2rem; /* Küçült */
  margin-bottom: 10px;
}

.loading-subtitle {
  font-size: 1rem; /* Küçült */
  margin-bottom: 30px;
}

.loading-progress {
  width: 350px;
  height: 6px; /* 8px'den küçült */
  margin-bottom: 15px;
}

.loading-steps {
  max-width: 400px;
  margin-top: 20px;
}

.loading-step-icon {
  width: 50px;
  height: 50px;
  font-size: 20px;
  margin-bottom: 8px;
}

.loading-step-text {
  font-size: 0.8rem; /* Küçült */
}

    @keyframes loadingTextFade {
      0% { opacity: 0.6; }
      100% { opacity: 1; }
    }

    .loading-steps {
      display: flex;
      justify-content: space-between;
      width: 100%;
      max-width: 500px;
      margin-top: 30px;
    }

    .loading-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      flex: 1;
      position: relative;
    }

    .loading-step-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: rgba(42, 42, 42, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-bottom: 10px;
      transition: all 0.5s ease;
      border: 2px solid var(--border-color);
    }

    .loading-step.active .loading-step-icon {
      background: var(--gradient-primary);
      border-color: var(--primary-pink);
      box-shadow: var(--shadow-pink);
      animation: stepPulse 1s ease-in-out infinite alternate;
    }

    @keyframes stepPulse {
      0% { transform: scale(1); }
      100% { transform: scale(1.1); }
    }

    .loading-step-text {
      color: var(--text-muted);
      font-size: 0.9rem;
      text-align: center;
    }

    .loading-step.active .loading-step-text {
      color: var(--primary-pink);
      font-weight: 600;
    }
    
    /* Enhanced Intelligence Form */
    .intelligence-form {
      max-width: 700px;
      margin: 0 auto;
      position: relative;
    }
    
    .form-group {
      margin-bottom: 35px;
      position: relative;
    }
    
    .form-label {
      color: var(--primary-pink);
      font-weight: 600;
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 15px;
      display: block;
      text-shadow: 0 0 15px var(--glow-soft);
      animation: labelGlow 3s ease-in-out infinite alternate;
    }

    @keyframes labelGlow {
      0% { text-shadow: 0 0 15px var(--glow-soft); }
      100% { text-shadow: 0 0 25px var(--glow-pink); }
    }
    
    input[type="number"] {
      width: 100%;
      padding: 25px 30px;
      border: 3px solid var(--border-color);
      border-radius: 20px;
      background: linear-gradient(135deg, rgba(42, 42, 42, 0.95) 0%, rgba(35, 35, 35, 0.95) 100%);
      color: var(--text-light);
      font-size: 20px;
      font-weight: 500;
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 
        inset 0 4px 8px rgba(0, 0, 0, 0.4),
        0 0 0 0 var(--glow-pink);
      position: relative;
    }
    
    input[type="number"]:focus {
      outline: none;
      border-color: var(--primary-pink);
      background: linear-gradient(135deg, rgba(35, 35, 35, 0.98) 0%, rgba(42, 42, 42, 0.98) 100%);
      box-shadow: 
        0 0 30px var(--glow-pink),
        inset 0 4px 8px rgba(0, 0, 0, 0.3),
        0 0 0 3px rgba(238, 129, 159, 0.2);
      transform: translateY(-5px) scale(1.02);
    }

    input[type="number"]:focus + .input-glow {
      opacity: 1;
      transform: scale(1.1);
    }
    
    /* Enhanced Analyze Button */
    .btn-analyze {
      background: var(--gradient-primary);
      color: white;
      border: none;
      padding: 20px 60px;
      border-radius: 60px;
      font-weight: 700;
      font-size: 20px;
      cursor: pointer;
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      text-transform: uppercase;
      letter-spacing: 3px;
      box-shadow: 
        0 15px 40px var(--glow-pink),
        0 0 0 2px rgba(255, 255, 255, 0.1),
        inset 0 2px 0 rgba(255, 255, 255, 0.2);
      position: relative;
      overflow: hidden;
      animation: buttonFloat 3s ease-in-out infinite alternate;
    }

    @keyframes buttonFloat {
      0% { transform: translateY(0px); }
      100% { transform: translateY(-3px); }
    }
    
    .btn-analyze::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.8s ease;
    }

    .btn-analyze::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.5) 0%, transparent 70%);
      border-radius: 50%;
      transform: translate(-50%, -50%);
      transition: all 0.3s ease;
    }
    
    .btn-analyze:hover {
      transform: translateY(-6px) scale(1.08);
      box-shadow: 
        0 25px 60px var(--glow-pink),
        0 0 0 3px rgba(255, 255, 255, 0.2),
        inset 0 2px 0 rgba(255, 255, 255, 0.3);
      animation: none;
    }
    
    .btn-analyze:hover::before {
      left: 100%;
    }

    .btn-analyze:hover::after {
      width: 300px;
      height: 300px;
    }
    
    .btn-analyze:active {
      transform: translateY(-3px) scale(1.05);
    }
    
    /* Enhanced Intelligence Dashboard */
    .intelligence-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 35px;
      margin-bottom: 60px;
    }
    
    .intelligence-card {
      background: linear-gradient(135deg, rgba(238, 129, 159, 0.18) 0%, rgba(184, 72, 94, 0.12) 100%);
      padding: 30px 25px;
      border-radius: 25px;
      border: 3px solid var(--glow-soft);
      text-align: center;
      position: relative;
      transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
      box-shadow: 
        0 20px 50px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
      animation: cardEntry 0.8s ease-out forwards;
    }

    @keyframes cardEntry {
      0% {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
      }
      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
    
    .intelligence-card::before {
      content: '';
      position: absolute;
      top: -60%;
      left: -60%;
      width: 220%;
      height: 220%;
      background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
      opacity: 0;
      transition: opacity 0.6s ease;
      animation: rotate 25s linear infinite;
    }

    .intelligence-card::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .intelligence-card:hover {
      transform: translateY(-15px) scale(1.05) rotateY(5deg);
      box-shadow: 
        0 35px 80px rgba(0, 0, 0, 0.4),
        0 0 40px var(--glow-pink),
        inset 0 2px 0 rgba(255, 255, 255, 0.15);
      border-color: var(--glow-pink);
    }
    
    .intelligence-card:hover::before {
      opacity: 0.6;
    }

    .intelligence-card:hover::after {
      opacity: 1;
      animation: shimmer 1.5s ease-in-out;
    }
    
    .intelligence-card > * {
      position: relative;
      z-index: 1;
    }
    
    .intelligence-value {
      font-size: 2.8rem;
      font-weight: 800;
      color: var(--primary-pink);
      margin-bottom: 18px;
      font-family: 'Manrope', sans-serif;
      text-shadow: var(--shadow-pink);
      animation: valueCount 2s ease-out;
    }
    
    @keyframes valueCount {
      from { 
        opacity: 0; 
        transform: translateY(30px) scale(0.5); 
      }
      to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
      }
    }
    
    .intelligence-label {
      color: var(--text-soft);
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-weight: 600;
      margin-bottom: 12px;
      animation: labelSlide 1s ease-out 0.3s both;
    }

    @keyframes labelSlide {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    .intelligence-icon {
      font-size: 3rem;
      margin-bottom: 25px;
      opacity: 0.9;
      filter: drop-shadow(var(--shadow-pink));
      animation: iconFloat 4s ease-in-out infinite alternate;
    }

    @keyframes iconFloat {
      0% { transform: translateY(0px) rotate(0deg); }
      100% { transform: translateY(-8px) rotate(5deg); }
    }
    
    .intelligence-description {
      color: var(--text-muted);
      font-size: 0.9rem;
      font-style: italic;
      margin-top: 12px;
      opacity: 0.8;
      animation: descFadeIn 1s ease-out 0.6s both;
    }

    @keyframes descFadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 0.8;
        transform: translateY(0);
      }
    }
    
    /* Enhanced Chart containers */
    .chart-container {
      position: relative;
      height: 350px;
      margin: 40px 0;
      background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(17, 17, 17, 0.8) 100%);
      border-radius: 25px;
      padding: 20px;
      border: 3px solid var(--glow-soft);
      box-shadow: 
        0 25px 70px rgba(0, 0, 0, 0.4),
        inset 0 2px 0 rgba(255, 255, 255, 0.08);
      overflow: hidden;
      transition: all 0.5s ease;
    }

    .chart-container:hover {
      transform: translateY(-5px);
      box-shadow: 
        0 35px 90px rgba(0, 0, 0, 0.5),
        0 0 30px var(--glow-soft),
        inset 0 2px 0 rgba(255, 255, 255, 0.12);
      border-color: var(--glow-pink);
    }
    
    .chart-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--gradient-primary);
      opacity: 0.8;
    }

    .chart-container::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at center, transparent 0%, rgba(238, 129, 159, 0.03) 100%);
      pointer-events: none;
    }
    
    /* Chart Fallback Styles */
    .chart-fallback {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, rgba(17, 17, 17, 0.95) 0%, rgba(26, 26, 26, 0.95) 100%);
      border-radius: 25px;
      border: 2px dashed rgba(238, 129, 159, 0.3);
      z-index: 10;
    }
    
    .fallback-icon {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0.7;
    }
    
    .fallback-text {
      color: var(--text-muted);
      font-size: 1rem;
      margin-bottom: 20px;
      text-align: center;
    }
    
    .fallback-data {
      color: var(--text-light);
      font-size: 0.9rem;
      text-align: center;
      line-height: 1.6;
      max-width: 80%;
    }
    
    .chart-small {
      height: 280px;
    }
    
    /* Charts Grid */
    .charts-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
      gap: 50px;
      margin: 50px 0;
    }
    
    /* Enhanced AI Analysis Insights */
    .ai-insight-card {
      background: linear-gradient(135deg, rgba(238, 129, 159, 0.15) 0%, rgba(212, 100, 122, 0.1) 100%);
      padding: 40px;
      border-radius: 25px;
      border-left: 6px solid var(--primary-pink);
      margin-bottom: 30px;
      position: relative;
      box-shadow: 
        0 20px 50px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
      transition: all 0.5s ease;
      overflow: hidden;
    }

    .ai-insight-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, transparent 40%, rgba(255, 255, 255, 0.05) 50%, transparent 60%);
      transform: translateX(-100%);
      transition: transform 0.8s ease;
    }
    
    .ai-insight-card:hover {
      transform: translateX(8px) translateY(-3px);
      box-shadow: 
        0 25px 60px rgba(0, 0, 0, 0.4),
        0 0 30px var(--glow-soft);
      border-left-color: var(--secondary-pink);
    }

    .ai-insight-card:hover::before {
      transform: translateX(100%);
    }
    
    .ai-insight-title {
      color: var(--primary-pink);
      font-weight: 700;
      font-size: 1.4rem;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 12px;
      text-shadow: 0 0 15px var(--glow-soft);
    }
    
    .ai-insight-text {
      color: var(--text-light);
      line-height: 1.9;
      font-size: 1.1rem;
      font-style: italic;
      text-align: justify;
      opacity: 0.95;
    }
    
    /* Enhanced Insufficient Dreams State */
    .insufficient-state {
      text-align: center;
      padding: 100px 50px;
      background: linear-gradient(135deg, rgba(184, 72, 94, 0.15) 0%, rgba(238, 129, 159, 0.08) 100%);
      border-radius: 30px;
      border: 3px dashed var(--glow-soft);
      margin: 50px 0;
      position: relative;
      overflow: hidden;
      animation: insufficientPulse 4s ease-in-out infinite alternate;
    }

    @keyframes insufficientPulse {
      0% { 
        border-color: var(--glow-soft);
        box-shadow: 0 0 20px rgba(238, 129, 159, 0.1);
      }
      100% { 
        border-color: var(--glow-pink);
        box-shadow: 0 0 40px rgba(238, 129, 159, 0.2);
      }
    }

    .insufficient-state::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
      animation: rotate 30s linear infinite;
      opacity: 0.3;
    }

    .insufficient-state > * {
      position: relative;
      z-index: 1;
    }
    
    .insufficient-icon {
      font-size: 100px;
      margin-bottom: 30px;
      opacity: 0.8;
      filter: drop-shadow(var(--shadow-pink));
      animation: insufficientFloat 3s ease-in-out infinite alternate;
    }

    @keyframes insufficientFloat {
      0% { transform: translateY(0px) scale(1); }
      100% { transform: translateY(-10px) scale(1.05); }
    }
    
    .insufficient-title {
      color: var(--primary-pink);
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      font-family: 'Manrope', sans-serif;
      text-shadow: var(--shadow-pink);
    }
    
    .insufficient-description {
      color: var(--text-muted);
      font-size: 1.2rem;
      max-width: 700px;
      margin: 0 auto 35px;
      line-height: 1.7;
    }
    
    .btn-dreams {
      background: var(--gradient-secondary);
      color: white;
      border: none;
      padding: 18px 45px;
      border-radius: 60px;
      font-weight: 600;
      font-size: 18px;
      text-decoration: none;
      display: inline-block;
      transition: all 0.4s ease;
      text-transform: uppercase;
      letter-spacing: 2px;
      box-shadow: 0 15px 40px var(--glow-pink);
      position: relative;
      overflow: hidden;
    }

    .btn-dreams::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.6s ease;
    }
    
    .btn-dreams:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 20px 50px var(--glow-pink);
      color: white;
      text-decoration: none;
    }

    .btn-dreams:hover::before {
      left: 100%;
    }
    
    /* Enhanced Progress Bars */
    .progress-container {
      margin: 25px 0;
    }
    
    .progress-bar {
      height: 10px;
      background: var(--border-color);
      border-radius: 15px;
      overflow: hidden;
      position: relative;
      box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.3);
    }
    
    .progress-fill {
      height: 100%;
      background: var(--gradient-primary);
      border-radius: 15px;
      transition: width 3s ease;
      position: relative;
      box-shadow: 0 0 15px var(--glow-pink);
    }
    
    .progress-fill::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      animation: shimmer 2.5s infinite;
    }
    
    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    /* Particles Container */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -5;
      overflow: hidden;
    }

    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: var(--primary-pink);
      border-radius: 50%;
      animation: particleFloat 20s infinite linear;
      opacity: 0.4;
      box-shadow: 0 0 10px var(--glow-pink);
    }

    .particle:nth-child(odd) {
      animation-duration: 25s;
      background: var(--secondary-pink);
    }

    .particle:nth-child(3n) {
      animation-duration: 18s;
      background: var(--dark-pink);
    }

    @keyframes particleFloat {
      0% {
        transform: translateY(100vh) translateX(0px) rotate(0deg);
        opacity: 0;
      }
      10% {
        opacity: 0.4;
      }
      90% {
        opacity: 0.4;
      }
      100% {
        transform: translateY(-10px) translateX(100px) rotate(360deg);
        opacity: 0;
      }
    }
    
    /* Loading animations */
    @keyframes fadeInUp {
      from { 
        opacity: 0; 
        transform: translateY(60px); 
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

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .container {
        padding: 40px 25px;
      }
      
      .intelligence-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
      }
      
      .charts-grid {
        grid-template-columns: 1fr;
        gap: 30px;
      }
      
      .chart-container {
        height: 400px;
        padding: 25px;
      }
      
      .chart-small {
        height: 300px;
      }
      
      .section {
        padding: 40px 30px;
      }
      
      .welcome-header {
        padding: 40px 30px;
      }
      
      .welcome-header h1 {
        font-size: 2.5rem;
      }
      
      .intelligence-value {
        font-size: 3rem;
      }
      
      .dream-orb {
        transform: scale(0.7);
      }
      
      .insufficient-icon {
        font-size: 70px;
      }
      
      .insufficient-title {
        font-size: 2rem;
      }

      .loading-progress {
        width: 300px;
      }

      .loading-steps {
        max-width: 350px;
      }

      .loading-step-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
      }
    }
    
    @media (max-width: 480px) {
      .welcome-header {
        padding: 30px 20px;
      }
      
      .intelligence-card {
        padding: 30px 25px;
      }
      
      .ai-insight-card {
        padding: 30px;
      }
      
      .btn-analyze {
        padding: 18px 45px;
        font-size: 18px;
      }

      .loading-title {
        font-size: 2rem;
      }

      .loading-progress {
        width: 250px;
      }

      .loading-steps {
        flex-direction: column;
        gap: 15px;
      }



      .premium-btn {
        padding: 6px 10px;
        font-size: 11px;
      }
      
      .premium-btn i {
        font-size: 12px;
      }
      
      .top-header-actions {
        gap: 10px;
      }

      .top-header {
        height: 55px;
        padding: 0 15px;
      }
      
      .app-logo {
        font-size: 20px;
      }
      
      .language-selector select {
        padding: 6px 12px;
        font-size: 12px;
      }
      
      .bottom-navigation {
        height: 75px;
        padding: 0 10px;
      }
      
      .nav-item {
        min-width: 50px;
        height: 50px;
        padding: 6px 10px;
      }
      
      .nav-icon {
        font-size: 20px;
        margin-bottom: 3px;
      }
      
      .nav-text {
        font-size: 10px;
      }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 10px;
    }

    ::-webkit-scrollbar-track {
      background: var(--dark-bg);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--gradient-primary);
      border-radius: 10px;
      box-shadow: 0 0 10px var(--glow-pink);
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--gradient-secondary);
      box-shadow: 0 0 15px var(--glow-pink);
    }
  </style>
  <link rel="icon" href="assets/logo.png" type="image/x-icon">
</head>

<body>
<!-- Dream Background -->
<div class="sleep-background"></div>
<div class="dream-orb"></div>
<div class="dream-orb"></div>
<div class="dream-orb"></div>
<div class="dream-orb"></div>
<div class="dream-orb"></div>

<!-- Particles -->
<div class="particles" id="particles"></div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay" style="display: none;">
  <div class="loading-content">
    <div class="loading-icon">🧠✨</div>
    <h2 class="loading-title"><?php echo $t['analyzing']; ?></h2>
    <p class="loading-subtitle"><?php echo $t['please_wait']; ?></p>
    
    <div class="loading-progress">
      <div class="loading-bar" id="loadingBar"></div>
    </div>
    <p class="loading-text" id="loadingText"><?php echo $t['processing_dreams']; ?></p>
    
    <div class="loading-steps">
      <div class="loading-step" id="step1">
        <div class="loading-step-icon">💭</div>
        <div class="loading-step-text"><?php echo $t['processing_dreams']; ?></div>
      </div>
      <div class="loading-step" id="step2">
        <div class="loading-step-icon">📊</div>
        <div class="loading-step-text"><?php echo $t['calculating_metrics']; ?></div>
      </div>
      <div class="loading-step" id="step3">
        <div class="loading-step-icon">🤖</div>
        <div class="loading-step-text"><?php echo $t['generating_insights']; ?></div>
      </div>
      <div class="loading-step" id="step4">
        <div class="loading-step-icon">✅</div>
        <div class="loading-step-text"><?php echo $t['finalizing_analysis']; ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Top Header with Logo and Language Selector -->
<div class="top-header">
  <div class="app-logo">
    <span style="color: rgba(255,255,255,0.75)">DREAM</span><span style="color: #ee819f;">LOCK</span>

  </div>
  <div class="top-header-actions">
    <a href="premium.php" class="premium-btn">
      <i class="bi bi-crown-fill"></i> <?php echo $t['premium']; ?>
    </a>
    <div class="language-selector">
      <select onchange="changeLanguage(this.value)">
        <option value="en" <?php echo $lang === 'en' ? 'selected' : ''; ?>>🇺🇸 EN</option>
        <option value="tr" <?php echo $lang === 'tr' ? 'selected' : ''; ?>>🇹🇷 TR</option>
        <option value="es" <?php echo $lang === 'es' ? 'selected' : ''; ?>>🇪🇸 ES</option>
      </select>
    </div>
  </div>
</div>

<div class="container" data-aos="fade-up">
  <!-- Welcome Header -->
  <div class="welcome-header">
    <h1>🧠 <?php echo $t['welcome']; ?>, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <div class="welcome-subtitle"><?php echo $t['sleep_tracking']; ?></div>
  </div>

  <?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
  <?php endif; ?>

  <?php if (!$has_sufficient_dreams): ?>
  <!-- Insufficient Dreams State -->
  <div class="insufficient-state">
    <div class="insufficient-icon">🌙✨</div>
    <h3 class="insufficient-title">AI Analysis Locked</h3>
    <p class="insufficient-description">
      <?php echo $t['insufficient_dreams']; ?><br>
      <strong>Current Dreams: <?php echo $dream_check['dream_count']; ?>/5</strong>
    </p>
    <a href="dream.php" class="btn-dreams">Record Your Dreams</a>
  </div>
  <?php else: ?>

  <!-- Sleep Intelligence Form -->
  <div class="section">
    <h2>🌟 <?php echo $t['track_sleep']; ?></h2>
    <form method="POST" action="" class="intelligence-form" id="sleepForm">
      <div class="form-group">
        <label class="form-label"><?php echo $t['enter_sleep_hours']; ?></label>
        <input type="number" name="average_sleep_hours" step="0.5" min="3" max="12" value="8" required>
        <div class="intelligence-description">Enter your typical sleep duration (3-12 hours)</div>
      </div>
      
      <center>
        <button type="submit" name="analyze_sleep" class="btn-analyze" id="analyzeBtn">
          🧠 <?php echo $t['analyze_dreams']; ?>
        </button>
      </center>
    </form>
  </div>

  <!-- Intelligence Dashboard -->
  <?php if (count($sleep_intelligence_data) > 0): ?>
  <?php $latest = $sleep_intelligence_data[0]; ?>
  <div class="intelligence-grid">
    <div class="intelligence-card">
      <div class="intelligence-icon">🛌</div>
      <div class="intelligence-value"><?php echo number_format($latest['average_sleep_hours'], 1); ?></div>
      <div class="intelligence-label"><?php echo $t['average_sleep']; ?></div>
      <div class="intelligence-description"><?php echo $t['hours']; ?></div>
    </div>
    
    <div class="intelligence-card">
      <div class="intelligence-icon">🧠</div>
      <div class="intelligence-value"><?php echo $latest['sleep_intelligence_score']; ?></div>
      <div class="intelligence-label"><?php echo $t['sleep_score']; ?></div>
      <div class="intelligence-description">/100 Points</div>
    </div>
    
    <div class="intelligence-card">
      <div class="intelligence-icon">💭</div>
      <div class="intelligence-value"><?php echo $latest['dream_count']; ?></div>
      <div class="intelligence-label"><?php echo $t['dream_count']; ?></div>
      <div class="intelligence-description">Dreams Analyzed</div>
    </div>
    
    <div class="intelligence-card">
      <div class="intelligence-icon">📝</div>
      <div class="intelligence-value"><?php echo number_format($latest['avg_dream_length']); ?></div>
      <div class="intelligence-label"><?php echo $t['avg_dream_length']; ?></div>
      <div class="intelligence-description">Characters</div>
    </div>
    
    <div class="intelligence-card">
      <div class="intelligence-icon">💖</div>
      <div class="intelligence-value"><?php echo number_format($latest['emotional_score'], 1); ?></div>
      <div class="intelligence-label"><?php echo $t['emotional_score']; ?></div>
      <div class="intelligence-description"><?php echo $t['emotional_score_desc']; ?></div>
    </div>
    
    <div class="intelligence-card">
      <div class="intelligence-icon">⚡</div>
      <div class="intelligence-value"><?php echo number_format($latest['stress_level'], 1); ?></div>
      <div class="intelligence-label"><?php echo $t['stress_level']; ?></div>
      <div class="intelligence-description"><?php echo $t['stress_mgmt_desc']; ?></div>
    </div>
    
    <div class="intelligence-card">
      <div class="intelligence-icon">✨</div>
      <div class="intelligence-value"><?php echo number_format($latest['sleep_efficiency'], 0); ?>%</div>
      <div class="intelligence-label"><?php echo $t['sleep_efficiency']; ?></div>
      <div class="intelligence-description"><?php echo $t['efficiency_desc']; ?></div>
    </div>
    
    <div class="intelligence-card">
      <div class="intelligence-icon">🌀</div>
      <div class="intelligence-value"><?php echo number_format($latest['rem_quality'], 1); ?></div>
      <div class="intelligence-label"><?php echo $t['rem_quality']; ?></div>
      <div class="intelligence-description"><?php echo $t['rem_quality_desc']; ?></div>
    </div>
  </div>
<!-- AI Analysis Modal -->
<div id="aiModal" class="ai-modal">
  <div class="ai-modal-content">
    <span class="ai-modal-close">&times;</span>
    <div class="ai-modal-title" id="modalTitle">
      <span>🧠</span>
      <span id="modalTitleText"></span>
    </div>
    <div class="ai-modal-text" id="modalText"></div>
    <div class="ai-modal-meta">
      <div class="ai-modal-score" id="modalScore"></div>
      <div class="ai-modal-date" id="modalDate"></div>
    </div>
  </div>
</div>
  <!-- Charts and Analytics -->
  <div class="section">
    <h2>📊 <?php echo $t['sleep_insights']; ?></h2>
    
    <div class="charts-grid">
  <!-- Uyku Skoru Gelişimi -->
  <div class="chart-container">
    <canvas id="intelligenceTrendChart"></canvas>
    <div class="chart-fallback" id="trendChartFallback" style="display: none;">
      <div class="fallback-icon">📈</div>
      <div class="fallback-text">Grafik yüklenemedi</div>
      <div class="fallback-data" id="trendChartData"></div>
    </div>
  </div>
  
  <!-- Uyku Kalitesi Özeti -->
  <div class="chart-container">
    <canvas id="dreamAnalysisChart"></canvas>
    <div class="chart-fallback" id="summaryChartFallback" style="display: none;">
      <div class="fallback-icon">📊</div>
      <div class="fallback-text">Grafik yüklenemedi</div>
      <div class="fallback-data" id="summaryChartData"></div>
    </div>
  </div>
  
  <!-- Haftalık Uyku Düzeni -->
  <div class="chart-container chart-small">
    <canvas id="correlationChart"></canvas>
    <div class="chart-fallback" id="weeklyChartFallback" style="display: none;">
      <div class="fallback-icon">📅</div>
      <div class="fallback-text">Grafik yüklenemedi</div>
      <div class="fallback-data" id="weeklyChartData"></div>
    </div>
  </div>
  
  <!-- Genel Uyku Skoru -->
  <div class="chart-container chart-small">
    <canvas id="emotionalStressChart"></canvas>
    <div class="chart-fallback" id="distributionChartFallback" style="display: none;">
      <div class="fallback-icon">🎯</div>
      <div class="fallback-text">Grafik yüklenemedi</div>
      <div class="fallback-data" id="distributionChartData"></div>
    </div>
  </div>
</div>
  
  <!-- AI Comprehensive Analysis -->
  <div class="section">
    <h2>🤖 <?php echo $t['recommendations']; ?></h2>
    <?php foreach (array_slice($sleep_intelligence_data, 0, 3) as $index => $data): ?>
      <?php if ($data['ai_comprehensive_analysis']): ?>
        <div class="ai-insight-card">
          <div class="ai-insight-title">
            <span>🧠</span>
            <?php echo date('M d, Y', strtotime($data['analysis_date'])); ?> - 
            Intelligence Score: <?php echo $data['sleep_intelligence_score']; ?>/100
          </div>
          <div class="ai-insight-text"><?php echo nl2br(htmlspecialchars($data['ai_comprehensive_analysis'])); ?></div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  
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
  <a href="subconscious.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-cpu-fill"></i></div>
    <div class="nav-text"><?php echo $t['subconscious']; ?></div>
  </a>
  <a href="sleep_analysis.php" class="nav-item active">
    <div class="nav-icon"><i class="bi bi-moon-stars-fill"></i></div>
    <div class="nav-text"><?php echo $t['sleep']; ?></div>
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
	const t = <?php echo json_encode($t); ?>;

  // Initialize AOS
  AOS.init({
    duration: 1200,
    easing: 'ease-out-cubic',
    once: true,
    offset: 120,
    delay: 100
  });

  // Create particles
  function createParticles() {
    const particlesContainer = document.getElementById('particles');
    const particleCount = 15;
    
    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement('div');
      particle.className = 'particle';
      particle.style.left = Math.random() * 100 + '%';
      particle.style.animationDelay = Math.random() * 20 + 's';
      particle.style.animationDuration = (15 + Math.random() * 10) + 's';
      particlesContainer.appendChild(particle);
    }
  }

  // Loading screen functionality
  function showLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const loadingBar = document.getElementById('loadingBar');
    const loadingText = document.getElementById('loadingText');
    const steps = ['step1', 'step2', 'step3', 'step4'];
    const texts = [
      '<?php echo $t['processing_dreams']; ?>',
      '<?php echo $t['calculating_metrics']; ?>',
      '<?php echo $t['generating_insights']; ?>',
      '<?php echo $t['finalizing_analysis']; ?>'
    ];
    
    loadingOverlay.style.display = 'flex';
    
    let progress = 0;
    let currentStep = 0;
    
    const interval = setInterval(() => {
      progress += Math.random() * 15 + 5;
      
      if (progress > 100) {
        progress = 100;
        clearInterval(interval);
        setTimeout(() => {
          loadingOverlay.style.animation = 'fadeOut 0.5s ease';
          setTimeout(() => {
            loadingOverlay.style.display = 'none';
          }, 500);
        }, 1000);
      }
      
      loadingBar.style.width = progress + '%';
      
      // Update steps
      const stepIndex = Math.floor(progress / 25);
      if (stepIndex !== currentStep && stepIndex < steps.length) {
        if (currentStep > 0) {
          document.getElementById(steps[currentStep - 1]).classList.remove('active');
        }
        document.getElementById(steps[stepIndex]).classList.add('active');
        loadingText.textContent = texts[stepIndex];
        currentStep = stepIndex + 1;
      }
    }, 300);
  }

  // Form submission with loading
  // Mevcut form submission kodunu şununla değiştirin:

// Form submission with loading - GERÇEK SÜREÇLE BAĞLANTILI
document.getElementById('sleepForm')?.addEventListener('submit', function(e) {
  // Loading'i göster
  showLoading();
  
  // Form'u normal şekilde submit et (sayfa yeniden yüklenecek)
  // e.preventDefault() KULLANMAYIN - form gerçekten submit olsun
});

// Loading function'ını güncelleyin:
function showLoading() {
  const loadingOverlay = document.getElementById('loadingOverlay');
  const loadingBar = document.getElementById('loadingBar');
  const loadingText = document.getElementById('loadingText');
  const steps = ['step1', 'step2', 'step3', 'step4'];
  const texts = [
    '<?php echo $t['processing_dreams']; ?>',
    '<?php echo $t['calculating_metrics']; ?>',
    '<?php echo $t['generating_insights']; ?>',
    '<?php echo $t['finalizing_analysis']; ?>'
  ];
  
  loadingOverlay.style.display = 'flex';
  
  let progress = 0;
  let currentStep = 0;
  
  // İlk step'i aktif yap
  document.getElementById('step1').classList.add('active');
  
  const interval = setInterval(() => {
    // İlerleme daha yavaş (gerçek analiz süresi)
    progress += Math.random() * 8 + 2; // Daha yavaş ilerleme
    
    if (progress > 95) {
      progress = 95; // %95'te dur, sayfa yeniden yüklenmeyi bekle
    }
    
    loadingBar.style.width = progress + '%';
    
    // Step güncellemeleri
    const stepIndex = Math.floor(progress / 23.75); // 4 step için
    if (stepIndex !== currentStep && stepIndex < steps.length) {
      if (currentStep > 0) {
        document.getElementById(steps[currentStep - 1]).classList.remove('active');
      }
      document.getElementById(steps[stepIndex]).classList.add('active');
      loadingText.textContent = texts[stepIndex];
      currentStep = stepIndex + 1;
    }
  }, 500); // Daha uzun aralıklar
  
  // Sayfa yeniden yüklendiğinde interval temizlenecek, loading otomatik kaybolacak
}
  
  // Language change function
  function changeLanguage(lang) {
    const url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location.href = url.toString();
  }
  
  <?php if ($has_sufficient_dreams && count($sleep_intelligence_data) > 0): ?>
  // Prepare data for charts
  const intelligenceData = <?php echo json_encode(array_reverse($sleep_intelligence_data)); ?>;
  
  // Debug: Check if Chart.js is loaded
  console.log('Chart.js available:', typeof Chart !== 'undefined');
  console.log('Intelligence data:', intelligenceData);
  
  // Wait for DOM to be ready and Chart.js to be loaded
  function initializeCharts() {
    if (typeof Chart === 'undefined') {
      console.error('Chart.js is not loaded!');
      setTimeout(initializeCharts, 100);
      return;
    }
    
    try {
      // 1. Uyku Skoru Gelişimi chart.js (Basit Çizgi Grafik)
      const trendCtx = document.getElementById('intelligenceTrendChart');
      if (trendCtx) {
        console.log('Creating trend chart...');
        const trendChart = new Chart(trendCtx.getContext('2d'), {
          type: 'line',
          data: {
            labels: intelligenceData.map(d => {
              const date = new Date(d.analysis_date);
              return (date.getDate() + '/' + (date.getMonth() + 1));
            }),
            datasets: [{
              label: 'Uyku Skoru',
              data: intelligenceData.map(d => parseInt(d.sleep_intelligence_score)),
              borderColor: '#ee819f',
              backgroundColor: 'rgba(238, 129, 159, 0.1)',
              borderWidth: 3,
              pointRadius: 6,
              pointBackgroundColor: '#ee819f',
              fill: true,
              tension: 0.3
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              title: {
                display: true,
                text: t.intelligence_trend || 'Uyku Skoru Gelişimi',
                color: '#ee819f',
                font: { size: 16, weight: 'bold' }
              },
              legend: { display: false }
            },
            scales: {
              y: {
                beginAtZero: true,
                max: 100,
                grid: { color: 'rgba(238, 129, 159, 0.1)' },
                ticks: { 
                  font: { size: 11 },
                  callback: function(value) { return value + '/100'; }
                }
              },
              x: {
                grid: { color: 'rgba(238, 129, 159, 0.1)' },
                ticks: { font: { size: 11 } }
              }
            }
          }
        });
        console.log('Trend chart created successfully');
      } else {
        console.error('Trend chart container not found');
      }

      // 2. Uyku Kalitesi Özeti (Basit Bar Grafik)
      const summaryCtx = document.getElementById('dreamAnalysisChart');
      if (summaryCtx && intelligenceData.length > 0) {
        console.log('Creating summary chart...');
        const latest = intelligenceData[intelligenceData.length - 1];
        const summaryChart = new Chart(summaryCtx.getContext('2d'), {
          type: 'bar',
          data: {
            labels: ['Duygusal\nDurum', 'REM\nKalitesi', 'Uyku\nVerimi', 'Stres\nYönetimi'],
            datasets: [{
              label: 'Puan',
              data: [
                parseFloat(latest.emotional_score),
                parseFloat(latest.rem_quality),
                parseFloat(latest.sleep_efficiency) / 10,
                10 - parseFloat(latest.stress_level)
              ],
              backgroundColor: [
                'rgba(238, 129, 159, 0.8)',
                'rgba(241, 152, 168, 0.8)', 
                'rgba(212, 100, 122, 0.8)',
                'rgba(184, 72, 94, 0.8)'
              ],
              borderColor: '#ee819f',
              borderWidth: 2,
              borderRadius: 8
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              title: {
                display: true,
                text: t.sleep_insights || 'Uyku Kalitesi Özeti',
                color: '#ee819f',
                font: { size: 16, weight: 'bold' }
              },
              legend: { display: false }
            },
            scales: {
              y: {
                beginAtZero: true,
                max: 10,
                grid: { color: 'rgba(238, 129, 159, 0.1)' },
                ticks: { 
                  font: { size: 11 },
                  stepSize: 2
                }
              },
              x: {
                grid: { display: false },
                ticks: { 
                  font: { size: 10 },
                  maxRotation: 0
                }
              }
            }
          }
        });
        console.log('Summary chart created successfully');
      } else {
        console.error('Summary chart container not found or no data');
      }

      // 3. Haftalık Uyku Saatleri (Basit Çubuk Grafik)
      const weeklyCtx = document.getElementById('correlationChart');
      if (weeklyCtx) {
        console.log('Creating weekly chart...');
        const weeklyChart = new Chart(weeklyCtx.getContext('2d'), {
          type: 'bar',
          data: {
            labels: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
            datasets: [{
              label: 'Uyku Saati',
              data: [7.5, 8, 7, 6.5, 6, 9, 8.5], // Örnek veri
              backgroundColor: 'rgba(238, 129, 159, 0.6)',
              borderColor: '#ee819f',
              borderWidth: 2,
              borderRadius: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              title: {
                display: true,
                text: t.weekly_pattern || 'Haftalık Uyku Düzeni',
                color: '#ee819f',
                font: { size: 16, weight: 'bold' }
              },
              legend: { display: false }
            },
            scales: {
              y: {
                beginAtZero: true,
                max: 12,
                grid: { color: 'rgba(238, 129, 159, 0.1)' },
                ticks: { 
                  font: { size: 11 },
                  callback: function(value) { return value + 'h'; }
                }
              },
              x: {
                grid: { display: false },
                ticks: { font: { size: 11 } }
              }
            }
          }
        });
        console.log('Weekly chart created successfully');
      } else {
        console.error('Weekly chart container not found');
      }

      // 4. Uyku Skoru Dağılımı (Donut Chart)
      const distributionCtx = document.getElementById('emotionalStressChart');
      if (distributionCtx && intelligenceData.length > 0) {
        console.log('Creating distribution chart...');
        const latest = intelligenceData[intelligenceData.length - 1];
        const currentScore = parseInt(latest.sleep_intelligence_score);
        let scoreCategory, scoreColor;

        if (currentScore >= 80) {
          scoreCategory = 'Mükemmel';
          scoreColor = '#4ade80';
        } else if (currentScore >= 60) {
          scoreCategory = 'İyi';
          scoreColor = '#ee819f';
        } else if (currentScore >= 40) {
          scoreCategory = 'Orta';
          scoreColor = '#fbbf24';
        } else {
          scoreCategory = 'Geliştirilmeli';
          scoreColor = '#f87171';
        }

        const distributionChart = new Chart(distributionCtx.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: [scoreCategory, 'Kalan'],
            datasets: [{
              data: [currentScore, 100 - currentScore],
              backgroundColor: [scoreColor, 'rgba(42, 42, 42, 0.3)'],
              borderColor: [scoreColor, 'rgba(42, 42, 42, 0.5)'],
              borderWidth: 3
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              title: {
                display: true,
                text: t.overall_score || 'Genel Uyku Skoru',
                color: '#ee819f',
                font: { size: 16, weight: 'bold' }
              },
              legend: {
                position: 'bottom',
                labels: {
                  font: { size: 12 },
                  usePointStyle: true,
                  padding: 20
                }
              }
            },
            cutout: '60%',
            elements: {
              arc: {
                borderRadius: 8
              }
            }
          },
          plugins: [{
            id: 'centerText',
            beforeDraw: function(chart) {
              const width = chart.width;
              const height = chart.height;
              const ctx = chart.ctx;
              
              ctx.restore();
              const fontSize = (height / 180).toFixed(2);
              ctx.font = fontSize + "em sans-serif";
              ctx.textBaseline = "middle";
              ctx.fillStyle = "#ee819f";
              
              const text = currentScore + "/100";
              const textX = Math.round((width - ctx.measureText(text).width) / 2);
              const textY = height / 2;
              
              ctx.fillText(text, textX, textY);
              ctx.save();
            }
          }]
        });
        console.log('Distribution chart created successfully');
      } else {
        console.error('Distribution chart container not found or no data');
      }
      
      console.log('All charts initialized successfully!');
      
         } catch (error) {
       console.error('Error initializing charts:', error);
       showChartFallbacks();
     }
   }
   
   // Function to show fallback data when charts fail
   function showChartFallbacks() {
     console.log('Showing chart fallbacks...');
     
     // Trend chart fallback
     const trendFallback = document.getElementById('trendChartFallback');
     const trendData = document.getElementById('trendChartData');
     if (trendFallback && intelligenceData.length > 0) {
       const latestScore = intelligenceData[intelligenceData.length - 1].sleep_intelligence_score;
       trendData.innerHTML = `
         <strong>Son Uyku Skoru:</strong> ${latestScore}/100<br>
         <strong>Toplam Analiz:</strong> ${intelligenceData.length} kayıt
       `;
       trendFallback.style.display = 'flex';
     }
     
     // Summary chart fallback
     const summaryFallback = document.getElementById('summaryChartFallback');
     const summaryData = document.getElementById('summaryChartData');
     if (summaryFallback && intelligenceData.length > 0) {
       const latest = intelligenceData[intelligenceData.length - 1];
       summaryData.innerHTML = `
         <strong>Duygusal Durum:</strong> ${parseFloat(latest.emotional_score).toFixed(1)}/10<br>
         <strong>REM Kalitesi:</strong> ${parseFloat(latest.rem_quality).toFixed(1)}/10<br>
         <strong>Uyku Verimi:</strong> ${parseFloat(latest.sleep_efficiency).toFixed(0)}%<br>
         <strong>Stres Yönetimi:</strong> ${(10 - parseFloat(latest.stress_level)).toFixed(1)}/10
       `;
       summaryFallback.style.display = 'flex';
     }
     
     // Weekly chart fallback
     const weeklyFallback = document.getElementById('weeklyChartFallback');
     const weeklyData = document.getElementById('weeklyChartData');
     if (weeklyFallback) {
       weeklyData.innerHTML = `
         <strong>Örnek Haftalık Veri:</strong><br>
         Pazartesi: 7.5h<br>
         Salı: 8.0h<br>
         Çarşamba: 7.0h<br>
         Perşembe: 6.5h<br>
         Cuma: 6.0h<br>
         Cumartesi: 9.0h<br>
         Pazar: 8.5h
       `;
       weeklyFallback.style.display = 'flex';
     }
     
     // Distribution chart fallback
     const distributionFallback = document.getElementById('distributionChartFallback');
     const distributionData = document.getElementById('distributionChartData');
     if (distributionFallback && intelligenceData.length > 0) {
       const latest = intelligenceData[intelligenceData.length - 1];
       const currentScore = parseInt(latest.sleep_intelligence_score);
       let scoreCategory;
       
       if (currentScore >= 80) {
         scoreCategory = 'Mükemmel';
       } else if (currentScore >= 60) {
         scoreCategory = 'İyi';
       } else if (currentScore >= 40) {
         scoreCategory = 'Orta';
       } else {
         scoreCategory = 'Geliştirilmeli';
       }
       
       distributionData.innerHTML = `
         <strong>Genel Skor:</strong> ${currentScore}/100<br>
         <strong>Kategori:</strong> ${scoreCategory}<br>
         <strong>Kalan:</strong> ${100 - currentScore} puan
       `;
       distributionFallback.style.display = 'flex';
     }
   }
  
     // Initialize charts when DOM is ready
   if (document.readyState === 'loading') {
     document.addEventListener('DOMContentLoaded', initializeCharts);
   } else {
     initializeCharts();
   }
   
   // Fallback timeout - if charts don't load within 5 seconds, show fallbacks
   setTimeout(() => {
     if (typeof Chart === 'undefined') {
       console.log('Chart.js failed to load, showing fallbacks...');
       showChartFallbacks();
     }
   }, 5000);
  <?php endif; ?>
  
  // Bottom Navigation Ripple Effect
  function createNavRipple(event, element) {
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    
    element.appendChild(ripple);
    
    setTimeout(() => {
      ripple.remove();
    }, 600);
  }

  // Add ripple effect to navigation items
  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function(e) {
      createNavRipple(e, this);
    });
  });

  // Enhanced animations and interactions
  document.addEventListener('DOMContentLoaded', function() {
    // Create particles
    createParticles();
    
    // Animate intelligence cards with staggered delay
    const cards = document.querySelectorAll('.intelligence-card');
    cards.forEach((card, index) => {
      card.style.animationDelay = (index * 0.15) + 's';
      card.style.animation = 'cardEntry 1s ease-out forwards';
    });
    
    // Enhanced hover effects for intelligence cards
    cards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-18px) scale(1.05) rotateY(8deg)';
        this.style.boxShadow = '0 40px 100px rgba(0, 0, 0, 0.5), 0 0 50px rgba(238, 129, 159, 0.4)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1) rotateY(0deg)';
        this.style.boxShadow = '0 20px 50px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1)';
      });
    });
    
    // Animate progress bars if they exist
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
      const width = bar.dataset.width || '0%';
      setTimeout(() => {
        bar.style.width = width;
      }, 800);
    });
    
    // Add dynamic glow effect to intelligence values
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animation = 'valueCount 2s ease-out, glowPulse 3s ease-in-out infinite alternate 2s';
        }
      });
    }, { threshold: 0.5 });
    
    document.querySelectorAll('.intelligence-value').forEach(value => {
      observer.observe(value);
    });
    
    // Enhanced button interactions
    const analyzeBtn = document.querySelector('.btn-analyze');
    if (analyzeBtn) {
      analyzeBtn.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.1)';
        this.style.boxShadow = '0 30px 70px rgba(238, 129, 159, 0.5), 0 0 0 4px rgba(255, 255, 255, 0.3)';
      });
      
      analyzeBtn.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0px) scale(1)';
        this.style.boxShadow = '0 15px 40px rgba(238, 129, 159, 0.4), 0 0 0 2px rgba(255, 255, 255, 0.1)';
      });
      
      // Add ripple effect on click
      analyzeBtn.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255, 255, 255, 0.6)';
        ripple.style.transform = 'scale(0)';
        ripple.style.animation = 'ripple 0.8s linear';
        ripple.style.pointerEvents = 'none';
        
        this.appendChild(ripple);
        
        setTimeout(() => {
          ripple.remove();
        }, 800);
      });
    }
    
    // Animate sections on scroll
    const sectionObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animation = 'sectionEntry 1.2s ease-out forwards';
        }
      });
    }, { threshold: 0.2 });
    
    document.querySelectorAll('.section').forEach(section => {
      sectionObserver.observe(section);
    });
    
    // Add parallax effect to dream orbs
    let mouseX = 0, mouseY = 0;
    document.addEventListener('mousemove', function(e) {
      mouseX = e.clientX / window.innerWidth;
      mouseY = e.clientY / window.innerHeight;
      
      document.querySelectorAll('.dream-orb').forEach((orb, index) => {
        const speed = (index + 1) * 0.5;
        const x = (mouseX - 0.5) * speed * 20;
        const y = (mouseY - 0.5) * speed * 20;
        orb.style.transform = `translate(${x}px, ${y}px)`;
      });

    });
    

    
    // Add smooth scrolling for all internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
    
    // Dynamic particle generation
    setInterval(() => {
      if (document.querySelectorAll('.particle').length < 20) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = '0s';
        particle.style.animationDuration = (15 + Math.random() * 10) + 's';
        document.getElementById('particles').appendChild(particle);
        
        setTimeout(() => {
          particle.remove();
        }, 25000);
      }
    }, 3000);
  });
  
  // Add CSS for additional animations
  const additionalStyles = document.createElement('style');
  additionalStyles.textContent = `
    @keyframes ripple {
      to {
        transform: scale(2.5);
        opacity: 0;
      }
    }
    
    @keyframes glowPulse {
      0% {
        text-shadow: 0 0 20px rgba(238, 129, 159, 0.4);
      }
      100% {
        text-shadow: 0 0 40px rgba(238, 129, 159, 0.6), 0 0 60px rgba(238, 129, 159, 0.4);
      }
    }
    
    .btn-analyze {
      position: relative;
      overflow: hidden;
    }
    
    .intelligence-card {
      transform-style: preserve-3d;
    }
    
    .chart-container {
      transform-style: preserve-3d;
    }
    
    .loading-overlay {
      backdrop-filter: blur(25px);
    }
    
    /* Enhanced focus styles */
    input[type="number"]:focus {
      animation: inputGlow 0.3s ease-out;
    }
    
    @keyframes inputGlow {
      0% {
        box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.4);
      }
      100% {
        box-shadow: 0 0 30px rgba(238, 129, 159, 0.4), inset 0 4px 8px rgba(0, 0, 0, 0.3), 0 0 0 3px rgba(238, 129, 159, 0.2);
      }
    }
    
    /* Mobile enhancements */
    @media (max-width: 768px) {
      .intelligence-card:hover {
        transform: translateY(-10px) scale(1.02);
      }
      
      .btn-analyze:hover {
        transform: translateY(-4px) scale(1.05);
      }
      
      .particle {
        animation-duration: 10s !important;
      }
    }
  `;
  document.head.appendChild(additionalStyles);
  
  // Add performance optimization
  let ticking = false;
  function updateAnimations() {
    // Batch DOM updates here
    ticking = false;
  }
  
  function requestTick() {
    if (!ticking) {
      requestAnimationFrame(updateAnimations);
      ticking = true;
    }
  }
  
  // Optimize scroll events
  let scrollTimeout;
  window.addEventListener('scroll', function() {
    if (scrollTimeout) {
      clearTimeout(scrollTimeout);
    }
    scrollTimeout = setTimeout(requestTick, 10);
  });
	// AI Modal functionality
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('aiModal');
  const modalTitle = document.getElementById('modalTitleText');
  const modalText = document.getElementById('modalText');
  const modalScore = document.getElementById('modalScore');
  const modalDate = document.getElementById('modalDate');
  const closeBtn = document.querySelector('.ai-modal-close');
  
  // Add click listeners to AI insight cards
  document.querySelectorAll('.ai-insight-card').forEach((card, index) => {
    card.classList.add('ai-insight-clickable');
    
    card.addEventListener('click', function() {
      // Get data from the card
      const title = this.querySelector('.ai-insight-title').textContent;
      const text = this.querySelector('.ai-insight-text').textContent;
      
      // Parse title to extract score and date
      const titleParts = title.split(' - Intelligence Score: ');
      const dateText = titleParts[0].replace('🧠 ', '');
      const scoreText = titleParts[1] || 'N/A';
      
      // Populate modal
      modalTitleText.textContent = '<?php echo $t['ai_comprehensive_analysis'] ?? 'AI Analysis'; ?>';
      modalText.textContent = text;
      modalScore.textContent = 'Score: ' + scoreText;
      modalDate.textContent = 'Analysis Date: ' + dateText;
      
      // Show modal
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
      
      // Add animation
      setTimeout(() => {
        modal.querySelector('.ai-modal-content').style.animation = 'modalSlideIn 0.4s ease';
      }, 10);
    });
    
    // Add hover effect
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateX(12px) translateY(-5px)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateX(8px) translateY(-3px)';
    });
  });
  
  // Close modal functions
  function closeModal() {
    modal.style.animation = 'fadeOut 0.3s ease';
    setTimeout(() => {
      modal.style.display = 'none';
      modal.style.animation = '';
      document.body.style.overflow = 'auto';
    }, 300);
  }
  
  // Close button
  closeBtn.addEventListener('click', closeModal);
  
  // Close on outside click
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeModal();
    }
  });
  
  // Close on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display === 'block') {
      closeModal();
    }
  });
});

// Add fadeOut animation to CSS
const fadeOutStyle = document.createElement('style');
fadeOutStyle.textContent = `
  @keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
  }
`;
document.head.appendChild(fadeOutStyle);
</script>

</body>
</html>