# DreamLock Production Deployment Checklist

Bu checklist, DreamLock uygulamasını production ortamına yayınlarken yapmanız gereken tüm değişiklikleri ve kontrolleri içerir.

## 🔧 Ön Hazırlık

### 1. **Domain ve SSL Sertifikası**
- [ ] Domain adınızı satın aldınız mı?
- [ ] SSL sertifikası (Let's Encrypt ücretsiz) kuruldu mu?
- [ ] HTTPS zorunlu hale getirildi mi?

### 2. **Hosting Seçimi**
- [ ] Shared hosting mi, VPS mi, Cloud hosting mi?
- [ ] PHP 8.0+ desteği var mı?
- [ ] MySQL/MariaDB desteği var mı?
- [ ] Email gönderimi destekleniyor mu?

## 📧 Email Ayarları (En Kritik)

### 3. **SMTP Servisi Seçimi**
Aşağıdaki seçeneklerden birini kullanın:

#### **Seçenek A: Gmail SMTP (Ücretsiz)**
- [ ] Gmail hesabı oluşturun
- [ ] 2-Factor Authentication aktif edin
- [ ] App Password oluşturun
- [ ] `forgot_password.php` dosyasında email ayarlarını güncelleyin

#### **Seçenek B: SendGrid (Ücretsiz 100 email/gün)**
- [ ] SendGrid hesabı oluşturun
- [ ] API key alın
- [ ] Domain doğrulaması yapın
- [ ] `production_email_setup.php` dosyasındaki SendGrid kodunu aktif edin

#### **Seçenek C: Mailgun (Ücretsiz 5,000 email/ay)**
- [ ] Mailgun hesabı oluşturun
- [ ] Domain doğrulaması yapın
- [ ] API key alın
- [ ] `production_email_setup.php` dosyasındaki Mailgun kodunu aktif edin

### 4. **Email Test**
- [ ] Test email'i gönderin
- [ ] Spam klasörünü kontrol edin
- [ ] Email template'inin doğru göründüğünü kontrol edin

## 🗄️ Veritabanı Ayarları

### 5. **Production Database**
- [ ] Production veritabanı oluşturun
- [ ] `dreamlock.sql` dosyasını import edin
- [ ] `password_resets` tablosunu oluşturun
- [ ] Database kullanıcısı oluşturun (sadece gerekli yetkiler)
- [ ] Database backup planı oluşturun

### 6. **Database Güvenliği**
- [ ] Güçlü database şifresi kullanın
- [ ] Database kullanıcısına minimum yetki verin
- [ ] Remote database erişimini kısıtlayın
- [ ] Database loglarını aktif edin

## 🔐 Güvenlik Ayarları

### 7. **Config Dosyası Güncellemeleri**
- [ ] `ENVIRONMENT` değişkenini `'production'` yapın
- [ ] Tüm API key'leri production değerleriyle güncelleyin
- [ ] Database bilgilerini production değerleriyle güncelleyin
- [ ] SMTP ayarlarını ekleyin
- [ ] Error reporting'i kapatın
- [ ] HTTPS zorunluluğunu aktif edin

### 8. **Dosya İzinleri**
- [ ] `uploads/` klasörü: 755
- [ ] `logs/` klasörü: 755
- [ ] `config.php`: 644
- [ ] Tüm PHP dosyaları: 644
- [ ] `.htaccess`: 644

### 9. **Güvenlik Kontrolleri**
- [ ] `.htaccess` dosyası oluşturun
- [ ] Directory listing'i kapatın
- [ ] PHP error display'i kapatın
- [ ] File upload güvenliğini kontrol edin
- [ ] CSRF koruması aktif mi?
- [ ] SQL injection koruması var mı?
- [ ] XSS koruması var mı?

## 📁 Dosya Yapısı

### 10. **Dosya Organizasyonu**
- [ ] Gereksiz dosyaları silin (`test_*.php`, `debug_*.php`)
- [ ] `var/www/secret/ni.php` dosyasını silin
- [ ] Backup dosyalarını silin
- [ ] Development dosyalarını silin

### 11. **Klasör Yapısı**
```
/var/www/dreamlock/
├── assets/
├── error/
├── includes/
├── uploads/
├── logs/
├── backups/
└── public_html/ (veya www/)
```

## 🔄 Deployment Süreci

### 12. **Dosya Yükleme**
- [ ] FTP/SFTP ile dosyaları yükleyin
- [ ] Dosya izinlerini ayarlayın
- [ ] `.htaccess` dosyasını yükleyin
- [ ] `robots.txt` dosyası oluşturun

### 13. **Veritabanı Migration**
- [ ] Production database'e bağlanın
- [ ] `dreamlock.sql` dosyasını import edin
- [ ] `add_password_resets_table.sql` dosyasını çalıştırın
- [ ] Test verilerini silin

### 14. **Config Güncellemeleri**
- [ ] `config.php` dosyasını production ayarlarıyla güncelleyin
- [ ] Environment değişkenlerini ayarlayın
- [ ] API key'leri güncelleyin
- [ ] Database bilgilerini güncelleyin

## 🧪 Test Süreci

### 15. **Fonksiyon Testleri**
- [ ] Ana sayfa yükleniyor mu?
- [ ] Kayıt olma çalışıyor mu?
- [ ] Giriş yapma çalışıyor mu?
- [ ] Şifre sıfırlama çalışıyor mu?
- [ ] Email gönderimi çalışıyor mu?
- [ ] Premium özellikler çalışıyor mu?

### 16. **Güvenlik Testleri**
- [ ] HTTPS zorunlu mu?
- [ ] HTTP'den HTTPS'e yönlendirme çalışıyor mu?
- [ ] Error sayfaları doğru görünüyor mu?
- [ ] CSRF token'ları çalışıyor mu?
- [ ] Rate limiting çalışıyor mu?

### 17. **Performance Testleri**
- [ ] Sayfa yükleme hızları kabul edilebilir mi?
- [ ] Database sorguları optimize edildi mi?
- [ ] Image optimization yapıldı mı?
- [ ] Caching aktif mi?

## 📊 Monitoring ve Logging

### 18. **Log Sistemi**
- [ ] Error logları aktif mi?
- [ ] Security logları aktif mi?
- [ ] Access logları aktif mi?
- [ ] Log rotation ayarlandı mı?

### 19. **Monitoring**
- [ ] Uptime monitoring kuruldu mu?
- [ ] Error tracking (Sentry) kuruldu mu?
- [ ] Google Analytics kuruldu mu?
- [ ] Performance monitoring kuruldu mu?

## 🔄 Backup ve Maintenance

### 20. **Backup Sistemi**
- [ ] Database backup otomasyonu kuruldu mu?
- [ ] File backup otomasyonu kuruldu mu?
- [ ] Backup retention policy ayarlandı mı?
- [ ] Backup restore testi yapıldı mı?

### 21. **Maintenance**
- [ ] Maintenance mode sistemi kuruldu mu?
- [ ] Cron job'lar ayarlandı mı?
- [ ] Log cleanup otomasyonu kuruldu mu?
- [ ] Database optimization otomasyonu kuruldu mu?

## 🚀 Go-Live

### 22. **Son Kontroller**
- [ ] Tüm testler geçti mi?
- [ ] SSL sertifikası aktif mi?
- [ ] DNS ayarları doğru mu?
- [ ] Email gönderimi çalışıyor mu?
- [ ] Database bağlantısı stabil mi?

### 23. **Launch**
- [ ] Siteyi canlıya alın
- [ ] İlk kullanıcı testlerini yapın
- [ ] Monitoring sistemlerini kontrol edin
- [ ] Backup sistemlerini test edin

## 📋 Post-Launch

### 24. **İlk 24 Saat**
- [ ] Error loglarını kontrol edin
- [ ] Performance metriklerini izleyin
- [ ] Kullanıcı geri bildirimlerini toplayın
- [ ] Gerekli düzeltmeleri yapın

### 25. **İlk Hafta**
- [ ] Tüm özelliklerin çalıştığını doğrulayın
- [ ] Security audit yapın
- [ ] Performance optimization yapın
- [ ] User feedback'lerini değerlendirin

## 🔧 Önemli Dosyalar

### 26. **Güncellenmesi Gereken Dosyalar**
- [ ] `config.php` - Production ayarları
- [ ] `forgot_password.php` - Email ayarları
- [ ] `.htaccess` - Security ve redirect ayarları
- [ ] `robots.txt` - SEO ayarları
- [ ] `sitemap.xml` - SEO ayarları

### 27. **Silinmesi Gereken Dosyalar**
- [ ] `test_*.php` dosyaları
- [ ] `debug_*.php` dosyaları
- [ ] `var/www/secret/ni.php`
- [ ] Development dosyaları
- [ ] Backup dosyaları

## 📞 Destek

### 28. **Destek Sistemi**
- [ ] Contact form çalışıyor mu?
- [ ] Email support aktif mi?
- [ ] Documentation hazır mı?
- [ ] FAQ sayfası var mı?

Bu checklist'i takip ederek DreamLock uygulamanızı güvenli ve stabil bir şekilde production ortamına yayınlayabilirsiniz. Her maddeyi kontrol ettikten sonra işaretleyin ve gerekli notları alın.








