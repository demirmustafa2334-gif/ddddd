# Yerel Tanıtım - Türkiye Şehir ve İlçe Rehberi

Modern, responsive ve SEO-optimized bir Türkiye turizm web sitesi. Tüm şehir ve ilçeleri, yerel mutfak, turistik yerler ve kültürel özellikler hakkında detaylı bilgiler sunar.

## 🚀 Özellikler

### Genel Özellikler
- ✅ Tamamen responsive tasarım (mobil ve masaüstü)
- ✅ Modern, şık ve kullanıcı dostu arayüz
- ✅ SEO-optimized yapı ve URL'ler
- ✅ Tamamen Türkçe içerik ve menüler
- ✅ Tüm Türk şehirlerini içeren ana menü

### İçerik ve Blog Özellikleri
- ✅ Her şehir ve ilçe için özel sayfalar
- ✅ Turistik yerler, yerel yemekler ve kültürel özellikler
- ✅ Blog sistemi ve admin paneli
- ✅ Blog yazılarında otomatik iç bağlantılar
- ✅ SEO için footer'da tüm şehir ve ilçe anahtar kelimeleri

### Admin Panel ve Otomasyon
- ✅ Blog yazıları, şehirler ve ilçeleri yönetme
- ✅ ChatGPT API entegrasyonu ile otomatik Türkçe içerik üretimi
- ✅ İletişim formu bildirimleri
- ✅ Kullanıcı yönetimi ve yetkilendirme

### Teknik Özellikler
- ✅ Modern frontend teknolojileri (React, Styled Components)
- ✅ Node.js ve Express.js backend
- ✅ MongoDB veritabanı
- ✅ SEO-friendly URL'ler ve meta etiketler
- ✅ Performans optimizasyonu ve caching

## 🛠️ Teknoloji Stack

### Frontend
- React 18
- React Router DOM
- Styled Components
- React Query
- React Helmet
- React Icons
- Framer Motion

### Backend
- Node.js
- Express.js
- MongoDB
- Mongoose
- JWT Authentication
- OpenAI API
- Multer (file upload)
- Nodemailer

## 📦 Kurulum

### Gereksinimler
- Node.js (v16 veya üzeri)
- MongoDB
- OpenAI API Key

### 1. Projeyi klonlayın
```bash
git clone <repository-url>
cd yereltanitim-website
```

### 2. Bağımlılıkları yükleyin
```bash
# Root dizinde
npm run install-all

# Veya ayrı ayrı
npm install
cd client && npm install
```

### 3. Environment değişkenlerini ayarlayın
```bash
# .env dosyası oluşturun
cp .env.example .env
```

`.env` dosyasını düzenleyin:
```env
MONGODB_URI=mongodb://localhost:27017/yereltanitim
JWT_SECRET=your-super-secret-jwt-key-here
OPENAI_API_KEY=your-openai-api-key-here
CLIENT_URL=http://localhost:3000
NODE_ENV=development
PORT=5000
```

### 4. Veritabanını başlatın
MongoDB'yi başlatın ve veritabanını seed edin:
```bash
# MongoDB'yi başlatın (yerel kurulum)
mongod

# Veritabanını seed edin
node server/scripts/seedData.js
```

### 5. Uygulamayı başlatın
```bash
# Development modunda (hem frontend hem backend)
npm run dev

# Veya ayrı ayrı
npm run server  # Backend (port 5000)
npm run client  # Frontend (port 3000)
```

## 🗄️ Veritabanı Yapısı

### Şehirler (Cities)
- Temel bilgiler (isim, açıklama, koordinatlar)
- Turistik yerler
- Yerel mutfak
- Özel lezzetler
- SEO bilgileri

### İlçeler (Districts)
- Şehir ile ilişki
- Turistik yerler (adres, çalışma saatleri, giriş ücreti)
- Yerel mutfak (malzemeler, hazırlama, önerilen yerler)
- Kültürel özellikler
- Konaklama seçenekleri
- Ulaşım bilgileri

