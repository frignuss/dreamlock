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
    'add_dream' => 'Add New Dream & Analyze',
    'write_dream' => 'Write your dream',
    'dream_title' => 'Dream title',
    'analyze_now' => 'Analyze Now',
    'past_dreams' => 'Past Dreams',
    'title' => 'Title',
    'ai_analysis' => 'AI Analysis',
    'dream_type' => 'Dream Type',
    'no_dreams' => 'No dreams have been added yet.',
    'home' => 'Home',
    'subconscious' => 'subconscious',
    'sleep' => 'Sleep Analysis',
    'logout' => 'Log Out',
    'delete_confirm' => 'Are you sure?',
    'save_error' => 'Dream could not be saved. Please try again.',
    'load_error' => 'Error loading dreams. Please try again later.',
    'culture_selection' => 'Select Culture',
    'culture_none' => 'None (General Analysis)',
    'culture_islam' => 'Islam',
    'culture_christianity' => 'Christianity', 
    'culture_hinduism' => 'Hinduism',
    'culture_buddhism' => 'Buddhism',
    'culture_greek' => 'Greek Mythology',
    'nightmare' => 'Nightmare',
    'lucid' => 'Lucid Dream',
    'prophetic' => 'Prophetic',
    'normal' => 'Normal Dream',
    'recurring' => 'Recurring',
    'spiritual' => 'Spiritual',
    'symbolic' => 'Symbolic',
    'emotional' => 'Emotional',
	      'premium' => 'Premium',
    'upgrade_premium' => 'Upgrade to Premium',
    'please_wait' => 'AI is analyzing your dreams...',
    'processing_dreams' => 'Processing dreams',
    'calculating_metrics' => 'AI analysis',
    'generating_insights' => 'Generating insights',
    'finalizing_analysis' => 'Finalizing',
    'share_dream' => 'Share Dream',
    'dream_copied' => 'Dream copied to clipboard!',
    'share_failed' => 'Failed to copy dream',
    'dream_sharing' => 'Dream Sharing',
    'sharing_dream' => 'Sharing dream...',
    'dream_shared_success' => 'Dream shared successfully!',
    'share_error' => 'An error occurred while sharing the dream',
    'dream_shared' => 'Dream Shared',
    'dream_analytics' => 'Dream Analytics',
  ],
  'tr' => [
    'welcome' => 'Hoş Geldiniz',
    'add_dream' => 'Yeni Rüya Ekle ve Analiz Et',
    'write_dream' => 'Rüyanızı yazın',
    'dream_title' => 'Rüya başlığı',
    'analyze_now' => 'Şimdi Analiz Et',
    'past_dreams' => 'Geçmiş Rüyalar',
    'title' => 'Başlık',
    'ai_analysis' => 'AI Analizi',
    'dream_type' => 'Rüya Türü',
    'no_dreams' => 'Henüz hiç rüya eklenmemiş.',
    'home' => 'Ana Sayfa',
    'subconscious' => 'Bilinçaltı',
    'sleep' => 'Uyku Analizi',
    'logout' => 'Çıkış Yap',
    'delete_confirm' => 'Emin misiniz?',
    'save_error' => 'Rüya kaydedilemedi. Lütfen tekrar deneyin.',
    'load_error' => 'Rüyalar yüklenirken hata oluştu. Lütfen daha sonra tekrar deneyin.',
    'culture_selection' => 'Kültür Seçin',
    'culture_none' => 'Hiçbiri (Genel Analiz)',
    'culture_islam' => 'İslam',
    'culture_christianity' => 'Hristiyan', 
    'culture_hinduism' => 'Hinduizm',
    'culture_buddhism' => 'Budizm',
    'culture_greek' => 'Yunan Mitolojisi',
    'nightmare' => 'Kabus',
    'lucid' => 'Lucid Rüya',
    'prophetic' => 'Kehanet',
    'normal' => 'Normal Rüya',
    'recurring' => 'Tekrarlayan',
    'spiritual' => 'Ruhani',
    'symbolic' => 'Sembolik',
    'emotional' => 'Duygusal',
	  'premium' => 'Premium',
    'upgrade_premium' => 'Premium\'a Yükselt',
    'please_wait' => 'AI rüyalarınızı analiz ediyor...',
    'processing_dreams' => 'Rüyalar işleniyor',
    'calculating_metrics' => 'AI analizi',
    'generating_insights' => 'Öngörüler oluşturuluyor',
    'finalizing_analysis' => 'Tamamlanıyor',
    'share_dream' => 'Rüyayı Paylaş',
    'dream_copied' => 'Rüya panoya kopyalandı!',
    'share_failed' => 'Rüya kopyalanamadı',
    'dream_sharing' => 'Rüya Paylaşımı',
    'sharing_dream' => 'Rüya paylaşılıyor...',
    'dream_shared_success' => 'Rüya başarıyla paylaşıldı!',
    'share_error' => 'Rüya paylaşılırken hata oluştu',
    'dream_shared' => 'Rüya Paylaşıldı',
    'dream_analytics' => 'Rüya Analizi',
  ],
  'es' => [
    'welcome' => 'Bienvenido',
    'add_dream' => 'Agregar Nuevo Sueño y Analizar',
    'write_dream' => 'Escribe tu sueño',
    'dream_title' => 'Título del sueño',
    'analyze_now' => 'Analizar Ahora',
    'past_dreams' => 'Sueños Pasados',
    'title' => 'Título',
    'ai_analysis' => 'Análisis IA',
    'dream_type' => 'Tipo de Sueño',
    'no_dreams' => 'Aún no se han agregado sueños.',
    'home' => 'Inicio',
    'subconscious' => 'subconsciente',
    'sleep' => 'Análisis del sueño',
    'logout' => 'Cerrar Sesión',
    'delete_confirm' => '¿Estás seguro?',
    'save_error' => 'No se pudo guardar el sueño. Inténtalo de nuevo.',
    'load_error' => 'Error al cargar los sueños. Inténtalo más tarde.',
    'culture_selection' => 'Seleccionar Cultura',
    'culture_none' => 'Ninguna (Análisis General)',
    'culture_islam' => 'Islam',
    'culture_christianity' => 'Cristianismo', 
    'culture_hinduism' => 'Hinduismo',
    'culture_buddhism' => 'Budismo',
    'culture_greek' => 'Mitología griega',
    'nightmare' => 'Pesadilla',
    'lucid' => 'Sueño Lúcido',
    'prophetic' => 'Profético',
    'normal' => 'Sueño Normal',
    'recurring' => 'Recurrente',
    'spiritual' => 'Espiritual',
    'symbolic' => 'Simbólico',
    'emotional' => 'Emocional',
	  'premium' => 'Premium', 
    'upgrade_premium' => 'Actualizar a Premium',
    'please_wait' => 'IA está analizando tus sueños...',
    'processing_dreams' => 'Procesando sueños',
    'calculating_metrics' => 'Análisis IA',
    'generating_insights' => 'Generando perspectivas',
    'finalizing_analysis' => 'Finalizando',
    'share_dream' => 'Compartir Sueño',
    'dream_copied' => '¡Sueño copiado al portapapeles!',
    'share_failed' => 'No se pudo copiar el sueño',
    'dream_sharing' => 'Compartir Sueños',
    'sharing_dream' => 'Compartiendo sueño...',
    'dream_shared_success' => '¡Sueño compartido exitosamente!',
    'share_error' => 'Ocurrió un error al compartir el sueño',
    'dream_shared' => 'Sueño Compartido',
    'dream_analytics' => 'Análisis de Sueños',
  ],
];

$culture_contexts = [
  'none' => [
    'en' => 'Analyze this dream from a general psychological perspective, focusing on universal dream symbolism, emotions, and subconscious patterns without any specific cultural or religious context.',
    'tr' => 'Bu rüyayı genel psikolojik perspektiften analiz et, evrensel rüya sembolizmi, duygular ve bilinçaltı kalıplarına odaklan, herhangi bir özel kültürel veya dini bağlam olmadan.',
    'es' => 'Analiza este sueño desde una perspectiva psicológica general, enfocándote en el simbolismo universal de los sueños, emociones y patrones subconscientes sin ningún contexto cultural o religioso específico.',
    'fr' => 'Analysez ce rêve dans une perspective psychologique générale, en vous concentrant sur le symbolisme universel des rêves, les émotions et les schémas subconscients sans aucun contexte culturel ou religieux spécifique.'
  ],
  'islam' => [
    'en' => 'Analyze this dream from an Islamic perspective, considering Islamic dream interpretation traditions, symbolism in Quran and Hadith.',
    'tr' => 'Bu rüyayı İslami perspektiften analiz et, İslami rüya yorumlama gelenekleri, Kuran ve Hadislerdeki sembolizmi göz önünde bulundur.',
    'es' => 'Analiza este sueño desde una perspectiva islámica, considerando las tradiciones de interpretación de sueños islámicos.',
    'fr' => 'Analysez ce rêve dans une perspective islamique, en considérant les traditions d\'interprétation des rêves islamiques.'
  ],
  'christianity' => [
    'en' => 'Analyze this dream from a Christian perspective, considering Biblical symbolism and Christian spiritual traditions.',
    'tr' => 'Bu rüyayı Hristiyan perspektifinden analiz et, İncil sembolizmi ve Hristiyan ruhani geleneklerini göz önünde bulundur.',
    'es' => 'Analiza este sueño desde una perspectiva cristiana, considerando el simbolismo bíblico.',
    'fr' => 'Analysez ce rêve dans une perspective chrétienne, en considérant le symbolisme biblique.'
  ],
  'hinduism' => [
    'en' => 'Analyze this dream from a Hindu perspective, considering Vedic traditions, karma, and spiritual symbolism.',
    'tr' => 'Bu rüyayı Hindu perspektifinden analiz et, Vedik gelenekler, karma ve ruhani sembolizmi göz önünde bulundur.',
    'es' => 'Analiza este sueño desde una perspectiva hindú, considerando las tradiciones védicas.',
    'fr' => 'Analysez ce rêve dans une perspective hindoue, en considérant les traditions védiques.'
  ],
  'buddhism' => [
    'en' => 'Analyze this dream from a Buddhist perspective, considering meditation, enlightenment, and Buddhist symbolism.',
    'tr' => 'Bu rüyayı Budist perspektifinden analiz et, meditasyon, aydınlanma ve Budist sembolizmi göz önünde bulundur.',
    'es' => 'Analiza este sueño desde una perspectiva budista, considerando la meditación y la iluminación.',
    'fr' => 'Analysez ce rêve dans une perspective bouddhiste, en considérant la méditation et l\'illumination.'
  ],
  'greek' => [
    'en' => 'Analyze this dream from a Greek mythology perspective, considering ancient Greek dream interpretation and mythological symbolism.',
    'tr' => 'Bu rüyayı Yunan mitolojisi perspektifinden analiz et, antik Yunan rüya yorumu ve mitolojik sembolizmi göz önünde bulundur.',
    'es' => 'Analiza este sueño desde una perspectiva de mitología griega, considerando la interpretación antigua.',
    'fr' => 'Analysez ce rêve dans une perspective de mythologie grecque, en considérant l\'interprétation antique.'
  ]
];

$t = $translations[$lang] ?? $translations['en'];

// Database connection
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);

// Function to get valid user ID
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

// Function to classify dream type using AI
function classifyDreamType($dream_text, $lang = 'en') {
    $type_prompts = [
        'en' => "Classify this dream into ONE of these categories only: nightmare, lucid, prophetic, normal, recurring, spiritual, symbolic, emotional. Respond with only the category name:\n\n",
        'tr' => "Bu rüyayı sadece şu kategorilerden BİRİNE ayır: nightmare, lucid, prophetic, normal, recurring, spiritual, symbolic, emotional. Sadece kategori adıyla cevap ver:\n\n",
        'es' => "Clasifica este sueño en UNA de estas categorías solamente: nightmare, lucid, prophetic, normal, recurring, spiritual, symbolic, emotional. Responde solo con el nombre de la categoría:\n\n",
        'fr' => "Classifiez ce rêve dans UNE de ces catégories seulement: nightmare, lucid, prophetic, normal, recurring, spiritual, symbolic, emotional. Répondez seulement avec le nom de la catégorie:\n\n"
    ];

    $system_prompt = [
        'en' => 'You are a dream type classifier. Respond with only one word from these options: nightmare, lucid, prophetic, normal, recurring, spiritual, symbolic, emotional',
        'tr' => 'Sen bir rüya türü sınıflandırıcısısın. Sadece şu seçeneklerden bir kelimeyle cevap ver: nightmare, lucid, prophetic, normal, recurring, spiritual, symbolic, emotional',
        'es' => 'Eres un clasificador de tipos de sueños. Responde solo con una palabra de estas opciones: nightmare, lucid, prophetic, normal, recurring, spiritual, symbolic, emotional',
        'fr' => 'Vous êtes un classificateur de types de rêves. Répondez seulement avec un mot de ces options: nightmare, lucid, prophetic, normal, recurring, spiritual, symbolic, emotional'
    ];

    $prompt = ($type_prompts[$lang] ?? $type_prompts['en']) . $dream_text;

    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENROUTER_API_KEY,
        'HTTP-Referer: http://localhost',
        'X-Title: DreamLock Dream Type Classifier'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'deepseek/deepseek-chat-v3-0324',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt[$lang] ?? $system_prompt['en']],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.3,
        'max_tokens' => 20
    ]));

    $response = curl_exec($ch);
    
    if (curl_error($ch)) {
        curl_close($ch);
        return 'normal';
    }
    
    $result = json_decode($response, true);
    curl_close($ch);
    
    $dream_type = trim(strtolower($result['choices'][0]['message']['content'] ?? 'normal'));
    
    // Validate dream type
    $valid_types = ['nightmare', 'lucid', 'prophetic', 'normal', 'recurring', 'spiritual', 'symbolic', 'emotional'];
    return in_array($dream_type, $valid_types) ? $dream_type : 'normal';
}

