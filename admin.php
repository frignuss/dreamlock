<?php
session_start();

include 'var/www/secret/ni.php';
require 'config.php';

// Handle login
$login_error = '';
if ($_POST['username'] ?? false) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        // Redirect to prevent form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $login_error = 'Geçersiz kullanıcı adı veya şifre!';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Check if logged in
$is_logged_in = $_SESSION['admin_logged_in'] ?? false;

// Handle AJAX requests for delete operations
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    // Database connection for AJAX
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        switch ($_POST['action']) {
            case 'delete_user':
                $user_id = (int)$_POST['user_id'];
                // Delete user (cascade will handle related records)
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $result = $stmt->execute([$user_id]);
                echo json_encode(['success' => $result]);
                break;
                
            case 'delete_dream':
                $dream_id = (int)$_POST['dream_id'];
                $stmt = $pdo->prepare("DELETE FROM dreams WHERE id = ?");
                $result = $stmt->execute([$dream_id]);
                echo json_encode(['success' => $result]);
                break;
                
            case 'delete_analysis':
                $analysis_id = (int)$_POST['analysis_id'];
                $stmt = $pdo->prepare("DELETE FROM subconscious_analyses WHERE id = ?");
                $result = $stmt->execute([$analysis_id]);
                echo json_encode(['success' => $result]);
                break;
                
            case 'get_dream_detail':
                $dream_id = (int)$_POST['dream_id'];
                $stmt = $pdo->prepare("SELECT d.*, u.username FROM dreams d LEFT JOIN users u ON d.user_id = u.id WHERE d.id = ?");
                $stmt->execute([$dream_id]);
                $dream = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($dream);
                break;
                
            case 'get_analysis_detail':
                $analysis_id = (int)$_POST['analysis_id'];
                $stmt = $pdo->prepare("SELECT sa.*, u.username FROM subconscious_analyses sa LEFT JOIN users u ON sa.user_id = u.id WHERE sa.id = ?");
                $stmt->execute([$analysis_id]);
                $analysis = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($analysis);
                break;
                
            case 'toggle_premium':
                $user_id = (int)$_POST['user_id'];
                $is_premium = (int)$_POST['is_premium'];
                
                if ($is_premium) {
                    // Premium yap - 1 yıl süre ver
                    $expires_at = date('Y-m-d H:i:s', strtotime('+1 year'));
                    $stmt = $pdo->prepare("UPDATE users SET is_premium = 1, premium_expires_at = ? WHERE id = ?");
                    $result = $stmt->execute([$expires_at, $user_id]);
                } else {
                    // Premium'u kaldır
                    $stmt = $pdo->prepare("UPDATE users SET is_premium = 0, premium_expires_at = NULL WHERE id = ?");
                    $result = $stmt->execute([$user_id]);
                }
                
                echo json_encode(['success' => $result]);
                break;

            case 'extend_premium':
                $user_id = (int)$_POST['user_id'];
                $months = (int)$_POST['months'];
                
                // Mevcut premium bitiş tarihini al
                $stmt = $pdo->prepare("SELECT premium_expires_at FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user['premium_expires_at'] && strtotime($user['premium_expires_at']) > time()) {
                    // Mevcut tarihe ekle
                    $new_expires = date('Y-m-d H:i:s', strtotime($user['premium_expires_at'] . " +{$months} months"));
                } else {
                    // Şu andan itibaren
                    $new_expires = date('Y-m-d H:i:s', strtotime("+{$months} months"));
                }
                
                $stmt = $pdo->prepare("UPDATE users SET is_premium = 1, premium_expires_at = ? WHERE id = ?");
                $result = $stmt->execute([$new_expires, $user_id]);
                
                echo json_encode(['success' => $result]);
                break;

            case 'update_user':
                $user_id = (int)$_POST['user_id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $is_premium = (int)$_POST['is_premium'];
                $premium_expires_at = $_POST['premium_expires_at'] ?: null;
                
                $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, is_premium = ?, premium_expires_at = ? WHERE id = ?");
                $result = $stmt->execute([$username, $email, $is_premium, $premium_expires_at, $user_id]);
                
                echo json_encode(['success' => $result]);
                break;

            case 'update_dream':
                $dream_id = (int)$_POST['dream_id'];
                $dream_text = $_POST['dream_text'];
                $analysis = $_POST['analysis'];
                $dream_type = $_POST['dream_type'];
                
                $stmt = $pdo->prepare("UPDATE dreams SET dream_text = ?, analysis = ?, dream_type = ? WHERE id = ?");
                $result = $stmt->execute([$dream_text, $analysis, $dream_type, $dream_id]);
                
                echo json_encode(['success' => $result]);
                break;

            case 'get_statistics':
                // Kullanıcı istatistikleri
                $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
                $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
                
                $stmt = $pdo->query("SELECT COUNT(*) as premium_users FROM users WHERE is_premium = 1");
                $premium_users = $stmt->fetch(PDO::FETCH_ASSOC)['premium_users'];
                
                $stmt = $pdo->query("SELECT COUNT(*) as total_dreams FROM dreams");
                $total_dreams = $stmt->fetch(PDO::FETCH_ASSOC)['total_dreams'];
                
                $stmt = $pdo->query("SELECT COUNT(*) as total_analyses FROM subconscious_analyses");
                $total_analyses = $stmt->fetch(PDO::FETCH_ASSOC)['total_analyses'];
                
                // Son 7 günlük aktivite
                $stmt = $pdo->query("SELECT COUNT(*) as recent_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
                $recent_users = $stmt->fetch(PDO::FETCH_ASSOC)['recent_users'];
                
                $stmt = $pdo->query("SELECT COUNT(*) as recent_dreams FROM dreams WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
                $recent_dreams = $stmt->fetch(PDO::FETCH_ASSOC)['recent_dreams'];
                
                echo json_encode([
                    'total_users' => $total_users,
                    'premium_users' => $premium_users,
                    'total_dreams' => $total_dreams,
                    'total_analyses' => $total_analyses,
                    'recent_users' => $recent_users,
                    'recent_dreams' => $recent_dreams
                ]);
                break;

            case 'export_data':
                $type = $_POST['type'];
                $data = [];
                
                switch($type) {
                    case 'users':
                        $stmt = $pdo->query("SELECT * FROM users");
                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        break;
                    case 'dreams':
                        $stmt = $pdo->query("SELECT d.*, u.username FROM dreams d LEFT JOIN users u ON d.user_id = u.id");
                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        break;
                    case 'analyses':
                        $stmt = $pdo->query("SELECT sa.*, u.username FROM subconscious_analyses sa LEFT JOIN users u ON sa.user_id = u.id");
                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        break;
                }
                
                echo json_encode($data);
                break;
        }
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Veritabanı bağlantısı ve veri çekme
$mockData = [
    'users' => [],
    'dreams' => [],
    'subconscious_analyses' => [],
    'dream_categories' => []
];

if ($is_logged_in) {
    // PDO bağlantısı
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Tüm tabloları çek
        try {
            // Users tablosu
            $stmt = $pdo->prepare("SELECT * FROM users");
            $stmt->execute();
            $mockData['users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Dreams tablosu
            $stmt = $pdo->prepare("SELECT * FROM dreams");
            $stmt->execute();
            $mockData['dreams'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Subconscious analyses tablosu
            $stmt = $pdo->prepare("SELECT * FROM subconscious_analyses");
            $stmt->execute();
            $mockData['subconscious_analyses'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Dream categories tablosu
            $stmt = $pdo->prepare("SELECT * FROM dream_categories");
            $stmt->execute();
            $mockData['dream_categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            echo "Veri çekme hatası: " . $e->getMessage();
        }
        
    } catch(PDOException $e) {
        echo "Bağlantı hatası: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DreamLock Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-green: #39FF14;
            --secondary-green: #2ecc71;
            --dark-bg: #0a0a0a;
            --card-bg: #1a1a1a;
            --text-light: #ffffff;
            --text-muted: #888888;
            --border-color: #2a2a2a;
            --premium-gold: #FFD700;
            --danger-red: #ff4444;
            --warning-orange: #ff8800;
            --info-blue: #0099ff;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #0f0f0f 100%);
            color: var(--text-light);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* ADMIN BACKGROUND SYSTEM */
        .admin-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
            background: 
                radial-gradient(circle at 15% 25%, rgba(57, 255, 20, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 85% 75%, rgba(46, 204, 113, 0.03) 0%, transparent 35%),
                radial-gradient(circle at 60% 15%, rgba(57, 255, 20, 0.02) 0%, transparent 50%),
                linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #0f0f0f 100%);
            animation: adminPulse 10s ease-in-out infinite alternate;
        }

        @keyframes adminPulse {
            0% {
                filter: brightness(1) contrast(1);
            }
            100% {
                filter: brightness(1.05) contrast(1.02);
            }
        }

        /* Admin Grid */
        .admin-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -5;
            background-image: 
                linear-gradient(rgba(57, 255, 20, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(57, 255, 20, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: adminGridPulse 15s ease-in-out infinite;
            opacity: 0.2;
        }

        @keyframes adminGridPulse {
            0%, 100% {
                opacity: 0.1;
                transform: scale(1);
            }
            50% {
                opacity: 0.3;
                transform: scale(1.01);
            }
        }

        /* Login Form */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: radial-gradient(circle at center, #1a1a1a 0%, #0a0a0a 100%);
            position: relative;
        }

        .login-form {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
            padding: 50px 40px;
            border-radius: 20px;
            border: 2px solid var(--primary-green);
            box-shadow: 0 20px 60px rgba(57, 255, 20, 0.3);
            width: 450px;
            text-align: center;
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
        }

        .login-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(57, 255, 20, 0.1), transparent);
            animation: loginShimmer 3s ease-in-out infinite;
        }

        @keyframes loginShimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        .login-form h2 {
            color: var(--primary-green);
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: 800;
            text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
            position: relative;
            z-index: 2;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
            position: relative;
            z-index: 2;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-green);
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-light);
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 20px rgba(57, 255, 20, 0.3);
            transform: translateY(-2px);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            border: none;
            border-radius: 10px;
            color: var(--dark-bg);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 2;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(57, 255, 20, 0.4);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .error-message {
            color: var(--danger-red);
            margin-top: 15px;
            padding: 12px;
            background: rgba(255, 68, 68, 0.1);
            border-radius: 8px;
            border-left: 4px solid var(--danger-red);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Admin Panel */
        .header {
            background: rgba(26, 26, 26, 0.95);
            padding: 25px 30px;
            border-bottom: 2px solid var(--primary-green);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .header h1 {
            color: var(--primary-green);
            font-size: 28px;
            font-weight: 800;
            text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header h1::before {
            content: '⚡';
            font-size: 24px;
            animation: adminIconPulse 2s ease-in-out infinite;
        }

        @keyframes adminIconPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            padding: 12px 25px;
            background: linear-gradient(135deg, var(--danger-red), #cc3333);
            border: none;
            border-radius: 10px;
            color: var(--text-light);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 68, 68, 0.4);
        }

        .admin-info {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
        }

        .nav-tabs {
            display: flex;
            background: rgba(15, 15, 15, 0.9);
            border-bottom: 1px solid var(--border-color);
            overflow-x: auto;
            backdrop-filter: blur(10px);
            position: sticky;
            top: 80px;
            z-index: 99;
        }

        .nav-tab {
            padding: 18px 30px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            border-bottom: 3px solid transparent;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(57, 255, 20, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-tab:hover::before {
            left: 100%;
        }

        .nav-tab:hover {
            background: rgba(57, 255, 20, 0.1);
            color: var(--primary-green);
            transform: translateY(-2px);
        }

        .nav-tab.active {
            color: var(--primary-green);
            border-bottom-color: var(--primary-green);
            background: rgba(57, 255, 20, 0.05);
            box-shadow: 0 0 20px rgba(57, 255, 20, 0.2);
        }

        .nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--secondary-green));
            animation: tabGlow 2s ease-in-out infinite;
        }

        @keyframes tabGlow {
            0%, 100% {
                box-shadow: 0 0 10px rgba(57, 255, 20, 0.5);
            }
            50% {
                box-shadow: 0 0 20px rgba(57, 255, 20, 0.8);
            }
        }

        .tab-content {
            padding: 20px;
            max-width: 100%;
            overflow-x: auto;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(20, 20, 20, 0.9) 100%);
            padding: 30px 25px;
            border-radius: 15px;
            border: 2px solid var(--primary-green);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(57, 255, 20, 0.05) 0%, transparent 50%, rgba(57, 255, 20, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(57, 255, 20, 0.2);
            border-color: var(--secondary-green);
        }

        .stat-card h3 {
            color: var(--primary-green);
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 2;
        }

        .stat-card .stat-number {
            font-size: 42px;
            font-weight: 900;
            color: var(--text-light);
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 2;
        }

        .stat-card .stat-description {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 10px;
            position: relative;
            z-index: 2;
        }

        .stat-card.premium {
            border-color: var(--premium-gold);
        }

        .stat-card.premium h3 {
            color: var(--premium-gold);
        }

        .stat-card.danger {
            border-color: var(--danger-red);
        }

        .stat-card.danger h3 {
            color: var(--danger-red);
        }

        .stat-card.info {
            border-color: var(--info-blue);
        }

        .stat-card.info h3 {
            color: var(--info-blue);
        }

        .table-container {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(20, 20, 20, 0.9) 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            border: 2px solid var(--border-color);
            backdrop-filter: blur(10px);
        }

        .table-header {
            padding: 25px 30px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(57, 255, 20, 0.05);
        }

        .table-header h3 {
            color: var(--primary-green);
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .table-btn {
            padding: 8px 15px;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            border: none;
            border-radius: 8px;
            color: var(--dark-bg);
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(57, 255, 20, 0.3);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .data-table th {
            background: rgba(57, 255, 20, 0.1);
            color: var(--primary-green);
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .data-table tr {
            transition: all 0.3s ease;
        }

        .data-table tr:hover {
            background: rgba(57, 255, 20, 0.05);
            transform: scale(1.01);
        }

        .data-table td {
            font-size: 14px;
            color: var(--text-light);
        }

        .data-table .user-email {
            color: var(--text-muted);
            font-size: 13px;
        }

        .data-table .premium-badge {
            background: linear-gradient(135deg, var(--premium-gold), #FFA500);
            color: var(--dark-bg);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .data-table .free-badge {
            background: linear-gradient(135deg, var(--text-muted), #666);
            color: var(--text-light);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .welcome-message {
            background: linear-gradient(45deg, #39FF14, #2ecc40);
            color: #000;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(57, 255, 20, 0.4);
        }

        /* Action Buttons */
        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            margin: 0 3px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .btn-delete {
            background: linear-gradient(135deg, var(--danger-red), #cc3333);
            color: var(--text-light);
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #cc3333, var(--danger-red));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 68, 68, 0.4);
        }

        .btn-view {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: var(--dark-bg);
        }

        .btn-view:hover {
            background: linear-gradient(135deg, var(--secondary-green), var(--primary-green));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(57, 255, 20, 0.4);
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--info-blue), #0080cc);
            color: var(--text-light);
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #0080cc, var(--info-blue));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 153, 255, 0.4);
        }

        .btn-premium-add {
            background: linear-gradient(135deg, var(--premium-gold), #FFA500);
            color: var(--dark-bg);
        }

        .btn-premium-add:hover {
            background: linear-gradient(135deg, #FFA500, var(--premium-gold));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        }

        .btn-premium-remove {
            background: linear-gradient(135deg, var(--warning-orange), #e67700);
            color: var(--text-light);
        }

        .btn-premium-remove:hover {
            background: linear-gradient(135deg, #e67700, var(--warning-orange));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 136, 0, 0.4);
        }

        .btn-premium-extend {
            background: linear-gradient(135deg, var(--info-blue), #0080cc);
            color: var(--text-light);
        }

        .btn-premium-extend:hover {
            background: linear-gradient(135deg, #0080cc, var(--info-blue));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 153, 255, 0.4);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background: #1a1a1a;
            margin: 5% auto;
            padding: 20px;
            border: 2px solid #39FF14;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
            max-height: 80%;
            overflow-y: auto;
            box-shadow: 0 0 30px rgba(57, 255, 20, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
        }

        .modal-header h2 {
            color: #39FF14;
            margin: 0;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #39FF14;
        }

        .modal-body {
            color: #fff;
            line-height: 1.6;
        }

        .modal-body h3 {
            color: #39FF14;
            margin: 15px 0 10px 0;
        }

        .modal-body pre {
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
            border-left: 3px solid #39FF14;
        }

        .confirm-dialog {
            background: #1a1a1a;
            border: 2px solid #ff4444;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .confirm-dialog h3 {
            color: #ff4444;
            margin-bottom: 15px;
        }

        .confirm-buttons {
            margin-top: 20px;
        }

        .confirm-buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-confirm {
            background: #ff4444;
            color: #fff;
        }

        .btn-cancel {
            background: #666;
            color: #fff;
        }

        .extend-premium-form {
            background: #1a1a1a;
            border: 2px solid #39FF14;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .extend-premium-form h3 {
            color: #39FF14;
            margin-bottom: 15px;
        }

        .extend-premium-form select,
        .extend-premium-form input {
            width: 100%;
            padding: 10px;
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid #333;
            border-radius: 5px;
            color: #fff;
            margin: 10px 0;
        }

        .extend-premium-form select:focus,
        .extend-premium-form input:focus {
            outline: none;
            border-color: #39FF14;
            box-shadow: 0 0 10px rgba(57, 255, 20, 0.3);
        }

        .extend-buttons {
            margin-top: 20px;
        }

        .extend-buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-extend-confirm {
            background: #39FF14;
            color: #000;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-form {
                width: 90%;
                margin: 20px;
                padding: 30px;
            }

            .nav-tabs {
                flex-wrap: wrap;
            }

            .tab-content {
                padding: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .table-container {
                overflow-x: auto;
            }

            .data-table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <?php if (!$is_logged_in): ?>
        <!-- Admin Background Elements -->
        <div class="admin-background"></div>
        <div class="admin-grid"></div>
        
        <!-- Login Form -->
        <div class="login-container">
            <form class="login-form" method="POST">
                <h2>🔐 DreamLock Admin</h2>
                
                <?php if ($login_error): ?>
                    <div class="error-message"><?php echo htmlspecialchars($login_error); ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="username">Kullanıcı Adı:</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="password">Şifre:</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="login-btn">Giriş Yap</button>
            </form>
        </div>
    <?php else: ?>
        <!-- Admin Background Elements -->
        <div class="admin-background"></div>
        <div class="admin-grid"></div>
        
        <!-- Admin Panel -->
        <header class="header">
            <h1>DreamLock Admin Panel</h1>
            <div class="header-actions">
                <div class="admin-info">
                    Admin: <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                </div>
            <a href="?logout=1" class="logout-btn">Çıkış Yap</a>
            </div>
        </header>

        <nav class="nav-tabs">
            <a href="#dashboard" class="nav-tab active" onclick="showTab('dashboard', this)">Dashboard</a>
            <a href="#users" class="nav-tab" onclick="showTab('users', this)">Kullanıcılar</a>
            <a href="#dreams" class="nav-tab" onclick="showTab('dreams', this)">Rüyalar</a>
            <a href="#analyses" class="nav-tab" onclick="showTab('analyses', this)">Analizler</a>
            <a href="#categories" class="nav-tab" onclick="showTab('categories', this)">Kategoriler</a>
        </nav>

        <div class="tab-content">
            <!-- Welcome Message -->
            <div class="welcome-message">
                🎉 Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>! Admin paneline başarıyla giriş yaptınız.
            </div>

            <!-- Dashboard Tab -->
            <div id="dashboard" class="tab-pane active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>👥 Toplam Kullanıcı</h3>
                        <div class="stat-number"><?php echo count($mockData['users']); ?></div>
                        <div class="stat-description">Kayıtlı kullanıcı sayısı</div>
                    </div>
                    <div class="stat-card premium">
                        <h3>⭐ Premium Kullanıcı</h3>
                        <div class="stat-number"><?php echo count(array_filter($mockData['users'], function($user) { return $user['is_premium'] && (!$user['premium_expires_at'] || strtotime($user['premium_expires_at']) > time()); })); ?></div>
                        <div class="stat-description">Aktif premium üyeler</div>
                    </div>
                    <div class="stat-card">
                        <h3>🌙 Toplam Rüya</h3>
                        <div class="stat-number"><?php echo count($mockData['dreams']); ?></div>
                        <div class="stat-description">Kaydedilen rüya sayısı</div>
                    </div>
                    <div class="stat-card">
                        <h3>🧠 Toplam Analiz</h3>
                        <div class="stat-number"><?php echo count($mockData['subconscious_analyses']); ?></div>
                        <div class="stat-description">Yapılan analiz sayısı</div>
                    </div>
                    <div class="stat-card info">
                        <h3>📊 Toplam Kategori</h3>
                        <div class="stat-number"><?php echo count($mockData['dream_categories']); ?></div>
                        <div class="stat-description">Rüya kategorileri</div>
                    </div>
                    <div class="stat-card danger">
                        <h3>⚠️ Süresi Dolmuş</h3>
                        <div class="stat-number"><?php echo count(array_filter($mockData['users'], function($user) { return $user['is_premium'] && $user['premium_expires_at'] && strtotime($user['premium_expires_at']) < time(); })); ?></div>
                        <div class="stat-description">Premium süresi dolmuş kullanıcılar</div>
                    </div>
                </div>
            </div>

            <!-- Users Tab -->
            <div id="users" class="tab-pane">
                <div class="table-container">
                    <div class="table-header">
                        <h3>👥 Kullanıcı Yönetimi</h3>
                        <div class="table-actions">
                            <button class="table-btn" onclick="exportData('users')">📊 Export</button>
                            <button class="table-btn" onclick="refreshStats()">🔄 Yenile</button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kullanıcı Adı</th>
                                <th>E-posta</th>
                                <th>Telefon</th>
                                <th>Dil</th>
                                <th>Premium Durumu</th>
                                <th>Premium Bitiş</th>
                                <th>E-posta Doğrulandı</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mockData['users'] as $user): ?>
                            <?php 
                                $is_premium = $user['is_premium'];
                                $premium_expires = $user['premium_expires_at'];
                                $is_expired = false;
                                
                                // Premium süresi dolmuş mu kontrol et
                                if ($premium_expires && strtotime($premium_expires) < time()) {
                                    $is_expired = true;
                                }
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['preferred_language']); ?></td>
                                <td>
                                    <?php if ($is_premium && !$is_expired): ?>
                                        <span class="premium-badge">⭐ Premium</span>
                                    <?php elseif ($is_expired): ?>
                                        <span class="free-badge" style="background: linear-gradient(135deg, var(--warning-orange), #e67700);">⏰ Süresi Dolmuş</span>
                                    <?php else: ?>
                                        <span class="free-badge">👤 Ücretsiz</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($premium_expires): ?>
                                        <span style="color: <?php echo $is_expired ? '#ff8800' : '#39FF14'; ?>">
                                            <?php echo date('d.m.Y H:i', strtotime($premium_expires)); ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #666;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $user['email_verified'] ? '✅' : '❌'; ?></td>
                                <td>
                                    <button class="action-btn btn-edit" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>', '<?php echo htmlspecialchars($user['email']); ?>', <?php echo $is_premium ? 1 : 0; ?>, '<?php echo $premium_expires; ?>')">
                                        ✏️ Düzenle
                                    </button>
                                    
                                    <?php if ($is_premium && !$is_expired): ?>
                                        <button class="action-btn btn-premium-remove" onclick="togglePremium(<?php echo $user['id']; ?>, 0, '<?php echo htmlspecialchars($user['username']); ?>')">
                                            🚫 Premium Kaldır
                                        </button>
                                    <?php else: ?>
                                        <button class="action-btn btn-premium-add" onclick="togglePremium(<?php echo $user['id']; ?>, 1, '<?php echo htmlspecialchars($user['username']); ?>')">
                                            ⭐ Premium Yap
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="action-btn btn-premium-extend" onclick="showExtendModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">
                                        ⏰ Süre Uzat
                                    </button>
                                    
                                    <button class="action-btn btn-delete" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">
                                        🗑️ Sil
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Dreams Tab -->
            <div id="dreams" class="tab-pane">
                <div class="table-container">
                    <div class="table-header">
                        <h3>🌙 Rüya Yönetimi</h3>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kullanıcı ID</th>
                                <th>Rüya Metni</th>
                                <th>Açılış Tarihi</th>
                                <th>Oluşturulma</th>
                                <th>Dil</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mockData['dreams'] as $dream): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($dream['id']); ?></td>
                                <td><?php echo htmlspecialchars($dream['user_id']); ?></td>
                                <td><?php echo htmlspecialchars(substr($dream['dream_text'], 0, 50) . '...'); ?></td>
                                <td><?php echo htmlspecialchars($dream['open_date']); ?></td>
                                <td><?php echo htmlspecialchars($dream['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($dream['language']); ?></td>
                                <td>
                                    <button class="action-btn btn-view" onclick="viewDream(<?php echo $dream['id']; ?>)">👁️ Görüntüle</button>
                                    <button class="action-btn btn-delete" onclick="deleteDream(<?php echo $dream['id']; ?>)">🗑️ Sil</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Analyses Tab -->
            <div id="analyses" class="tab-pane">
                <div class="table-container">
                    <div class="table-header">
                        <h3>🧠 Analiz Yönetimi</h3>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kullanıcı ID</th>
                                <th>Rüya Sayısı</th>
                                <th>Oluşturulma</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mockData['subconscious_analyses'] as $analysis): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($analysis['id']); ?></td>
                                <td><?php echo htmlspecialchars($analysis['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($analysis['dream_count']); ?></td>
                                <td><?php echo htmlspecialchars($analysis['created_at']); ?></td>
                                <td>
                                    <button class="action-btn btn-view" onclick="viewAnalysis(<?php echo $analysis['id']; ?>)">👁️ Görüntüle</button>
                                    <button class="action-btn btn-delete" onclick="deleteAnalysis(<?php echo $analysis['id']; ?>)">🗑️ Sil</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categories Tab -->
            <div id="categories" class="tab-pane">
                <div class="table-container">
                    <div class="table-header">
                        <h3>📊 Kategori Yönetimi</h3>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>İsim</th>
                                <th>Açıklama</th>
                                <th>Renk</th>
                                <th>Oluşturulma</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mockData['dream_categories'] as $category): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category['id']); ?></td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td><?php echo htmlspecialchars($category['description']); ?></td>
                                <td>
                                    <span style="background: <?php echo htmlspecialchars($category['color']); ?>; padding: 5px 10px; border-radius: 3px; color: white;">
                                        <?php echo htmlspecialchars($category['color']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($category['created_at']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal for viewing dream/analysis details -->
        <div id="detailModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle">Detaylar</h2>
                    <span class="close" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div id="confirmModal" class="modal">
            <div class="modal-content">
                <div class="confirm-dialog">
                    <h3>⚠️ Dikkat!</h3>
                    <p id="confirmMessage">Bu işlemi gerçekleştirmek istediğinizden emin misiniz?</p>
                    <div class="confirm-buttons">
                        <button class="btn-confirm" id="confirmYes">Evet, Sil</button>
                        <button class="btn-cancel" onclick="closeConfirm()">İptal</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Extension Modal -->
        <div id="extendModal" class="modal">
            <div class="modal-content">
                <div class="extend-premium-form">
                    <h3>⏰ Premium Süre Uzatma</h3>
                    <p id="extendUserInfo">Kullanıcı premium süresini uzatın</p>
                    
                    <label for="extendMonths">Süre (Ay):</label>
                    <select id="extendMonths">
                        <option value="1">1 Ay</option>
                        <option value="3">3 Ay</option>
                        <option value="6">6 Ay</option>
                        <option value="12" selected>12 Ay (1 Yıl)</option>
                        <option value="24">24 Ay (2 Yıl)</option>
                    </select>
                    
                    <div class="extend-buttons">
                        <button class="btn-extend-confirm" id="extendConfirm">Süre Uzat</button>
                        <button class="btn-cancel" onclick="closeExtendModal()">İptal</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let currentDeleteAction = null;
            let currentExtendUserId = null;

            function showTab(tabName, element) {
                // Hide all tab panes
                const tabPanes = document.querySelectorAll('.tab-pane');
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Remove active class from all tabs
                const tabs = document.querySelectorAll('.nav-tab');
                tabs.forEach(tab => tab.classList.remove('active'));

                // Show selected tab pane
                document.getElementById(tabName).classList.add('active');

                // Add active class to clicked tab
                element.classList.add('active');
                
                // Prevent default link behavior
                return false;
            }

            // Premium Toggle
            function togglePremium(userId, isPremium, username) {
                const action = isPremium ? 'premium yapmak' : 'premium\'u kaldırmak';
                
                if (confirm(`${username} kullanıcısını ${action} istediğinizden emin misiniz?`)) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=toggle_premium&user_id=${userId}&is_premium=${isPremium}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('İşlem başarısız: ' + (data.error || 'Bilinmeyen hata'));
                        }
                    })
                    .catch(error => {
                        alert('Hata: ' + error);
                    });
                }
            }

            // Show Extend Modal
            function showExtendModal(userId, username) {
                currentExtendUserId = userId;
                document.getElementById('extendUserInfo').textContent = 
                    `${username} kullanıcısının premium süresini uzatın`;
                document.getElementById('extendModal').style.display = 'block';
            }

            // Close Extend Modal
            function closeExtendModal() {
                document.getElementById('extendModal').style.display = 'none';
                currentExtendUserId = null;
            }

            // Extend Confirm
            document.getElementById('extendConfirm').onclick = function() {
                if (currentExtendUserId) {
                    const months = document.getElementById('extendMonths').value;
                    
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=extend_premium&user_id=${currentExtendUserId}&months=${months}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('İşlem başarısız: ' + (data.error || 'Bilinmeyen hata'));
                        }
                    })
                    .catch(error => {
                        alert('Hata: ' + error);
                    });
                    
                    closeExtendModal();
                }
            };

            // Delete User
            function deleteUser(userId, username) {
                currentDeleteAction = () => {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete_user&user_id=${userId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Silme işlemi başarısız: ' + (data.error || 'Bilinmeyen hata'));
                        }
                    })
                    .catch(error => {
                        alert('Hata: ' + error);
                    });
                };
                
                document.getElementById('confirmMessage').innerHTML = 
                    `<strong>${username}</strong> kullanıcısını ve tüm verilerini (rüyalar, analizler) silmek istediğinizden emin misiniz?<br><br><strong>Bu işlem geri alınamaz!</strong>`;
                document.getElementById('confirmModal').style.display = 'block';
            }

            // Delete Dream
            function deleteDream(dreamId) {
                currentDeleteAction = () => {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete_dream&dream_id=${dreamId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Silme işlemi başarısız: ' + (data.error || 'Bilinmeyen hata'));
                        }
                    })
                    .catch(error => {
                        alert('Hata: ' + error);
                    });
                };
                
                document.getElementById('confirmMessage').innerHTML = 
                    `Bu rüyayı silmek istediğinizden emin misiniz?<br><br><strong>Bu işlem geri alınamaz!</strong>`;
                document.getElementById('confirmModal').style.display = 'block';
            }

            // Delete Analysis
            function deleteAnalysis(analysisId) {
                currentDeleteAction = () => {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete_analysis&analysis_id=${analysisId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Silme işlemi başarısız: ' + (data.error || 'Bilinmeyen hata'));
                        }
                    })
                    .catch(error => {
                        alert('Hata: ' + error);
                    });
                };
                
                document.getElementById('confirmMessage').innerHTML = 
                    `Bu analizi silmek istediğinizden emin misiniz?<br><br><strong>Bu işlem geri alınamaz!</strong>`;
                document.getElementById('confirmModal').style.display = 'block';
            }

            // View Dream Details
            function viewDream(dreamId) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=get_dream_detail&dream_id=${dreamId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('modalTitle').textContent = 'Rüya Detayları';
                        document.getElementById('modalBody').innerHTML = `
                            <h3>📋 Genel Bilgiler</h3>
                            <p><strong>ID:</strong> ${data.id}</p>
                            <p><strong>Kullanıcı:</strong> ${data.username || 'Bilinmiyor'} (ID: ${data.user_id})</p>
                            <p><strong>Açılış Tarihi:</strong> ${data.open_date || 'Belirtilmemiş'}</p>
                            <p><strong>Oluşturulma:</strong> ${data.created_at}</p>
                            <p><strong>Dil:</strong> ${data.language}</p>
                            
                            <h3>🌙 Rüya Metni</h3>
                            <pre>${data.dream_text}</pre>
                            
                            ${data.analysis ? `
                                <h3>🧠 Analiz</h3>
                                <pre>${data.analysis}</pre>
                            ` : '<p><em>Bu rüya için henüz analiz yapılmamış.</em></p>'}
                        `;
                        document.getElementById('detailModal').style.display = 'block';
                    } else {
                        alert('Rüya detayları alınamadı');
                    }
                })
                .catch(error => {
                    alert('Hata: ' + error);
                });
            }

            // View Analysis Details
            function viewAnalysis(analysisId) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=get_analysis_detail&analysis_id=${analysisId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('modalTitle').textContent = 'Bilinçaltı Analizi Detayları';
                        document.getElementById('modalBody').innerHTML = `
                            <h3>📋 Genel Bilgiler</h3>
                            <p><strong>ID:</strong> ${data.id}</p>
                            <p><strong>Kullanıcı:</strong> ${data.username || 'Bilinmiyor'} (ID: ${data.user_id})</p>
                            <p><strong>Analiz Edilen Rüya Sayısı:</strong> ${data.dream_count}</p>
                            <p><strong>Oluşturulma:</strong> ${data.created_at}</p>
                            
                            <h3>🧠 Bilinçaltı Analizi</h3>
                            <pre>${data.analysis_text}</pre>
                        `;
                        document.getElementById('detailModal').style.display = 'block';
                    } else {
                        alert('Analiz detayları alınamadı');
                    }
                })
                .catch(error => {
                    alert('Hata: ' + error);
                });
            }

            // Edit User Function
            function editUser(userId, username, email, isPremium, premiumExpires) {
                const modal = document.getElementById('detailModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalBody = document.getElementById('modalBody');
                
                modalTitle.textContent = 'Kullanıcı Düzenle';
                modalBody.innerHTML = `
                    <form id="editUserForm">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; color: var(--primary-green); font-weight: 600;">Kullanıcı Adı:</label>
                            <input type="text" id="editUsername" value="${username}" style="width: 100%; padding: 10px; background: rgba(0,0,0,0.7); border: 1px solid #333; border-radius: 5px; color: #fff;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; color: var(--primary-green); font-weight: 600;">E-posta:</label>
                            <input type="email" id="editEmail" value="${email}" style="width: 100%; padding: 10px; background: rgba(0,0,0,0.7); border: 1px solid #333; border-radius: 5px; color: #fff;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; color: var(--primary-green); font-weight: 600;">Premium Durumu:</label>
                            <select id="editIsPremium" style="width: 100%; padding: 10px; background: rgba(0,0,0,0.7); border: 1px solid #333; border-radius: 5px; color: #fff;">
                                <option value="0" ${isPremium == 0 ? 'selected' : ''}>Ücretsiz</option>
                                <option value="1" ${isPremium == 1 ? 'selected' : ''}>Premium</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; color: var(--primary-green); font-weight: 600;">Premium Bitiş Tarihi:</label>
                            <input type="datetime-local" id="editPremiumExpires" value="${premiumExpires ? premiumExpires.replace(' ', 'T') : ''}" style="width: 100%; padding: 10px; background: rgba(0,0,0,0.7); border: 1px solid #333; border-radius: 5px; color: #fff;">
                        </div>
                        <div style="text-align: center; margin-top: 30px;">
                            <button type="button" onclick="saveUserEdit(${userId})" style="background: var(--primary-green); color: #000; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-right: 10px;">Kaydet</button>
                            <button type="button" onclick="closeModal()" style="background: #666; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">İptal</button>
                        </div>
                    </form>
                `;
                modal.style.display = 'block';
            }

            // Save User Edit
            function saveUserEdit(userId) {
                const username = document.getElementById('editUsername').value;
                const email = document.getElementById('editEmail').value;
                const isPremium = document.getElementById('editIsPremium').value;
                const premiumExpires = document.getElementById('editPremiumExpires').value;

                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=update_user&user_id=${userId}&username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&is_premium=${isPremium}&premium_expires_at=${encodeURIComponent(premiumExpires)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Güncelleme başarısız: ' + (data.error || 'Bilinmeyen hata'));
                    }
                })
                .catch(error => {
                    alert('Hata: ' + error);
                });
            }

            // Export Data Function
            function exportData(type) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=export_data&type=${type}`
                })
                .then(response => response.json())
                .then(data => {
                    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${type}_export_${new Date().toISOString().split('T')[0]}.json`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                })
                .catch(error => {
                    alert('Export hatası: ' + error);
                });
            }

            // Refresh Statistics
            function refreshStats() {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=get_statistics'
                })
                .then(response => response.json())
                .then(data => {
                    // Update stats cards
                    const statsCards = document.querySelectorAll('.stat-card .stat-number');
                    if (statsCards.length >= 6) {
                        statsCards[0].textContent = data.total_users;
                        statsCards[1].textContent = data.premium_users;
                        statsCards[2].textContent = data.total_dreams;
                        statsCards[3].textContent = data.total_analyses;
                        statsCards[4].textContent = data.total_categories || 0;
                        statsCards[5].textContent = data.expired_premium || 0;
                    }
                })
                .catch(error => {
                    console.error('Stats refresh error:', error);
                });
            }

            // Modal Functions
            function closeModal() {
                document.getElementById('detailModal').style.display = 'none';
            }

            function closeConfirm() {
                document.getElementById('confirmModal').style.display = 'none';
                currentDeleteAction = null;
            }

            // Confirmation Yes Button
            document.getElementById('confirmYes').onclick = function() {
                if (currentDeleteAction) {
                    currentDeleteAction();
                    closeConfirm();
                }
            };

            // Close modals when clicking outside
            window.onclick = function(event) {
                const detailModal = document.getElementById('detailModal');
                const confirmModal = document.getElementById('confirmModal');
                const extendModal = document.getElementById('extendModal');
                
                if (event.target === detailModal) {
                    closeModal();
                }
                if (event.target === confirmModal) {
                    closeConfirm();
                }
                if (event.target === extendModal) {
                    closeExtendModal();
                }
            };
        </script>
    <?php endif; ?>
</body>
</html>