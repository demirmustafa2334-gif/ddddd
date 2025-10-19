<?php
$citySlug = $_GET['citySlug'] ?? '';
$districtSlug = $_GET['districtSlug'] ?? '';

// Get district data
$district = getDistrictBySlug($db, $citySlug, $districtSlug);

if (!$district) {
    include 'pages/404.php';
    exit;
}

// Get related districts
$relatedDistricts = getRelatedDistricts($db, $district['city_id'], $district['id']);

// Get blog posts for this district
$districtPosts = getBlogPosts($db, 4, 0, null, $district['city_id']);

// Generate meta tags
$meta = generateMetaTags(
    $district['name'] . ', ' . $district['city_name'],
    $district['meta_description'] ?: $district['description'],
    $district['seo_keywords'] ?: $district['name'] . ', ' . $district['city_name'] . ', turizm, gezilecek yerler, yerel mutfak',
    SITE_URL . '/assets/images/districts/' . $district['image']
);

include 'includes/header.php';
?>

<!-- District Header -->
<section class="city-header">
    <div class="container">
        <h1 class="city-title"><?php echo $district['name']; ?>, <?php echo $district['city_name']; ?></h1>
        <p class="city-description"><?php echo $district['description']; ?></p>
        
        <?php if ($district['population'] || $district['area']): ?>
        <div class="city-stats">
            <?php if ($district['population']): ?>
            <div class="stat-item">
                <span class="stat-number"><?php echo number_format($district['population']); ?></span>
                <div class="stat-label">Nüfus</div>
            </div>
            <?php endif; ?>
            
            <?php if ($district['area']): ?>
            <div class="stat-item">
                <span class="stat-number"><?php echo number_format($district['area'], 0); ?></span>
                <div class="stat-label">km² Alan</div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Tourist Attractions -->
<?php 
$attractions = json_decode($district['tourist_attractions'], true);
if (!empty($attractions)): 
?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Turistik Yerler</h2>
        <p class="section-subtitle"><?php echo $district['name']; ?>'da görülmesi gereken yerler</p>
        
        <div class="attractions-grid">
            <?php foreach ($attractions as $attraction): ?>
            <div class="attraction-card">
                <img src="/assets/images/attractions/<?php echo $attraction['image']; ?>" alt="<?php echo $attraction['name']; ?>" class="attraction-image">
                <div class="attraction-content">
                    <span class="attraction-type"><?php echo ucfirst($attraction['type']); ?></span>
                    <h3 class="attraction-title"><?php echo $attraction['name']; ?></h3>
                    <p class="attraction-description"><?php echo $attraction['description']; ?></p>
                    <?php if (!empty($attraction['address'])): ?>
                    <p><strong>Adres:</strong> <?php echo $attraction['address']; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($attraction['opening_hours'])): ?>
                    <p><strong>Çalışma Saatleri:</strong> <?php echo $attraction['opening_hours']; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($attraction['entry_fee'])): ?>
                    <p><strong>Giriş Ücreti:</strong> <?php echo $attraction['entry_fee']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Local Cuisine -->
<?php 
$cuisine = json_decode($district['local_cuisine'], true);
if (!empty($cuisine)): 
?>
<section class="section section-light">
    <div class="container">
        <h2 class="section-title">Yerel Mutfak</h2>
        <p class="section-subtitle"><?php echo $district['name']; ?>'nın geleneksel yemekleri</p>
        
        <div class="cuisine-grid">
            <?php foreach ($cuisine as $dish): ?>
            <div class="cuisine-card">
                <img src="/assets/images/cuisine/<?php echo $dish['image']; ?>" alt="<?php echo $dish['name']; ?>" class="cuisine-image">
                <div class="cuisine-content">
                    <h3 class="cuisine-title"><?php echo $dish['name']; ?></h3>
                    <p class="cuisine-description"><?php echo $dish['description']; ?></p>
                    <?php if (!empty($dish['ingredients'])): ?>
                    <p class="cuisine-ingredients">
                        <strong>Malzemeler:</strong> <?php echo implode(', ', $dish['ingredients']); ?>
                    </p>
                    <?php endif; ?>
                    <?php if (!empty($dish['preparation'])): ?>
                    <p class="cuisine-preparation">
                        <strong>Hazırlanışı:</strong> <?php echo $dish['preparation']; ?>
                    </p>
                    <?php endif; ?>
                    <?php if (!empty($dish['restaurant_recommendations'])): ?>
                    <p class="cuisine-restaurants">
                        <strong>Önerilen Restoranlar:</strong> <?php echo implode(', ', $dish['restaurant_recommendations']); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Special Flavors -->
