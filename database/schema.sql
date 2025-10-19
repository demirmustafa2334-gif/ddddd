-- Yerel Tanıtım Database Schema
-- Turkish Tourism Website Database

CREATE DATABASE IF NOT EXISTS yereltanitim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE yereltanitim;

-- Cities table
CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    coordinates_lat DECIMAL(10, 8),
    coordinates_lng DECIMAL(11, 8),
    population INT,
    area DECIMAL(10, 2),
    established_year INT,
    tourist_attractions JSON,
    local_cuisine JSON,
    special_flavors JSON,
    seo_keywords TEXT,
    meta_description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Districts table
CREATE TABLE districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    coordinates_lat DECIMAL(10, 8),
    coordinates_lng DECIMAL(11, 8),
    population INT,
    area DECIMAL(10, 2),
    tourist_attractions JSON,
    local_cuisine JSON,
    special_flavors JSON,
    cultural_highlights JSON,
    accommodation JSON,
    transportation JSON,
    seo_keywords TEXT,
    meta_description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
    UNIQUE KEY unique_district_slug (city_id, slug)
);

-- Blog posts table
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(255),
    author_id INT,
    city_id INT,
    district_id INT,
    tags JSON,
    category ENUM('tourism', 'cuisine', 'culture', 'history', 'nature', 'events', 'travel_tips') DEFAULT 'tourism',
    related_districts JSON,
    seo_keywords TEXT,
    meta_description TEXT,
    is_published BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL,
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE SET NULL
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    city VARCHAR(100),
    district VARCHAR(100),
    is_read BOOLEAN DEFAULT FALSE,
    is_replied BOOLEAN DEFAULT FALSE,
    reply_message TEXT,
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'editor', 'author') DEFAULT 'author',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO admin_users (username, email, password, first_name, last_name, role) 
VALUES ('admin', 'admin@yereltanitim.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin');

-- Insert sample Turkish cities
INSERT INTO cities (name, slug, description, image, seo_keywords, meta_description) VALUES
('İstanbul', 'istanbul', 'Türkiye\'nin en büyük şehri ve kültürel başkenti. Tarihi yarımada, Boğaz manzarası ve zengin kültürel mirası ile dünyaca ünlü bir metropol.', 'istanbul.jpg', 'istanbul, türkiye, boğaz, tarihi yarımada, sultanahmet, galata', 'İstanbul şehri hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.'),
('Ankara', 'ankara', 'Türkiye\'nin başkenti ve ikinci büyük şehri. Modern yapısı, tarihi önemi ve önemli kurumları ile ülkenin siyasi merkezi.', 'ankara.jpg', 'ankara, başkent, anıtkabir, kızılay, çankaya', 'Ankara şehri hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.'),
('İzmir', 'izmir', 'Ege\'nin incisi İzmir, antik çağlardan beri önemli bir liman şehri. Modern yapısı, deniz manzarası ve zengin tarihi ile ünlü.', 'izmir.jpg', 'izmir, ege, körfez, konak, kemeraltı, alsancak', 'İzmir şehri hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.'),
('Antalya', 'antalya', 'Türkiye\'nin turizm başkenti. Akdeniz\'in mavi suları, antik kentler ve doğal güzellikleri ile dünyaca ünlü bir tatil destinasyonu.', 'antalya.jpg', 'antalya, akdeniz, kaleiçi, düden, perge, aspendos', 'Antalya şehri hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.'),
('Bursa', 'bursa', 'Osmanlı İmparatorluğu\'nun ilk başkenti. Tarihi çarşıları, kaplıcaları ve doğal güzellikleri ile ünlü bir şehir.', 'bursa.jpg', 'bursa, osmanlı, uludağ, çarşı, kaplıca, iskender', 'Bursa şehri hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.'),
('Konya', 'konya', 'Mevlana\'nın şehri. Tarihi önemi, mistik atmosferi ve geleneksel kültürü ile ünlü bir şehir.', 'konya.jpg', 'konya, mevlana, çatalhöyük, selçuklu, etli ekmek', 'Konya şehri hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.');

