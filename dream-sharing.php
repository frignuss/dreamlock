<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

require 'config.php';

// Database connection
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create AI cache table if not exists
try {
    $db->exec("CREATE TABLE IF NOT EXISTS dream_ai_cache (
        id INT AUTO_INCREMENT PRIMARY KEY,
        text_hash VARCHAR(32) NOT NULL,
        lang VARCHAR(5) NOT NULL,
        ai_analysis TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_hash_lang (text_hash, lang),
        INDEX idx_created_at (created_at)
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS daily_trends_cache (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        lang VARCHAR(5) NOT NULL,
        trend_data TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_date_lang (date, lang),
        INDEX idx_date (date)
    )");
    
    // Translation cache table
    $db->exec("CREATE TABLE IF NOT EXISTS translation_cache (
        id INT AUTO_INCREMENT PRIMARY KEY,
        text_hash VARCHAR(32) NOT NULL,
        source_lang VARCHAR(5) NOT NULL,
        target_lang VARCHAR(5) NOT NULL,
        translated_text TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_translation (text_hash, source_lang, target_lang),
        INDEX idx_created_at (created_at),
        INDEX idx_languages (source_lang, target_lang)
    )");
} catch (Exception $e) {
    // Table creation failed, continue without cache
}

// Multi-language support
$lang = loadLanguage();

$translations = [
  'en' => [
    'dream_sharing' => 'Dream Sharing',
    'share_dream' => 'Share Your Dream',
    'shared_dreams' => 'Shared Dreams',
    'my_shared_dreams' => 'My Shared Dreams',
    'no_shared_dreams' => 'No dreams have been shared yet.',
    'share_this_dream' => 'Share This Dream',
    'unshare_dream' => 'Unshare Dream',
    'add_comment' => 'Add Comment',
    'write_comment' => 'Write your comment...',
    'post_comment' => 'Post Comment',
    'like' => 'Like',
    'unlike' => 'Unlike',
    'comments' => 'Comments',
    'views' => 'Views',
    'shared_by' => 'Shared by',
    'shared_on' => 'Shared on',
    'dream_type' => 'Dream Type',
    'culture' => 'Culture',
    'filter_all' => 'All Dreams',
    'filter_my_dreams' => 'My Dreams',
    'filter_popular' => 'Popular',
    'filter_recent' => 'Recent',
    'home' => 'Home',
    'subconscious' => 'Subconscious',
    'sleep' => 'Sleep Analysis',
    'logout' => 'Log Out',
    'premium' => 'Premium',
    'upgrade_premium' => 'Upgrade to Premium',
    'comment_success' => 'Comment posted successfully!',
    'comment_error' => 'Error posting comment. Please try again.',
    'share_success' => 'Dream shared successfully!',
    'share_error' => 'Error sharing dream. Please try again.',
    'dream_not_found' => 'Dream not found',
    'dream_already_shared' => 'Dream already shared',
    'dream_id_missing' => 'Dream ID is missing',
    'unshare_success' => 'Dream unshared successfully!',
    'unshare_error' => 'Error unsharing dream. Please try again.',
    'like_success' => 'Dream liked!',
    'unlike_success' => 'Dream unliked!',
    'like_error' => 'Error processing like. Please try again.',
    'delete_comment' => 'Delete Comment',
    'delete_comment_confirm' => 'Are you sure you want to delete this comment?',
    'comment_deleted' => 'Comment deleted successfully!',
    'comment_delete_error' => 'Error deleting comment. Please try again.',
    'no_comments' => 'No comments yet. Be the first to comment!',
    'load_more' => 'Load More',
    'no_more_dreams' => 'No more dreams to load.',
    'search_dreams' => 'Search dreams...',
    'sort_by' => 'Sort by',
    'sort_recent' => 'Most Recent',
    'sort_popular' => 'Most Popular',
    'sort_views' => 'Most Viewed',
    'sort_likes' => 'Most Liked',
    'ai_analysis' => 'AI Analysis',
    'translate_dream' => 'Translate Dream',
    'original_language' => 'Original Language',
    'translated_to' => 'Translated to',
    'auto_translate' => 'Auto Translate',
    'translation_loading' => 'Translating...',
    'translation_error' => 'Translation failed. Please try again.',
    'show_original' => 'Show Original',
    'show_translated' => 'Show Translated',
    'daily_trends' => 'Daily Trends',
    'todays_dream_trends' => 'Today\'s Dream Trends',
    'dreams_today' => 'dreams today',
    'top_symbols' => 'Top Symbols',
    'top_emotions' => 'Top Emotions',
    'top_themes' => 'Top Themes',
    'dream_types_label' => 'Dream Types',
    'loading_trends' => 'Loading trends...',
    'dream_types' => [
        'normal' => 'Normal',
        'lucid' => 'Lucid',
        'nightmare' => 'Nightmare',
        'recurring' => 'Recurring',
        'prophetic' => 'Prophetic',
        'vivid' => 'Vivid'
    ]
  ],
  'tr' => [
    'dream_sharing' => 'Rüya Paylaşımı',
    'share_dream' => 'Rüyanı Paylaş',
    'shared_dreams' => 'Paylaşılan Rüyalar',
    'my_shared_dreams' => 'Paylaştığım Rüyalar',
    'no_shared_dreams' => 'Henüz hiç rüya paylaşılmamış.',
    'share_this_dream' => 'Bu Rüyayı Paylaş',
    'unshare_dream' => 'Paylaşımı Kaldır',
    'add_comment' => 'Yorum Ekle',
    'write_comment' => 'Yorumunuzu yazın...',
    'post_comment' => 'Yorum Gönder',
    'like' => 'Beğen',
    'unlike' => 'Beğenmeyi Kaldır',
    'comments' => 'Yorumlar',
    'views' => 'Görüntülenme',
    'shared_by' => 'Paylaşan',
    'shared_on' => 'Paylaşım tarihi',
    'dream_type' => 'Rüya Türü',
    'culture' => 'Kültür',
    'filter_all' => 'Tüm Rüyalar',
    'filter_my_dreams' => 'Rüyalarım',
    'filter_popular' => 'Popüler',
    'filter_recent' => 'Yeni',
    'home' => 'Ana Sayfa',
    'subconscious' => 'Bilinçaltı',
    'sleep' => 'Uyku Analizi',
    'logout' => 'Çıkış Yap',
    'premium' => 'Premium',
    'upgrade_premium' => 'Premium\'a Yükselt',
    'comment_success' => 'Yorum başarıyla gönderildi!',
    'comment_error' => 'Yorum gönderilirken hata oluştu. Lütfen tekrar deneyin.',
    'share_success' => 'Rüya başarıyla paylaşıldı!',
    'share_error' => 'Rüya paylaşılırken hata oluştu. Lütfen tekrar deneyin.',
    'dream_not_found' => 'Rüya bulunamadı',
    'dream_already_shared' => 'Rüya zaten paylaşılmış',
    'dream_id_missing' => 'Rüya ID\'si eksik',
    'unshare_success' => 'Rüya paylaşımı kaldırıldı!',
    'unshare_error' => 'Rüya paylaşımı kaldırılırken hata oluştu. Lütfen tekrar deneyin.',
    'like_success' => 'Rüya beğenildi!',
    'unlike_success' => 'Rüya beğenisi kaldırıldı!',
    'like_error' => 'Beğeni işlenirken hata oluştu. Lütfen tekrar deneyin.',
    'delete_comment' => 'Yorumu Sil',
    'delete_comment_confirm' => 'Bu yorumu silmek istediğinizden emin misiniz?',
    'comment_deleted' => 'Yorum başarıyla silindi!',
    'comment_delete_error' => 'Yorum silinirken hata oluştu. Lütfen tekrar deneyin.',
    'no_comments' => 'Henüz yorum yok. İlk yorumu siz yapın!',
    'load_more' => 'Daha Fazla Yükle',
    'no_more_dreams' => 'Yüklenecek başka rüya yok.',
    'search_dreams' => 'Rüya ara...',
    'sort_by' => 'Sırala',
    'sort_recent' => 'En Yeni',
    'sort_popular' => 'En Popüler',
    'sort_views' => 'En Çok Görüntülenen',
    'sort_likes' => 'En Çok Beğenilen',
    'ai_analysis' => 'Yapay Zeka Analizi',
    'translate_dream' => 'Rüyayı Çevir',
    'original_language' => 'Orijinal Dil',
    'translated_to' => 'Çevrildi',
    'auto_translate' => 'Otomatik Çevir',
    'translation_loading' => 'Çevriliyor...',
    'translation_error' => 'Çeviri başarısız. Lütfen tekrar deneyin.',
    'show_original' => 'Orijinali Göster',
    'show_translated' => 'Çeviriyi Göster',
    'daily_trends' => 'Günlük Trendler',
    'todays_dream_trends' => 'Bugünün Rüya Trendleri',
    'dreams_today' => 'rüya bugün',
    'top_symbols' => 'En Çok Semboller',
    'top_emotions' => 'En Çok Duygular',
    'top_themes' => 'En Çok Temalar',
    'dream_types_label' => 'Rüya Türleri',
    'loading_trends' => 'Trendler yükleniyor...',
    'dream_types' => [
        'normal' => 'Normal',
        'lucid' => 'Lucid',
        'nightmare' => 'Kabus',
        'recurring' => 'Tekrarlayan',
        'prophetic' => 'Kehanet',
        'vivid' => 'Canlı'
    ]
  ],
  'es' => [
    'dream_sharing' => 'Compartir Sueños',
    'share_dream' => 'Compartir tu Sueño',
    'shared_dreams' => 'Sueños Compartidos',
    'my_shared_dreams' => 'Mis Sueños Compartidos',
    'no_shared_dreams' => 'Aún no se han compartido sueños.',
    'share_this_dream' => 'Compartir este Sueño',
    'unshare_dream' => 'Dejar de Compartir',
    'add_comment' => 'Agregar Comentario',
    'write_comment' => 'Escribe tu comentario...',
    'post_comment' => 'Publicar Comentario',
    'like' => 'Me Gusta',
    'unlike' => 'No Me Gusta',
    'comments' => 'Comentarios',
    'views' => 'Vistas',
    'shared_by' => 'Compartido por',
    'shared_on' => 'Compartido el',
    'dream_type' => 'Tipo de Sueño',
    'culture' => 'Cultura',
    'filter_all' => 'Todos los Sueños',
    'filter_my_dreams' => 'Mis Sueños',
    'filter_popular' => 'Populares',
    'filter_recent' => 'Recientes',
    'home' => 'Inicio',
    'subconscious' => 'Subconsciente',
    'sleep' => 'Análisis del Sueño',
    'logout' => 'Cerrar Sesión',
    'premium' => 'Premium',
    'upgrade_premium' => 'Actualizar a Premium',
    'comment_success' => '¡Comentario publicado exitosamente!',
    'comment_error' => 'Error al publicar comentario. Inténtalo de nuevo.',
    'share_success' => '¡Sueño compartido exitosamente!',
    'share_error' => 'Error al compartir sueño. Inténtalo de nuevo.',
    'dream_not_found' => 'Sueño no encontrado',
    'dream_already_shared' => 'Sueño ya compartido',
    'dream_id_missing' => 'ID del sueño faltante',
    'unshare_success' => '¡Sueño dejado de compartir exitosamente!',
    'unshare_error' => 'Error al dejar de compartir sueño. Inténtalo de nuevo.',
    'like_success' => '¡Sueño gustado!',
    'unlike_success' => '¡Sueño no gustado!',
    'like_error' => 'Error al procesar me gusta. Inténtalo de nuevo.',
    'delete_comment' => 'Eliminar Comentario',
    'delete_comment_confirm' => '¿Estás seguro de que quieres eliminar este comentario?',
    'comment_deleted' => '¡Comentario eliminado exitosamente!',
    'comment_delete_error' => 'Error al eliminar comentario. Inténtalo de nuevo.',
    'no_comments' => 'Aún no hay comentarios. ¡Sé el primero en comentar!',
    'load_more' => 'Cargar Más',
    'no_more_dreams' => 'No hay más sueños para cargar.',
    'search_dreams' => 'Buscar sueños...',
    'sort_by' => 'Ordenar por',
    'sort_recent' => 'Más Recientes',
    'sort_popular' => 'Más Populares',
    'sort_views' => 'Más Vistos',
    'sort_likes' => 'Más Gustados',
    'ai_analysis' => 'Análisis IA',
    'translate_dream' => 'Traducir Sueño',
    'original_language' => 'Idioma Original',
    'translated_to' => 'Traducido a',
    'auto_translate' => 'Traducción Automática',
    'translation_loading' => 'Traduciendo...',
    'translation_error' => 'Error en la traducción. Inténtalo de nuevo.',
    'show_original' => 'Mostrar Original',
    'show_translated' => 'Mostrar Traducido',
    'daily_trends' => 'Tendencias Diarias',
    'todays_dream_trends' => 'Tendencias de Sueños de Hoy',
    'dreams_today' => 'sueños hoy',
    'top_symbols' => 'Símbolos Principales',
    'top_emotions' => 'Emociones Principales',
    'top_themes' => 'Temas Principales',
    'dream_types_label' => 'Tipos de Sueños',
    'loading_trends' => 'Cargando tendencias...',
    'dream_types' => [
        'normal' => 'Normal',
        'lucid' => 'Lúcido',
        'nightmare' => 'Pesadilla',
        'recurring' => 'Recurrente',
        'prophetic' => 'Profético',
        'vivid' => 'Vívido'
    ]
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

$current_user_id = getValidUserId($db);

// Run cache cleanup occasionally (1 in 100 requests to avoid performance impact)
if (rand(1, 100) === 1) {
    cleanupOldCache($db);
}

// Translation function using OpenRouter API with caching
function translateText($text, $targetLang, $sourceLang = 'auto', $db = null) {
    if (empty($text)) return $text;

    // Create text hash for caching
    $text_hash = md5($text . $sourceLang . $targetLang);
    
    // Check cache first if database is available
    if ($db) {
        try {
            $cache_stmt = $db->prepare("SELECT translated_text FROM translation_cache WHERE text_hash = ? AND source_lang = ? AND target_lang = ? AND created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $cache_stmt->execute([$text_hash, $sourceLang, $targetLang]);
            $cached_result = $cache_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($cached_result) {
                return $cached_result['translated_text'];
            }
        } catch (Exception $e) {
            // Cache query failed, continue with API call
            error_log("Translation cache query failed: " . $e->getMessage());
        }
    }

    $langMap = [
        'en' => 'English',
        'tr' => 'Turkish',
        'es' => 'Spanish'
    ];

    $targetLangName = $langMap[$targetLang] ?? $targetLang;

    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . OPENROUTER_API_KEY,
        'HTTP-Referer: http://localhost',
        'X-Title: DreamLock Translation'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'deepseek/deepseek-chat-v3-0324',
        'messages' => [
            ['role' => 'system', 'content' => "You are a professional translator. Translate the given text to $targetLangName. Keep the original meaning and tone. Only return the translated text, nothing else."],
            ['role' => 'user', 'content' => $text]
        ],
        'temperature' => 0.3,
        'max_tokens' => 1000
    ]));

    $response = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno !== 0 || $httpCode < 200 || $httpCode >= 300) {
        return false;
    }

    $result = json_decode($response, true);
    if (!is_array($result)) {
        return false;
    }

    $content = $result['choices'][0]['message']['content'] ?? null;
    if (!is_string($content) || $content === '') {
        return false;
    }
    
    // Cache the translation if database is available
    if ($db && $content) {
        try {
            $insert_stmt = $db->prepare("INSERT INTO translation_cache (text_hash, source_lang, target_lang, translated_text, created_at) VALUES (?, ?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE translated_text = VALUES(translated_text), created_at = NOW()");
            $insert_stmt->execute([$text_hash, $sourceLang, $targetLang, $content]);
        } catch (Exception $e) {
            // Cache insert failed, but translation was successful
            error_log("Translation cache insert failed: " . $e->getMessage());
        }
    }
    
    return $content;
}

