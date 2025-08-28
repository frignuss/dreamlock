<?php
session_start();
require 'config.php';

// Test database connection
try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    echo "✅ Veritabanı bağlantısı başarılı<br>";
} catch (PDOException $e) {
    echo "❌ Veritabanı bağlantı hatası: " . $e->getMessage() . "<br>";
    exit();
}

// Check if users table has premium columns
try {
    $stmt = $db->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $required_columns = ['is_premium', 'premium_expires_at', 'paddle_subscription_id'];
    $missing_columns = array_diff($required_columns, $columns);
    
    if (empty($missing_columns)) {
        echo "✅ Premium sütunları mevcut<br>";
    } else {
        echo "❌ Eksik sütunlar: " . implode(', ', $missing_columns) . "<br>";
        echo "Veritabanını güncelleyin: <code>ALTER TABLE users ADD COLUMN is_premium TINYINT(1) DEFAULT 0, ADD COLUMN premium_expires_at DATETIME NULL, ADD COLUMN paddle_subscription_id VARCHAR(255) NULL;</code><br>";
    }
} catch (PDOException $e) {
    echo "❌ Tablo kontrol hatası: " . $e->getMessage() . "<br>";
}

// Check if dreams table has analysis columns
try {
    $stmt = $db->query("DESCRIBE dreams");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $required_columns = ['analysis', 'dream_type'];
    $missing_columns = array_diff($required_columns, $columns);
    
    if (empty($missing_columns)) {
        echo "✅ Dreams tablosu sütunları mevcut<br>";
    } else {
        echo "❌ Eksik dreams sütunları: " . implode(', ', $missing_columns) . "<br>";
        echo "Veritabanını güncelleyin: <code>ALTER TABLE dreams ADD COLUMN analysis TEXT NULL, ADD COLUMN dream_type VARCHAR(50) NULL;</code><br>";
    }
} catch (PDOException $e) {
    echo "❌ Dreams tablo kontrol hatası: " . $e->getMessage() . "<br>";
}

// Check Paddle configuration
echo "<br><h3>Paddle Konfigürasyonu:</h3>";
echo "Vendor ID: " . (PADDLE_VENDOR_ID !== 'your_vendor_id' ? '✅ Ayarlanmış' : '❌ Ayarlanmamış') . "<br>";
echo "Environment: " . PADDLE_ENVIRONMENT . "<br>";
echo "Product ID: " . (PADDLE_PRODUCT_ID !== 'your_product_id' ? '✅ Ayarlanmış' : '❌ Ayarlanmamış') . "<br>";

// Test premium user creation
if (isset($_GET['test_user'])) {
    try {
        $username = 'test_premium_user_' . time();
        $email = $username . '@test.com';
        $password = password_hash('test123', PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users (username, email, password, is_premium, premium_expires_at) VALUES (?, ?, ?, 1, DATE_ADD(NOW(), INTERVAL 1 MONTH))");
        $stmt->execute([$username, $email, $password]);
        
        echo "<br>✅ Test premium kullanıcısı oluşturuldu: " . $username . "<br>";
        echo "Şifre: test123<br>";
    } catch (PDOException $e) {
        echo "<br>❌ Test kullanıcısı oluşturma hatası: " . $e->getMessage() . "<br>";
    }
}

// Check existing users
try {
    $stmt = $db->query("SELECT COUNT(*) as total_users FROM users");
    $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
    
    $stmt = $db->query("SELECT COUNT(*) as premium_users FROM users WHERE is_premium = 1");
    $premium_users = $stmt->fetch(PDO::FETCH_ASSOC)['premium_users'];
    
    echo "<br><h3>Kullanıcı İstatistikleri:</h3>";
    echo "Toplam kullanıcı: " . $total_users . "<br>";
    echo "Premium kullanıcı: " . $premium_users . "<br>";
} catch (PDOException $e) {
    echo "<br>❌ Kullanıcı istatistikleri hatası: " . $e->getMessage() . "<br>";
}

echo "<br><h3>Test İşlemleri:</h3>";
echo "<a href='?test_user=1'>Test Premium Kullanıcısı Oluştur</a><br>";
echo "<a href='premium.php'>Premium Sayfasını Test Et</a><br>";
echo "<a href='dream.php'>Dream Sayfasını Test Et</a><br>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h3 { color: #39FF14; }
a { color: #39FF14; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>

