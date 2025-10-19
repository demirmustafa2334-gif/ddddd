<?php
$meta = generateMetaTags(
    'Ana Sayfa',
    'Türkiye\'nin tüm şehir ve ilçelerini keşfedin. Turistik yerler, yerel mutfak, kültürel özellikler ve daha fazlası.',
    'türkiye, şehirler, ilçeler, turizm, yerel mutfak, kültür, tarih, yereltanitim'
);

// Get featured cities
$featuredCities = getCities($db, 6);

// Get latest blog posts
$latestPosts = getBlogPosts($db, 4);

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Türkiye'yi Keşfedin</h1>
        <p>81 il, yüzlerce ilçe, binlerce hikaye. Türkiye'nin her köşesinde sizi bekleyen güzellikleri keşfedin.</p>
        <div class="hero-buttons">
            <a href="/sehirler" class="btn btn-white">
                <i class="fas fa-city"></i>
                Şehirleri Keşfet
            </a>
            <a href="/blog" class="btn btn-accent">
                <i class="fas fa-blog"></i>
                Blog Yazıları
            </a>
        </div>
    </div>
</section>

<!-- Featured Cities -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Öne Çıkan Şehirler</h2>
        <p class="section-subtitle">Türkiye'nin en popüler ve turistik şehirlerini keşfedin</p>
        
        <div class="grid grid-3">
            <?php foreach ($featuredCities as $city): ?>
            <div class="card">
                <img src="/assets/images/cities/<?php echo $city['image']; ?>" alt="<?php echo $city['name']; ?>" class="card-image">
                <div class="card-content">
                    <h3 class="card-title">
                        <a href="/sehir/<?php echo $city['slug']; ?>"><?php echo $city['name']; ?></a>
                    </h3>
                    <p class="card-description"><?php echo truncateText($city['description'], 120); ?></p>
                    <div class="card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo $city['name']; ?></span>
                        <a href="/sehir/<?php echo $city['slug']; ?>" class="btn btn-primary">Keşfet</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/sehirler" class="btn btn-secondary">Tüm Şehirleri Gör</a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="section section-light">
    <div class="container">
        <div class="grid grid-4">
            <div class="text-center">
                <div class="stat-number">81</div>
                <div class="stat-label">İl</div>
            </div>
            <div class="text-center">
                <div class="stat-number">973</div>
                <div class="stat-label">İlçe</div>
            </div>
            <div class="text-center">
                <div class="stat-number">1000+</div>
                <div class="stat-label">Turistik Yer</div>
            </div>
            <div class="text-center">
                <div class="stat-number">500+</div>
                <div class="stat-label">Yerel Lezzet</div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Blog Posts -->
<?php if (!empty($latestPosts)): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Son Blog Yazıları</h2>
        <p class="section-subtitle">Türkiye hakkında en güncel bilgiler ve rehberler</p>
        
        <div class="blog-grid">
            <?php foreach ($latestPosts as $post): ?>
            <article class="blog-card">
                <img src="/assets/images/blog/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>" class="blog-image">
                <div class="blog-content">
                    <div class="blog-category"><?php echo ucfirst($post['category']); ?></div>
                    <h3 class="blog-title">
                        <a href="/blog/<?php echo $post['slug']; ?>"><?php echo $post['title']; ?></a>
                    </h3>
                    <p class="blog-excerpt"><?php echo $post['excerpt']; ?></p>
                    <div class="blog-meta">
                        <span><i class="fas fa-calendar"></i> <?php echo formatDate($post['published_at']); ?></span>
                        <?php if ($post['city_name']): ?>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo $post['city_name']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/blog" class="btn btn-secondary">Tüm Yazıları Gör</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="section section-light">
    <div class="container text-center">
        <h2 class="section-title">Hikayenizi Paylaşın</h2>
        <p class="section-subtitle">Türkiye'deki deneyimlerinizi bizimle paylaşın ve diğer gezginlere ilham verin</p>
        <div class="hero-buttons">
            <a href="/iletisim" class="btn btn-primary">
                <i class="fas fa-envelope"></i>
                İletişime Geçin
            </a>
            <a href="/blog" class="btn btn-secondary">
                <i class="fas fa-pen"></i>
                Blog Yazısı Yaz
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>