// Function to clean up old cache entries
function cleanupOldCache($db) {
    try {
        // Clean up translation cache older than 30 days
        $db->exec("DELETE FROM translation_cache WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        
        // Clean up AI cache older than 7 days
        $db->exec("DELETE FROM dream_ai_cache WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
        
        // Clean up daily trends cache older than 7 days
        $db->exec("DELETE FROM daily_trends_cache WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
        
        return true;
    } catch (Exception $e) {
        error_log("Cache cleanup failed: " . $e->getMessage());
        return false;
    }
}

// Function to get cache statistics
function getCacheStats($db) {
    try {
        $stats = [];
        
        // Translation cache stats
        $stmt = $db->query("SELECT COUNT(*) as count, COUNT(DISTINCT text_hash) as unique_texts FROM translation_cache");
        $translation_stats = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['translation'] = $translation_stats;
        
        // AI cache stats
        $stmt = $db->query("SELECT COUNT(*) as count, COUNT(DISTINCT text_hash) as unique_texts FROM dream_ai_cache");
        $ai_stats = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['ai'] = $ai_stats;
        
        // Daily trends cache stats
        $stmt = $db->query("SELECT COUNT(*) as count FROM daily_trends_cache");
        $trends_stats = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['trends'] = $trends_stats;
        
                return $stats;
    } catch (Exception $e) {
        error_log("Cache stats failed: " . $e->getMessage());
        return null;
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'share_dream':
            $dream_id = $_POST['dream_id'] ?? null;
            if ($dream_id) {
                try {
                    // Get dream data
                    $stmt = $db->prepare("SELECT * FROM dreams WHERE id = ? AND user_id = ?");
                    $stmt->execute([$dream_id, $current_user_id]);
                    $dream = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($dream) {
                        // Check if already shared
                        $stmt = $db->prepare("SELECT id FROM shared_dreams WHERE user_id = ? AND dream_text = ?");
                        $stmt->execute([$current_user_id, $dream['dream_text']]);
                        
                        if (!$stmt->fetch()) {
                            // Share the dream
                            $stmt = $db->prepare("INSERT INTO shared_dreams (user_id, dream_title, dream_text, analysis, dream_type, culture) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$current_user_id, $dream['open_date'], $dream['dream_text'], $dream['analysis'], $dream['dream_type'], 'none']);
                            
                            // Update dreams table
                            $shared_id = $db->lastInsertId();
                            $stmt = $db->prepare("UPDATE dreams SET sharing_enabled = TRUE, shared_dream_id = ? WHERE id = ?");
                            $stmt->execute([$shared_id, $dream_id]);
                            
                            echo json_encode(['success' => true, 'message' => $t['share_success']]);
                        } else {
                            echo json_encode(['success' => false, 'message' => $t['dream_already_shared'] ?? 'Dream already shared']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => $t['dream_not_found'] ?? 'Dream not found']);
                    }
                } catch (Exception $e) {
                    error_log("Share dream error: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => $t['share_error']]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => $t['dream_id_missing'] ?? 'Dream ID is missing']);
            }
            exit();
            
        case 'unshare_dream':
            $shared_dream_id = $_POST['shared_dream_id'] ?? $_POST['dream_id'] ?? null;
            if ($shared_dream_id) {
                try {
                    // If dream_id is provided, find the corresponding shared_dream_id
                    if (isset($_POST['dream_id'])) {
                        $stmt = $db->prepare("SELECT shared_dream_id FROM dreams WHERE id = ? AND user_id = ?");
                        $stmt->execute([$shared_dream_id, $current_user_id]);
                        $dream = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($dream && $dream['shared_dream_id']) {
                            $shared_dream_id = $dream['shared_dream_id'];
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Dream not found or not shared']);
                            exit();
                        }
                    }
                    
                    $stmt = $db->prepare("DELETE FROM shared_dreams WHERE id = ? AND user_id = ?");
                    $stmt->execute([$shared_dream_id, $current_user_id]);
                    
                    if ($stmt->rowCount() > 0) {
                        // Update dreams table
                        $stmt = $db->prepare("UPDATE dreams SET sharing_enabled = FALSE, shared_dream_id = NULL WHERE shared_dream_id = ?");
                        $stmt->execute([$shared_dream_id]);
                        
                        echo json_encode(['success' => true, 'message' => $t['unshare_success']]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Dream not found']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $t['unshare_error']]);
                }
            }
            exit();
            
        case 'add_comment':
            $shared_dream_id = $_POST['shared_dream_id'] ?? null;
            $comment_text = trim($_POST['comment_text'] ?? '');
            
            if ($shared_dream_id && $comment_text) {
                try {
                    $stmt = $db->prepare("INSERT INTO dream_comments (shared_dream_id, user_id, comment_text) VALUES (?, ?, ?)");
                    $stmt->execute([$shared_dream_id, $current_user_id, $comment_text]);
                    
                    // Update comment count
                    $stmt = $db->prepare("UPDATE shared_dreams SET comments_count = comments_count + 1 WHERE id = ?");
                    $stmt->execute([$shared_dream_id]);
                    
                    echo json_encode(['success' => true, 'message' => $t['comment_success']]);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $t['comment_error']]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid comment']);
            }
            exit();
            
        case 'like_dream':
            $shared_dream_id = $_POST['shared_dream_id'] ?? null;
            if ($shared_dream_id) {
                try {
                    // Check if already liked
                    $stmt = $db->prepare("SELECT id FROM dream_likes WHERE shared_dream_id = ? AND user_id = ?");
                    $stmt->execute([$shared_dream_id, $current_user_id]);
                    
                    if ($stmt->fetch()) {
                        // Unlike
                        $stmt = $db->prepare("DELETE FROM dream_likes WHERE shared_dream_id = ? AND user_id = ?");
                        $stmt->execute([$shared_dream_id, $current_user_id]);
                        
                        $stmt = $db->prepare("UPDATE shared_dreams SET likes_count = likes_count - 1 WHERE id = ?");
                        $stmt->execute([$shared_dream_id]);
                        
                        echo json_encode(['success' => true, 'message' => $t['unlike_success'], 'action' => 'unliked']);
                    } else {
                        // Like
                        $stmt = $db->prepare("INSERT INTO dream_likes (shared_dream_id, user_id) VALUES (?, ?)");
                        $stmt->execute([$shared_dream_id, $current_user_id]);
                        
                        $stmt = $db->prepare("UPDATE shared_dreams SET likes_count = likes_count + 1 WHERE id = ?");
                        $stmt->execute([$shared_dream_id]);
                        
                        echo json_encode(['success' => true, 'message' => $t['like_success'], 'action' => 'liked']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $t['like_error']]);
                }
            }
            exit();
            
        case 'delete_comment':
            $comment_id = $_POST['comment_id'] ?? null;
            if ($comment_id) {
                try {
                    // Get shared_dream_id before deleting
                    $stmt = $db->prepare("SELECT shared_dream_id FROM dream_comments WHERE id = ? AND user_id = ?");
                    $stmt->execute([$comment_id, $current_user_id]);
                    $comment = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($comment) {
                        // Delete the comment
                        $stmt = $db->prepare("DELETE FROM dream_comments WHERE id = ? AND user_id = ?");
                        $stmt->execute([$comment_id, $current_user_id]);
                        
                        // Update comment count
                        $stmt = $db->prepare("UPDATE shared_dreams SET comments_count = comments_count - 1 WHERE id = ?");
                        $stmt->execute([$comment['shared_dream_id']]);
                        
                        echo json_encode(['success' => true, 'message' => $t['comment_deleted']]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Comment not found']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $t['comment_delete_error']]);
                }
            }
            exit();
            
        case 'load_comments':
            $shared_dream_id = $_POST['shared_dream_id'] ?? null;
            if ($shared_dream_id) {
                try {
                    $stmt = $db->prepare("
                        SELECT dc.*, u.username, 
                               (dc.user_id = ?) as can_delete
                        FROM dream_comments dc 
                        LEFT JOIN users u ON dc.user_id = u.id 
                        WHERE dc.shared_dream_id = ? AND dc.is_approved = TRUE 
                        ORDER BY dc.created_at ASC
                    ");
                    $stmt->execute([$current_user_id, $shared_dream_id]);
                    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Format dates
                    foreach ($comments as &$comment) {
                        $comment['created_at'] = date('M d, Y H:i', strtotime($comment['created_at']));
                        $comment['comment_text'] = htmlspecialchars($comment['comment_text']);
                        $comment['username'] = htmlspecialchars($comment['username']);
                    }
                    
                    echo json_encode(['success' => true, 'comments' => $comments]);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => 'Error loading comments']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid dream ID']);
            }
            exit();
            

            
        case 'translate_dream':
            $shared_dream_id = $_POST['shared_dream_id'] ?? null;
            $target_lang = $_POST['target_lang'] ?? $lang;
            
            if ($shared_dream_id) {
                try {
                    // Get dream data
                    $stmt = $db->prepare("SELECT dream_text, analysis FROM shared_dreams WHERE id = ?");
                    $stmt->execute([$shared_dream_id]);
                    $dream = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($dream) {
                        // Detect source language (assuming it's stored in the database or default to 'en')
                        $source_lang = 'en'; // You can modify this to detect or store the original language
                        
                        $translated_text = translateText($dream['dream_text'], $target_lang, $source_lang, $db);
                        $translated_analysis = $dream['analysis'] ? translateText($dream['analysis'], $target_lang, $source_lang, $db) : '';
                        
                        if ($translated_text !== false) {
                            echo json_encode([
                                'success' => true, 
                                'translated_text' => $translated_text,
                                'translated_analysis' => $translated_analysis,
                                'target_lang' => $target_lang
                            ]);
                        } else {
                            echo json_encode(['success' => false, 'message' => $t['translation_error']]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => $t['dream_not_found']]);
                    }
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $t['translation_error']]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid dream ID']);
            }
            exit();
            
        case 'get_cache_stats':
            // Only allow admin users or for debugging
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) { // Assuming user ID 1 is admin
                $stats = getCacheStats($db);
                if ($stats) {
                    echo json_encode(['success' => true, 'stats' => $stats]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to get cache stats']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
            exit();
            
        case 'clear_cache':
            // Only allow admin users
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
                try {
                    $db->exec("DELETE FROM translation_cache");
                    $db->exec("DELETE FROM dream_ai_cache");
                    $db->exec("DELETE FROM daily_trends_cache");
                    echo json_encode(['success' => true, 'message' => 'Cache cleared successfully']);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => 'Failed to clear cache: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
            exit();
    }
}

        // Handle logout
        if (isset($_GET['logout'])) {
            session_destroy();
            header("Location: index.php");
            exit();
        }

        // Handle language change
        if (isset($_GET['lang'])) {
            $newLang = sanitizeLangCode($_GET['lang']);
            $_SESSION['lang'] = $newLang;
            $secure = (ENVIRONMENT === 'production');
            setcookie('lang', $newLang, time() + 31536000, '/', '', $secure, true);
            // Preserve other query parameters without lang
            $current_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $query_params = $_GET;
            unset($query_params['lang']);
            $redirect_url = $current_url;
            if (!empty($query_params)) {
                $redirect_url .= '?' . http_build_query($query_params);
            }
            header("Location: " . $redirect_url);
            exit();
        }

// Get filter and sort parameters
$filter = $_GET['filter'] ?? 'all';
$sort = $_GET['sort'] ?? 'recent';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

// Build query based on filter and sort
$where_clause = "WHERE sd.is_public = TRUE";
$order_clause = "ORDER BY sd.created_at DESC";

if ($filter === 'my_dreams') {
    $where_clause = "WHERE sd.user_id = ?";
    $params = [$current_user_id];
} else {
    $params = [];
}

switch ($sort) {
    case 'popular':
        $order_clause = "ORDER BY sd.likes_count DESC, sd.views_count DESC";
        break;
    case 'views':
        $order_clause = "ORDER BY sd.views_count DESC";
        break;
    case 'likes':
        $order_clause = "ORDER BY sd.likes_count DESC";
        break;
    default:
        $order_clause = "ORDER BY sd.created_at DESC";
}

// Get shared dreams
$query = "SELECT sd.*, u.username, 
          (SELECT COUNT(*) FROM dream_likes WHERE shared_dream_id = sd.id AND user_id = ?) as user_liked
          FROM shared_dreams sd 
          LEFT JOIN users u ON sd.user_id = u.id 
          $where_clause 
          $order_clause 
          LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

$params = array_merge($params, [$current_user_id]);
$stmt = $db->prepare($query);
$stmt->execute($params);
$shared_dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's dreams that can be shared
$stmt = $db->prepare("SELECT id, open_date, dream_text, analysis, dream_type, sharing_enabled FROM dreams WHERE user_id = ? AND sharing_enabled = FALSE ORDER BY created_at DESC");
$stmt->execute([$current_user_id]);
$user_dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

// AI-powered dream symbol and emotion analysis with caching
function analyzeDreamWithAI($dream_text, $analysis_text, $lang = 'en', $db = null) {
    $combined_text = $dream_text . ' ' . $analysis_text;
    $text_hash = md5($combined_text . $lang);
    
    // Check cache first
    if ($db) {
        $cache_stmt = $db->prepare("SELECT ai_analysis FROM dream_ai_cache WHERE text_hash = ? AND lang = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        $cache_stmt->execute([$text_hash, $lang]);
        $cached_result = $cache_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cached_result) {
            $parsed = json_decode($cached_result['ai_analysis'], true);
            if ($parsed && isset($parsed['symbols']) && isset($parsed['emotions'])) {
                return $parsed;
            }
        }
    }
    
    $prompts = [
        'en' => "Analyze this dream text and identify the most prominent symbols, themes, and emotions. Return ONLY a JSON object with this exact structure:
{
  \"symbols\": [\"symbol1\", \"symbol2\", \"symbol3\"],
  \"emotions\": [\"emotion1\", \"emotion2\"],
  \"themes\": [\"theme1\", \"theme2\"]
}

Focus on universal dream symbols like: water, flying, falling, chase, house, animals, family, school, work, death, love, fear, colors, numbers, vehicles, trees, mountains, bridges, doors, windows, stairs, elevators, cars, planes, trains, boats, fire, earth, air, light, darkness, mirrors, clocks, phones, computers, books, money, food, clothes, shoes, jewelry, weapons, tools, machines, buildings, cities, forests, gardens, flowers, fruits, weather, seasons, time, space, stars, moon, sun, clouds, rain, snow, wind, thunder, lightning, etc.

For emotions, identify: joy, sadness, anger, fear, surprise, peace, anxiety, excitement, confusion, wonder, nostalgia, hope, despair, love, hate, jealousy, envy, pride, shame, guilt, relief, frustration, satisfaction, etc.

Dream text: ",
        'tr' => "Bu rüya metnini analiz et ve en belirgin sembolleri, temaları ve duyguları tanımla. SADECE bu tam yapıda bir JSON nesnesi döndür:
{
  \"symbols\": [\"sembol1\", \"sembol2\", \"sembol3\"],
  \"emotions\": [\"duygu1\", \"duygu2\"],
  \"themes\": [\"tema1\", \"tema2\"]
}

Evrensel rüya sembollerine odaklan: su, uçma, düşme, kovalamaca, ev, hayvanlar, aile, okul, iş, ölüm, aşk, korku, renkler, sayılar, araçlar, ağaçlar, dağlar, köprüler, kapılar, pencereler, merdivenler, asansörler, arabalar, uçaklar, trenler, tekneler, ateş, toprak, hava, ışık, karanlık, aynalar, saatler, telefonlar, bilgisayarlar, kitaplar, para, yemek, kıyafetler, ayakkabılar, mücevherler, silahlar, aletler, makineler, binalar, şehirler, ormanlar, bahçeler, çiçekler, meyveler, hava durumu, mevsimler, zaman, uzay, yıldızlar, ay, güneş, bulutlar, yağmur, kar, rüzgar, gök gürültüsü, şimşek, vb.

Duygular için tanımla: sevinç, üzüntü, öfke, korku, şaşkınlık, huzur, kaygı, heyecan, karışıklık, merak, nostalji, umut, umutsuzluk, aşk, nefret, kıskançlık, haset, gurur, utanç, suçluluk, rahatlama, hayal kırıklığı, memnuniyet, vb.

Rüya metni: ",
        'es' => "Analiza este texto de sueño e identifica los símbolos, temas y emociones más prominentes. Devuelve SOLO un objeto JSON con esta estructura exacta:
{
  \"symbols\": [\"símbolo1\", \"símbolo2\", \"símbolo3\"],
  \"emotions\": [\"emoción1\", \"emoción2\"],
  \"themes\": [\"tema1\", \"tema2\"]
}

Enfócate en símbolos universales de sueños como: agua, volar, caer, persecución, casa, animales, familia, escuela, trabajo, muerte, amor, miedo, colores, números, vehículos, árboles, montañas, puentes, puertas, ventanas, escaleras, ascensores, coches, aviones, trenes, barcos, fuego, tierra, aire, luz, oscuridad, espejos, relojes, teléfonos, computadoras, libros, dinero, comida, ropa, zapatos, joyas, armas, herramientas, máquinas, edificios, ciudades, bosques, jardines, flores, frutas, clima, estaciones, tiempo, espacio, estrellas, luna, sol, nubes, lluvia, nieve, viento, trueno, relámpago, etc.

Para emociones, identifica: alegría, tristeza, ira, miedo, sorpresa, paz, ansiedad, emoción, confusión, asombro, nostalgia, esperanza, desesperación, amor, odio, celos, envidia, orgullo, vergüenza, culpa, alivio, frustración, satisfacción, etc.

Texto del sueño: ",
        'fr' => "Analyse ce texte de rêve et identifie les symboles, thèmes et émotions les plus proéminents. Retourne SEULEMENT un objet JSON avec cette structure exacte:
{
  \"symbols\": [\"symbole1\", \"symbole2\", \"symbole3\"],
  \"emotions\": [\"émotion1\", \"émotion2\"],
  \"themes\": [\"thème1\", \"thème2\"]
}

Concentre-toi sur les symboles universels de rêves comme: eau, voler, tomber, poursuite, maison, animaux, famille, école, travail, mort, amour, peur, couleurs, nombres, véhicules, arbres, montagnes, ponts, portes, fenêtres, escaliers, ascenseurs, voitures, avions, trains, bateaux, feu, terre, air, lumière, obscurité, miroirs, horloges, téléphones, ordinateurs, livres, argent, nourriture, vêtements, chaussures, bijoux, armes, outils, machines, bâtiments, villes, forêts, jardins, fleurs, fruits, météo, saisons, temps, espace, étoiles, lune, soleil, nuages, pluie, neige, vent, tonnerre, éclair, etc.

Pour les émotions, identifie: joie, tristesse, colère, peur, surprise, paix, anxiété, excitation, confusion, émerveillement, nostalgie, espoir, désespoir, amour, haine, jalousie, envie, fierté, honte, culpabilité, soulagement, frustration, satisfaction, etc.

Texte du rêve: "
    ];
    
    $system_prompts = [
        'en' => 'You are a dream analysis expert. Extract symbols, emotions, and themes from dream text. Return only valid JSON.',
        'tr' => 'Sen bir rüya analiz uzmanısın. Rüya metninden semboller, duygular ve temalar çıkar. Sadece geçerli JSON döndür.',
        'es' => 'Eres un experto en análisis de sueños. Extrae símbolos, emociones y temas del texto del sueño. Devuelve solo JSON válido.',
        'fr' => 'Vous êtes un expert en analyse de rêves. Extrayez les symboles, émotions et thèmes du texte du rêve. Retournez seulement du JSON valide.'
    ];
    
    $prompt = ($prompts[$lang] ?? $prompts['en']) . $combined_text;
    
    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENROUTER_API_KEY,
        'HTTP-Referer: http://localhost',
        'X-Title: DreamLock AI Symbol Analysis'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'deepseek/deepseek-chat-v3-0324',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompts[$lang] ?? $system_prompts['en']],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.3,
        'max_tokens' => 300
    ]));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if (curl_error($ch)) {
        return null;
    }
    
    $result = json_decode($response, true);
    $content = $result['choices'][0]['message']['content'] ?? '';
    
    // Try to extract JSON from response
    $json_start = strpos($content, '{');
    $json_end = strrpos($content, '}');
    
    if ($json_start !== false && $json_end !== false) {
        $json_string = substr($content, $json_start, $json_end - $json_start + 1);
        $parsed = json_decode($json_string, true);
        
        if ($parsed && isset($parsed['symbols']) && isset($parsed['emotions'])) {
            // Cache the result
            if ($db) {
                $insert_stmt = $db->prepare("INSERT INTO dream_ai_cache (text_hash, lang, ai_analysis, created_at) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE ai_analysis = VALUES(ai_analysis), created_at = NOW()");
                $insert_stmt->execute([$text_hash, $lang, $json_string]);
            }
            return $parsed;
        }
    }
    
    return null;
}

// Translate AI results to user's language
function translateAIResults($ai_results, $target_lang) {
    if (!$ai_results) return null;
    
    $translations = [
        'en' => [
            'symbols' => [
                'su' => 'water', 'deniz' => 'sea', 'okyanus' => 'ocean', 'nehir' => 'river', 'göl' => 'lake', 'yağmur' => 'rain',
                'uçma' => 'flying', 'uçmak' => 'flying', 'düşme' => 'falling', 'düşmek' => 'falling',
                'kovalamaca' => 'chase', 'kaçmak' => 'escape', 'ev' => 'house', 'oda' => 'room', 'kapı' => 'door',
                'hayvan' => 'animal', 'kedi' => 'cat', 'köpek' => 'dog', 'kuş' => 'bird',
                'aile' => 'family', 'anne' => 'mother', 'baba' => 'father', 'kardeş' => 'sibling',
                'okul' => 'school', 'sınıf' => 'class', 'öğretmen' => 'teacher',
                'iş' => 'work', 'ofis' => 'office', 'ölüm' => 'death', 'ölmek' => 'death',
                'aşk' => 'love', 'sevmek' => 'love', 'korku' => 'fear', 'korkmak' => 'fear',
                'renk' => 'color', 'kırmızı' => 'red', 'mavi' => 'blue', 'yeşil' => 'green',
                'sayı' => 'number', 'bir' => 'one', 'iki' => 'two', 'üç' => 'three',
                'araç' => 'vehicle', 'araba' => 'car', 'uçak' => 'plane', 'tren' => 'train'
            ],
            'emotions' => [
                'sevinç' => 'joy', 'mutlu' => 'happy', 'üzgün' => 'sad', 'ağlamak' => 'cry',
                'kızgın' => 'angry', 'öfke' => 'anger', 'korku' => 'fear', 'panik' => 'panic',
                'şaşkın' => 'surprise', 'huzur' => 'peace', 'sakin' => 'calm', 'kaygı' => 'anxiety',
                'heyecan' => 'excitement', 'karışıklık' => 'confusion', 'merak' => 'wonder',
                'nostalji' => 'nostalgia', 'umut' => 'hope', 'umutsuzluk' => 'despair',
                'aşk' => 'love', 'nefret' => 'hate', 'kıskançlık' => 'jealousy', 'haset' => 'envy',
                'gurur' => 'pride', 'utanç' => 'shame', 'suçluluk' => 'guilt', 'rahatlama' => 'relief',
                'hayal kırıklığı' => 'frustration', 'memnuniyet' => 'satisfaction'
            ]
        ],
        'tr' => [
            'symbols' => [
                'water' => 'su', 'sea' => 'deniz', 'ocean' => 'okyanus', 'river' => 'nehir', 'lake' => 'göl', 'rain' => 'yağmur',
                'flying' => 'uçma', 'fly' => 'uçmak', 'falling' => 'düşme', 'fall' => 'düşmek',
                'chase' => 'kovalamaca', 'escape' => 'kaçmak', 'house' => 'ev', 'room' => 'oda', 'door' => 'kapı',
                'animal' => 'hayvan', 'cat' => 'kedi', 'dog' => 'köpek', 'bird' => 'kuş',
                'family' => 'aile', 'mother' => 'anne', 'father' => 'baba', 'sibling' => 'kardeş',
                'school' => 'okul', 'class' => 'sınıf', 'teacher' => 'öğretmen',
                'work' => 'iş', 'office' => 'ofis', 'death' => 'ölüm', 'die' => 'ölmek',
                'love' => 'aşk', 'romance' => 'romantik', 'fear' => 'korku', 'afraid' => 'korkmak',
                'color' => 'renk', 'red' => 'kırmızı', 'blue' => 'mavi', 'green' => 'yeşil',
                'number' => 'sayı', 'one' => 'bir', 'two' => 'iki', 'three' => 'üç',
                'vehicle' => 'araç', 'car' => 'araba', 'plane' => 'uçak', 'train' => 'tren'
            ],
            'emotions' => [
                'joy' => 'sevinç', 'happy' => 'mutlu', 'sad' => 'üzgün', 'cry' => 'ağlamak',
                'angry' => 'kızgın', 'anger' => 'öfke', 'fear' => 'korku', 'panic' => 'panik',
                'surprise' => 'şaşkın', 'peace' => 'huzur', 'calm' => 'sakin', 'anxiety' => 'kaygı',
                'excitement' => 'heyecan', 'confusion' => 'karışıklık', 'wonder' => 'merak',
                'nostalgia' => 'nostalji', 'hope' => 'umut', 'despair' => 'umutsuzluk',
                'love' => 'aşk', 'hate' => 'nefret', 'jealousy' => 'kıskançlık', 'envy' => 'haset',
                'pride' => 'gurur', 'shame' => 'utanç', 'guilt' => 'suçluluk', 'relief' => 'rahatlama',
                'frustration' => 'hayal kırıklığı', 'satisfaction' => 'memnuniyet'
            ]
        ],
        'es' => [
            'symbols' => [
                'water' => 'agua', 'sea' => 'mar', 'ocean' => 'océano', 'river' => 'río', 'lake' => 'lago', 'rain' => 'lluvia',
                'flying' => 'volar', 'fly' => 'vuelo', 'falling' => 'caer', 'fall' => 'caída',
                'chase' => 'persecución', 'escape' => 'escapar', 'house' => 'casa', 'room' => 'habitación', 'door' => 'puerta',
                'animal' => 'animal', 'cat' => 'gato', 'dog' => 'perro', 'bird' => 'pájaro',
                'family' => 'familia', 'mother' => 'madre', 'father' => 'padre', 'sibling' => 'hermano',
                'school' => 'escuela', 'class' => 'clase', 'teacher' => 'maestro',
                'work' => 'trabajo', 'office' => 'oficina', 'death' => 'muerte', 'die' => 'morir',
                'love' => 'amor', 'romance' => 'romance', 'fear' => 'miedo', 'afraid' => 'temer',
                'color' => 'color', 'red' => 'rojo', 'blue' => 'azul', 'green' => 'verde',
                'number' => 'número', 'one' => 'uno', 'two' => 'dos', 'three' => 'tres',
                'vehicle' => 'vehículo', 'car' => 'coche', 'plane' => 'avión', 'train' => 'tren'
            ],
            'emotions' => [
                'joy' => 'alegría', 'happy' => 'feliz', 'sad' => 'triste', 'cry' => 'llorar',
                'angry' => 'enojado', 'anger' => 'ira', 'fear' => 'miedo', 'panic' => 'pánico',
                'surprise' => 'sorpresa', 'peace' => 'paz', 'calm' => 'calma', 'anxiety' => 'ansiedad',
                'excitement' => 'emoción', 'confusion' => 'confusión', 'wonder' => 'asombro',
                'nostalgia' => 'nostalgia', 'hope' => 'esperanza', 'despair' => 'desesperación',
                'love' => 'amor', 'hate' => 'odio', 'jealousy' => 'celos', 'envy' => 'envidia',
                'pride' => 'orgullo', 'shame' => 'vergüenza', 'guilt' => 'culpa', 'relief' => 'alivio',
                'frustration' => 'frustración', 'satisfaction' => 'satisfacción'
            ]
        ],
        'fr' => [
            'symbols' => [
                'water' => 'eau', 'sea' => 'mer', 'ocean' => 'océan', 'river' => 'rivière', 'lake' => 'lac', 'rain' => 'pluie',
                'flying' => 'voler', 'fly' => 'vol', 'falling' => 'tomber', 'fall' => 'chute',
                'chase' => 'poursuite', 'escape' => 'échapper', 'house' => 'maison', 'room' => 'chambre', 'door' => 'porte',
                'animal' => 'animal', 'cat' => 'chat', 'dog' => 'chien', 'bird' => 'oiseau',
                'family' => 'famille', 'mother' => 'mère', 'father' => 'père', 'sibling' => 'frère',
                'school' => 'école', 'class' => 'classe', 'teacher' => 'professeur',
                'work' => 'travail', 'office' => 'bureau', 'death' => 'mort', 'die' => 'mourir',
                'love' => 'amour', 'romance' => 'romance', 'fear' => 'peur', 'afraid' => 'craindre',
                'color' => 'couleur', 'red' => 'rouge', 'blue' => 'bleu', 'green' => 'vert',
                'number' => 'nombre', 'one' => 'un', 'two' => 'deux', 'three' => 'trois',
                'vehicle' => 'véhicule', 'car' => 'voiture', 'plane' => 'avion', 'train' => 'train'
            ],
            'emotions' => [
                'joy' => 'joie', 'happy' => 'heureux', 'sad' => 'triste', 'cry' => 'pleurer',
                'angry' => 'en colère', 'anger' => 'colère', 'fear' => 'peur', 'panic' => 'panique',
                'surprise' => 'surprise', 'peace' => 'paix', 'calm' => 'calme', 'anxiety' => 'anxiété',
                'excitement' => 'excitation', 'confusion' => 'confusion', 'wonder' => 'émerveillement',
                'nostalgia' => 'nostalgie', 'hope' => 'espoir', 'despair' => 'désespoir',
                'love' => 'amour', 'hate' => 'haine', 'jealousy' => 'jalousie', 'envy' => 'envie',
                'pride' => 'fierté', 'shame' => 'honte', 'guilt' => 'culpabilité', 'relief' => 'soulagement',
                'frustration' => 'frustration', 'satisfaction' => 'satisfaction'
            ]
        ]
    ];
    
    $translated = $ai_results;
    
    if (isset($translated['symbols'])) {
        foreach ($translated['symbols'] as $key => $symbol) {
            $symbol_lower = strtolower(trim($symbol));
            if (isset($translations[$target_lang]['symbols'][$symbol_lower])) {
                $translated['symbols'][$key] = $translations[$target_lang]['symbols'][$symbol_lower];
            }
        }
    }
    
    if (isset($translated['emotions'])) {
        foreach ($translated['emotions'] as $key => $emotion) {
            $emotion_lower = strtolower(trim($emotion));
            if (isset($translations[$target_lang]['emotions'][$emotion_lower])) {
                $translated['emotions'][$key] = $translations[$target_lang]['emotions'][$emotion_lower];
            }
        }
    }
    
    if (isset($translated['themes'])) {
        foreach ($translated['themes'] as $key => $theme) {
            $theme_lower = strtolower(trim($theme));
            if (isset($translations[$target_lang]['symbols'][$theme_lower])) {
                $translated['themes'][$key] = $translations[$target_lang]['symbols'][$theme_lower];
            }
        }
    }
    
    return $translated;
}

// Daily Dream Trends - Get trending symbols, themes, and dream types for today using AI with optimization
function getDailyDreamTrends($db, $lang = 'en') {
    $trends = [];
    
    // Get today's date
    $today = date('Y-m-d');
    
    // Get all dreams from today
    $stmt = $db->prepare("SELECT dream_text, dream_type, analysis FROM dreams WHERE DATE(created_at) = ?");
    $stmt->execute([$today]);
    $todays_dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($todays_dreams)) {
        return null;
    }
    
    // Dream type trends for today
    $dream_types = [];
    foreach ($todays_dreams as $dream) {
        $type = $dream['dream_type'] ?? 'normal';
        $dream_types[$type] = ($dream_types[$type] ?? 0) + 1;
    }
    arsort($dream_types);
    $trends['dream_types'] = array_slice($dream_types, 0, 3, true);
    
    // Check if we have cached trends for today
    $cache_stmt = $db->prepare("SELECT trend_data FROM daily_trends_cache WHERE date = ? AND lang = ?");
    $cache_stmt->execute([$today, $lang]);
    $cached_trends = $cache_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cached_trends) {
        $trends_data = json_decode($cached_trends['trend_data'], true);
        if ($trends_data) {
            $trends['trending_symbols'] = $trends_data['symbols'] ?? [];
            $trends['trending_emotions'] = $trends_data['emotions'] ?? [];
            $trends['trending_themes'] = $trends_data['themes'] ?? [];
            $trends['todays_count'] = count($todays_dreams);
            return $trends;
        }
    }
    
    // If no cache, analyze only first 5 dreams for performance
    $dreams_to_analyze = array_slice($todays_dreams, 0, 5);
    
    // AI-powered symbol and emotion analysis
    $all_symbols = [];
    $all_emotions = [];
    $all_themes = [];
    
    foreach ($dreams_to_analyze as $dream) {
        $ai_analysis = analyzeDreamWithAI($dream['dream_text'], $dream['analysis'] ?? '', $lang, $db);
        
        if ($ai_analysis) {
            // Translate AI results to user's language
            $translated_analysis = translateAIResults($ai_analysis, $lang);
            
            // Count symbols
            if (isset($translated_analysis['symbols'])) {
                foreach ($translated_analysis['symbols'] as $symbol) {
                    $symbol = strtolower(trim($symbol));
                    if (!empty($symbol)) {
                        $all_symbols[$symbol] = ($all_symbols[$symbol] ?? 0) + 1;
                    }
                }
            }
            
            // Count emotions
            if (isset($translated_analysis['emotions'])) {
                foreach ($translated_analysis['emotions'] as $emotion) {
                    $emotion = strtolower(trim($emotion));
                    if (!empty($emotion)) {
                        $all_emotions[$emotion] = ($all_emotions[$emotion] ?? 0) + 1;
                    }
                }
            }
            
            // Count themes
            if (isset($translated_analysis['themes'])) {
                foreach ($translated_analysis['themes'] as $theme) {
                    $theme = strtolower(trim($theme));
                    if (!empty($theme)) {
                        $all_themes[$theme] = ($all_themes[$theme] ?? 0) + 1;
                    }
                }
            }
        }
    }
    
    // Sort and get top results
    arsort($all_symbols);
    arsort($all_emotions);
    arsort($all_themes);
    
    $trends['trending_symbols'] = array_slice($all_symbols, 0, 5, true);
    $trends['trending_emotions'] = array_slice($all_emotions, 0, 3, true);
    $trends['trending_themes'] = array_slice($all_themes, 0, 3, true);
    
    // Cache the results
    $trends_data = [
        'symbols' => $trends['trending_symbols'],
        'emotions' => $trends['trending_emotions'],
        'themes' => $trends['trending_themes']
    ];
    
    $insert_stmt = $db->prepare("INSERT INTO daily_trends_cache (date, lang, trend_data, created_at) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE trend_data = VALUES(trend_data), created_at = NOW()");
    $insert_stmt->execute([$today, $lang, json_encode($trends_data)]);
    
    // Today's dream count
    $trends['todays_count'] = count($todays_dreams);
    
    return $trends;
}

// Load daily trends
$daily_trends = getDailyDreamTrends($db, $lang);

// Handle incoming dream data from dream.php
$incoming_dream = null;
if (isset($_GET['dream_id']) && isset($_GET['title']) && isset($_GET['text'])) {
    $incoming_dream = [
        'id' => $_GET['dream_id'],
        'title' => $_GET['title'],
        'text' => $_GET['text'],
        'analysis' => $_GET['analysis'] ?? '',
        'type' => $_GET['type'] ?? 'normal',
        'type_label' => $_GET['type_label'] ?? 'Normal Dream'
    ];
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DreamLock - <?php echo $t['dream_sharing']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #00BFFF;
            --secondary-blue: #1E90FF;
            --dark-bg: #0a0a0a;
            --card-bg: #1a1a1a;
            --text-light: #ffffff;
            --text-muted: #888888;
            --border-color: #2a2a2a;
            --success-bg: #0f1f2a;
            --success-text: #87ceeb;
            --danger-bg: #2a0f0f;
            --danger-text: #ff6b6b;
            --warning-bg: #2a1f0f;
            --warning-text: #ffd700;
            --info-bg: #0f1f2a;
            --info-text: #87ceeb;
        }

        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 100%);
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            padding-top: 80px;
            padding-bottom: 100px; /* Space for bottom nav */
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        .animated-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 25%, #0f1f2a 50%, #1a1a1a 75%, #0a0a0a 100%);
        }

        /* Animated Gradient Overlay */
        .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, 
                rgba(0, 191, 255, 0.1) 0%, 
                rgba(30, 144, 255, 0.05) 25%, 
                rgba(0, 191, 255, 0.08) 50%, 
                rgba(30, 144, 255, 0.03) 75%, 
                rgba(0, 191, 255, 0.1) 100%);
            animation: gradientShift 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes gradientShift {
            0%, 100% {
                transform: scale(1) rotate(0deg);
                opacity: 0.7;
            }
            25% {
                transform: scale(1.2) rotate(90deg);
                opacity: 0.9;
            }
            50% {
                transform: scale(0.8) rotate(180deg);
                opacity: 0.5;
            }
            75% {
                transform: scale(1.1) rotate(270deg);
                opacity: 0.8;
            }
        }

        /* Floating Geometric Shapes */
        .geometric-shape {
            position: absolute;
            opacity: 0.1;
            animation: floatShape 15s ease-in-out infinite;
        }

        .shape-1 {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            background: linear-gradient(45deg, rgba(0, 191, 255, 0.3), rgba(30, 144, 255, 0.2));
            clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
            animation-delay: 0s;
        }

        .shape-2 {
            width: 80px;
            height: 80px;
            top: 70%;
            right: 15%;
            background: linear-gradient(45deg, rgba(30, 144, 255, 0.3), rgba(0, 191, 255, 0.2));
            clip-path: polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%);
            animation-delay: 3s;
        }

        .shape-3 {
            width: 120px;
            height: 120px;
            top: 40%;
            left: 80%;
            background: linear-gradient(45deg, rgba(0, 191, 255, 0.2), rgba(30, 144, 255, 0.3));
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            animation-delay: 6s;
        }

        .shape-4 {
            width: 60px;
            height: 60px;
            top: 20%;
            right: 30%;
            background: linear-gradient(45deg, rgba(30, 144, 255, 0.2), rgba(0, 191, 255, 0.3));
            clip-path: polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%);
            animation-delay: 9s;
        }

        @keyframes floatShape {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
                opacity: 0.1;
            }
            25% {
                transform: translateY(-30px) rotate(90deg) scale(1.1);
                opacity: 0.2;
            }
            50% {
                transform: translateY(-60px) rotate(180deg) scale(0.9);
                opacity: 0.15;
            }
            75% {
                transform: translateY(-30px) rotate(270deg) scale(1.05);
                opacity: 0.25;
            }
        }

        /* Animated Waves */
        .wave-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(0, 191, 255, 0.1) 25%, 
                rgba(30, 144, 255, 0.15) 50%, 
                rgba(0, 191, 255, 0.1) 75%, 
                transparent 100%);
            border-radius: 50% 50% 0 0;
            animation: waveMove 8s ease-in-out infinite;
        }

        .wave:nth-child(1) {
            animation-delay: 0s;
            opacity: 0.3;
        }

        .wave:nth-child(2) {
            animation-delay: 2s;
            opacity: 0.2;
        }

        .wave:nth-child(3) {
            animation-delay: 4s;
            opacity: 0.1;
        }

        @keyframes waveMove {
            0%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            50% {
                transform: translateX(0%) translateY(-20px);
            }
        }

        /* Glowing Orbs */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(2px);
            animation: glowPulse 12s ease-in-out infinite;
        }

        .orb-1 {
            width: 150px;
            height: 150px;
            top: 15%;
            left: 5%;
            background: radial-gradient(circle, rgba(0, 191, 255, 0.2) 0%, rgba(30, 144, 255, 0.1) 50%, transparent 100%);
            animation-delay: 0s;
        }

        .orb-2 {
            width: 100px;
            height: 100px;
            top: 60%;
            right: 10%;
            background: radial-gradient(circle, rgba(30, 144, 255, 0.2) 0%, rgba(0, 191, 255, 0.1) 50%, transparent 100%);
            animation-delay: 4s;
        }

        .orb-3 {
            width: 120px;
            height: 120px;
            top: 30%;
            left: 70%;
            background: radial-gradient(circle, rgba(0, 191, 255, 0.15) 0%, rgba(30, 144, 255, 0.08) 50%, transparent 100%);
            animation-delay: 8s;
        }

        @keyframes glowPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.3;
            }
            50% {
                transform: scale(1.4);
                opacity: 0.6;
            }
        }

        /* Animated Grid */
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 191, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 191, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 30s linear infinite;
        }

        @keyframes gridMove {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(50px, 50px);
            }
        }

        /* Light Rays */
        .light-ray {
            position: absolute;
            width: 2px;
            height: 100%;
            background: linear-gradient(to bottom, 
                transparent 0%, 
                rgba(0, 191, 255, 0.1) 20%, 
                rgba(30, 144, 255, 0.2) 50%, 
                rgba(0, 191, 255, 0.1) 80%, 
                transparent 100%);
            animation: raySweep 10s ease-in-out infinite;
        }

        .ray-1 {
            left: 20%;
            animation-delay: 0s;
        }

        .ray-2 {
            left: 40%;
            animation-delay: 3s;
        }

        .ray-3 {
            left: 60%;
            animation-delay: 6s;
        }

        .ray-4 {
            left: 80%;
            animation-delay: 9s;
        }

        @keyframes raySweep {
            0%, 100% {
                transform: scaleY(0);
                opacity: 0;
            }
            50% {
                transform: scaleY(1);
                opacity: 1;
            }
        }

        /* Floating Dots */
        .floating-dot {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(0, 191, 255, 0.6);
            border-radius: 50%;
            animation: floatDot 8s ease-in-out infinite;
        }

        .dot-1 {
            top: 25%;
            left: 15%;
            animation-delay: 0s;
        }

        .dot-2 {
            top: 45%;
            left: 85%;
            animation-delay: 2s;
            animation-duration: 22s;
        }

        .dot-3 {
            top: 65%;
            left: 25%;
            animation-delay: 4s;
        }

        .dot-4 {
            top: 35%;
            left: 75%;
            animation-delay: 6s;
        }

        @keyframes floatDot {
            0%, 100% {
                transform: translateY(0px) scale(1);
                opacity: 0.6;
            }
            50% {
                transform: translateY(-40px) scale(1.5);
                opacity: 1;
            }
        }

        .navbar {
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            color: var(--primary-blue) !important;
            font-weight: 800;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-light) !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-blue) !important;
        }

        .main-container {
            padding: 2rem 0;
        }

        .section-title {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: linear-gradient(135deg, rgba(42, 42, 42, 0.8) 0%, rgba(35, 35, 35, 0.8) 100%);
            border: 2px solid var(--border-color);
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            border-radius: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .filter-btn:hover, .filter-btn.active {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--dark-bg);
            border-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 191, 255, 0.3);
        }

        .dream-card {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 2rem;
            padding-top: 3rem; /* Extra space for badge */
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dream-card:hover {
            border-color: var(--primary-blue);
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 191, 255, 0.2);
        }

        .dream-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .dream-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0;
        }

        .dream-meta {
            display: flex;
            gap: 1rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .dream-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
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

        /* Dream Type Badge Colors */
        .dream-type-badge.dream-type-nightmare {
            background: linear-gradient(135deg, rgba(255, 71, 87, 0.9) 0%, rgba(139, 0, 0, 0.8) 100%);
            color: #ffffff;
            border-color: #ff4757;
            text-shadow: 0 0 10px rgba(255, 71, 87, 0.8);
        }

        .dream-type-badge.dream-type-lucid {
            background: linear-gradient(135deg, rgba(83, 82, 237, 0.9) 0%, rgba(130, 87, 229, 0.8) 100%);
            color: #ffffff;
            border-color: #5352ed;
            text-shadow: 0 0 10px rgba(83, 82, 237, 0.8);
        }

        .dream-type-badge.dream-type-prophetic {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.9) 0%, rgba(255, 193, 7, 0.8) 100%);
            color: #000000;
            border-color: #ffd700;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.8);
        }

        .dream-type-badge.dream-type-normal {
            background: linear-gradient(135deg, rgba(0, 191, 255, 0.9) 0%, rgba(30, 144, 255, 0.8) 100%);
            color: #000000;
            border-color: var(--primary-blue);
            text-shadow: 0 0 10px rgba(0, 191, 255, 0.8);
        }

        .dream-type-badge.dream-type-recurring {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.9) 0%, rgba(255, 99, 132, 0.8) 100%);
            color: #ffffff;
            border-color: #ff6b6b;
            text-shadow: 0 0 10px rgba(255, 107, 107, 0.8);
        }

        .dream-type-badge.dream-type-spiritual {
            background: linear-gradient(135deg, rgba(156, 136, 255, 0.9) 0%, rgba(116, 185, 255, 0.8) 100%);
            color: #ffffff;
            border-color: #9c88ff;
            text-shadow: 0 0 10px rgba(156, 136, 255, 0.8);
        }

        .dream-type-badge.dream-type-symbolic {
            background: linear-gradient(135deg, rgba(255, 165, 2, 0.9) 0%, rgba(255, 140, 0, 0.8) 100%);
            color: #000000;
            border-color: #ffa502;
            text-shadow: 0 0 10px rgba(255, 165, 2, 0.8);
        }

        .dream-type-badge.dream-type-emotional {
            background: linear-gradient(135deg, rgba(255, 118, 117, 0.9) 0%, rgba(253, 121, 168, 0.8) 100%);
            color: #ffffff;
            border-color: #ff7675;
            text-shadow: 0 0 10px rgba(255, 118, 117, 0.8);
        }

        .dream-text {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .dream-analysis {
            background: rgba(15, 31, 42, 0.3);
            border-left: 4px solid var(--primary-blue);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .dream-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .action-btn {
            background: linear-gradient(135deg, rgba(42, 42, 42, 0.8) 0%, rgba(35, 35, 35, 0.8) 100%);
            border: 2px solid var(--border-color);
            color: var(--text-light);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--dark-bg);
            border-color: var(--primary-blue);
            transform: translateY(-2px);
        }

        .like-btn.liked {
            background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
            color: white;
            border-color: #ff4757;
        }

        .stats {
            display: flex;
            gap: 1rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .comment-section {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .comment-form {
            margin-bottom: 1rem;
        }

        .comment-input {
            background: linear-gradient(135deg, rgba(42, 42, 42, 0.8) 0%, rgba(35, 35, 35, 0.8) 100%);
            border: 2px solid var(--border-color);
            color: var(--text-light);
            padding: 1rem;
            border-radius: 15px;
            width: 100%;
            resize: vertical;
            min-height: 80px;
        }

        .comment-input:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.2);
        }

        .comment {
            background: rgba(42, 42, 42, 0.3);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .comment-author {
            font-weight: 600;
            color: var(--primary-blue);
        }

        .comment-date {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .comment-text {
            color: var(--text-light);
            line-height: 1.5;
        }

        .share-dream-section {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
            border: 2px solid var(--primary-blue);
            border-radius: 25px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 191, 255, 0.1);
        }

        .share-dream-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 191, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .share-dream-section h3 {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .dream-select {
            background: linear-gradient(135deg, rgba(42, 42, 42, 0.9) 0%, rgba(35, 35, 35, 0.9) 100%);
            border: 2px solid var(--border-color);
            color: var(--text-light);
            padding: 1.2rem;
            border-radius: 15px;
            width: 100%;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .dream-select:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.3);
            transform: translateY(-2px);
        }

        .dream-select option {
            background: var(--card-bg);
            color: var(--text-light);
            padding: 0.5rem;
        }

        .load-more-btn {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--dark-bg);
            border: none;
            padding: 1rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: block;
            margin: 2rem auto;
        }

        .load-more-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 191, 255, 0.3);
        }

        .alert {
            border-radius: 15px;
            border: none;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
        }

        .alert-danger {
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        /* Modern Notification System */
        .notification-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 10000;
            max-width: 400px;
        }

        .notification {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.98) 0%, rgba(20, 20, 20, 0.98) 100%);
            border: 2px solid var(--primary-green);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(20px);
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification.success {
            border-color: var(--primary-blue);
            box-shadow: 0 10px 30px rgba(0, 191, 255, 0.3);
        }

        .notification.error {
            border-color: var(--danger-text);
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
        }

        .notification.warning {
            border-color: var(--warning-text);
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
        }

        .notification-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .notification-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-light);
        }

        .notification-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }

        .notification-close:hover {
            color: var(--text-light);
        }

        .notification-message {
            color: var(--text-light);
            line-height: 1.5;
        }

        .notification-icon {
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }

        .notification.success .notification-icon {
            color: var(--primary-green);
        }

        .notification.error .notification-icon {
            color: var(--danger-text);
        }

        .notification.warning .notification-icon {
            color: var(--warning-text);
        }

        /* Modern Modal System */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            z-index: 10001;
            animation: modalFadeIn 0.3s ease;
        }

        .modal-overlay.show {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.98) 0%, rgba(20, 20, 20, 0.98) 100%);
            border: 2px solid var(--primary-blue);
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 191, 255, 0.3);
            transform: scale(0.8);
            opacity: 0;
            animation: modalSlideIn 0.3s ease forwards;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalSlideIn {
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-title {
            color: var(--primary-blue);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .modal-message {
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 100px;
        }

        .modal-btn.confirm {
            background: linear-gradient(135deg, var(--danger-text) 0%, #ff3742 100%);
            color: white;
        }

        .modal-btn.confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 107, 0.4);
        }

        .modal-btn.cancel {
            background: linear-gradient(135deg, rgba(42, 42, 42, 0.8) 0%, rgba(35, 35, 35, 0.8) 100%);
            color: var(--text-light);
            border: 2px solid var(--border-color);
        }

        .modal-btn.cancel:hover {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--dark-bg);
            border-color: var(--primary-blue);
            transform: translateY(-2px);
        }

        /* Translation Controls Styling */
        .translation-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .translate-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border-color: #4CAF50;
        }

        .translate-btn:hover {
            background: linear-gradient(135deg, #45a049 0%, #4CAF50 100%);
            color: white;
            border-color: #45a049;
        }

        .show-original-btn {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
            border-color: #FF9800;
        }

        .show-original-btn:hover {
            background: linear-gradient(135deg, #F57C00 0%, #FF9800 100%);
            color: white;
            border-color: #F57C00;
        }

        .translation-status {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-style: italic;
        }

        .translation-loading {
            color: var(--primary-blue);
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Language Selector Styling */
        .language-menu {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.98) 0%, rgba(20, 20, 20, 0.98) 100%);
            border: 2px solid var(--border-color);
            border-radius: 15px;
            backdrop-filter: blur(20px);
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 200px;
            z-index: 10002;
            margin-top: 5px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            padding: 8px 0;
            visibility: hidden;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        
        .language-menu.show {
            display: block !important;
            animation: dropdownFadeIn 0.3s ease;
			 visibility: visible;
    opacity: 1;
    transform: translateY(0);
        }
        
        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .nav-item.dropdown {
            position: relative;
        }

        .language-item {
            color: var(--text-light);
            transition: all 0.3s ease;
            border-radius: 10px;
            margin: 2px 8px;
            padding: 12px 16px;
            display: block;
            text-decoration: none;
            border: none;
            background: transparent;
            width: calc(100% - 16px);
            text-align: left;
        }

        .language-item:hover {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--dark-bg);
            transform: translateX(5px);
            text-decoration: none;
        }

        /* Dream Type Filter Buttons */
        .dream-type-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .dream-type-filter-btn {
            padding: 10px 16px;
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
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dream-type-filter-btn.active {
            transform: scale(1.1);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        .dream-type-filter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .dream-type-filter-btn:hover::before {
            left: 100%;
        }

        .dream-type-filter-btn.all {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        .dream-type-filter-btn.all.active {
            background: var(--primary-blue);
            color: var(--dark-bg);
        }

        .dream-type-filter-btn.nightmare {
            border-color: #ff4757;
            color: #ff4757;
        }

        .dream-type-filter-btn.nightmare.active {
            background: #ff4757;
            color: #ffffff;
        }

        .dream-type-filter-btn.lucid {
            border-color: #5352ed;
            color: #5352ed;
        }

        .dream-type-filter-btn.lucid.active {
            background: #5352ed;
            color: #ffffff;
        }

        .dream-type-filter-btn.prophetic {
            border-color: #ffd700;
            color: #ffd700;
        }

        .dream-type-filter-btn.prophetic.active {
            background: #ffd700;
            color: #000000;
        }

        .dream-type-filter-btn.normal {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        .dream-type-filter-btn.normal.active {
            background: var(--primary-blue);
            color: #000000;
        }

        .dream-type-filter-btn.recurring {
            border-color: #ff6b6b;
            color: #ff6b6b;
        }

        .dream-type-filter-btn.recurring.active {
            background: #ff6b6b;
            color: #ffffff;
        }

        .dream-type-filter-btn.spiritual {
            border-color: #9c88ff;
            color: #9c88ff;
        }

        .dream-type-filter-btn.spiritual.active {
            background: #9c88ff;
            color: #ffffff;
        }

        .dream-type-filter-btn.symbolic {
            border-color: #ffa502;
            color: #ffa502;
        }

        .dream-type-filter-btn.symbolic.active {
            background: #ffa502;
            color: #000000;
        }

        .dream-type-filter-btn.emotional {
            border-color: #ff7675;
            color: #ff7675;
        }

        .dream-type-filter-btn.emotional.active {
            background: #ff7675;
            color: #ffffff;
        }

        /* Daily Trends Notification Styling */
        .daily-trends-notification {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
            border: 1px solid rgba(0, 191, 255, 0.3);
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 191, 255, 0.1);
            backdrop-filter: blur(10px);
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .daily-trends-notification::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 191, 255, 0.05), transparent);
            animation: shimmer 3s infinite;
        }

        .trends-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .trends-header i {
            color: var(--primary-blue);
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .trends-header span:first-of-type {
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .trends-count {
            color: var(--text-muted);
            font-size: 0.8rem;
            background: rgba(0, 191, 255, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            border: 1px solid rgba(0, 191, 255, 0.2);
        }

        .trends-content {
            position: relative;
            z-index: 1;
        }

        .trend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .trend-item:last-child {
            margin-bottom: 0;
        }

        .trend-label {
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 500;
            min-width: 80px;
        }

        .trend-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .trend-tag {
            background: rgba(0, 191, 255, 0.1);
            color: var(--primary-blue);
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid rgba(0, 191, 255, 0.2);
            transition: all 0.3s ease;
        }

        .trend-tag:hover {
            background: rgba(0, 191, 255, 0.2);
            transform: translateY(-1px);
        }

        .trend-tag.emotion-joy {
            background: rgba(46, 213, 115, 0.1);
            color: #2ed573;
            border-color: rgba(46, 213, 115, 0.3);
        }

        .trend-tag.emotion-sadness {
            background: rgba(116, 125, 140, 0.1);
            color: #747d8c;
            border-color: rgba(116, 125, 140, 0.3);
        }

        .trend-tag.emotion-anger {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            border-color: rgba(255, 71, 87, 0.3);
        }

        .trend-tag.emotion-fear {
            background: rgba(255, 165, 2, 0.1);
            color: #ffa502;
            border-color: rgba(255, 165, 2, 0.3);
        }

        .trend-tag.emotion-surprise {
            background: rgba(255, 215, 0, 0.1);
            color: #ffd700;
            border-color: rgba(255, 215, 0, 0.3);
        }

        .trend-tag.emotion-peace {
            background: rgba(156, 136, 255, 0.1);
            color: #9c88ff;
            border-color: rgba(156, 136, 255, 0.3);
        }

        /* Theme Tags */
        .trend-tag[class*="theme-"] {
            background: rgba(255, 165, 2, 0.1);
            color: #ffa502;
            border-color: rgba(255, 165, 2, 0.3);
        }

        /* Dream Type Tags */
        .trend-tag[class*="dream-type-"] {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
            border-color: rgba(46, 204, 113, 0.3);
        }

        /* Responsive Design for Trends */
        @media (max-width: 768px) {
            .daily-trends-notification {
                max-width: 100%;
                margin: 0 1rem 1.5rem 1rem;
            }

            .trends-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .trend-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .trend-label {
                min-width: auto;
            }

            .trend-tags {
                width: 100%;
            }
        }

        /* Bottom Navigation */
        .bottom-navigation {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: rgba(20, 20, 20, 0.98);
            backdrop-filter: blur(25px);
            border-top: 2px solid rgba(0, 191, 255, 0.2);
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
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
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
            box-shadow: 0 10px 25px rgba(0, 191, 255, 0.3);
        }

        .nav-item.active {
            color: var(--primary-blue);
            background: rgba(0, 191, 255, 0.1);
            border: 1px solid rgba(0, 191, 255, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 191, 255, 0.2);
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
                filter: drop-shadow(0 4px 12px rgba(0, 191, 255, 0.6));
            }
        }

        @media (max-width: 768px) {
            .filter-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .dream-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .stats {
                justify-content: center;
            }

            .dream-type-filters {
                flex-direction: column;
                align-items: center;
            }

            .dream-type-filter-btn {
                margin: 5px;
                min-width: 120px;
            }

            .bottom-navigation {
                height: 70px;
                padding: 0 5px;
                gap: 2px;
            }
            
            .nav-item {
                min-width: 45px;
                height: 45px;
                padding: 4px 6px;
                border-radius: 15px;
            }
            
            .nav-icon {
                font-size: 18px;
                margin-bottom: 2px;
            }
            
            .nav-text {
                font-size: 9px;
                line-height: 1;
            }
            
            /* Ensure proper spacing for 6 nav items */
            .bottom-navigation .nav-item {
                flex: 1;
                max-width: calc(16.666% - 4px);
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-background">
        <div class="gradient-overlay"></div>
        <div class="grid-overlay"></div>
        
        <!-- Geometric Shapes -->
        <div class="geometric-shape shape-1"></div>
        <div class="geometric-shape shape-2"></div>
        <div class="geometric-shape shape-3"></div>
        <div class="geometric-shape shape-4"></div>
        
        <!-- Glowing Orbs -->
        <div class="glow-orb orb-1"></div>
        <div class="glow-orb orb-2"></div>
        <div class="glow-orb orb-3"></div>
        
        <!-- Light Rays -->
        <div class="light-ray ray-1"></div>
        <div class="light-ray ray-2"></div>
        <div class="light-ray ray-3"></div>
        <div class="light-ray ray-4"></div>
        
        <!-- Floating Dots -->
        <div class="floating-dot dot-1"></div>
        <div class="floating-dot dot-2"></div>
        <div class="floating-dot dot-3"></div>
        <div class="floating-dot dot-4"></div>
        
        <!-- Animated Waves -->
        <div class="wave-container">
            <div class="wave"></div>
            <div class="wave"></div>
            <div class="wave"></div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="dream.php">
                <i class="bi bi-moon-stars-fill me-2"></i>DREAMLOCK
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                   
                </ul>
                <ul class="navbar-nav">
                    <!-- Language Selector -->
                    <li class="nav-item dropdown position-relative">
    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button">
        <i class="bi bi-translate me-1"></i>
        <?php 
        $langNames = ['en' => '🇺🇸 EN', 'tr' => '🇹🇷 TR', 'es' => '🇪🇸 ES'];
        echo $langNames[$lang] ?? '🇺🇸 EN';
        ?>
    </a>
    <div class="language-menu" id="languageMenu">
        <a class="language-item" href="?lang=en">🇺🇸 English</a>
        <a class="language-item" href="?lang=tr">🇹🇷 Türkçe</a>
        <a class="language-item" href="?lang=es">🇪🇸 Español</a>
        
    </div>
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

    <div class="container main-container">
        <h1 class="section-title"><?php echo $t['dream_sharing']; ?></h1>

        <!-- Admin Cache Management Panel (only visible to admin) -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1): ?>
        <div class="admin-panel" style="background: rgba(0, 191, 255, 0.1); border: 1px solid rgba(0, 191, 255, 0.3); border-radius: 10px; padding: 1rem; margin-bottom: 2rem; text-align: center;">
            <h5 style="color: var(--primary-blue); margin-bottom: 1rem;">🛠️ Admin Cache Management</h5>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <button class="action-btn" onclick="getCacheStats()" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white;">
                    <i class="bi bi-bar-chart"></i> Cache Stats
                </button>
                <button class="action-btn" onclick="clearCache()" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white;">
                    <i class="bi bi-trash"></i> Clear Cache
                </button>
            </div>
        </div>
        <?php endif; ?>

        <!-- Dream Type Filter Buttons -->
        <div class="dream-type-filters">
            <button class="dream-type-filter-btn all active" onclick="filterByDreamType('all')">
                <i class="bi bi-collection"></i> All Types
            </button>
            <button class="dream-type-filter-btn nightmare" onclick="filterByDreamType('nightmare')">
                <i class="bi bi-exclamation-triangle-fill"></i> Nightmare
            </button>
            <button class="dream-type-filter-btn lucid" onclick="filterByDreamType('lucid')">
                <i class="bi bi-stars"></i> Lucid
            </button>
            <button class="dream-type-filter-btn prophetic" onclick="filterByDreamType('prophetic')">
                <i class="bi bi-crystal-ball"></i> Prophetic
            </button>
            <button class="dream-type-filter-btn normal" onclick="filterByDreamType('normal')">
                <i class="bi bi-emoji-smile-fill"></i> Normal
            </button>
            <button class="dream-type-filter-btn recurring" onclick="filterByDreamType('recurring')">
                <i class="bi bi-arrow-clockwise"></i> Recurring
            </button>
            <button class="dream-type-filter-btn spiritual" onclick="filterByDreamType('spiritual')">
                <i class="bi bi-flower2"></i> Spiritual
            </button>
            <button class="dream-type-filter-btn symbolic" onclick="filterByDreamType('symbolic')">
                <i class="bi bi-gem"></i> Symbolic
            </button>
            <button class="dream-type-filter-btn emotional" onclick="filterByDreamType('emotional')">
                <i class="bi bi-heart-fill"></i> Emotional
            </button>
        </div>

                        <!-- Daily Trends Notification -->
                <?php if ($daily_trends): ?>
                <div id="daily-trends-container" class="daily-trends-notification">
                    <div class="trends-header">
                        <i class="bi bi-trending-up"></i>
                        <span><?php echo $t['todays_dream_trends']; ?></span>
                        <span class="trends-count"><?php echo $daily_trends['todays_count']; ?> <?php echo $t['dreams_today']; ?></span>
                    </div>
                    <div class="trends-content">
                        <?php if (!empty($daily_trends['dream_types'])): ?>
                        <div class="trend-item">
                            <span class="trend-label"><?php echo $t['dream_types_label']; ?>:</span>
                            <div class="trend-tags">
                                <?php 
                                $count = 0;
                                foreach ($daily_trends['dream_types'] as $type => $frequency): 
                                    if ($count >= 2) break;
                                ?>
                                <span class="trend-tag dream-type-<?php echo $type; ?>"><?php echo $t['dream_types'][$type] ?? ucfirst($type); ?></span>
                                <?php $count++; endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($daily_trends['trending_symbols'])): ?>
                        <div class="trend-item">
                            <span class="trend-label"><?php echo $t['top_symbols']; ?>:</span>
                            <div class="trend-tags">
                                <?php 
                                $count = 0;
                                foreach ($daily_trends['trending_symbols'] as $symbol => $frequency): 
                                    if ($count >= 3) break;
                                ?>
                                <span class="trend-tag"><?php echo ucfirst($symbol); ?></span>
                                <?php $count++; endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($daily_trends['trending_emotions'])): ?>
                        <div class="trend-item">
                            <span class="trend-label"><?php echo $t['top_emotions']; ?>:</span>
                            <div class="trend-tags">
                                <?php 
                                $count = 0;
                                foreach ($daily_trends['trending_emotions'] as $emotion => $frequency): 
                                    if ($count >= 2) break;
                                ?>
                                <span class="trend-tag emotion-<?php echo $emotion; ?>"><?php echo ucfirst($emotion); ?></span>
                                <?php $count++; endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($daily_trends['trending_themes'])): ?>
                        <div class="trend-item">
                            <span class="trend-label"><?php echo $t['top_themes']; ?>:</span>
                            <div class="trend-tags">
                                <?php 
                                $count = 0;
                                foreach ($daily_trends['trending_themes'] as $theme => $frequency): 
                                    if ($count >= 2) break;
                                ?>
                                <span class="trend-tag theme-<?php echo $theme; ?>"><?php echo ucfirst($theme); ?></span>
                                <?php $count++; endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

        <!-- Incoming Dream Section (from dream.php) -->
        <?php if ($incoming_dream): ?>
        <div class="share-dream-section" style="border-color: var(--primary-blue); background: linear-gradient(135deg, rgba(0, 191, 255, 0.1) 0%, rgba(30, 144, 255, 0.05) 100%);">
            <h3 class="text-center mb-3">
                <i class="bi bi-share-circle"></i> <?php echo $t['share_this_dream']; ?>
            </h3>
            <div class="incoming-dream-preview">
                <div class="dream-card" style="margin-bottom: 1rem;">
                    <div class="dream-type-badge dream-type-<?php echo $incoming_dream['type']; ?>">
                        <?php echo ucfirst($incoming_dream['type']); ?>
                    </div>
                    <h4 class="dream-title"><?php echo htmlspecialchars($incoming_dream['title']); ?></h4>
                    <div class="dream-text">
                        <?php echo nl2br(htmlspecialchars($incoming_dream['text'])); ?>
                    </div>
                    <?php if ($incoming_dream['analysis']): ?>
                    <div class="dream-analysis">
                        <strong><?php echo $t['ai_analysis']; ?>:</strong><br>
                        <?php echo htmlspecialchars($incoming_dream['analysis']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <button class="action-btn" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%); color: #000; font-weight: 700;" onclick="shareIncomingDream()">
                    <i class="bi bi-share"></i> <?php echo $t['share_this_dream']; ?>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <!-- Share Dream Section -->
        <?php if (!empty($user_dreams)): ?>
        <div class="share-dream-section">
            <h3 class="text-center mb-3"><?php echo $t['share_dream']; ?></h3>
            <select class="dream-select" id="dreamSelect">
                <option value=""><?php echo $t['share_dream']; ?>...</option>
                <?php foreach ($user_dreams as $dream): ?>
                <option value="<?php echo $dream['id']; ?>"><?php echo htmlspecialchars($dream['open_date']); ?></option>
                <?php endforeach; ?>
            </select>
            <button class="action-btn" onclick="shareDream()"><?php echo $t['share_dream']; ?></button>
        </div>
        <?php endif; ?>

        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <a href="?filter=all&sort=<?php echo $sort; ?>" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">
                <?php echo $t['filter_all']; ?>
            </a>
            <a href="?filter=my_dreams&sort=<?php echo $sort; ?>" class="filter-btn <?php echo $filter === 'my_dreams' ? 'active' : ''; ?>">
                <?php echo $t['filter_my_dreams']; ?>
            </a>
            <a href="?filter=<?php echo $filter; ?>&sort=recent" class="filter-btn <?php echo $sort === 'recent' ? 'active' : ''; ?>">
                <?php echo $t['sort_recent']; ?>
            </a>
            <a href="?filter=<?php echo $filter; ?>&sort=popular" class="filter-btn <?php echo $sort === 'popular' ? 'active' : ''; ?>">
                <?php echo $t['sort_popular']; ?>
            </a>
            <a href="?filter=<?php echo $filter; ?>&sort=views" class="filter-btn <?php echo $sort === 'views' ? 'active' : ''; ?>">
                <?php echo $t['sort_views']; ?>
            </a>
            <a href="?filter=<?php echo $filter; ?>&sort=likes" class="filter-btn <?php echo $sort === 'likes' ? 'active' : ''; ?>">
                <?php echo $t['sort_likes']; ?>
            </a>
        </div>

        <!-- Shared Dreams -->
        <div id="sharedDreamsContainer">
            <?php if (empty($shared_dreams)): ?>
            <div class="text-center">
                <p class="text-muted"><?php echo $t['no_shared_dreams']; ?></p>
            </div>
            <?php else: ?>
            <?php foreach ($shared_dreams as $dream): ?>
            <div class="dream-card" data-dream-id="<?php echo $dream['id']; ?>">
                <div class="dream-header">
                    <h3 class="dream-title"><?php echo htmlspecialchars($dream['dream_title']); ?></h3>
                    <?php if ($dream['user_id'] == $current_user_id): ?>
                    <button class="action-btn" onclick="unshareDream(<?php echo $dream['id']; ?>)">
                        <i class="bi bi-trash"></i> <?php echo $t['unshare_dream']; ?>
                    </button>
                    <?php endif; ?>
                </div>
                
                <!-- Dream Type Badge -->
                <div class="dream-type-badge dream-type-<?php echo $dream['dream_type']; ?>">
                    <?php echo ucfirst($dream['dream_type']); ?>
                </div>
                
                <div class="dream-meta">
                    <span><i class="bi bi-person"></i> <?php echo $t['shared_by']; ?>: <?php echo htmlspecialchars($dream['username']); ?></span>
                    <span><i class="bi bi-calendar"></i> <?php echo $t['shared_on']; ?>: <?php echo date('M d, Y H:i', strtotime($dream['created_at'])); ?></span>
                </div>
                
                <div class="dream-text" id="dream-text-<?php echo $dream['id']; ?>">
                    <?php echo nl2br(htmlspecialchars($dream['dream_text'])); ?>
                </div>
                
                <?php if ($dream['analysis']): ?>
                <div class="dream-analysis" id="dream-analysis-<?php echo $dream['id']; ?>">
                    <strong><?php echo $t['ai_analysis']; ?>:</strong><br>
                    <?php echo htmlspecialchars($dream['analysis']); ?>
                </div>
                <?php endif; ?>
                
                <!-- Translation Controls -->
                <div class="translation-controls" style="margin-bottom: 1rem;">
                    <button class="action-btn translate-btn" onclick="translateDream(<?php echo $dream['id']; ?>, '<?php echo $lang; ?>')" id="translate-btn-<?php echo $dream['id']; ?>">
                        <i class="bi bi-translate"></i> <?php echo $t['auto_translate']; ?>
                    </button>
                    <button class="action-btn show-original-btn" onclick="showOriginal(<?php echo $dream['id']; ?>)" id="show-original-btn-<?php echo $dream['id']; ?>" style="display: none;">
                        <i class="bi bi-arrow-counterclockwise"></i> <?php echo $t['show_original']; ?>
                    </button>
                    <span class="translation-status" id="translation-status-<?php echo $dream['id']; ?>"></span>
                </div>
                
                <div class="dream-actions">
                    <button class="action-btn like-btn <?php echo $dream['user_liked'] ? 'liked' : ''; ?>" 
                            onclick="toggleLike(<?php echo $dream['id']; ?>)">
                        <i class="bi bi-heart<?php echo $dream['user_liked'] ? '-fill' : ''; ?>"></i>
                        <?php echo $dream['user_liked'] ? $t['unlike'] : $t['like']; ?>
                    </button>
                    
                    <button class="action-btn" onclick="toggleComments(<?php echo $dream['id']; ?>)">
                        <i class="bi bi-chat"></i> <?php echo $t['comments']; ?>
                    </button>
                    
                    <div class="stats">
                        <span><i class="bi bi-heart"></i> <?php echo $dream['likes_count']; ?></span>
                        <span><i class="bi bi-chat"></i> <?php echo $dream['comments_count']; ?></span>
                        <span><i class="bi bi-eye"></i> <?php echo $dream['views_count']; ?></span>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="comment-section" id="comments-<?php echo $dream['id']; ?>" style="display: none;">
                    <div class="comment-form">
                        <textarea class="comment-input" placeholder="<?php echo $t['write_comment']; ?>" id="comment-<?php echo $dream['id']; ?>"></textarea>
                        <button class="action-btn" onclick="addComment(<?php echo $dream['id']; ?>)">
                            <?php echo $t['post_comment']; ?>
                        </button>
                    </div>
                    <div class="comments-list" id="comments-list-<?php echo $dream['id']; ?>">
                        <!-- Comments will be loaded here -->
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (count($shared_dreams) >= $limit): ?>
        <button class="load-more-btn" onclick="loadMoreDreams()">
            <?php echo $t['load_more']; ?>
        </button>
        <?php endif; ?>
    </div>

    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <!-- Bottom Mobile Navigation -->
    <div class="bottom-navigation">
        
        <a href="dream.php" class="nav-item">
            <div class="nav-icon"><i class="bi bi-cloud-moon-fill"></i></div>
            <div class="nav-text">Dreams</div>
        </a>
        <a href="subconscious.php" class="nav-item">
            <div class="nav-icon"><i class="bi bi-cpu-fill"></i></div>
            <div class="nav-text"><?php echo $t['subconscious']; ?></div>
        </a>
        <a href="dream-sharing.php" class="nav-item active">
            <div class="nav-icon"><i class="bi bi-share"></i></div>
            <div class="nav-text"><?php echo $t['dream_sharing']; ?></div>
        </a>
        <a href="sleep_analysis.php" class="nav-item">
            <div class="nav-icon"><i class="bi bi-moon-stars-fill"></i></div>
            <div class="nav-text"><?php echo $t['sleep']; ?></div>
        </a>
    </div>

    <!-- Modal Overlay -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content">
            <div class="modal-title" id="modalTitle"></div>
            <div class="modal-message" id="modalMessage"></div>
            <div class="modal-buttons">
                <button class="modal-btn cancel" id="modalCancel">Cancel</button>
                <button class="modal-btn confirm" id="modalConfirm">Confirm</button>
            </div>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentPage = <?php echo $page; ?>;
        let currentFilter = '<?php echo $filter; ?>';
        let currentSort = '<?php echo $sort; ?>';

        // Modern Notification System
        function showNotification(message, type = 'success', title = '') {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const icon = type === 'success' ? 'bi-check-circle' : 
                        type === 'error' ? 'bi-x-circle' : 'bi-exclamation-triangle';
            
            notification.innerHTML = `
                <div class="notification-header">
                    <div class="notification-title">
                        <i class="bi ${icon} notification-icon"></i>
                        ${title || (type === 'success' ? 'Success' : type === 'error' ? 'Error' : 'Warning')}
                    </div>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="notification-message">${message}</div>
            `;
            
            container.appendChild(notification);
            
            // Show animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 400);
                }
            }, 5000);
        }

        // Modern Modal System
        function showModal(title, message, onConfirm) {
            const modal = document.getElementById('modalOverlay');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const confirmBtn = document.getElementById('modalConfirm');
            const cancelBtn = document.getElementById('modalCancel');
            
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            const closeModal = () => {
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
            };
            
            confirmBtn.onclick = () => {
                closeModal();
                if (onConfirm) onConfirm();
            };
            
            cancelBtn.onclick = closeModal;
            
            modal.onclick = (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            };
            
            // Close on Escape key
            document.addEventListener('keydown', function escapeHandler(e) {
                if (e.key === 'Escape') {
                    closeModal();
                    document.removeEventListener('keydown', escapeHandler);
                }
            });
        }

        async function postAction(params) {
            try {
                const response = await fetch('dream-sharing.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(params)
                });
                const text = await response.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response:', text);
                    throw new Error('Invalid server response');
                }
            } catch (err) {
                console.error('Request failed:', err);
                throw err;
            }
        }

        function shareDream() {
            const dreamId = document.getElementById('dreamSelect').value;
            if (!dreamId) {
                showNotification('Please select a dream to share', 'warning', 'Selection Required');
                return;
            }

            postAction({ action: 'share_dream', dream_id: dreamId })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success', 'Dream Shared');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message, 'error', 'Error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while sharing the dream', 'error', 'Error');
            });
        }

        function shareIncomingDream() {
            // Get dream data from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const dreamId = urlParams.get('dream_id');
            
            if (!dreamId) {
                showNotification('Dream data not found', 'error', 'Error');
                return;
            }

            postAction({ action: 'share_dream', dream_id: dreamId })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success', 'Dream Shared');
                    // Remove URL parameters and reload
                    setTimeout(() => {
                        window.location.href = 'dream-sharing.php';
                    }, 1500);
                } else {
                    showNotification(data.message, 'error', 'Error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while sharing the dream', 'error', 'Error');
            });
        }

        function unshareDream(sharedDreamId) {
            showModal(
                'Unshare Dream',
                'Are you sure you want to unshare this dream? This action cannot be undone.',
                () => {
                    postAction({ action: 'unshare_dream', shared_dream_id: sharedDreamId })
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success', 'Dream Unshared');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(data.message, 'error', 'Error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred while unsharing the dream', 'error', 'Error');
                    });
                }
            );
        }

        function toggleLike(sharedDreamId) {
            postAction({ action: 'like_dream', shared_dream_id: sharedDreamId })
            .then(data => {
                if (data.success) {
                    const likeBtn = document.querySelector(`[onclick="toggleLike(${sharedDreamId})"]`);
                    const icon = likeBtn.querySelector('i');
                    const stats = likeBtn.parentElement.querySelector('.stats');
                    const likesCount = stats.querySelector('span:first-child');
                    const current = parseInt((likesCount.textContent.match(/\d+/) || [0])[0], 10) || 0;
                    const newCount = data.action === 'liked' ? current + 1 : Math.max(0, current - 1);
                    
                    if (data.action === 'liked') {
                        likeBtn.classList.add('liked');
                        likeBtn.innerHTML = `<i class="bi bi-heart-fill"></i> ${likeBtn.innerText.replace(/^(Like|Unlike)/, 'Unlike')}`;
                        likesCount.innerHTML = `<i class="bi bi-heart-fill"></i> ${newCount}`;
                        showNotification(data.message, 'success', 'Liked');
                    } else {
                        likeBtn.classList.remove('liked');
                        likeBtn.innerHTML = `<i class="bi bi-heart"></i> ${likeBtn.innerText.replace(/^(Like|Unlike)/, 'Like')}`;
                        likesCount.innerHTML = `<i class="bi bi-heart"></i> ${newCount}`;
                        showNotification(data.message, 'success', 'Unliked');
                    }
                } else {
                    showNotification(data.message, 'error', 'Error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while processing your like', 'error', 'Error');
            });
        }

        function toggleComments(sharedDreamId) {
            const commentsSection = document.getElementById(`comments-${sharedDreamId}`);
            const commentsList = document.getElementById(`comments-list-${sharedDreamId}`);
            
            if (commentsSection.style.display === 'none') {
                commentsSection.style.display = 'block';
                loadComments(sharedDreamId);
            } else {
                commentsSection.style.display = 'none';
            }
        }

        function loadComments(sharedDreamId) {
            const commentsList = document.getElementById(`comments-list-${sharedDreamId}`);
            commentsList.innerHTML = '<p class="text-muted">Loading comments...</p>';
            
            postAction({ action: 'load_comments', shared_dream_id: sharedDreamId })
            .then(data => {
                if (data.success) {
                    if (data.comments.length > 0) {
                        let commentsHtml = '';
                        data.comments.forEach(comment => {
                            commentsHtml += `
                                <div class="comment">
                                    <div class="comment-header">
                                        <span class="comment-author">${comment.username}</span>
                                        <span class="comment-date">${comment.created_at}</span>
                                        ${comment.can_delete ? `<button class="action-btn" onclick="deleteComment(${comment.id})"><i class="bi bi-trash"></i></button>` : ''}
                                    </div>
                                    <div class="comment-text">${comment.comment_text}</div>
                                </div>
                            `;
                        });
                        commentsList.innerHTML = commentsHtml;
                    } else {
                        commentsList.innerHTML = '<p class="text-muted"><?php echo $t["no_comments"]; ?></p>';
                    }
                } else {
                    commentsList.innerHTML = '<p class="text-muted">Error loading comments</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                commentsList.innerHTML = '<p class="text-muted">Error loading comments</p>';
            });
        }

        function addComment(sharedDreamId) {
            const commentText = document.getElementById(`comment-${sharedDreamId}`).value.trim();
            if (!commentText) {
                showNotification('Please enter a comment', 'warning', 'Comment Required');
                return;
            }

            postAction({ action: 'add_comment', shared_dream_id: sharedDreamId, comment_text: commentText })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success', 'Comment Posted');
                    document.getElementById(`comment-${sharedDreamId}`).value = '';
                    loadComments(sharedDreamId);
                } else {
                    showNotification(data.message, 'error', 'Error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while posting your comment', 'error', 'Error');
            });
        }

        function deleteComment(commentId) {
            showModal(
                'Delete Comment',
                '<?php echo $t["delete_comment_confirm"]; ?>',
                () => {
                    postAction({ action: 'delete_comment', comment_id: commentId })
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success', 'Comment Deleted');
                            // Reload comments for the current dream
                            const sharedDreamId = document.querySelector('.comment-section[style*="block"]').id.replace('comments-', '');
                            loadComments(sharedDreamId);
                        } else {
                            showNotification(data.message, 'error', 'Error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred while deleting the comment', 'error', 'Error');
                    });
                }
            );
        }

        function loadMoreDreams() {
            currentPage++;
            const url = `dream-sharing.php?filter=${currentFilter}&sort=${currentSort}&page=${currentPage}`;
            window.location.href = url;
        }

        // Dream Type Filtering
        let currentDreamTypeFilter = 'all';

        function filterByDreamType(dreamType) {
            currentDreamTypeFilter = dreamType;
            const dreamCards = document.querySelectorAll('.dream-card');
            const filterButtons = document.querySelectorAll('.dream-type-filter-btn');
            
            // Update active filter button
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.classList.contains(dreamType)) {
                    btn.classList.add('active');
                }
            });
            
            // Filter dreams with animation
            dreamCards.forEach((card, index) => {
                const cardDreamType = card.querySelector('.dream-type-badge')?.textContent.toLowerCase().trim();
                const dreamTypeMatch = dreamType === 'all' || cardDreamType === dreamType;
                
                if (dreamTypeMatch) {
                    card.style.display = 'block';
                    card.style.animation = `fadeInUp 0.6s ease ${index * 0.1}s forwards`;
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.opacity = '1';
                    }, index * 100);
                } else {
                    card.style.animation = 'fadeOut 0.3s ease forwards';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
            
            // Add filter effect
            createDreamTypeFilterEffect(dreamType);
        }
        
        function createDreamTypeFilterEffect(dreamType) {
            const colors = {
                'nightmare': '#ff4757',
                'lucid': '#5352ed',
                'prophetic': '#ffd700',
                'normal': '#00BFFF',
                'recurring': '#ff6b6b',
                'spiritual': '#9c88ff',
                'symbolic': '#ffa502',
                'emotional': '#ff7675',
                'all': '#00BFFF'
            };
            
            const color = colors[dreamType] || '#39FF14';
            
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

        // Add fadeInUp animation if not exists
        if (!document.querySelector('#fadeInUpAnimation')) {
            const style = document.createElement('style');
            style.id = 'fadeInUpAnimation';
            style.textContent = `
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
                @keyframes fadeOut {
                    from { 
                        opacity: 1; 
                        transform: translateY(0); 
                    }
                    to { 
                        opacity: 0; 
                        transform: translateY(-20px); 
                    }
                }
            `;
            document.head.appendChild(style);
        }

        // Translation Functions
        const originalTexts = new Map();
        const originalAnalyses = new Map();

        function translateDream(sharedDreamId, targetLang) {
            const translateBtn = document.getElementById(`translate-btn-${sharedDreamId}`);
            const showOriginalBtn = document.getElementById(`show-original-btn-${sharedDreamId}`);
            const statusSpan = document.getElementById(`translation-status-${sharedDreamId}`);
            const dreamTextDiv = document.getElementById(`dream-text-${sharedDreamId}`);
            const dreamAnalysisDiv = document.getElementById(`dream-analysis-${sharedDreamId}`);
            
            // Store original text if not already stored
            if (!originalTexts.has(sharedDreamId)) {
                originalTexts.set(sharedDreamId, dreamTextDiv.innerHTML);
                if (dreamAnalysisDiv) {
                    originalAnalyses.set(sharedDreamId, dreamAnalysisDiv.innerHTML);
                }
            }
            
            // Show loading state
            translateBtn.disabled = true;
            translateBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <?php echo $t["translation_loading"]; ?>';
            statusSpan.textContent = '<?php echo $t["translation_loading"]; ?>';
            statusSpan.className = 'translation-status translation-loading';
            
            const startTime = performance.now();
            
            fetch('dream-sharing.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'translate_dream',
                    shared_dream_id: sharedDreamId,
                    target_lang: targetLang
                })
            })
            .then(response => response.json())
            .then(data => {
                const endTime = performance.now();
                const duration = endTime - startTime;
                
                // Log performance info
                console.log(`Translation completed in ${duration.toFixed(2)}ms`);
                
                if (data.success) {
                    // Update dream text
                    dreamTextDiv.innerHTML = data.translated_text.replace(/\n/g, '<br>');
                    
                    // Update analysis if exists
                    if (dreamAnalysisDiv && data.translated_analysis) {
                        const analysisTitleElem = dreamAnalysisDiv.querySelector('strong');
                        const analysisTitleHtml = analysisTitleElem ? analysisTitleElem.outerHTML : '<strong><?php echo $t["ai_analysis"]; ?>:</strong>';
                        dreamAnalysisDiv.innerHTML = `${analysisTitleHtml}<br>${data.translated_analysis.replace(/\n/g, '<br>')}`;
                    }
                    
                    // Update UI
                    translateBtn.style.display = 'none';
                    showOriginalBtn.style.display = 'inline-flex';
                    statusSpan.textContent = '<?php echo $t["translated_to"]; ?> ' + getLangName(targetLang);
                    statusSpan.className = 'translation-status';
                    
                    showNotification('Dream translated successfully!', 'success', 'Translation Complete');
                } else {
                    showNotification(data.message, 'error', 'Translation Error');
                    resetTranslationUI(sharedDreamId);
                }
            })
            .catch(error => {
                console.error('Translation error:', error);
                showNotification('Translation failed. Please try again.', 'error', 'Translation Error');
                resetTranslationUI(sharedDreamId);
            });
        }

        function showOriginal(sharedDreamId) {
            const translateBtn = document.getElementById(`translate-btn-${sharedDreamId}`);
            const showOriginalBtn = document.getElementById(`show-original-btn-${sharedDreamId}`);
            const statusSpan = document.getElementById(`translation-status-${sharedDreamId}`);
            const dreamTextDiv = document.getElementById(`dream-text-${sharedDreamId}`);
            const dreamAnalysisDiv = document.getElementById(`dream-analysis-${sharedDreamId}`);
            
            // Restore original text
            if (originalTexts.has(sharedDreamId)) {
                dreamTextDiv.innerHTML = originalTexts.get(sharedDreamId);
            }
            
            if (originalAnalyses.has(sharedDreamId) && dreamAnalysisDiv) {
                dreamAnalysisDiv.innerHTML = originalAnalyses.get(sharedDreamId);
            }
            
            // Update UI
            translateBtn.style.display = 'inline-flex';
            showOriginalBtn.style.display = 'none';
            statusSpan.textContent = '';
            statusSpan.className = 'translation-status';
            
            showNotification('Original text restored', 'success', 'Original Text');
        }

        function resetTranslationUI(sharedDreamId) {
            const translateBtn = document.getElementById(`translate-btn-${sharedDreamId}`);
            const showOriginalBtn = document.getElementById(`show-original-btn-${sharedDreamId}`);
            const statusSpan = document.getElementById(`translation-status-${sharedDreamId}`);
            
            translateBtn.disabled = false;
            translateBtn.innerHTML = '<i class="bi bi-translate"></i> <?php echo $t["auto_translate"]; ?>';
            statusSpan.textContent = '';
            statusSpan.className = 'translation-status';
        }

        function getLangName(langCode) {
            const langNames = {
                'en': 'English',
                'tr': 'Turkish',
                'es': 'Spanish',
                
            };
            return langNames[langCode] || langCode.toUpperCase();
        }

        // Cache management functions (for debugging)
        function getCacheStats() {
            postAction({ action: 'get_cache_stats' })
            .then(data => {
                if (data.success) {
                    console.log('Cache Statistics:', data.stats);
                    showNotification('Cache stats logged to console', 'success', 'Cache Info');
                } else {
                    showNotification(data.message, 'error', 'Cache Error');
                }
            })
            .catch(error => {
                console.error('Cache stats error:', error);
                showNotification('Failed to get cache stats', 'error', 'Error');
            });
        }

        function clearCache() {
            showModal(
                'Clear Cache',
                'Are you sure you want to clear all cached translations? This will force new API calls for all translations.',
                () => {
                    postAction({ action: 'clear_cache' })
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success', 'Cache Cleared');
                        } else {
                            showNotification(data.message, 'error', 'Cache Error');
                        }
                    })
                    .catch(error => {
                        console.error('Clear cache error:', error);
                        showNotification('Failed to clear cache', 'error', 'Error');
                    });
                }
            );
        }



        // Enhanced language dropdown functionality
        document.addEventListener('DOMContentLoaded', () => {
            const dropdownToggle = document.getElementById('languageDropdown');
            const dropdownMenu = document.getElementById('languageMenu');
            
            console.log('DOM loaded, checking dropdown elements...');
            console.log('dropdownToggle:', dropdownToggle);
            console.log('dropdownMenu:', dropdownMenu);
            
            if (dropdownToggle && dropdownMenu) {
                console.log('Language dropdown elements found successfully');
                
                // Toggle dropdown on click
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Language dropdown clicked');
                    
                    // Toggle dropdown
                    const isVisible = dropdownMenu.classList.contains('show');
                    if (isVisible) {
                        dropdownMenu.classList.remove('show');
                        console.log('Dropdown hidden');
                    } else {
                        dropdownMenu.classList.add('show');
                        console.log('Dropdown shown');
                    }
                    
                    console.log('Dropdown show class:', dropdownMenu.classList.contains('show'));
                    console.log('Dropdown computed styles:', window.getComputedStyle(dropdownMenu));
                });
                
                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                        console.log('Dropdown closed (clicked outside)');
                    }
                });
                
                // Close on escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        dropdownMenu.classList.remove('show');
                        console.log('Dropdown closed (Escape key)');
                    }
                });
                
                // Prevent dropdown from closing when clicking inside menu
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    console.log('Menu item clicked, keeping dropdown open');
                });
                
            } else {
                console.error('Language dropdown elements not found!');
                console.log('dropdownToggle:', dropdownToggle);
                console.log('dropdownMenu:', dropdownMenu);
            }
            
            // Enhanced Background Interactions
            const background = document.querySelector('.animated-background');
            
            if (background) {
                // Mouse parallax effect
                document.addEventListener('mousemove', (e) => {
                    const { clientX, clientY } = e;
                    const centerX = window.innerWidth / 2;
                    const centerY = window.innerHeight / 2;
                    
                    const moveX = (clientX - centerX) / centerX * 20;
                    const moveY = (clientY - centerY) / centerY * 20;
                    
                    background.style.transform = `translate(${moveX}px, ${moveY}px)`;
                });
                
                // Add subtle mouse trail effect
                document.addEventListener('mousemove', (e) => {
                    if (Math.random() > 0.7) {
                        const trail = document.createElement('div');
                        trail.style.cssText = `
                            position: fixed;
                            width: 2px;
                            height: 2px;
                            background: rgba(0, 191, 255, 0.3);
                            border-radius: 50%;
                            left: ${e.clientX}px;
                            top: ${e.clientY}px;
                            pointer-events: none;
                            z-index: 9998;
                            animation: fadeOut 2s ease-out forwards;
                        `;
                        document.body.appendChild(trail);
                        
                        setTimeout(() => {
                            if (trail.parentNode) {
                                trail.parentNode.removeChild(trail);
                            }
                        }, 2000);
                    }
                });
            }
            
        });

        // Add fadeOut animation for mouse trail
        if (!document.querySelector('#fadeOutAnimation')) {
            const style = document.createElement('style');
            style.id = 'fadeOutAnimation';
            style.textContent = `
                @keyframes fadeOut {
                    0% {
                        opacity: 1;
                    }
                    100% {
                        opacity: 0;
                    }
                }
                
                /* Loading Animation */
                .loading-trends {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    color: var(--text-muted);
                    font-size: 0.9rem;
                }
                
                .spin {
                    animation: spin 1s linear infinite;
                }
                
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
    

</body>
</html>
