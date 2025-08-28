# DreamLock Password Reset Setup

Bu dokümantasyon, DreamLock uygulamasında şifre sıfırlama özelliğinin nasıl kurulacağını ve kullanılacağını açıklar.

## Kurulum Adımları

### 1. Veritabanı Tablosu Ekleme

Şifre sıfırlama özelliği için gerekli veritabanı tablosunu ekleyin:

```sql
-- add_password_resets_table.sql dosyasını çalıştırın
-- veya aşağıdaki SQL komutunu manuel olarak çalıştırın:

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  KEY `expires_at` (`expires_at`),
  KEY `used` (`used`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Performans için indeksler
CREATE INDEX `idx_password_resets_token_expires` ON `password_resets` (`token`, `expires_at`);
CREATE INDEX `idx_password_resets_user_expires` ON `password_resets` (`user_id`, `expires_at`);
```

### 2. Email Ayarları

Şifre sıfırlama email'lerinin gönderilebilmesi için sunucunuzda PHP mail fonksiyonunun çalıştığından emin olun.

**XAMPP için:**
- `php.ini` dosyasında `sendmail_path` ayarını kontrol edin
- SMTP ayarlarını yapılandırın

**Production için:**
- SMTP servisi (Gmail, SendGrid, vb.) kullanmanız önerilir
- `forgot_password.php` dosyasındaki mail fonksiyonunu SMTP kütüphanesi ile değiştirin

### 3. Dosya Yapısı

Aşağıdaki dosyalar oluşturulmuştur:

- `login.php` - Şifre sıfırlama modal'ı eklendi
- `forgot_password.php` - Şifre sıfırlama isteği sayfası
- `reset_password.php` - Yeni şifre belirleme sayfası
- `add_password_resets_table.sql` - Veritabanı tablosu

## Kullanım

### Kullanıcı Tarafı

1. **Şifre Sıfırlama İsteği:**
   - Login sayfasında "I forgot my password" linkine tıklayın
   - Email adresinizi girin
   - "Reset Password" butonuna tıklayın

2. **Email Kontrolü:**
   - Email kutunuzu kontrol edin
   - DreamLock'tan gelen şifre sıfırlama linkine tıklayın

3. **Yeni Şifre Belirleme:**
   - Yeni şifrenizi girin
   - Şifreyi tekrar girin
   - "Şifreyi Güncelle" butonuna tıklayın

### Güvenlik Özellikleri

- **CSRF Koruması:** Tüm formlarda CSRF token kullanılır
- **Token Güvenliği:** 64 karakterlik güvenli token'lar oluşturulur
- **Süre Sınırı:** Token'lar 24 saat geçerlidir
- **Tek Kullanım:** Her token sadece bir kez kullanılabilir
- **Rate Limiting:** Şifre sıfırlama istekleri sınırlandırılır
- **Güvenlik Logları:** Tüm işlemler loglanır

### Email Şablonu

Şifre sıfırlama email'i HTML formatında gönderilir ve şunları içerir:
- DreamLock logosu ve marka kimliği
- Kişiselleştirilmiş selamlama
- Güvenli şifre sıfırlama linki
- 24 saat geçerlilik uyarısı
- Güvenlik bilgilendirmesi

## Bakım

### Eski Token'ları Temizleme

Veritabanında eski ve kullanılmamış token'ları temizlemek için:

```sql
-- Süresi dolmuş ve kullanılmamış token'ları sil
DELETE FROM password_resets 
WHERE expires_at < NOW() AND used = 0;

-- Bu komutu düzenli olarak çalıştırın (örn: günlük cron job)
```

### Log Kontrolü

Güvenlik loglarını kontrol etmek için:
- `includes/security.php` dosyasındaki `logSecurityEvent` fonksiyonunu inceleyin
- Şifre sıfırlama ile ilgili log kayıtlarını takip edin

## Sorun Giderme

### Email Gönderilmiyor
1. PHP mail fonksiyonunun çalıştığını kontrol edin
2. Sunucu SMTP ayarlarını kontrol edin
3. Spam klasörünü kontrol edin

### Token Geçersiz
1. Token'ın 24 saat içinde kullanıldığından emin olun
2. Token'ın daha önce kullanılmadığından emin olun
3. Veritabanı bağlantısını kontrol edin

### Veritabanı Hatası
1. `password_resets` tablosunun oluşturulduğunu kontrol edin
2. Foreign key kısıtlamalarını kontrol edin
3. Veritabanı bağlantı ayarlarını kontrol edin

## Güvenlik Notları

- Şifre sıfırlama linkleri HTTPS üzerinden gönderilir
- Token'lar kriptografik olarak güvenli random değerlerdir
- Email adresinin varlığı gizlenir (güvenlik için)
- Tüm işlemler güvenlik loglarında kayıt altına alınır
- Rate limiting ile brute force saldırıları önlenir

## Geliştirme

Bu sistem modüler olarak tasarlanmıştır ve kolayca genişletilebilir:

- Email şablonları özelleştirilebilir
- Token süreleri ayarlanabilir
- Ek güvenlik katmanları eklenebilir
- SMS doğrulama eklenebilir








