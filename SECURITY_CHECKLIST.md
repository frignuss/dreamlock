# DreamLock Güvenlik Kontrol Listesi

## 🛡️ Uygulanan Güvenlik Önlemleri

### ✅ Tamamlanan Güvenlik Önlemleri

#### 1. **Güvenlik Modülü (includes/security.php)**
- [x] CSRF koruması
- [x] Input sanitization
- [x] Rate limiting
- [x] Güvenli şifre hashleme (Argon2id)
- [x] Session güvenliği
- [x] Güvenlik başlıkları
- [x] Dosya yükleme validasyonu
- [x] Güvenlik event logging

#### 2. **Apache Güvenlik (.htaccess)**
- [x] Güvenlik başlıkları (X-Frame-Options, X-XSS-Protection, etc.)
- [x] Content Security Policy (CSP)
- [x] Hassas dosyalara erişim engelleme
- [x] Kötü bot engelleme
- [x] SQL injection koruması
- [x] XSS koruması
- [x] Directory browsing engelleme

#### 3. **Veritabanı Güvenliği**
- [x] Prepared statements kullanımı
- [x] Güvenli bağlantı ayarları
- [x] Audit logging tablosu
- [x] Token tabloları (remember me, password reset)
- [x] Otomatik temizlik eventleri
- [x] Güvenli stored procedures

#### 4. **Kullanıcı Kimlik Doğrulama**
- [x] Güçlü şifre politikası
- [x] Rate limiting (giriş/kayıt denemeleri)
- [x] Session hijacking koruması
- [x] Güvenli logout
- [x] Remember me fonksiyonu
- [x] CSRF token validasyonu

#### 5. **Input Validation**
- [x] Client-side validation (HTML5)
- [x] Server-side validation
- [x] Input sanitization
- [x] File upload validation
- [x] Email validation
- [x] Phone number validation

## 🔧 Kurulum Talimatları

### 1. Veritabanı Güvenlik Güncellemeleri

```bash
# MySQL'e bağlanın
mysql -u root -p dreamlock

# Güvenlik güncellemelerini çalıştırın
source database_security_updates.sql;
```

### 2. Environment Variables Ayarları

`.env` dosyası oluşturun (production ortamında):

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=dreamlock
DB_USER=dreamlock_user
DB_PASS=your_secure_password

# API Keys
OPENROUTER_API_KEY=your_openrouter_api_key
PADDLE_VENDOR_ID=your_paddle_vendor_id
PADDLE_CLIENT_SIDE_TOKEN=your_paddle_client_token
PADDLE_API_KEY=your_paddle_api_key

# Security Settings
SECURE_SESSION=true
SESSION_TIMEOUT=3600
MAX_LOGIN_ATTEMPTS=5
LOGIN_LOCKOUT_TIME=300
PASSWORD_MIN_LENGTH=8
MAX_FILE_SIZE=5242880
```

### 3. Dosya İzinleri

```bash
# Hassas dosyaları koruyun
chmod 600 .env
chmod 600 config.php
chmod 755 includes/
chmod 644 .htaccess

# Upload dizinini güvenli hale getirin
chmod 755 uploads/
chmod 644 uploads/.htaccess
```

### 4. SSL/HTTPS Kurulumu

Production ortamında SSL sertifikası kurun:

```apache
# .htaccess dosyasında HTTPS yönlendirmesini aktifleştirin
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# HSTS başlığını aktifleştirin
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
```

## 🔍 Güvenlik Testleri

### 1. Otomatik Güvenlik Taraması

```bash
# OWASP ZAP ile güvenlik taraması
zap-cli quick-scan --self-contained --start-options "-config api.disablekey=true" http://your-domain.com

# Nikto ile web sunucu taraması
nikto -h your-domain.com
```

### 2. Manuel Güvenlik Testleri

- [ ] SQL Injection testleri
- [ ] XSS testleri
- [ ] CSRF testleri
- [ ] File upload testleri
- [ ] Authentication bypass testleri
- [ ] Session hijacking testleri

### 3. Güvenlik Başlıkları Kontrolü

```bash
# Güvenlik başlıklarını kontrol edin
curl -I http://your-domain.com
```

Beklenen başlıklar:
- X-Frame-Options: DENY
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block
- Content-Security-Policy: (CSP başlığı)
- Strict-Transport-Security: (HTTPS'de)

## 📊 Güvenlik İzleme

### 1. Log İzleme

```bash
# Güvenlik loglarını izleyin
tail -f /var/log/apache2/error.log | grep "SECURITY"

# PHP error loglarını izleyin
tail -f /var/log/php/error.log
```

### 2. Veritabanı Audit Logları

```sql
-- Son güvenlik olaylarını görüntüleyin
SELECT * FROM security_audit_log 
ORDER BY created_at DESC 
LIMIT 50;

-- Başarısız giriş denemelerini izleyin
SELECT * FROM security_audit_log 
WHERE event_type = 'login_failed' 
ORDER BY created_at DESC;
```

## 🚨 Acil Durum Prosedürleri

### 1. Güvenlik İhlali Tespit Edildiğinde

1. **Hemen yanıt verin:**
   - Sunucuyu geçici olarak kapatın
   - Tüm şifreleri sıfırlayın
   - Logları analiz edin

2. **İhlali belgeleyin:**
   - Zaman damgası
   - Etkilenen sistemler
   - Potansiyel veri kaybı
   - Saldırı vektörü

3. **Güvenliği geri yükleyin:**
   - Güvenlik yamalarını uygulayın
   - Şifreleri değiştirin
   - Sistemleri yeniden başlatın

### 2. Veri Sızıntısı Durumunda

1. **Veri sızıntısını sınırlayın:**
   - Etkilenen hesapları dondurun
   - API anahtarlarını değiştirin
   - Veritabanı bağlantılarını kesin

2. **Kullanıcıları bilgilendirin:**
   - Şeffaf iletişim
   - Şifre değiştirme talimatları
   - Güvenlik önerileri

## 🔄 Düzenli Güvenlik Bakımı

### Haftalık Kontroller
- [ ] Güvenlik loglarını inceleyin
- [ ] Başarısız giriş denemelerini kontrol edin
- [ ] Sistem güncellemelerini kontrol edin
- [ ] Yedekleme durumunu kontrol edin

### Aylık Kontroller
- [ ] Güvenlik başlıklarını test edin
- [ ] SSL sertifikasını kontrol edin
- [ ] Veritabanı güvenlik ayarlarını gözden geçirin
- [ ] Kullanıcı izinlerini kontrol edin

### Yıllık Kontroller
- [ ] Kapsamlı güvenlik denetimi
- [ ] Güvenlik politikalarını güncelleyin
- [ ] Personel eğitimini planlayın
- [ ] Acil durum planlarını gözden geçirin

## 📞 Güvenlik Desteği

Güvenlik sorunları için:
- **E-posta:** security@dreamlock.com
- **Telefon:** +90 XXX XXX XX XX
- **Acil durum:** 7/24 destek hattı

## 📚 Ek Kaynaklar

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [MySQL Security Guidelines](https://dev.mysql.com/doc/refman/8.0/en/security.html)
- [Apache Security](https://httpd.apache.org/docs/2.4/security/)

---

**Son güncelleme:** <?= date('Y-m-d') ?>
**Güvenlik seviyesi:** Yüksek
**Sonraki gözden geçirme:** <?= date('Y-m-d', strtotime('+1 month')) ?>