### Blog Yazıları (BlogPosts)
- İçerik yönetimi
- Kategori ve etiketler
- Yazar bilgileri
- SEO optimizasyonu
- İlgili ilçe bağlantıları

### Kullanıcılar (Users)
- Kimlik doğrulama
- Rol tabanlı yetkilendirme
- Profil yönetimi

### İletişim Mesajları (ContactMessages)
- Form gönderimleri
- Admin bildirimleri
- Yanıt takibi

## 🎨 Tasarım Özellikleri

### Renk Paleti
- Primary: #2c5530 (Koyu yeşil)
- Secondary: #8B4513 (Kahverengi)
- Accent: #FFD700 (Altın sarısı)
- Text: #333 (Koyu gri)

### Typography
- Başlıklar: Playfair Display (serif)
- Metin: Inter (sans-serif)

### Responsive Breakpoints
- Mobile: 768px
- Tablet: 1024px
- Desktop: 1200px

## 🔧 API Endpoints

### Şehirler
- `GET /api/cities` - Tüm şehirler
- `GET /api/cities/:slug` - Şehir detayı
- `GET /api/cities/:slug/districts` - Şehir ilçeleri

### İlçeler
- `GET /api/districts` - Tüm ilçeler
- `GET /api/districts/:citySlug/:slug` - İlçe detayı

### Blog
- `GET /api/blog` - Blog yazıları
- `GET /api/blog/:slug` - Blog yazısı detayı
- `POST /api/blog` - Yeni blog yazısı (auth gerekli)

### İletişim
- `POST /api/contact` - İletişim formu
- `GET /api/contact` - Mesajları listele (admin)

### AI İçerik
- `POST /api/ai/generate-blog-post` - Blog yazısı oluştur
- `POST /api/ai/generate-city-description` - Şehir açıklaması oluştur

## 📱 Responsive Tasarım

Site tüm cihazlarda mükemmel görünüm sağlar:
- **Mobil**: Hamburger menü, dokunmatik optimizasyon
- **Tablet**: Grid düzenleri, orta boyut optimizasyonu
- **Desktop**: Tam özellikli arayüz, geniş ekran optimizasyonu

## 🔍 SEO Optimizasyonu

- Meta etiketleri ve açıklamalar
- Yapılandırılmış veri (Schema.org)
- SEO-friendly URL'ler
- Sitemap ve robots.txt
- Hızlı yükleme süreleri
- Mobil-first tasarım

## 🚀 Deployment

### Production Build
```bash
npm run build
```

### Environment Variables (Production)
```env
NODE_ENV=production
MONGODB_URI=your-production-mongodb-uri
JWT_SECRET=your-production-jwt-secret
OPENAI_API_KEY=your-openai-api-key
CLIENT_URL=https://yereltanitim.com
PORT=5000
```

### Docker (Opsiyonel)
```dockerfile
# Dockerfile örneği
FROM node:16-alpine
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build
EXPOSE 5000
CMD ["npm", "start"]
```

## 📊 Performans

- React Query ile veri caching
- Lazy loading ve code splitting
- Optimized images
- Minified CSS ve JS
- Gzip compression
- CDN ready

## 🤝 Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit edin (`git commit -m 'Add amazing feature'`)
4. Push edin (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

## 📞 İletişim

- Website: [yereltanitim.com](https://yereltanitim.com)
- Email: info@yereltanitim.com
- GitHub: [Repository Link]

## 🙏 Teşekkürler

- React ve tüm açık kaynak kütüphaneler
- Türkiye'nin güzel şehirleri ve ilçeleri
- Tüm katkıda bulunanlar

---

**Not**: Bu proje tamamen Türkçe içerik ve kullanıcı deneyimi için optimize edilmiştir. Tüm şehir ve ilçe bilgileri, yerel mutfak rehberleri ve kültürel özellikler Türkiye'nin zengin mirasını yansıtmaktadır.