-- Insert sample districts for İstanbul
INSERT INTO districts (city_id, name, slug, description, image, seo_keywords, meta_description) VALUES
(1, 'Fatih', 'fatih', 'İstanbul\'un tarihi yarımadasında yer alan Fatih, Bizans ve Osmanlı dönemlerinin en önemli eserlerini barındırır.', 'fatih.jpg', 'fatih, sultanahmet, ayasofya, topkapı, kapalıçarşı', 'Fatih ilçesi hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.'),
(1, 'Beşiktaş', 'besiktas', 'Boğaz kıyısında yer alan Beşiktaş, modern yaşamın ve tarihi dokunun buluştuğu bir ilçedir.', 'besiktas.jpg', 'beşiktaş, boğaz, ortaköy, bosphorus, dolmabahçe', 'Beşiktaş ilçesi hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.'),
(1, 'Kadıköy', 'kadikoy', 'Anadolu yakasının en popüler ilçesi Kadıköy, genç nüfusu ve canlı kültürel yaşamı ile ünlüdür.', 'kadikoy.jpg', 'kadıköy, moda, bağdat caddesi, çarşı, anadolu yakası', 'Kadıköy ilçesi hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.');

-- Insert sample districts for Ankara
INSERT INTO districts (city_id, name, slug, description, image, seo_keywords, meta_description) VALUES
(2, 'Çankaya', 'cankaya', 'Ankara\'nın merkezi ilçesi Çankaya, modern yapısı ve önemli kurumları ile ünlüdür.', 'cankaya.jpg', 'çankaya, kızılay, tunalı, çukurambar, modern', 'Çankaya ilçesi hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.'),
(2, 'Altındağ', 'altindag', 'Ankara\'nın tarihi ilçesi Altındağ, Roma döneminden kalma eserler ve geleneksel yapısı ile ünlüdür.', 'altindag.jpg', 'altındağ, roma, hamamönü, ulus, tarihi', 'Altındağ ilçesi hakkında detaylı bilgiler. Tarihi yerler, turistik mekanlar, yerel mutfak ve kültürel özellikler.');

-- Insert sample blog posts
INSERT INTO blog_posts (title, slug, content, excerpt, featured_image, city_id, category, tags, is_published, published_at) VALUES
('İstanbul\'da Gezilecek En Güzel Yerler', 'istanbulda-gezilecek-en-guzel-yerler', '<h2>İstanbul\'un Tarihi Yarımadası</h2><p>İstanbul\'un kalbi olan tarihi yarımada, binlerce yıllık tarihi ile ziyaretçilerini büyülüyor...</p>', 'İstanbul\'un en güzel ve tarihi yerlerini keşfedin. Sultanahmet, Ayasofya, Topkapı Sarayı ve daha fazlası.', 'istanbul-gezilecek-yerler.jpg', 1, 'tourism', '["istanbul", "gezilecek yerler", "tarihi yarımada", "turizm"]', TRUE, NOW()),
('Antalya\'nın En Lezzetli Yemekleri', 'antalyada-en-lezzetli-yemekler', '<h2>Akdeniz Mutfağının Lezzetleri</h2><p>Antalya\'nın zengin mutfak kültürü, taze deniz ürünleri ve Akdeniz otları ile harmanlanmış...</p>', 'Antalya\'nın en lezzetli yemeklerini keşfedin. Taze balık, sebze yemekleri ve geleneksel tatlar.', 'antalya-yemekler.jpg', 4, 'cuisine', '["antalya", "yemek", "mutfak", "akdeniz", "lezzet"]', TRUE, NOW());

-- Create indexes for better performance
CREATE INDEX idx_cities_slug ON cities(slug);
CREATE INDEX idx_cities_active ON cities(is_active);
CREATE INDEX idx_districts_slug ON districts(slug);
CREATE INDEX idx_districts_city ON districts(city_id);
CREATE INDEX idx_districts_active ON districts(is_active);
CREATE INDEX idx_blog_posts_slug ON blog_posts(slug);
CREATE INDEX idx_blog_posts_published ON blog_posts(is_published);
CREATE INDEX idx_blog_posts_city ON blog_posts(city_id);
CREATE INDEX idx_blog_posts_category ON blog_posts(category);
CREATE INDEX idx_contact_messages_read ON contact_messages(is_read);
CREATE INDEX idx_contact_messages_created ON contact_messages(created_at);