$current_user_id = getValidUserId($db);

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: index.php");
  exit();
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $stmt = $db->prepare("DELETE FROM dreams WHERE id = ? AND user_id = ?");
  $stmt->execute([$_GET['delete'], $current_user_id]);
  header("Location: dream.php");
  exit();
}

if (isset($_POST['submit_dream'])) {
  // Check premium status and dream limit
  $stmt = $db->prepare("SELECT is_premium, premium_expires_at FROM users WHERE id = ?");
  $stmt->execute([$current_user_id]);
  $user_premium = $stmt->fetch(PDO::FETCH_ASSOC);

  $is_premium = $user_premium['is_premium'] && (!$user_premium['premium_expires_at'] || strtotime($user_premium['premium_expires_at']) > time());

  if (!$is_premium) {
    $stmt = $db->prepare("SELECT COUNT(*) as dream_count FROM dreams WHERE user_id = ?");
    $stmt->execute([$current_user_id]);
    $dream_count = $stmt->fetch(PDO::FETCH_ASSOC)['dream_count'];
    
    if ($dream_count >= 4) {
      $error_message = $lang === 'tr' ? 'Ücretsiz kullanıcılar sadece 4 rüya ekleyebilir. Premium\'a yükseltin!' : 
                      ($lang === 'es' ? 'Los usuarios gratuitos solo pueden agregar 4 sueños. ¡Actualiza a Premium!' :
                      ($lang === 'fr' ? 'Les utilisateurs gratuits ne peuvent ajouter que 4 rêves. Passez à Premium!' :
                      'Free users can only add 4 dreams. Upgrade to Premium!'));
    } else {
      // Process dream for free user
      $culture = $_POST['culture'] ?? 'none';
      $dream = trim($_POST['dream_text']);
      $date = trim($_POST['open_date']);
      $user_id = $current_user_id;

      // Get dream type
      $dream_type = classifyDreamType($dream, $lang);

      // Get dream analysis
      $analysis_prompts = [
        'en' => "Analyze this dream briefly in 2-3 sentences. Focus on key symbols and emotions:\n\n",
        'tr' => "Bu rüyayı 2-3 cümle ile kısaca analiz et. Ana semboller ve duygulara odaklan:\n\n",
        'es' => "Analiza este sueño brevemente en 2-3 oraciones. Enfócate en símbolos clave y emociones:\n\n",
        'fr' => "Analysez ce rêve brièvement en 2-3 phrases. Concentrez-vous sur les symboles clés et les émotions:\n\n"
      ];
      
          $system_prompts = [
      'en' => $culture === 'none' ? 'You are a professional dream analyst specializing in general psychological dream interpretation. Provide concise, meaningful analysis in 2-3 sentences maximum.' : 'You are a professional dream analyst specializing in ' . $culture . ' interpretation. Provide concise, meaningful analysis in 2-3 sentences maximum.',
      'tr' => $culture === 'none' ? 'Sen genel psikolojik rüya yorumlamasında uzmanlaşmış profesyonel bir rüya analistisin. Maksimum 2-3 cümle ile özlü ve anlamlı analiz yap.' : 'Sen ' . $culture . ' yorumlamasında uzmanlaşmış profesyonel bir rüya analistisin. Maksimum 2-3 cümle ile özlü ve anlamlı analiz yap.',
      'es' => $culture === 'none' ? 'Eres un analista profesional de sueños especializado en interpretación psicológica general. Proporciona análisis conciso y significativo en máximo 2-3 oraciones.' : 'Eres un analista profesional de sueños especializado en interpretación ' . $culture . '. Proporciona análisis conciso y significativo en máximo 2-3 oraciones.',
      'fr' => $culture === 'none' ? 'Vous êtes un analyste professionnel des rêves spécialisé dans l\'interprétation psychologique générale. Fournissez une analyse concise et significative en 2-3 phrases maximum.' : 'Vous êtes un analyste professionnel des rêves spécialisé dans l\'interprétation ' . $culture . '. Fournissez une analyse concise et significative en 2-3 phrases maximum.'
    ];

      $cultural_context = $culture_contexts[$culture][$lang] ?? $culture_contexts[$culture]['en'];
      $prompt = $cultural_context . "\n\n" . ($analysis_prompts[$lang] ?? $analysis_prompts['en']) . $dream;

      $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENROUTER_API_KEY,
        'HTTP-Referer: http://localhost',
        'X-Title: DreamLock Dream Analyzer'
      ]);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'deepseek/deepseek-chat-v3-0324',
        'messages' => [
          ['role' => 'system', 'content' => $system_prompts[$lang] ?? $system_prompts['en']],
          ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.7,
        'max_tokens' => 350
      ]));

      $response = curl_exec($ch);
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      
      if (curl_error($ch)) {
        $analysis = $lang === 'tr' ? 'Bağlantı hatası nedeniyle analiz tamamlanamadı.' : 
                    ($lang === 'es' ? 'El análisis no pudo completarse debido a un error de conexión.' :
                    ($lang === 'fr' ? 'L\'analyse n\'a pas pu être complétée en raison d\'une erreur de connexion.' :
                    'Analysis could not be completed due to connection error.'));
        error_log('AI analysis cURL error: ' . curl_error($ch));
      } else {
        $result = json_decode($response, true);
        $json_ok = json_last_error() === JSON_ERROR_NONE;
        $content = $result['choices'][0]['message']['content'] ?? null;
        if ($http_code !== 200 || !$json_ok || empty($content)) {
          error_log('AI analysis API issue. HTTP: ' . $http_code . ' JSON_OK: ' . ($json_ok ? 'yes' : 'no') . ' Response: ' . substr((string)$response, 0, 1000));
          $analysis = ($lang === 'tr' ? 'Analiz mevcut değil.' : 
                      ($lang === 'es' ? 'Análisis no disponible.' :
                      ($lang === 'fr' ? 'Analyse non disponible.' :
                      'Analysis not available.')));
        } else {
          $analysis = $content;
        }
      }
      
      curl_close($ch);

      // Save to database with dream type
      try {
        $stmt = $db->prepare("INSERT INTO dreams (user_id, dream_text, open_date, analysis, dream_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $dream, $date, $analysis, $dream_type]);
        header("Location: dream.php");
        exit();
      } catch (PDOException $e) {
        $error_message = $t['save_error'];
        error_log("Dream save error: " . $e->getMessage());
      }
    }
  } else {
    // Premium user processing
    $culture = $_POST['culture'] ?? 'none';
    $dream = trim($_POST['dream_text']);
    $date = trim($_POST['open_date']);
    $user_id = $current_user_id;

    // Get dream type
    $dream_type = classifyDreamType($dream, $lang);

    // Same analysis code as above
    $analysis_prompts = [
      'en' => "Analyze this dream briefly in 2-3 sentences. Focus on key symbols and emotions:\n\n",
      'tr' => "Bu rüyayı 2-3 cümle ile kısaca analiz et. Ana semboller ve duygulara odaklan:\n\n",
      'es' => "Analiza este sueño brevemente en 2-3 oraciones. Enfócate en símbolos clave y emociones:\n\n",
      'fr' => "Analysez ce rêve brièvement en 2-3 phrases. Concentrez-vous sur les symboles clés et les émotions:\n\n"
    ];
    
    $system_prompts = [
      'en' => $culture === 'none' ? 'You are a professional dream analyst specializing in general psychological dream interpretation. Provide concise, meaningful analysis in 2-3 sentences maximum.' : 'You are a professional dream analyst specializing in ' . $culture . ' interpretation. Provide concise, meaningful analysis in 2-3 sentences maximum.',
      'tr' => $culture === 'none' ? 'Sen genel psikolojik rüya yorumlamasında uzmanlaşmış profesyonel bir rüya analistisin. Maksimum 2-3 cümle ile özlü ve anlamlı analiz yap.' : 'Sen ' . $culture . ' yorumlamasında uzmanlaşmış profesyonel bir rüya analistisin. Maksimum 2-3 cümle ile özlü ve anlamlı analiz yap.',
      'es' => $culture === 'none' ? 'Eres un analista profesional de sueños especializado en interpretación psicológica general. Proporciona análisis conciso y significativo en máximo 2-3 oraciones.' : 'Eres un analista profesional de sueños especializado en interpretación ' . $culture . '. Proporciona análisis conciso y significativo en máximo 2-3 oraciones.',
      'fr' => $culture === 'none' ? 'Vous êtes un analyste professionnel des rêves spécialisé dans l\'interprétation psychologique générale. Fournissez une analyse concise et significative en 2-3 phrases maximum.' : 'Vous êtes un analyste professionnel des rêves spécialisé dans l\'interprétation ' . $culture . '. Fournissez une analyse concise et significative en 2-3 phrases maximum.'
    ];

    $cultural_context = $culture_contexts[$culture][$lang] ?? $culture_contexts[$culture]['en'];
    $prompt = $cultural_context . "\n\n" . ($analysis_prompts[$lang] ?? $analysis_prompts['en']) . $dream;

    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Authorization: Bearer ' . OPENROUTER_API_KEY,
      'HTTP-Referer: http://localhost',
      'X-Title: DreamLock Dream Analyzer'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
      'model' => 'deepseek/deepseek-chat-v3-0324',
      'messages' => [
        ['role' => 'system', 'content' => $system_prompts[$lang] ?? $system_prompts['en']],
        ['role' => 'user', 'content' => $prompt]
      ],
      'temperature' => 0.7,
      'max_tokens' => 150
    ]));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_error($ch)) {
      $analysis = $lang === 'tr' ? 'Bağlantı hatası nedeniyle analiz tamamlanamadı.' : 
                  ($lang === 'es' ? 'El análisis no pudo completarse debido a un error de conexión.' :
                  ($lang === 'fr' ? 'L\'analyse n\'a pas pu être complétée en raison d\'une erreur de connexion.' :
                  'Analysis could not be completed due to connection error.'));
      error_log('AI analysis cURL error: ' . curl_error($ch));
    } else {
      $result = json_decode($response, true);
      $json_ok = json_last_error() === JSON_ERROR_NONE;
      $content = $result['choices'][0]['message']['content'] ?? null;
      if ($http_code !== 200 || !$json_ok || empty($content)) {
        error_log('AI analysis API issue. HTTP: ' . $http_code . ' JSON_OK: ' . ($json_ok ? 'yes' : 'no') . ' Response: ' . substr((string)$response, 0, 1000));
        $analysis = ($lang === 'tr' ? 'Analiz mevcut değil.' : 
                    ($lang === 'es' ? 'Análisis no disponible.' :
                    ($lang === 'fr' ? 'Analyse non disponible.' :
                    'Analysis not available.')));
      } else {
        $analysis = $content;
      }
    }
    
    curl_close($ch);

    // Save to database with dream type
    try {
      $stmt = $db->prepare("INSERT INTO dreams (user_id, dream_text, open_date, analysis, dream_type) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$user_id, $dream, $date, $analysis, $dream_type]);
      header("Location: dream.php");
      exit();
    } catch (PDOException $e) {
      $error_message = $t['save_error'];
      error_log("Dream save error: " . $e->getMessage());
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DreamLock - My Dreams</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <style>
    :root {
      --primary-green: #39FF14;
      --secondary-green: #2ecc71;
      --dark-bg: #0a0a0a;
      --card-bg: #1a1a1a;
      --text-light: #ffffff;
      --text-muted: #888888;
      --border-color: #2a2a2a;
      --success-bg: #0f2a0f;
      --success-text: #b6fcb6;
      --danger-bg: #2a0f0f;
      --danger-text: #ff6b6b;
      
      /* Dream Type Colors */
      --nightmare-color: #ff4757;
      --lucid-color: #5352ed;
      --prophetic-color: #ffd700;
      --normal-color: #39FF14;
      --recurring-color: #ff6b6b;
      --spiritual-color: #9c88ff;
      --symbolic-color: #ffa502;
      --emotional-color: #ff7675;
    }
    
    * { 
      font-family: 'Inter', 'Manrope', sans-serif; 
      font-weight: 400;
    }
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
    /* PREMIUM BACKGROUND SYSTEM - START */
    
    /* Advanced Dream Background */
    .dream-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -10;
      background: 
          radial-gradient(circle at 15% 25%, rgba(57, 255, 20, 0.08) 0%, transparent 40%),
          radial-gradient(circle at 85% 75%, rgba(46, 204, 113, 0.06) 0%, transparent 35%),
          radial-gradient(circle at 60% 15%, rgba(57, 255, 20, 0.04) 0%, transparent 50%),
          radial-gradient(circle at 30% 85%, rgba(57, 255, 20, 0.05) 0%, transparent 45%),
          linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #0f0f0f 100%);
      animation: dreamPulse 8s ease-in-out infinite alternate;
    }

    @keyframes dreamPulse {
      0% {
          filter: brightness(1) contrast(1);
      }
      100% {
          filter: brightness(1.1) contrast(1.05);
      }
    }

    /* Floating Dream Orbs */
    .dream-orb {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(1px);
      animation: floatDream 12s infinite ease-in-out;
    }

    .dream-orb:nth-child(1) {
      width: 120px;
      height: 120px;
      background: radial-gradient(circle, rgba(57, 255, 20, 0.1) 0%, rgba(57, 255, 20, 0.02) 70%, transparent 100%);
      top: 10%;
      left: 15%;
      animation-delay: -2s;
      animation-duration: 15s;
    }

    .dream-orb:nth-child(2) {
      width: 80px;
      height: 80px;
      background: radial-gradient(circle, rgba(46, 204, 113, 0.08) 0%, rgba(46, 204, 113, 0.01) 70%, transparent 100%);
      top: 60%;
      right: 20%;
      animation-delay: -5s;
      animation-duration: 18s;
    }

    .dream-orb:nth-child(3) {
      width: 100px;
      height: 100px;
      background: radial-gradient(circle, rgba(57, 255, 20, 0.06) 0%, rgba(57, 255, 20, 0.01) 70%, transparent 100%);
      bottom: 20%;
      left: 25%;
      animation-delay: -8s;
      animation-duration: 20s;
    }

    .dream-orb:nth-child(4) {
      width: 60px;
      height: 60px;
      background: radial-gradient(circle, rgba(57, 255, 20, 0.12) 0%, rgba(57, 255, 20, 0.03) 70%, transparent 100%);
      top: 30%;
      right: 35%;
      animation-delay: -3s;
      animation-duration: 14s;
    }

    .dream-orb:nth-child(5) {
      width: 90px;
      height: 90px;
      background: radial-gradient(circle, rgba(46, 204, 113, 0.07) 0%, rgba(46, 204, 113, 0.02) 70%, transparent 100%);
      bottom: 40%;
      right: 15%;
      animation-delay: -6s;
      animation-duration: 16s;
    }

    @keyframes floatDream {
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

    /* Mystical Particles */
    .particle-system {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -8;
    }

    .mystical-particle {
      position: absolute;
      background: #39FF14;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(0.5px);
      box-shadow: 0 0 10px rgba(57, 255, 20, 0.6);
    }

    /* Dream Waves */
    .dream-wave {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -9;
      background: 
          linear-gradient(90deg, transparent 0%, rgba(57, 255, 20, 0.02) 50%, transparent 100%);
      animation: waveMotion 25s linear infinite;
    }

    @keyframes waveMotion {
      0% {
          transform: translateX(-100%) skewX(-15deg);
      }
      100% {
          transform: translateX(100%) skewX(-15deg);
      }
    }

    /* Ethereal Grid */
    .ethereal-grid {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -7;
      background-image: 
          linear-gradient(rgba(57, 255, 20, 0.03) 1px, transparent 1px),
          linear-gradient(90deg, rgba(57, 255, 20, 0.03) 1px, transparent 1px);
      background-size: 100px 100px;
      animation: gridPulse 10s ease-in-out infinite;
      opacity: 0.3;
    }

    @keyframes gridPulse {
      0%, 100% {
          opacity: 0.2;
          transform: scale(1);
      }
      50% {
          opacity: 0.4;
          transform: scale(1.02);
      }
    }

    /* Nebula Effect */
    .nebula-layer {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -6;
      background: 
          radial-gradient(ellipse at 20% 30%, rgba(57, 255, 20, 0.05) 0%, transparent 60%),
          radial-gradient(ellipse at 80% 70%, rgba(46, 204, 113, 0.04) 0%, transparent 50%),
          radial-gradient(ellipse at 60% 20%, rgba(57, 255, 20, 0.03) 0%, transparent 70%);
      animation: nebulaSwirl 30s linear infinite;
    }

    @keyframes nebulaSwirl {
      0% {
          transform: rotate(0deg) scale(1);
      }
      100% {
          transform: rotate(360deg) scale(1.1);
      }
    }

    /* Starfield */
    .starfield {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -5;
    }

    .star {
      position: absolute;
      background: #39FF14;
      border-radius: 50%;
      animation: twinkle 3s ease-in-out infinite;
    }

    @keyframes twinkle {
      0%, 100% {
          opacity: 0.3;
          transform: scale(1);
      }
      50% {
          opacity: 1;
          transform: scale(1.2);
      }
    }

    /* Dream Ripples */
    .dream-ripple {
      position: fixed;
      border: 2px solid rgba(57, 255, 20, 0.1);
      border-radius: 50%;
      pointer-events: none;
      z-index: -4;
      animation: rippleExpand 6s linear infinite;
    }

    @keyframes rippleExpand {
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

    /* Ambient Light Rays */
    .light-ray {
      position: fixed;
      width: 2px;
      height: 100%;
      background: linear-gradient(to bottom, 
          transparent 0%, 
          rgba(57, 255, 20, 0.1) 30%, 
          rgba(57, 255, 20, 0.05) 50%, 
          rgba(57, 255, 20, 0.1) 70%, 
          transparent 100%);
      pointer-events: none;
      z-index: -3;
      animation: rayMove 15s linear infinite;
      filter: blur(1px);
    }

    .light-ray:nth-child(1) {
      left: 10%;
      animation-delay: -2s;
      animation-duration: 18s;
    }

    .light-ray:nth-child(2) {
      left: 35%;
      animation-delay: -7s;
      animation-duration: 22s;
    }

    .light-ray:nth-child(3) {
      right: 20%;
      animation-delay: -4s;
      animation-duration: 16s;
    }

    .light-ray:nth-child(4) {
      right: 45%;
      animation-delay: -9s;
      animation-duration: 20s;
    }

    @keyframes rayMove {
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
    
    /* DREAM TYPE STYLING SYSTEM - START */
    
    /* Dream Type Badge Styles */
    .dream-type-badge {
      position: absolute;
      top: 15px;
      left: 15px;
      padding: 8px 16px;
      border-radius: 25px;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      border: 2px solid;
      backdrop-filter: blur(10px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 2;
      animation: typeBadgeGlow 3s ease-in-out infinite;
    }

    @keyframes typeBadgeGlow {
      0%, 100% {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      }
      50% {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4), 0 0 20px currentColor;
      }
    }

    /* Nightmare Styling */
    .dream-type-nightmare {
      background: linear-gradient(135deg, rgba(255, 71, 87, 0.9) 0%, rgba(139, 0, 0, 0.8) 100%);
      color: #ffffff;
      border-color: var(--nightmare-color);
      text-shadow: 0 0 10px rgba(255, 71, 87, 0.8);
      animation: nightmarePulse 2s ease-in-out infinite alternate;
    }

    @keyframes nightmarePulse {
      0% {
        background: linear-gradient(135deg, rgba(255, 71, 87, 0.9) 0%, rgba(139, 0, 0, 0.8) 100%);
        transform: scale(1);
      }
      100% {
        background: linear-gradient(135deg, rgba(255, 71, 87, 1) 0%, rgba(139, 0, 0, 0.9) 100%);
        transform: scale(1.05);
      }
    }

    /* Lucid Dream Styling */
    .dream-type-lucid {
      background: linear-gradient(135deg, rgba(83, 82, 237, 0.9) 0%, rgba(130, 87, 229, 0.8) 100%);
      color: #ffffff;
      border-color: var(--lucid-color);
      text-shadow: 0 0 10px rgba(83, 82, 237, 0.8);
      animation: lucidShimmer 3s ease-in-out infinite;
    }

    @keyframes lucidShimmer {
      0%, 100% {
        background: linear-gradient(135deg, rgba(83, 82, 237, 0.9) 0%, rgba(130, 87, 229, 0.8) 100%);
      }
      50% {
        background: linear-gradient(135deg, rgba(130, 87, 229, 0.9) 0%, rgba(83, 82, 237, 0.8) 100%);
      }
    }

    /* Prophetic Dream Styling */
    .dream-type-prophetic {
      background: linear-gradient(135deg, rgba(255, 215, 0, 0.9) 0%, rgba(255, 193, 7, 0.8) 100%);
      color: #000000;
      border-color: var(--prophetic-color);
      text-shadow: 0 0 10px rgba(255, 215, 0, 0.8);
      animation: propheticGlow 2.5s ease-in-out infinite;
    }

    @keyframes propheticGlow {
      0%, 100% {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3), 0 0 20px rgba(255, 215, 0, 0.5);
      }
      50% {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4), 0 0 30px rgba(255, 215, 0, 0.8);
        transform: scale(1.1);
      }
    }

    /* Normal Dream Styling */
    .dream-type-normal {
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.9) 0%, rgba(46, 204, 113, 0.8) 100%);
      color: #000000;
      border-color: var(--normal-color);
      text-shadow: 0 0 10px rgba(57, 255, 20, 0.8);
    }

    /* Recurring Dream Styling */
    .dream-type-recurring {
      background: linear-gradient(135deg, rgba(255, 107, 107, 0.9) 0%, rgba(255, 99, 132, 0.8) 100%);
      color: #ffffff;
      border-color: var(--recurring-color);
      text-shadow: 0 0 10px rgba(255, 107, 107, 0.8);
      animation: recurringRotate 4s linear infinite;
    }

    @keyframes recurringRotate {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Spiritual Dream Styling */
    .dream-type-spiritual {
      background: linear-gradient(135deg, rgba(156, 136, 255, 0.9) 0%, rgba(116, 185, 255, 0.8) 100%);
      color: #ffffff;
      border-color: var(--spiritual-color);
      text-shadow: 0 0 10px rgba(156, 136, 255, 0.8);
      animation: spiritualFloat 3s ease-in-out infinite;
    }

    @keyframes spiritualFloat {
      0%, 100% {
        transform: translateY(0px) rotate(0deg);
      }
      50% {
        transform: translateY(-8px) rotate(2deg);
      }
    }

    /* Symbolic Dream Styling */
    .dream-type-symbolic {
      background: linear-gradient(135deg, rgba(255, 165, 2, 0.9) 0%, rgba(255, 140, 0, 0.8) 100%);
      color: #000000;
      border-color: var(--symbolic-color);
      text-shadow: 0 0 10px rgba(255, 165, 2, 0.8);
      animation: symbolicPulse 2s ease-in-out infinite;
    }

    @keyframes symbolicPulse {
      0%, 100% {
        border-width: 2px;
      }
      50% {
        border-width: 4px;
        transform: scale(1.05);
      }
    }

    /* Emotional Dream Styling */
    .dream-type-emotional {
      background: linear-gradient(135deg, rgba(255, 118, 117, 0.9) 0%, rgba(253, 121, 168, 0.8) 100%);
      color: #ffffff;
      border-color: var(--emotional-color);
      text-shadow: 0 0 10px rgba(255, 118, 117, 0.8);
      animation: emotionalHeartbeat 1.5s ease-in-out infinite;
    }

    @keyframes emotionalHeartbeat {
      0%, 100% {
        transform: scale(1);
      }
      14% {
        transform: scale(1.1);
      }
      28% {
        transform: scale(1);
      }
      42% {
        transform: scale(1.1);
      }
      70% {
        transform: scale(1);
      }
    }

    /* Dream Card Type-Based Styling */
    .dream-card.type-nightmare {
      border-left-color: var(--nightmare-color);
      background: linear-gradient(135deg, rgba(255, 71, 87, 0.05) 0%, rgba(42, 42, 42, 0.9) 100%);
    }

    .dream-card.type-nightmare:hover {
      box-shadow: 0 20px 50px rgba(255, 71, 87, 0.3);
      border-color: rgba(255, 71, 87, 0.4);
    }

    .dream-card.type-lucid {
      border-left-color: var(--lucid-color);
      background: linear-gradient(135deg, rgba(83, 82, 237, 0.05) 0%, rgba(42, 42, 42, 0.9) 100%);
    }

    .dream-card.type-lucid:hover {
      box-shadow: 0 20px 50px rgba(83, 82, 237, 0.3);
      border-color: rgba(83, 82, 237, 0.4);
    }

    .dream-card.type-prophetic {
      border-left-color: var(--prophetic-color);
      background: linear-gradient(135deg, rgba(255, 215, 0, 0.05) 0%, rgba(42, 42, 42, 0.9) 100%);
    }

    .dream-card.type-prophetic:hover {
      box-shadow: 0 20px 50px rgba(255, 215, 0, 0.3);
      border-color: rgba(255, 215, 0, 0.4);
    }

    .dream-card.type-normal {
      border-left-color: var(--normal-color);
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.05) 0%, rgba(42, 42, 42, 0.9) 100%);
    }

    .dream-card.type-normal:hover {
      box-shadow: 0 20px 50px rgba(57, 255, 20, 0.3);
      border-color: rgba(57, 255, 20, 0.4);
    }

    .dream-card.type-recurring {
      border-left-color: var(--recurring-color);
      background: linear-gradient(135deg, rgba(255, 107, 107, 0.05) 0%, rgba(42, 42, 42, 0.9) 100%);
    }

    .dream-card.type-recurring:hover {
      box-shadow: 0 20px 50px rgba(255, 107, 107, 0.3);
      border-color: rgba(255, 107, 107, 0.4);
    }

    .dream-card.type-spiritual {
      border-left-color: var(--spiritual-color);
      background: linear-gradient(135deg, rgba(156, 136, 255, 0.05) 0%, rgba(42, 42, 42, 0.9) 100%);
    }

    .dream-card.type-spiritual:hover {
      box-shadow: 0 20px 50px rgba(156, 136, 255, 0.3);
      border-color: rgba(156, 136, 255, 0.4);
    }

    .dream-card.type-symbolic {
      border-left-color: var(--symbolic-color);
      background: linear-gradient(135deg, rgba(255, 165, 2, 0.05) 0%, rgba(42, 42, 42, 0.9) 100%);
    }

    .dream-card.type-symbolic:hover {
      box-shadow: 0 20px 50px rgba(255, 165, 2, 0.3);
      border-color: rgba(255, 165, 2, 0.4);
    }

    .dream-card.type-emotional {
      border-left-color: var(--emotional-color);
      background: linear-gradient(135deg, rgba(255, 118, 117, 0.05) 0%, rgba(42, 42, 42, 0.9) 100%);
    }

    .dream-card.type-emotional:hover {

      box-shadow: 0 20px 50px rgba(255, 118, 117, 0.3);
      border-color: rgba(255, 118, 117, 0.4);
    }

    /* Dream Type Icons */
    .dream-type-icon {
      position: absolute;
      top: 15px;
      right: 60px;
      font-size: 24px;
      animation: iconFloat 3s ease-in-out infinite;
      z-index: 1;
    }

    @keyframes iconFloat {
      0%, 100% {
        transform: translateY(0px) rotate(0deg);
      }
      50% {
        transform: translateY(-5px) rotate(5deg);
      }
    }

    /* DREAM TYPE STYLING SYSTEM - END */

    /* Culture Selector Styling */
    select[name="culture"] {
      background: linear-gradient(135deg, rgba(42, 42, 42, 0.8) 0%, rgba(35, 35, 35, 0.8) 100%) !important;
      color: var(--text-light) !important;
      border: 2px solid var(--border-color) !important;
      border-radius: 15px !important;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    select[name="culture"]:focus {
      outline: none;
      border-color: var(--primary-green) !important;
      background: linear-gradient(135deg, rgba(35, 35, 35, 0.9) 0%, rgba(42, 42, 42, 0.9) 100%) !important;
      box-shadow: 0 0 20px rgba(57, 255, 20, 0.2) !important;
      transform: translateY(-2px);
    }

    select[name="culture"]:hover {
      background: linear-gradient(135deg, #252525 0%, var(--card-bg) 100%) !important;
      box-shadow: 0 0 15px rgba(57, 255, 20, 0.3);
      transform: translateY(-2px);
    }

    select[name="culture"] option {
      background: var(--card-bg) !important;
      color: var(--text-light) !important;
      padding: 10px;
      border: none;
    }
    
    /* Loading Screen - Enhanced */
                #loading-screen {
              position: fixed !important;
              top: 0 !important;
              left: 0 !important;
              width: 100% !important;
              height: 100% !important;
              background:
                radial-gradient(circle at 20% 30%, rgba(57, 255, 20, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(46, 204, 113, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 60% 80%, rgba(57, 255, 20, 0.08) 0%, transparent 55%),
                linear-gradient(135deg, rgba(10, 10, 10, 0.98) 0%, rgba(17, 17, 17, 0.96) 50%, rgba(5, 5, 5, 0.98) 100%);
              backdrop-filter: blur(25px);
              -webkit-backdrop-filter: blur(25px);
              z-index: 99999 !important;
              display: none;
              justify-content: center;
              align-items: center;
              transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
              overflow: hidden;
              will-change: opacity, transform, filter;
              opacity: 0;
              visibility: hidden;
            }

                #loading-screen.show {
      display: flex !important;
      opacity: 1 !important;
      visibility: visible !important;
      transition: opacity 0.5s ease, visibility 0.5s ease;
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 100vw !important;
      height: 100vh !important;
      z-index: 99999 !important;
    }
    
    #loading-screen::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 50% 50%, rgba(57, 255, 20, 0.05) 0%, transparent 70%);
      animation: backgroundPulse 4s ease-in-out infinite;
    }
    
    #loading-screen::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        linear-gradient(45deg, transparent 30%, rgba(57, 255, 20, 0.02) 50%, transparent 70%);
      animation: backgroundShift 6s ease-in-out infinite;
    }
    
    @keyframes backgroundPulse {
      0%, 100% {
        opacity: 0.3;
        transform: scale(1);
      }
      50% {
        opacity: 0.6;
        transform: scale(1.1);
      }
    }
    
    @keyframes backgroundShift {
      0% {
        transform: translateX(-100%) translateY(-100%) rotate(0deg);
      }
      100% {
        transform: translateX(100%) translateY(100%) rotate(360deg);
      }
    }
    
    #loading-screen.hidden {
      opacity: 0 !important;
      visibility: hidden !important;
      transform: scale(1.1) rotate(5deg);
      filter: blur(10px);
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 100vw !important;
      height: 100vh !important;
      z-index: 99999 !important;
    }
    
    #loading-screen.fade-out {
      animation: loadingFadeOut 1s ease-in-out forwards;
    }
    
    @keyframes loadingFadeOut {
      0% {
        opacity: 1;
        transform: scale(1) rotate(0deg);
        filter: blur(0px);
      }
      100% {
        opacity: 0;
        transform: scale(1.2) rotate(10deg);
        filter: blur(20px);
      }
    }
    
    .loading-content {
      text-align: center;
      color: var(--text-light);
      max-width: 500px;
      padding: 0 20px;
    }
    
    .loading-spinner {
      width: 60px;
      height: 60px;
      border: 3px solid rgba(57, 255, 20, 0.1);
      border-top: 3px solid var(--primary-green);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 30px auto;
      box-shadow: 0 0 20px rgba(57, 255, 20, 0.3);
      position: relative;
      will-change: transform;
    }
    
    .loading-spinner::before {
      content: '';
      position: absolute;
      top: -3px;
      left: -3px;
      right: -3px;
      bottom: -3px;
      border: 3px solid transparent;
      border-top: 3px solid rgba(57, 255, 20, 0.3);
      border-radius: 50%;
      animation: spin 1.5s linear infinite reverse;
    }
    
    .loading-spinner::after {
      content: '';
      position: absolute;
      top: -6px;
      left: -6px;
      right: -6px;
      bottom: -6px;
      border: 2px solid transparent;
      border-top: 2px solid rgba(57, 255, 20, 0.1);
      border-radius: 50%;
      animation: spin 2s linear infinite;
    }
    
    .loading-text {
      font-size: 24px;
      font-weight: 800;
      color: var(--primary-green);
      letter-spacing: 4px;
      text-shadow: 0 0 10px rgba(57, 255, 20, 0.5);
      font-family: 'Manrope', sans-serif;
      margin-bottom: 20px;
      animation: textGlow 2s ease-in-out infinite alternate;
      position: relative;
      will-change: transform, text-shadow;
    }
    
    .loading-text::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--primary-green), var(--secondary-green));
      animation: textUnderline 3s ease-in-out infinite;
    }
    
    @keyframes textGlow {
      0% {
        text-shadow: 0 0 10px rgba(57, 255, 20, 0.5);
        transform: scale(1);
      }
      100% {
        text-shadow: 0 0 20px rgba(57, 255, 20, 0.8), 0 0 30px rgba(57, 255, 20, 0.4);
        transform: scale(1.05);
      }
    }
    
    @keyframes textUnderline {
      0%, 100% {
        width: 0;
        opacity: 0;
      }
      50% {
        width: 100%;
        opacity: 1;
      }
    }
    
    .loading-subtitle {
      font-size: 16px;
      color: var(--text-muted);
      margin-bottom: 30px;
      font-weight: 400;
    }
    
    .loading-progress {
      width: 350px;
      height: 6px;
      background: rgba(57, 255, 20, 0.1);
      border-radius: 3px;
      margin: 0 auto 20px auto;
      overflow: hidden;
      position: relative;
    }
    
    .loading-bar {
      height: 100%;
      background: linear-gradient(90deg, var(--primary-green), var(--secondary-green));
      border-radius: 3px;
      width: 0%;
      transition: width 0.3s ease;
      position: relative;
      overflow: hidden;
      will-change: width;
    }
    
    .loading-bar::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      animation: shimmer 2s infinite;
    }
    
    .loading-bar::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(57, 255, 20, 0.1) 20%, 
        rgba(57, 255, 20, 0.2) 50%, 
        rgba(57, 255, 20, 0.1) 80%, 
        transparent 100%);
      animation: progressGlow 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
      0% { left: -100%; }
      100% { left: 100%; }
    }
    
    @keyframes progressGlow {
      0%, 100% {
        opacity: 0.5;
        transform: scaleX(1);
      }
      50% {
        opacity: 1;
        transform: scaleX(1.05);
      }
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
      opacity: 0.5;
      transition: all 0.5s ease;
      transform: translateY(20px);
    }
    
    .loading-step.active {
      opacity: 1;
      transform: translateY(0);
    }
    
    .loading-step.completed {
      opacity: 0.8;
      transform: translateY(0);
    }
    
    .loading-step.completed .loading-step-icon {
      background: linear-gradient(135deg, var(--secondary-green), var(--primary-green));
      border-color: var(--secondary-green);
      animation: completedPulse 2s ease-in-out infinite;
    }
    
    @keyframes completedPulse {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 20px rgba(46, 204, 113, 0.4);
      }
      50% {
        transform: scale(1.05);
        box-shadow: 0 0 30px rgba(46, 204, 113, 0.6);
      }
    }
    
    .loading-step-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: rgba(42, 42, 42, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      margin-bottom: 10px;
      transition: all 0.5s ease;
      border: 2px solid var(--border-color);
      will-change: transform, background, border-color, box-shadow;
    }
    
    .loading-step.active .loading-step-icon {
      background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
      border-color: var(--primary-green);
      box-shadow: 0 0 20px rgba(57, 255, 20, 0.4);
      animation: stepPulse 1s ease-in-out infinite alternate;
    }
    
    .loading-step.active .loading-step-icon::after {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      border: 2px solid var(--primary-green);
      border-radius: 50%;
      animation: stepRipple 2s ease-out infinite;
    }
    
    @keyframes stepPulse {
      0% { transform: scale(1); }
      100% { transform: scale(1.1); }
    }
    
    @keyframes stepRipple {
      0% {
        transform: scale(1);
        opacity: 1;
      }
      100% {
        transform: scale(1.5);
        opacity: 0;
      }
    }
    
    .loading-step-text {
      color: var(--text-muted);
      font-size: 0.8rem;
      text-align: center;
      font-weight: 400;
    }
    
    .loading-step.active .loading-step-text {
      color: var(--primary-green);
      font-weight: 600;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    body {
      margin: 0;
      padding-top: 20px;
      padding-bottom: 100px; /* Space for bottom nav */
      color: var(--text-light);
      background: transparent;
      min-height: 100vh;
      overflow-x: hidden;
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
      border-top: 2px solid rgba(57, 255, 20, 0.2);
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
      background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
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
      box-shadow: 0 10px 25px rgba(57, 255, 20, 0.3);
    }

    .nav-item.active {
      color: var(--primary-green);
      background: rgba(57, 255, 20, 0.1);
      border: 1px solid rgba(57, 255, 20, 0.3);
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(57, 255, 20, 0.2);
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

    /* Language Selector in Top Header */
    .top-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 60px;
      background: rgba(20, 20, 20, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(57, 255, 20, 0.1);
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
      text-shadow: 0 0 15px rgba(57, 255, 20, 0.3);
    }

    .app-logo span:last-child {
      color: var(--primary-green);
    }

    .language-selector select {
      background: linear-gradient(135deg, rgba(42, 42, 42, 0.9) 0%, rgba(35, 35, 35, 0.9) 100%) !important;
      color: var(--text-light) !important;
      border: 2px solid rgba(57, 255, 20, 0.3);
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
      box-shadow: 0 0 15px rgba(57, 255, 20, 0.3);
      transform: translateY(-2px);
      border-color: rgba(57, 255, 20, 0.5);
    }
    
    .language-selector select:focus {
      outline: none;
      box-shadow: 0 0 20px rgba(57, 255, 20, 0.4);
      border-color: var(--primary-green);
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
        filter: drop-shadow(0 4px 12px rgba(57, 255, 20, 0.6));
      }
    }

    /* Notification Badge for Navigation */
    .nav-item .notification-badge {
      position: absolute;
      top: 8px;
      right: 8px;
      background: linear-gradient(135deg, #ff4757, #ff6b7a);
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      font-weight: 700;
      animation: badgePulse 2s infinite;
      box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
    }

    @keyframes badgePulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.2);
      }
    }

    /* Navigation Ripple Effect */
    .nav-item .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(57, 255, 20, 0.3);
      transform: scale(0);
      animation: rippleEffect 0.6s linear;
    }

    @keyframes rippleEffect {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }
    
    /* Enhanced Container */
    .container {
      max-width: 900px;
      margin: auto;
      padding: 40px 20px;
      animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Enhanced Welcome Header */
    .welcome-header {
      text-align: center;
      margin-bottom: 50px;
      padding: 40px;
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.1) 0%, rgba(57, 255, 20, 0.05) 100%);
      border-radius: 20px;
      border: 1px solid rgba(57, 255, 20, 0.2);
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
      background: linear-gradient(45deg, transparent, rgba(57, 255, 20, 0.05), transparent);
      animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
      0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
      100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }
    
    .welcome-header h1 {
      color: var(--primary-green);
      font-weight: 800;
      font-size: 2.5rem;
      margin: 0;
      text-shadow: 0 0 20px rgba(57, 255, 20, 0.3);
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
    
    /* Enhanced Sections */
    .section {
      background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
      backdrop-filter: blur(20px);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      margin-bottom: 40px;
      border: 1px solid rgba(57, 255, 20, 0.1);
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
      background: linear-gradient(90deg, transparent, var(--primary-green), transparent);
      animation: borderGlow 2s infinite;
    }
    
    @keyframes borderGlow {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }
    
    .section:hover {
      transform: translateY(-5px);
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
      border-color: rgba(57, 255, 20, 0.3);
    }
    
    .section h2 {
      color: var(--primary-green);
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
      background: linear-gradient(90deg, var(--primary-green), var(--secondary-green));
      border-radius: 2px;
    }
    
    /* Enhanced Form Elements */
    .form-group {
      margin-bottom: 25px;
      position: relative;
    }
    
    .form-label {
      color: var(--primary-green);
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
      display: block;
    }
    
    textarea, input[type="text"] {
      width: 100%;
      padding: 18px 20px;
      border: 2px solid var(--border-color);
      border-radius: 15px;
      background: linear-gradient(135deg, rgba(42, 42, 42, 0.8) 0%, rgba(35, 35, 35, 0.8) 100%);
      color: var(--text-light);
      font-size: 16px;
      font-weight: 400;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    textarea:focus, input[type="text"]:focus {
      outline: none;
      border-color: var(--primary-green);
      background: linear-gradient(135deg, rgba(35, 35, 35, 0.9) 0%, rgba(42, 42, 42, 0.9) 100%);
      box-shadow: 0 0 20px rgba(57, 255, 20, 0.2), inset 0 2px 10px rgba(0, 0, 0, 0.2);
      transform: translateY(-2px);
    }
    
    textarea {
      resize: vertical;
      min-height: 120px;
      font-family: 'Inter', sans-serif;
      line-height: 1.6;
    }
    
    /* Enhanced Button */
    .btn-analyze {
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
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
      box-shadow: 0 10px 30px rgba(57, 255, 20, 0.3);
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
      box-shadow: 0 15px 40px rgba(57, 255, 20, 0.4);
    }
    
    .btn-analyze:active {
      transform: translateY(-1px) scale(1.02);
    }
    
    /* Enhanced Dream Cards */
    .dream-card {
      background: linear-gradient(135deg, rgba(42, 42, 42, 0.9) 0%, rgba(30, 30, 30, 0.9) 100%);
      border: 1px solid rgba(57, 255, 20, 0.2);
      border-left: 4px solid var(--primary-green);
      padding: 25px;
      padding-top: 60px; /* Extra space for badges */
      border-radius: 15px;
      margin-bottom: 25px;
      position: relative;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      backdrop-filter: blur(10px);
      overflow: hidden;
    }
    
    .dream-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.05) 0%, transparent 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .dream-card:hover::before {
      opacity: 1;
    }
    
    .dream-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 50px rgba(57, 255, 20, 0.2);
      border-color: rgba(57, 255, 20, 0.4);
    }
    
    .dream-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      font-size: 0.85rem;
      color: var(--text-muted);
    }
    
    .dream-title {
      color: var(--primary-green);
      font-weight: 600;
      font-size: 1.1rem;
    }
    
    .dream-date {
      color: var(--text-muted);
      font-size: 0.8rem;
      background: rgba(57, 255, 20, 0.1);
      padding: 4px 8px;
      border-radius: 20px;
      border: 1px solid rgba(57, 255, 20, 0.2);
    }
    
    .dream-text {
      color: var(--text-light);
      line-height: 1.6;
      margin-bottom: 20px;
      font-size: 15px;
    }
    
    .dream-analysis {
      background: linear-gradient(135deg, var(--success-bg) 0%, rgba(15, 42, 15, 0.8) 100%);
      padding: 20px;
      border-radius: 12px;
      color: var(--success-text);
      font-style: italic;
      border: 1px solid rgba(57, 255, 20, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    .dream-analysis::before {
      content: '';
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 20px;
      opacity: 0.3;
      font-family: "bootstrap-icons";
    }

    .dream-analysis::after {
      content: "\f4a4"; /* Bootstrap Icons brain icon */
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 20px;
      opacity: 0.3;
      font-family: "bootstrap-icons";
    }
    
    .analysis-label {
      color: var(--primary-green);
      font-weight: 700;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
      display: block;
      font-style: normal;
    }
    
    .delete-btn {
      position: absolute;
      top: 15px;
      right: 15px;
      background: linear-gradient(135deg, var(--danger-bg) 0%, rgba(42, 15, 15, 0.9) 100%);
      border: 1px solid rgba(255, 107, 107, 0.3);
      color: var(--danger-text);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      font-size: 16px;
      opacity: 0.7;
      z-index: 3;
    }
    
    .delete-btn:hover {
      opacity: 1;
      transform: scale(1.1);
      box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
      background: linear-gradient(135deg, rgba(42, 15, 15, 1) 0%, var(--danger-bg) 100%);
    }
    
    .share-btn {
      position: absolute;
      top: 15px;
      right: 65px;
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.1) 0%, rgba(57, 255, 20, 0.2) 100%);
      border: 1px solid rgba(57, 255, 20, 0.3);
      color: var(--primary-green);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      font-size: 16px;
      opacity: 0.7;
      z-index: 3;
    }
    
    .share-btn:hover {
      opacity: 1;
      transform: scale(1.1);
      box-shadow: 0 5px 15px rgba(57, 255, 20, 0.3);
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.2) 0%, rgba(57, 255, 20, 0.3) 100%);
      color: var(--primary-green);
    }
    
    /* Enhanced Alert */
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
      content: '';
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 20px;
      opacity: 0.7;
      font-family: "bootstrap-icons";
    }

    .alert::after {
      content: "\f33a"; /* Bootstrap Icons exclamation-triangle icon */
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 20px;
      opacity: 0.7;
      font-family: "bootstrap-icons";
    }
    
    /* Enhanced Modal */
    .dream-modal {
      display: none;
      position: fixed;
      z-index: 10000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.9);
      backdrop-filter: blur(10px);
      animation: modalFadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .dream-modal.show {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    
    .dream-modal-content {
      background: linear-gradient(135deg, rgba(26, 26, 26, 0.98) 0%, rgba(20, 20, 20, 0.98) 100%);
      backdrop-filter: blur(20px);
      border: 2px solid var(--primary-green);
      border-radius: 20px;
      padding: 40px;
      max-width: 900px;
      max-height: 90vh;
      width: 100%;
      overflow-y: auto;
      position: relative;
      box-shadow: 0 30px 100px rgba(57, 255, 20, 0.2);
      animation: modalSlideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .dream-modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 20px;
      border-bottom: 2px solid rgba(57, 255, 20, 0.2);
      position: relative;
    }
    
    .dream-modal-header::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 100px;
      height: 2px;
      background: linear-gradient(90deg, var(--primary-green), transparent);
    }
    
    .dream-modal-title {
      color: var(--primary-green);
      font-size: 28px;
      font-weight: 800;
      margin: 0;
      font-family: 'Manrope', sans-serif;
      text-shadow: 0 0 10px rgba(57, 255, 20, 0.3);
    }
    
    .dream-modal-close {
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.1) 0%, rgba(57, 255, 20, 0.2) 100%);
      border: 2px solid var(--primary-green);
      color: var(--primary-green);
      font-size: 24px;
      cursor: pointer;
      padding: 0;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      font-weight: 300;
    }
    
    .dream-modal-close:hover {
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
      color: var(--dark-bg);
      transform: rotate(90deg) scale(1.1);
      box-shadow: 0 5px 20px rgba(57, 255, 20, 0.4);
    }
    
    .modal-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .modal-share-btn {
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.1) 0%, rgba(57, 255, 20, 0.2) 100%);
      border: 2px solid var(--primary-green);
      color: var(--primary-green);
      font-size: 18px;
      cursor: pointer;
      padding: 0;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      font-weight: 300;
    }
    
    .modal-share-btn:hover {
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
      color: var(--dark-bg);
      transform: scale(1.1);
      box-shadow: 0 5px 20px rgba(57, 255, 20, 0.4);
    }
    
    .dream-modal-date {
      color: var(--text-muted);
      font-size: 16px;
      margin-bottom: 25px;
      padding: 10px 15px;
      background: rgba(57, 255, 20, 0.05);
      border-radius: 25px;
      border: 1px solid rgba(57, 255, 20, 0.2);
      display: inline-block;
    }
    
    .dream-modal-text {
      color: var(--text-light);
      font-size: 18px;
      line-height: 1.8;
      margin-bottom: 30px;
      padding: 25px;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
      border-radius: 15px;
      border-left: 4px solid var(--primary-green);
      box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .dream-modal-analysis {
      background: linear-gradient(135deg, var(--success-bg) 0%, rgba(15, 42, 15, 0.9) 100%);
      padding: 30px;
      border-radius: 15px;
      color: var(--success-text);
      font-style: italic;
      border: 2px solid rgba(57, 255, 20, 0.3);
      position: relative;
      font-size: 16px;
      line-height: 1.7;
      margin-bottom: 20px;
    }
    
    .dream-modal-analysis::before {
      content: '';
      position: absolute;
      top: -1px;
      left: -1px;
      right: -1px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-green), var(--secondary-green), var(--primary-green));
      border-radius: 15px 15px 0 0;
    }
    
    .dream-modal-analysis-title {
      color: var(--primary-green);
      font-weight: 800;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-style: normal;
      font-size: 18px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Modal Dream Type Display */
    .dream-modal-type {
      padding: 15px 25px;
      border-radius: 15px;
      font-weight: 700;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 25px;
      border: 2px solid;
      position: relative;
      overflow: hidden;
      animation: modalTypePulse 3s ease-in-out infinite;
    }

    @keyframes modalTypePulse {
      0%, 100% {
        transform: scale(1);
        filter: brightness(1);
      }
      50% {
        transform: scale(1.02);
        filter: brightness(1.1);
      }
    }

    .dream-modal-type-title {
      color: var(--primary-green);
      font-weight: 800;
      margin-bottom: 10px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    /* No Dreams State */
    .no-dreams {
      text-align: center;
      padding: 60px 20px;
      color: var(--text-muted);
      font-size: 18px;
      background: linear-gradient(135deg, rgba(57, 255, 20, 0.03) 0%, rgba(57, 255, 20, 0.01) 100%);
      border-radius: 15px;
      border: 2px dashed rgba(57, 255, 20, 0.2);
      position: relative;
    }
    
    .no-dreams::before {
      content: '';
      font-size: 60px;
      display: block;
      margin-bottom: 20px;
      opacity: 0.5;
      font-family: "bootstrap-icons";
    }

    .no-dreams::after {
      content: "\f4a6"; /* Bootstrap Icons cloud-moon icon */
      font-size: 60px;
      display: block;
      margin-bottom: 20px;
      opacity: 0.5;
      font-family: "bootstrap-icons";
      position: absolute;
      top: 30px;
      left: 50%;
      transform: translateX(-50%);
    }

    /* Dream Type Filter Buttons */
    .dream-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 30px;
      justify-content: center;
    }

    .filter-btn {
      padding: 8px 16px;
      border-radius: 25px;
      border: 2px solid;
      background: transparent;
      cursor: pointer;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .filter-btn.active {
      transform: scale(1.1);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    }

    .filter-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .filter-btn:hover::before {
      left: 100%;
    }

    .filter-btn.all {
      border-color: var(--primary-green);
      color: var(--primary-green);
    }

    .filter-btn.all.active {
      background: var(--primary-green);
      color: var(--dark-bg);
    }

    .filter-btn.nightmare {
      border-color: var(--nightmare-color);
      color: var(--nightmare-color);
    }

    .filter-btn.nightmare.active {
      background: var(--nightmare-color);
      color: #ffffff;
    }

    .filter-btn.lucid {
      border-color: var(--lucid-color);
      color: var(--lucid-color);
    }

    .filter-btn.lucid.active {
      background: var(--lucid-color);
      color: #ffffff;
    }

    .filter-btn.prophetic {
      border-color: var(--prophetic-color);
      color: var(--prophetic-color);
    }

    .filter-btn.prophetic.active {
      background: var(--prophetic-color);
      color: #000000;
    }

    .filter-btn.normal {
      border-color: var(--normal-color);
      color: var(--normal-color);
    }

    .filter-btn.normal.active {
      background: var(--normal-color);
      color: #000000;
    }

    .filter-btn.recurring {
      border-color: var(--recurring-color);
      color: var(--recurring-color);
    }

    .filter-btn.recurring.active {
      background: var(--recurring-color);
      color: #ffffff;
    }

    .filter-btn.spiritual {
      border-color: var(--spiritual-color);
      color: var(--spiritual-color);
    }

    .filter-btn.spiritual.active {
      background: var(--spiritual-color);
      color: #ffffff;
    }

    .filter-btn.symbolic {
      border-color: var(--symbolic-color);
      color: var(--symbolic-color);
    }

    .filter-btn.symbolic.active {
      background: var(--symbolic-color);
      color: #000000;
    }

    .filter-btn.emotional {
      border-color: var(--emotional-color);
      color: var(--emotional-color);
    }

    .filter-btn.emotional.active {
      background: var(--emotional-color);
      color: #ffffff;
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
    
    @keyframes modalFadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
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
    
    /* Mobile Responsiveness */
    @media (max-width: 991.98px) {
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
      
      .dream-modal-content {
        margin: 10px;
        padding: 25px 20px;
        max-height: 95vh;
      }
      
      .dream-modal-title {
        font-size: 22px;
      }
      
      .dream-modal-text {
        font-size: 16px;
        padding: 20px;
      }
      
      .dream-card {
        padding: 20px;
        padding-top: 55px;
      }
      
      .delete-btn {
        width: 35px;
        height: 35px;
        font-size: 14px;
      }
      
      .dream-orb {
        transform: scale(0.7);
      }

      .dream-filters {
        flex-direction: column;
        align-items: center;
      }

      .filter-btn {
        margin: 5px;
        min-width: 120px;
      }

      .dream-type-badge {
        font-size: 10px;
        padding: 6px 12px;
      }

      .dream-type-icon {
        font-size: 20px;
        right: 50px;
      }
      
      .share-btn {
        width: 35px;
        height: 35px;
        font-size: 14px;
        right: 60px;
      }
      
      .modal-share-btn {
        width: 40px;
        height: 40px;
        font-size: 16px;
      }
      
      /* Tablet Loading Screen Adjustments */
      .loading-content {
        max-width: 450px;
      }
      
      .loading-progress {
        width: 320px;
      }
      
      .loading-steps {
        max-width: 450px;
      }
      
      .loading-step-icon {
        width: 45px;
        height: 45px;
        font-size: 18px;
      }
      
      .loading-step-text {
        font-size: 0.75rem;
      }
    }
    
    @media (max-width: 576px) {
      body {
        padding-top: 60px;
        padding-bottom: 85px;
      }
      
      .welcome-header h1 {
        font-size: 1.8rem;
      }
      
      .btn-analyze {
        padding: 12px 30px;
        font-size: 14px;
      }
      
      textarea, input[type="text"] {
        padding: 15px 18px;
        font-size: 15px;
      }
      
      .nav-item {
        min-width: 45px;
        height: 45px;
      }
      
      .nav-icon {
        font-size: 18px;
      }
      
      .nav-text {
        font-size: 9px;
      }
      
      /* Mobile Loading Screen Adjustments */
      .loading-content {
        padding: 0 15px;
      }
      
      .loading-title {
        font-size: 1.5rem;
      }
      
      .loading-subtitle {
        font-size: 14px;
      }
      
      .loading-progress {
        width: 280px;
        height: 5px;
      }
      
      .loading-steps {
        max-width: 350px;
        flex-direction: column;
        gap: 15px;
      }
      
      .loading-step {
        flex-direction: row;
        gap: 10px;
        text-align: left;
      }
      
      .loading-step-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
        margin-bottom: 0;
      }
      
      .loading-step-text {
        font-size: 0.7rem;
        text-align: left;
      }
      
      .loading-spinner {
        width: 50px;
        height: 50px;
      }
      
      .loading-text {
        font-size: 20px;
        letter-spacing: 2px;
      }
    }
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
  
  .share-btn {
    width: 32px;
    height: 32px;
    font-size: 13px;
    right: 55px;
  }
  
  .modal-share-btn {
    width: 38px;
    height: 38px;
    font-size: 15px;
  }
}
  </style>
  <link rel="icon" href="assets/logo.png" type="image/x-icon">
</head>

<body>

<!-- PREMIUM BACKGROUND SYSTEM START -->
<!-- Main Dream Background -->
<div class="dream-background"></div>

<!-- Floating Dream Orbs -->
<div class="dream-orb"></div>
<div class="dream-orb"></div>
<div class="dream-orb"></div>
<div class="dream-orb"></div>
<div class="dream-orb"></div>

<!-- Particle System -->
<div class="particle-system" id="particleSystem"></div>

<!-- Dream Waves -->
<div class="dream-wave"></div>

<!-- Ethereal Grid -->
<div class="ethereal-grid"></div>

<!-- Nebula Layer -->
<div class="nebula-layer"></div>

<!-- Starfield -->
<div class="starfield" id="starfield"></div>

<!-- Light Rays -->
<div class="light-ray"></div>
<div class="light-ray"></div>
<div class="light-ray"></div>
<div class="light-ray"></div>
<!-- PREMIUM BACKGROUND SYSTEM END -->

<!-- Loading Screen -->
<div id="loading-screen">
  <div class="loading-content">
    <div class="loading-spinner"></div>
    <div class="loading-text">DREAMLOCK</div>
    <div class="loading-subtitle"><?php echo $t['please_wait'] ?? 'AI is analyzing your dreams...'; ?></div>
    
    <div class="loading-progress">
      <div class="loading-bar" id="loadingBar"></div>
    </div>
    
    <div class="loading-steps">
      <div class="loading-step" id="step1">
        <div class="loading-step-icon">💭</div>
        <div class="loading-step-text"><?php echo $t['processing_dreams'] ?? 'Processing dreams'; ?></div>
      </div>
      <div class="loading-step" id="step2">
        <div class="loading-step-icon">🧠</div>
        <div class="loading-step-text"><?php echo $t['calculating_metrics'] ?? 'AI analysis'; ?></div>
      </div>
      <div class="loading-step" id="step3">
        <div class="loading-step-icon">✨</div>
        <div class="loading-step-text"><?php echo $t['generating_insights'] ?? 'Generating insights'; ?></div>
      </div>
      <div class="loading-step" id="step4">
        <div class="loading-step-icon">✅</div>
        <div class="loading-step-text"><?php echo $t['finalizing_analysis'] ?? 'Finalizing'; ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Top Header with Logo and Language Selector -->
<div class="top-header">
  <div class="app-logo">
    <span style="color: rgba(255,255,255,0.75)">DREAM</span><span style="color: #39FF14;">LOCK</span>
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

<!-- Bottom Mobile Navigation -->
<div class="bottom-navigation">
  <a href="index.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-house-fill"></i></div>
    <div class="nav-text"><?php echo $t['home']; ?></div>
  </a>
  <a href="dream.php" class="nav-item active">
    <div class="nav-icon"><i class="bi bi-cloud-moon-fill"></i></div>
    <div class="nav-text">Dreams</div>
    <!-- <div class="notification-badge">3</div> -->
  </a>
  <a href="subconscious.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-cpu-fill"></i></div>
    <div class="nav-text"><?php echo $t['subconscious']; ?></div>
  </a>
  <a href="dream-sharing.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-share"></i></div>
    <div class="nav-text"><?php echo $t['dream_sharing'] ?? 'Share'; ?></div>
  </a>
  <a href="sleep_analysis.php" class="nav-item">
    <div class="nav-icon"><i class="bi bi-moon-stars-fill"></i></div>
    <div class="nav-text"><?php echo $t['sleep']; ?></div>
  </a>
  <a href="?logout=1" class="nav-item">
    <div class="nav-icon"><i class="bi bi-box-arrow-right"></i></div>
    <div class="nav-text"><?php echo $t['logout']; ?></div>
  </a>
</div>

<!-- Dream Modal -->
<div id="dreamModal" class="dream-modal">
  <div class="dream-modal-content">
    <div class="dream-modal-header">
      <h3 class="dream-modal-title" id="modalTitle"></h3>
      <div class="modal-actions">
        <button class="modal-share-btn" onclick="shareDreamFromModal()" title="<?php echo $t['share_dream']; ?>">
          <i class="bi bi-share"></i>
        </button>
        <button class="dream-modal-close" onclick="closeDreamModal()">&times;</button>
      </div>
    </div>
    <div class="dream-modal-date" id="modalDate"></div>
    
    <!-- Dream Type Display in Modal -->
    <div class="dream-modal-type-title"><?php echo $t['dream_type']; ?></div>
    <div class="dream-modal-type" id="modalType"></div>
    
    <div class="dream-modal-text" id="modalText"></div>
    <div class="dream-modal-analysis">
      <div class="dream-modal-analysis-title"><i class="bi bi-cpu-fill"></i> <?php echo $t['ai_analysis']; ?>:</div>
      <div id="modalAnalysis"></div>
    </div>
  </div>
</div>

<div class="container" data-aos="fade-up">
  <!-- Welcome Header -->
  <div class="welcome-header">
    <h1><?php echo $t['welcome']; ?>, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <div class="welcome-subtitle">Unlock the mysteries of your subconscious mind</div>
  </div>

  <?php if (isset($error_message)): ?>
    <div class="alert"><?php echo $error_message; ?></div>
  <?php endif; ?>

  <!-- Dream Entry Section -->
  <div class="section">
    <h2><?php echo $t['add_dream']; ?></h2>
    <form method="POST" action="" onsubmit="showAnalysisLoading()">
      <div class="form-group">
        <label class="form-label"><?php echo $t['culture_selection']; ?></label>
        <select name="culture" required style="width: 100%; padding: 18px 20px; border: 2px solid var(--border-color); border-radius: 15px; background: linear-gradient(135deg, rgba(42, 42, 42, 0.8) 0%, rgba(35, 35, 35, 0.8) 100%); color: var(--text-light); font-size: 16px;">
          <option value="none"><?php echo $t['culture_none']; ?></option>
          <option value="islam"><?php echo $t['culture_islam']; ?></option>
          <option value="christianity"><?php echo $t['culture_christianity']; ?></option>
          <option value="hinduism"><?php echo $t['culture_hinduism']; ?></option>
          <option value="buddhism"><?php echo $t['culture_buddhism']; ?></option>
          <option value="greek"><?php echo $t['culture_greek']; ?></option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label"><?php echo $t['write_dream']; ?></label>
        <textarea name="dream_text" rows="6" placeholder="Describe your dream in detail..." required></textarea>
      </div>
      <div class="form-group">
        <label class="form-label"><?php echo $t['dream_title']; ?></label>
        <input type="text" name="open_date" placeholder="Enter a title for your dream" required>
      </div>
      <center>
        <button type="submit" name="submit_dream" class="btn-analyze">
          <?php echo $t['analyze_now']; ?>
        </button>
      </center>
    </form>
  </div>

  <!-- Past Dreams Section -->
  <div class="section">
    <h2><?php echo $t['past_dreams']; ?></h2>
    
    <!-- Dream Analytics Button -->
    <div style="text-align: center; margin-bottom: 2rem;">
      <a href="dream-sharing.php" class="btn-analyze" style="display: inline-flex; align-items: center; gap: 10px; text-decoration: none; margin: 0;">
        <i class="bi bi-graph-up"></i>
        <?php echo $t['dream_analytics'] ?? 'Dream Analytics'; ?>
      </a>
    </div>
    
    <!-- Dream Type Filters -->
    <div class="dream-filters">
      <button class="filter-btn all active" onclick="filterDreams('all')">ALL</button>
      <button class="filter-btn nightmare" onclick="filterDreams('nightmare')"><i class="bi bi-exclamation-triangle-fill"></i> <?php echo $t['nightmare']; ?></button>
      <button class="filter-btn lucid" onclick="filterDreams('lucid')"><i class="bi bi-stars"></i> <?php echo $t['lucid']; ?></button>
      <button class="filter-btn prophetic" onclick="filterDreams('prophetic')"><i class="bi bi-crystal-ball"></i> <?php echo $t['prophetic']; ?></button>
      <button class="filter-btn normal" onclick="filterDreams('normal')"><i class="bi bi-emoji-smile-fill"></i> <?php echo $t['normal']; ?></button>
      <button class="filter-btn recurring" onclick="filterDreams('recurring')"><i class="bi bi-arrow-clockwise"></i> <?php echo $t['recurring']; ?></button>
      <button class="filter-btn spiritual" onclick="filterDreams('spiritual')"><i class="bi bi-flower2"></i> <?php echo $t['spiritual']; ?></button>
      <button class="filter-btn symbolic" onclick="filterDreams('symbolic')"><i class="bi bi-gem"></i> <?php echo $t['symbolic']; ?></button>
      <button class="filter-btn emotional" onclick="filterDreams('emotional')"><i class="bi bi-heart-fill"></i> <?php echo $t['emotional']; ?></button>
    </div>

    <div id="dreamsContainer">
      <?php
      try {
        $stmt = $db->prepare("SELECT id, dream_text, open_date, analysis, dream_type, created_at, sharing_enabled FROM dreams WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$current_user_id]);
        $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($dreams) {
          foreach ($dreams as $dream) {
            $dream_type = $dream['dream_type'] ?? 'normal';
            
            // Get dream type icon
            $type_icons = [
              'nightmare' => '<i class="bi bi-exclamation-triangle-fill"></i>',
              'lucid' => '<i class="bi bi-stars"></i>',
              'prophetic' => '<i class="bi bi-crystal-ball"></i>',
              'normal' => '<i class="bi bi-emoji-smile-fill"></i>',
              'recurring' => '<i class="bi bi-arrow-clockwise"></i>',
              'spiritual' => '<i class="bi bi-flower2"></i>',
              'symbolic' => '<i class="bi bi-gem"></i>',
              'emotional' => '<i class="bi bi-heart-fill"></i>'
            ];
            
            $dreamData = [
              'id' => $dream['id'],
              'title' => $dream['open_date'],
              'date' => date("M d, Y - H:i", strtotime($dream['created_at'])),
              'text' => $dream['dream_text'],
              'analysis' => $dream['analysis'],
              'type' => $dream_type,
              'type_label' => $t[$dream_type] ?? $t['normal']
            ];

            echo '<div class="dream-card type-' . $dream_type . '" data-type="' . $dream_type . '" onclick="openDreamModal(' . htmlspecialchars(json_encode($dreamData), ENT_QUOTES) . ')">';
            
            // Dream type badge
            echo '<div class="dream-type-badge dream-type-' . $dream_type . '">' . ($t[$dream_type] ?? $t['normal']) . '</div>';
            
            // Dream type icon
            echo '<div class="dream-type-icon">' . ($type_icons[$dream_type] ?? '<i class="bi bi-emoji-smile-fill"></i>') . '</div>';
            
            // Share button - check if already shared
            $is_shared = isset($dream['sharing_enabled']) && $dream['sharing_enabled'];
            if ($is_shared) {
              echo '<button class="share-btn" data-dream-id="' . $dream['id'] . '" title="' . $t['dream_shared'] . '" style="background: linear-gradient(135deg, rgba(46, 204, 113, 0.9) 0%, rgba(46, 204, 113, 0.8) 100%); color: #ffffff; cursor: default;"><i class="bi bi-check-circle"></i></button>';
            } else {
              echo '<button class="share-btn" data-dream-id="' . $dream['id'] . '" title="' . $t['share_dream'] . '" onclick="event.stopPropagation(); shareDream(' . htmlspecialchars(json_encode($dreamData), ENT_QUOTES) . ')"><i class="bi bi-share"></i></button>';
            }
            
            // Delete button
            echo '<button class="delete-btn" title="Delete Dream" onclick="event.stopPropagation(); if(confirm(\'' . $t['delete_confirm'] . '\')) window.location.href=\'?delete=' . $dream['id'] . '\'"><i class="bi bi-trash"></i></button>';
            
            echo '<div class="dream-meta">';
            echo '<span class="dream-title">' . htmlspecialchars($dream['open_date']) . '</span>';
            echo '<span class="dream-date">' . date("M d, Y - H:i", strtotime($dream['created_at'])) . '</span>';
            echo '</div>';
            
            echo '<div class="dream-text">' . (strlen($dream['dream_text']) > 200 ? nl2br(htmlspecialchars(substr($dream['dream_text'], 0, 200))) . '...' : nl2br(htmlspecialchars($dream['dream_text']))) . '</div>';
            
            echo '<div class="dream-analysis">';
            echo '<span class="analysis-label">' . $t['ai_analysis'] . '</span>';
            echo (strlen($dream['analysis']) > 120 ? htmlspecialchars(substr($dream['analysis'], 0, 120)) . '...' : htmlspecialchars($dream['analysis']));
            echo '</div>';
            
            echo '</div>';
          }
        } else {
          echo '<div class="no-dreams">' . $t['no_dreams'] . '</div>';
        }
      } catch (PDOException $e) {
        echo '<div class="alert">' . $t['load_error'] . '</div>';
        error_log("Dreams fetch error: " . $e->getMessage());
      }
      ?>
    </div>
  </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script>
  // PREMIUM BACKGROUND JAVASCRIPT START
  
  // Advanced Particle System
  function createMysticalParticles() {
    const particleSystem = document.getElementById('particleSystem');
    const particleCount = 80;
    
    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement('div');
      particle.className = 'mystical-particle';
      
      // Random size between 1-4px
      const size = Math.random() * 3 + 1;
      particle.style.width = size + 'px';
      particle.style.height = size + 'px';
      
      // Random position
      particle.style.left = Math.random() * 100 + '%';
      particle.style.top = Math.random() * 100 + '%';
      
      // Random animation properties
      const duration = Math.random() * 8 + 4; // 4-12 seconds
      const delay = Math.random() * 5; // 0-5 seconds delay
      
      particle.style.animation = `floatMystical ${duration}s ease-in-out ${delay}s infinite`;
      
      // Random opacity
      particle.style.opacity = Math.random() * 0.8 + 0.2;
      
      particleSystem.appendChild(particle);
      
      // Add floating animation
      const keyframes = `
        @keyframes floatMystical${i} {
          0%, 100% {
            transform: translateY(0px) translateX(0px) rotate(0deg);
            opacity: ${Math.random() * 0.5 + 0.3};
          }
          25% {
            transform: translateY(${-20 - Math.random() * 20}px) translateX(${Math.random() * 30 - 15}px) rotate(90deg);
            opacity: ${Math.random() * 0.3 + 0.7};
          }
          50% {
            transform: translateY(${-10 - Math.random() * 15}px) translateX(${Math.random() * 40 - 20}px) rotate(180deg);
            opacity: ${Math.random() * 0.4 + 0.4};
          }
          75% {
            transform: translateY(${-30 - Math.random() * 25}px) translateX(${Math.random() * 25 - 12}px) rotate(270deg);
            opacity: ${Math.random() * 0.6 + 0.3};
          }
        }
      `;
      
      // Inject unique keyframes
      if (!document.getElementById(`particle-style-${i}`)) {
        const style = document.createElement('style');
        style.id = `particle-style-${i}`;
        style.textContent = keyframes;
        document.head.appendChild(style);
        particle.style.animation = particle.style.animation.replace('floatMystical', `floatMystical${i}`);
      }
    }
  }
  
  // Create Starfield
  function createStarfield() {
    const starfield = document.getElementById('starfield');
    const starCount = 150;
    
    for (let i = 0; i < starCount; i++) {
      const star = document.createElement('div');
      star.className = 'star';
      
      // Random size between 0.5-2px
      const size = Math.random() * 1.5 + 0.5;
      star.style.width = size + 'px';
      star.style.height = size + 'px';
      
      // Random position
      star.style.left = Math.random() * 100 + '%';
      star.style.top = Math.random() * 100 + '%';
      
      // Random twinkle timing
      const duration = Math.random() * 4 + 2; // 2-6 seconds
      const delay = Math.random() * 3; // 0-3 seconds delay
      
      star.style.animationDuration = duration + 's';
      star.style.animationDelay = delay + 's';
      
      starfield.appendChild(star);
    }
  }
  
  // Create Dream Ripples
  function createDreamRipple() {
    const ripple = document.createElement('div');
    ripple.className = 'dream-ripple';
    
    // Random position
    const x = Math.random() * window.innerWidth;
    const y = Math.random() * window.innerHeight;
    
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    
    document.body.appendChild(ripple);
    
    // Remove ripple after animation
    setTimeout(() => {
      ripple.remove();
    }, 6000);
  }
  
  // Mouse interaction for extra particles
  let mouseX = 0;
  let mouseY = 0;
  
  document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    
    // Randomly create particles near mouse
    if (Math.random() < 0.1) {
      createMouseParticle(mouseX, mouseY);
    }
  });
  
  function createMouseParticle(x, y) {
    const particle = document.createElement('div');
    particle.style.position = 'fixed';
    particle.style.left = x + 'px';
    particle.style.top = y + 'px';
    particle.style.width = '3px';
    particle.style.height = '3px';
    particle.style.background = '#39FF14';
    particle.style.borderRadius = '50%';
    particle.style.pointerEvents = 'none';
    particle.style.zIndex = '5';
    particle.style.boxShadow = '0 0 10px rgba(57, 255, 20, 0.8)';
    
    document.body.appendChild(particle);
    
    // Animate particle
    const angle = Math.random() * Math.PI * 2;
    const distance = Math.random() * 100 + 50;
    const duration = Math.random() * 1000 + 500;
    
    particle.animate([
      {
        transform: 'translate(0, 0) scale(1)',
        opacity: 1
      },
      {
        transform: `translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px) scale(0)`,
        opacity: 0
      }
    ], {
      duration: duration,
      easing: 'ease-out'
    }).addEventListener('finish', () => {
      particle.remove();
    });
  }
  
  // PREMIUM BACKGROUND JAVASCRIPT END

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

  // Active navigation state management
  function setActiveNavItem(currentPage) {
    document.querySelectorAll('.nav-item').forEach(item => {
      item.classList.remove('active');
    });
    
    // Set active based on current page
    if (currentPage.includes('dream.php') || currentPage.includes('Dreams')) {
      document.querySelector('.nav-item[href*="dream.php"]').classList.add('active');
    } else if (currentPage.includes('subconscious.php')) {
      document.querySelector('.nav-item[href*="subconscious.php"]').classList.add('active');
    } else if (currentPage.includes('sleep_analysis.php')) {
      document.querySelector('.nav-item[href*="sleep_analysis.php"]').classList.add('active');
    } else if (currentPage.includes('index.php')) {
      document.querySelector('.nav-item[href*="index.php"]').classList.add('active');
    }
  }

  // Set active navigation on page load
  setActiveNavItem(window.location.href);
  
  // Dream Type Filter System
  let currentFilter = 'all';
  
  function filterDreams(type) {
    currentFilter = type;
    const dreams = document.querySelectorAll('.dream-card');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    // Update active filter button
    filterButtons.forEach(btn => {
      btn.classList.remove('active');
      if (btn.classList.contains(type)) {
        btn.classList.add('active');
      }
    });
    
    // Filter dreams with animation
    dreams.forEach((dream, index) => {
      const dreamType = dream.dataset.type;
      
      if (type === 'all' || dreamType === type) {
        dream.style.display = 'block';
        dream.style.animation = `fadeInUp 0.6s ease ${index * 0.1}s forwards`;
        dream.style.opacity = '0';
        setTimeout(() => {
          dream.style.opacity = '1';
        }, index * 100);
      } else {
        dream.style.animation = 'fadeOut 0.3s ease forwards';
        setTimeout(() => {
          dream.style.display = 'none';
        }, 300);
      }
    });
    
    // Add filter effect
    createFilterEffect(type);
  }
  
  function createFilterEffect(type) {
    const colors = {
      'nightmare': '#ff4757',
      'lucid': '#5352ed',
      'prophetic': '#ffd700',
      'normal': '#39FF14',
      'recurring': '#ff6b6b',
      'spiritual': '#9c88ff',
      'symbolic': '#ffa502',
      'emotional': '#ff7675',
      'all': '#39FF14'
    };
    
    const color = colors[type] || '#39FF14';
    
    // Create temporary filter effect
    const effect = document.createElement('div');
    effect.style.position = 'fixed';
    effect.style.top = '0';
    effect.style.left = '0';
    effect.style.width = '100%';
    effect.style.height = '100%';
    effect.style.background = `radial-gradient(circle at center, ${color}22 0%, transparent 70%)`;
    effect.style.pointerEvents = 'none';
    effect.style.zIndex = '1000';
    effect.style.opacity = '0';
    
    document.body.appendChild(effect);
    
    // Animate effect
    effect.animate([
      { opacity: 0, transform: 'scale(0.5)' },
      { opacity: 1, transform: 'scale(1.2)' },
      { opacity: 0, transform: 'scale(1.5)' }
    ], {
      duration: 800,
      easing: 'ease-out'
    }).addEventListener('finish', () => {
      effect.remove();
    });
  }
  
  // Dream Modal Functions
  let currentModalDreamData = null;
  
  function openDreamModal(dreamData) {
    // Store dream data for sharing from modal
    currentModalDreamData = dreamData;
    
    document.getElementById('modalTitle').textContent = dreamData.title;
    document.getElementById('modalDate').textContent = dreamData.date;
    document.getElementById('modalText').innerHTML = dreamData.text.replace(/\n/g, '<br>');
    document.getElementById('modalAnalysis').innerHTML = dreamData.analysis.replace(/\n/g, '<br>');
    
    // Set dream type in modal
    const modalType = document.getElementById('modalType');
    const dreamType = dreamData.type || 'normal';
    modalType.textContent = dreamData.type_label || 'Normal Dream';
    modalType.className = `dream-modal-type dream-type-${dreamType}`;
    
    const modal = document.getElementById('dreamModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Add modal opening effect
    createModalOpenEffect(dreamType);
  }
  
  function shareDreamFromModal() {
    if (currentModalDreamData) {
      shareDream(currentModalDreamData);
    }
  }
  
  function createModalOpenEffect(dreamType) {
    const colors = {
      'nightmare': '#ff4757',
      'lucid': '#5352ed',
      'prophetic': '#ffd700',
      'normal': '#39FF14',
      'recurring': '#ff6b6b',
      'spiritual': '#9c88ff',
      'symbolic': '#ffa502',
      'emotional': '#ff7675'
    };
    
    const color = colors[dreamType] || '#39FF14';
    
    // Create particles burst effect
    for (let i = 0; i < 20; i++) {
      const particle = document.createElement('div');
      particle.style.position = 'fixed';
      particle.style.left = '50%';
      particle.style.top = '50%';
      particle.style.width = '4px';
      particle.style.height = '4px';
      particle.style.background = color;
      particle.style.borderRadius = '50%';
      particle.style.pointerEvents = 'none';
      particle.style.zIndex = '10001';
      
      document.body.appendChild(particle);
      
      const angle = (Math.PI * 2 * i) / 20;
      const distance = Math.random() * 200 + 100;
      
      particle.animate([
        {
          transform: 'translate(-50%, -50%) scale(1)',
          opacity: 1
        },
        {
          transform: `translate(${Math.cos(angle) * distance - 50}%, ${Math.sin(angle) * distance - 50}%) scale(0)`,
          opacity: 0
        }
      ], {
        duration: 1000,
        easing: 'ease-out'
      }).addEventListener('finish', () => {
        particle.remove();
      });
    }
  }
  
  function closeDreamModal() {
    const modal = document.getElementById('dreamModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
  }
  
  // Share Dream Function
  function shareDream(dreamData) {
    // Show loading notification
    showShareNotification('<?php echo $t['sharing_dream']; ?>', 'info');
    
    // Debug: Log dream data
    console.log('Sharing dream data:', dreamData);
    
    // Directly share the dream using AJAX
    fetch('dream-sharing.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `action=share_dream&dream_id=${dreamData.id}`
    })
    .then(response => response.json())
    .then(data => {
      console.log('Share response:', data);
      if (data.success) {
        showShareNotification(data.message, 'success');
        createShareSuccessEffect();
        // Update the share button to show it's shared
        updateShareButton(dreamData.id, true);
      } else {
        showShareNotification(data.message, 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showShareNotification('<?php echo $t['share_error']; ?>', 'error');
    });
  }
  
  function updateShareButton(dreamId, isShared) {
    const shareBtn = document.querySelector(`[data-dream-id="${dreamId}"]`);
    if (shareBtn) {
      if (isShared) {
        shareBtn.innerHTML = '<i class="bi bi-check-circle"></i>';
        shareBtn.title = '<?php echo $t['dream_shared']; ?>';
        shareBtn.style.background = 'linear-gradient(135deg, rgba(46, 204, 113, 0.9) 0%, rgba(46, 204, 113, 0.8) 100%)';
        shareBtn.style.color = '#ffffff';
        shareBtn.onclick = null;
        shareBtn.style.cursor = 'default';
      }
    }
  }
  
  function showShareNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.left = '50%';
    notification.style.transform = 'translateX(-50%)';
    
    // Set background based on type
    if (type === 'success') {
      notification.style.background = 'linear-gradient(135deg, rgba(57, 255, 20, 0.9) 0%, rgba(46, 204, 113, 0.9) 100%)';
      notification.style.color = '#000000';
    } else if (type === 'error') {
      notification.style.background = 'linear-gradient(135deg, rgba(255, 107, 107, 0.9) 0%, rgba(255, 71, 87, 0.9) 100%)';
      notification.style.color = '#ffffff';
    } else if (type === 'info') {
      notification.style.background = 'linear-gradient(135deg, rgba(87, 206, 235, 0.9) 0%, rgba(116, 185, 255, 0.9) 100%)';
      notification.style.color = '#ffffff';
    }
    
    notification.style.padding = '15px 25px';
    notification.style.borderRadius = '25px';
    notification.style.fontWeight = '600';
    notification.style.fontSize = '14px';
    notification.style.zIndex = '10002';
    notification.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.3)';
    notification.style.backdropFilter = 'blur(10px)';
    notification.style.border = '2px solid rgba(255, 255, 255, 0.2)';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(-50%) translateY(-20px)';
    
    setTimeout(() => {
      notification.style.transition = 'all 0.3s ease';
      notification.style.opacity = '1';
      notification.style.transform = 'translateX(-50%) translateY(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
      notification.style.opacity = '0';
      notification.style.transform = 'translateX(-50%) translateY(-20px)';
      setTimeout(() => {
        if (notification.parentNode) {
          notification.parentNode.removeChild(notification);
        }
      }, 300);
    }, 3000);
  }
  
  function createShareSuccessEffect() {
    // Create success particles
    for (let i = 0; i < 15; i++) {
      const particle = document.createElement('div');
      particle.style.position = 'fixed';
      particle.style.left = '50%';
      particle.style.top = '50%';
      particle.style.width = '6px';
      particle.style.height = '6px';
      particle.style.background = '#39FF14';
      particle.style.borderRadius = '50%';
      particle.style.pointerEvents = 'none';
      particle.style.zIndex = '10001';
      particle.style.boxShadow = '0 0 10px rgba(57, 255, 20, 0.8)';
      
      document.body.appendChild(particle);
      
      const angle = (Math.PI * 2 * i) / 15;
      const distance = Math.random() * 300 + 150;
      
      particle.animate([
        {
          transform: 'translate(-50%, -50%) scale(1)',
          opacity: 1
        },
        {
          transform: `translate(${Math.cos(angle) * distance - 50}%, ${Math.sin(angle) * distance - 50}%) scale(0)`,
          opacity: 0
        }
      ], {
        duration: 1500,
        easing: 'ease-out'
      }).addEventListener('finish', () => {
        particle.remove();
      });
    }
  }
  

  
  // Close modal when clicking outside
  document.getElementById('dreamModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeDreamModal();
    }
  });
  
  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeDreamModal();
    }
  });
  
  function changeLanguage(lang) {
    const url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location.href = url.toString();
  }
  
  // Show loading screen during dream analysis
  function showAnalysisLoading() {
    const loadingScreen = document.getElementById('loading-screen');
    const loadingBar = document.getElementById('loadingBar');
    const steps = ['step1', 'step2', 'step3', 'step4'];
    
    // Ensure loading screen is properly positioned and covers entire viewport
    loadingScreen.style.position = 'fixed';
    loadingScreen.style.top = '0';
    loadingScreen.style.left = '0';
    loadingScreen.style.width = '100vw';
    loadingScreen.style.height = '100vh';
    loadingScreen.style.zIndex = '99999';
    loadingScreen.style.margin = '0';
    loadingScreen.style.padding = '0';
    
    // Show loading screen
    loadingScreen.classList.add('show');
    loadingScreen.classList.remove('hidden');
    loadingBar.style.width = '0%';
    steps.forEach(step => {
      document.getElementById(step).classList.remove('active');
      document.getElementById(step).classList.remove('completed');
    });
    
    // Start analysis loading animation
    let progress = 0;
    let currentStep = 0;
    
    // Activate first step
    document.getElementById('step1').classList.add('active');
    
    const interval = setInterval(() => {
      progress += Math.random() * 8 + 2; // Slower progress for analysis
      
      if (progress > 95) {
        progress = 95; // Stop at 95%, wait for page reload
        clearInterval(interval);
      }
      
      loadingBar.style.width = progress + '%';
      
      // Update steps
      const stepIndex = Math.floor(progress / 23.75); // 4 steps
      if (stepIndex !== currentStep && stepIndex < steps.length) {
        if (currentStep > 0) {
          document.getElementById(steps[currentStep - 1]).classList.remove('active');
          document.getElementById(steps[currentStep - 1]).classList.add('completed');
        }
        document.getElementById(steps[stepIndex]).classList.add('active');
        currentStep = stepIndex + 1;
      }
    }, 500); // Longer intervals for analysis
  }

  // Hide loading screen when analysis is complete
  function hideAnalysisLoading() {
    const loadingScreen = document.getElementById('loading-screen');
    loadingScreen.classList.add('fade-out');
    setTimeout(() => {
      loadingScreen.classList.remove('show');
      loadingScreen.classList.remove('fade-out');
    }, 1000);
  }
  
  // Initialize background effects on page load
  window.addEventListener('load', function() {
    // Initialize premium background effects
    setTimeout(() => {
      createMysticalParticles();
      createStarfield();
      
      // Create ripples periodically
      setInterval(createDreamRipple, 3000);
      
      // Add extra sparkle effects
      setInterval(() => {
        if (Math.random() < 0.3) {
          const x = Math.random() * window.innerWidth;
          const y = Math.random() * window.innerHeight;
          createMouseParticle(x, y);
        }
      }, 2000);
      
      // Initialize dream type animations
      initializeDreamTypeAnimations();
    }, 500);
  });
  
  // Dream Type Animation System
  function initializeDreamTypeAnimations() {
    const dreamCards = document.querySelectorAll('.dream-card');
    
    dreamCards.forEach((card, index) => {
      const dreamType = card.dataset.type;
      
      // Add entrance animation with delay
      card.style.animationDelay = (index * 0.15) + 's';
      
      // Add hover effects based on dream type
      card.addEventListener('mouseenter', function() {
        createDreamTypeEffect(this, dreamType);
      });
      
      // Add periodic glow effects
      setInterval(() => {
        if (Math.random() < 0.3) {
          createCardGlowEffect(card, dreamType);
        }
      }, 5000 + Math.random() * 5000);
    });
  }
  
  function createDreamTypeEffect(card, dreamType) {
    const colors = {
      'nightmare': '#ff4757',
      'lucid': '#5352ed',
      'prophetic': '#ffd700',
      'normal': '#39FF14',
      'recurring': '#ff6b6b',
      'spiritual': '#9c88ff',
      'symbolic': '#ffa502',
      'emotional': '#ff7675'
    };
    
    const color = colors[dreamType] || '#39FF14';
    
    // Create hover particles
    for (let i = 0; i < 8; i++) {
      const particle = document.createElement('div');
      particle.style.position = 'absolute';
      particle.style.width = '6px';
      particle.style.height = '6px';
      particle.style.background = color;
      particle.style.borderRadius = '50%';
      particle.style.pointerEvents = 'none';
      particle.style.left = Math.random() * 100 + '%';
      particle.style.top = Math.random() * 100 + '%';
      particle.style.boxShadow = `0 0 10px ${color}`;
      
      card.appendChild(particle);
      
      const angle = Math.random() * Math.PI * 2;
      const distance = Math.random() * 50 + 20;
      
      particle.animate([
        {
          transform: 'scale(0)',
          opacity: 1
        },
        {
          transform: `translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px) scale(1)`,
          opacity: 0
        }
      ], {
        duration: 1500,
        easing: 'ease-out'
      }).addEventListener('finish', () => {
        particle.remove();
      });
    }
  }
  
  function createCardGlowEffect(card, dreamType) {
    const colors = {
      'nightmare': '#ff4757',
      'lucid': '#5352ed',
      'prophetic': '#ffd700',
      'normal': '#39FF14',
      'recurring': '#ff6b6b',
      'spiritual': '#9c88ff',
      'symbolic': '#ffa502',
      'emotional': '#ff7675'
    };
    
    const color = colors[dreamType] || '#39FF14';
    
    // Create glow effect
    const originalBoxShadow = card.style.boxShadow;
    card.style.transition = 'box-shadow 0.5s ease';
    card.style.boxShadow = `0 0 30px ${color}77, ${originalBoxShadow}`;
    
    setTimeout(() => {
      card.style.boxShadow = originalBoxShadow;
    }, 1000);
  }
  
  // Page navigation handling (removed loading screen trigger)
  window.addEventListener('beforeunload', function() {
    // Handle any cleanup if needed
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
  
  // Add smooth hover effects to cards
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.dream-card');
    cards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.02)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
    
    // Initialize filter animations
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach((btn, index) => {
      btn.style.animationDelay = (index * 0.1) + 's';
      btn.style.animation = 'fadeInUp 0.6s ease forwards';
      btn.style.opacity = '0';
      
      setTimeout(() => {
        btn.style.opacity = '1';
      }, index * 100);
    });
  });
  
  // Add typing effect for form placeholders
  function typeWriter(element, text, speed = 100) {
    let i = 0;
    element.placeholder = '';
    function type() {
      if (i < text.length) {
        element.placeholder += text.charAt(i);
        i++;
        setTimeout(type, speed);
      }
    }
    type();
  }
  
  // Initialize typing effects
  document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="dream_text"]');
    const titleInput = document.querySelector('input[name="open_date"]');
    
    if (textarea) {
      setTimeout(() => typeWriter(textarea, 'Describe your dream in detail...'), 1000);
    }
    if (titleInput) {
      setTimeout(() => typeWriter(titleInput, 'Enter a title for your dream'), 1500);
    }
  });
  
  // Responsive adjustments
  window.addEventListener('resize', () => {
    // Adjust particle positions on resize
    const particles = document.querySelectorAll('.mystical-particle');
    particles.forEach(particle => {
      particle.style.left = Math.random() * 100 + '%';
      particle.style.top = Math.random() * 100 + '%';
    });
  });

  // 🎉 EASTER EGG: "irem" Konami Code
  let konamiCode = [];
  const konamiSequence = ['i', 'r', 'e', 'm'];
  
  document.addEventListener('keydown', function(e) {
    konamiCode.push(e.key.toLowerCase());
    
    // Keep only the last 4 keys
    if (konamiCode.length > 4) {
      konamiCode.shift();
    }
    
    // Check if the sequence matches "irem"
    if (konamiCode.join('') === 'irem') {
      triggerIremEasterEgg();
      konamiCode = []; // Reset the sequence
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
      z-index: 99999;
      pointer-events: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Manrope', sans-serif;
    `;
    
    // Create the main text
    const mainText = document.createElement('div');
    mainText.textContent = 'irem <3';
    mainText.style.cssText = `
      font-size: 8rem;
      font-weight: 900;
      color: #ff69b4;
      text-shadow: 
        0 0 20px #ff69b4,
        0 0 40px #ff69b4,
        0 0 60px #ff69b4,
        0 0 80px #ff69b4;
      animation: iremGlow 2s ease-in-out infinite alternate;
      position: relative;
      z-index: 100000;
    `;
    
    // Add CSS animation for the glow effect
    const style = document.createElement('style');
    style.textContent = `
      @keyframes iremGlow {
        0% {
          text-shadow: 
            0 0 20px #ff69b4,
            0 0 40px #ff69b4,
            0 0 60px #ff69b4,
            0 0 80px #ff69b4;
          transform: scale(1) rotate(0deg);
        }
        100% {
          text-shadow: 
            0 0 30px #ff1493,
            0 0 60px #ff1493,
            0 0 90px #ff1493,
            0 0 120px #ff1493;
          transform: scale(1.1) rotate(2deg);
        }
      }
      
      @keyframes firework {
        0% {
          transform: translateY(0) scale(0);
          opacity: 1;
        }
        100% {
          transform: translateY(-100vh) scale(1);
          opacity: 0;
        }
      }
      
      @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
      }
      
      @keyframes sparkle {
        0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
        50% { opacity: 1; transform: scale(1) rotate(180deg); }
      }
    `;
    
    document.head.appendChild(style);
    easterEgg.appendChild(mainText);
    document.body.appendChild(easterEgg);
    
    // Create fireworks
    createFireworks();
    
    // Create floating hearts
    createFloatingHearts();
    
    // Create sparkles
    createSparkles();
    
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
  
  function createFireworks() {
    const colors = ['#ff69b4', '#ff1493', '#ff69b4', '#ff1493', '#ff69b4'];
    
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
          animation: firework ${2 + Math.random() * 2}s ease-out forwards;
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
  
  function createFloatingHearts() {
    const hearts = ['❤️', '💖', '💕', '💗', '💓', '💝'];
    
    for (let i = 0; i < 15; i++) {
      setTimeout(() => {
        const heart = document.createElement('div');
        heart.textContent = hearts[Math.floor(Math.random() * hearts.length)];
        heart.style.cssText = `
          position: fixed;
          left: ${Math.random() * 100}%;
          top: ${Math.random() * 100}%;
          font-size: ${2 + Math.random() * 3}rem;
          animation: heartBeat ${1 + Math.random()}s ease-in-out infinite;
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
  
  function createSparkles() {
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
          animation: sparkle ${2 + Math.random() * 2}s ease-in-out infinite;
          z-index: 99996;
          pointer-events: none;
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

</script>

</body>
</html>