<?php 
$specialFlavors = json_decode($district['special_flavors'], true);
if (!empty($specialFlavors)): 
?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Özel Lezzetler</h2>
        <p class="section-subtitle"><?php echo $district['name']; ?>'ya özgü tatlar ve içecekler</p>
        
        <div class="cuisine-grid">
            <?php foreach ($specialFlavors as $flavor): ?>
            <div class="cuisine-card">
                <img src="/assets/images/flavors/<?php echo $flavor['image']; ?>" alt="<?php echo $flavor['name']; ?>" class="cuisine-image">
                <div class="cuisine-content">
                    <h3 class="cuisine-title"><?php echo $flavor['name']; ?></h3>
                    <p class="cuisine-description"><?php echo $flavor['description']; ?></p>
                    <span class="cuisine-type"><?php echo ucfirst($flavor['type']); ?></span>
                    <?php if (!empty($flavor['where_to_find'])): ?>
                    <p class="cuisine-where">
                        <strong>Nerede Bulunur:</strong> <?php echo implode(', ', $flavor['where_to_find']); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Cultural Highlights -->
<?php 
$culturalHighlights = json_decode($district['cultural_highlights'], true);
if (!empty($culturalHighlights)): 
?>
<section class="section section-light">
    <div class="container">
        <h2 class="section-title">Kültürel Özellikler</h2>
        <p class="section-subtitle"><?php echo $district['name']; ?>'nın kültürel zenginlikleri</p>
        
        <div class="attractions-grid">
            <?php foreach ($culturalHighlights as $highlight): ?>
            <div class="attraction-card">
                <img src="/assets/images/culture/<?php echo $highlight['image']; ?>" alt="<?php echo $highlight['title']; ?>" class="attraction-image">
                <div class="attraction-content">
                    <span class="attraction-type"><?php echo ucfirst($highlight['type']); ?></span>
                    <h3 class="attraction-title"><?php echo $highlight['title']; ?></h3>
                    <p class="attraction-description"><?php echo $highlight['description']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Accommodation -->
<?php 
$accommodation = json_decode($district['accommodation'], true);
if (!empty($accommodation)): 
?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Konaklama</h2>
        <p class="section-subtitle"><?php echo $district['name']; ?>'da konaklama seçenekleri</p>
        
        <div class="cuisine-grid">
            <?php foreach ($accommodation as $place): ?>
            <div class="cuisine-card">
                <img src="/assets/images/accommodation/<?php echo $place['image'] ?? 'default.jpg'; ?>" alt="<?php echo $place['name']; ?>" class="cuisine-image">
                <div class="cuisine-content">
                    <h3 class="cuisine-title"><?php echo $place['name']; ?></h3>
                    <p class="cuisine-description"><?php echo $place['description']; ?></p>
                    <p><strong>Tür:</strong> <?php echo ucfirst($place['type']); ?></p>
                    <?php if (!empty($place['price_range'])): ?>
                    <p><strong>Fiyat Aralığı:</strong> <?php echo $place['price_range']; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($place['contact'])): ?>
                    <p><strong>İletişim:</strong> <?php echo $place['contact']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Transportation -->
<?php if (!empty($district['transportation'])): ?>
<section class="section section-light">
    <div class="container">
        <h2 class="section-title">Ulaşım</h2>
        <p class="section-subtitle"><?php echo $district['name']; ?>'a nasıl gidilir?</p>
        
        <div class="grid grid-2">
            <div class="card">
                <div class="card-content">
                    <h3>Nasıl Gidilir?</h3>
                    <p><?php echo $district['transportation']['how_to_reach'] ?? 'Ulaşım bilgisi mevcut değil.'; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <h3>Yerel Ulaşım</h3>
                    <p><?php echo $district['transportation']['local_transport'] ?? 'Yerel ulaşım bilgisi mevcut değil.'; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Related Districts -->
<?php if (!empty($relatedDistricts)): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title"><?php echo $district['city_name']; ?>'nın Diğer İlçeleri</h2>
        <p class="section-subtitle">Aynı şehirdeki diğer ilçeleri de keşfedin</p>
        
        <div class="grid grid-3">
            <?php foreach ($relatedDistricts as $relatedDistrict): ?>
            <div class="card">
                <img src="/assets/images/districts/<?php echo $relatedDistrict['image']; ?>" alt="<?php echo $relatedDistrict['name']; ?>" class="card-image">
                <div class="card-content">
                    <h3 class="card-title">
                        <a href="/sehir/<?php echo $district['city_slug']; ?>/<?php echo $relatedDistrict['slug']; ?>"><?php echo $relatedDistrict['name']; ?></a>
                    </h3>
                    <p class="card-description"><?php echo truncateText($relatedDistrict['description'], 100); ?></p>
                    <div class="card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo $relatedDistrict['name']; ?></span>
                        <a href="/sehir/<?php echo $district['city_slug']; ?>/<?php echo $relatedDistrict['slug']; ?>" class="btn btn-primary">Keşfet</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog Posts -->
<?php if (!empty($districtPosts)): ?>
<section class="section section-light">
    <div class="container">
        <h2 class="section-title"><?php echo $district['city_name']; ?> Hakkında Blog Yazıları</h2>
        <p class="section-subtitle">Bu bölge hakkında yazılmış en güncel içerikler</p>
        
        <div class="blog-grid">
            <?php foreach ($districtPosts as $post): ?>
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
                        <span><i class="fas fa-eye"></i> <?php echo $post['view_count']; ?> görüntüleme</span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>