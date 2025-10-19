# Yerel Tanıtım - Türkiye Turizm Websitesi

Modern, responsive ve SEO-optimized Türkiye turizm websitesi. Tüm şehir ve ilçelerin detaylı tanıtımı, yerel mutfak, turistik yerler ve blog sistemi ile kapsamlı bir platform.

## Özellikler

### 🏙️ Şehir ve İlçe Yönetimi
- 81 il ve yüzlerce ilçe için detaylı sayfalar
- Turistik yerler, yerel mutfak ve özel lezzetler
- Kültürel özellikler ve konaklama bilgileri
- Ulaşım rehberleri

### 📝 Blog Sistemi
- Kategorize edilmiş blog yazıları
- SEO-optimized içerik yönetimi
- İç bağlantı sistemi
- Yorum ve etkileşim özellikleri

### 👥 Admin Paneli
- **Admin**: Tüm site ayarları ve içerik yönetimi
- **Editör**: İçerik ekleme ve düzenleme
- **Yazar**: Sadece blog yazısı yazma
- Gerçek zamanlı istatistikler
- İletişim mesajları yönetimi

### 📱 Responsive Tasarım
- Mobil ve desktop uyumlu
- Modern ve kullanıcı dostu arayüz
- Hızlı yükleme süreleri
- SEO-optimized yapı

### 🔍 SEO Özellikleri
- Meta tag optimizasyonu
- URL-friendly yapı
- Sitemap desteği
- Sosyal medya entegrasyonu

## Kurulum

### Gereksinimler
- PHP 7.4 veya üzeri
- MySQL 5.7 veya üzeri
- Apache/Nginx web sunucusu
- Composer (opsiyonel)

### Adım 1: Dosyaları İndirin
```bash
git clone https://github.com/yourusername/yereltanitim.git
cd yereltanitim
```

### Adım 2: Veritabanını Oluşturun
```sql
CREATE DATABASE yereltanitim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Adım 3: Veritabanı Ayarlarını Yapın
`config/database.php` dosyasını düzenleyin:
```php
private $host = 'localhost';
private $db_name = 'yereltanitim';
private $username = 'your_username';
private $password = 'your_password';
```

### Adım 4: Veritabanı Şemasını Oluşturun
```bash
mysql -u your_username -p yereltanitim < database/schema.sql
```

### Adım 5: Örnek Verileri Yükleyin
```bash
php database/seed.php
```

### Adım 6: Web Sunucusunu Yapılandırın
Apache için `.htaccess` dosyası:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

### Adım 7: Dosya İzinlerini Ayarlayın
```bash
chmod 755 uploads/
chmod 644 config/database.php
```

## Kullanım

### Admin Paneli
1. `http://yoursite.com/admin` adresine gidin
2. Varsayılan giriş bilgileri:
   - Kullanıcı adı: `admin`
   - Şifre: `admin123`

### İçerik Yönetimi
- **Şehirler**: Yeni şehir ekleme, düzenleme, turistik yerler ve mutfak bilgileri
- **İlçeler**: Detaylı ilçe sayfaları, kültürel özellikler, konaklama bilgileri
- **Blog**: Kategorize edilmiş yazılar, SEO ayarları, iç bağlantılar
- **İletişim**: Mesaj yönetimi, yanıtlama sistemi

### SEO Optimizasyonu
- Her sayfa için özel meta taglar
- URL-friendly yapı
- İç bağlantı sistemi
- Sitemap otomatik oluşturma

## Dosya Yapısı

```
yereltanitim/
├── admin/                  # Admin paneli
│   ├── pages/             # Admin sayfaları
│   ├── auth.php           # Giriş sistemi
│   └── index.php          # Ana admin dosyası
├── assets/                # Statik dosyalar
│   ├── css/               # CSS dosyaları
│   ├── js/                # JavaScript dosyaları
│   └── images/            # Resim dosyaları
├── config/                # Yapılandırma dosyaları
│   ├── database.php       # Veritabanı ayarları
│   └── config.php         # Genel ayarlar
├── database/              # Veritabanı dosyaları
│   ├── schema.sql         # Veritabanı şeması
│   └── seed.php           # Örnek veri yükleyici
├── includes/              # Ortak dosyalar
│   ├── functions.php      # Yardımcı fonksiyonlar
│   ├── header.php         # Sayfa başlığı
│   └── footer.php         # Sayfa altı
├── pages/                 # Ana sayfalar
│   ├── home.php           # Ana sayfa
│   ├── cities.php         # Şehirler listesi
│   ├── city.php           # Şehir detay sayfası
│   ├── district.php       # İlçe detay sayfası
│   ├── blog.php           # Blog listesi
│   ├── blog-post.php      # Blog yazısı detayı
│   ├── contact.php        # İletişim sayfası
│   └── 404.php            # Hata sayfası
├── index.php              # Ana giriş noktası
└── README.md              # Bu dosya
```

## Veritabanı Şeması

### Cities (Şehirler)
- Temel şehir bilgileri
- Turistik yerler (JSON)
- Yerel mutfak (JSON)
- Özel lezzetler (JSON)
- SEO bilgileri

### Districts (İlçeler)
- İlçe bilgileri
- Turistik yerler
- Yerel mutfak
- Kültürel özellikler
- Konaklama bilgileri
- Ulaşım bilgileri

### Blog Posts (Blog Yazıları)
- Yazı içeriği
- Kategori ve etiketler
- SEO optimizasyonu
- Yazar bilgileri
- Yayın durumu

### Contact Messages (İletişim Mesajları)
- Mesaj içeriği
- Gönderen bilgileri
- Okunma durumu
- Yanıt sistemi

### Admin Users (Yönetici Kullanıcıları)
- Kullanıcı bilgileri
- Rol sistemi
- Yetki yönetimi

## Güvenlik

- SQL injection koruması
- XSS koruması
- CSRF token sistemi
- Dosya yükleme güvenliği
- Şifre hashleme

## Performans

- Veritabanı optimizasyonu
- Resim sıkıştırma
- CSS/JS minifikasyonu
- Önbellekleme sistemi
- Lazy loading

## Katkıda Bulunma

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/AmazingFeature`)
3. Commit yapın (`git commit -m 'Add some AmazingFeature'`)
4. Push yapın (`git push origin feature/AmazingFeature`)
5. Pull Request oluşturun

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için `LICENSE` dosyasına bakın.

## İletişim

- Website: [yereltanitim.com](https://yereltanitim.com)
- Email: info@yereltanitim.com
- GitHub: [@yourusername](https://github.com/yourusername)

## Teşekkürler

- Türkiye İstatistik Kurumu (TÜİK) - Nüfus verileri
- T.C. Kültür ve Turizm Bakanlığı - Turizm bilgileri
- Açık kaynak topluluğu - Kullanılan kütüphaneler

---

**Not**: Bu proje eğitim amaçlı geliştirilmiştir. Üretim ortamında kullanmadan önce güvenlik testlerini yapın.