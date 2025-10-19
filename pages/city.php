<?php
$citySlug = $_GET['citySlug'] ?? '';

// Get city data
$city = getCityBySlug($db, $citySlug);

if (!$city) {
    include 'pages/404.php';
    exit;
}

// Get districts for this city
$districts = getDistrictsByCity($db, $city['id']);

// Get blog posts for this city
$cityPosts = getBlogPosts($db, 6, 0, null, $city['id']);

// Generate meta tags
$meta = generateMetaTags(
    $city['name'],
    $city['meta_description'] ?: $city['description'],
    $city['seo_keywords'] ?: $city['name'] . ', turizm, gezilecek yerler, yerel mutfak',
    SITE_URL . '/assets/images/cities/' . $city['image']
);

include 'includes/header.php';
?>

<!-- City Header -->
<section class="city-header">
    <div class="container">
        <h1 class="city-title"><?php echo $city['name']; ?></h1>
        <p class="city-description"><?php echo $city['description']; ?></p>
        
        <?php if ($city['population'] || $city['area'] || $city['established_year']): ?>
        <div class="city-stats">
            <?php if ($city['population']): ?>
            <div class="stat-item">
                <span class="stat-number"><?php echo number_format($city['population']); ?></span>
                <div class="stat-label">Nüfus</div>
            </div>
            <?php endif; ?>
            
            <?php if ($city['area']): ?>
            <div class="stat-item">
                <span class="stat-number"><?php echo number_format($city['area'], 0); ?></span>
                <div class="stat-label">km² Alan</div>
            </div>
            <?php endif; ?>
            
            <?php if ($city['established_year']): ?>
            <div class="stat-item">
                <span class="stat-number"><?php echo $city['established_year']; ?></span>
                <div class="stat-label">Kuruluş Yılı</div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Tourist Attractions -->
<?php 
$attractions = json_decode($city['tourist_attractions'], true);
if (!empty($attractions)): 
?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Turistik Yerler</h2>
        <p class="section-subtitle"><?php echo $city['name']; ?>'da mutlaka görülmesi gereken yerler</p>
        
        <div class="attractions-grid">
            <?php foreach ($attractions as $attraction): ?>
            <div class="attraction-card">
                <img src="/assets/images/attractions/<?php echo $attraction['image']; ?>" alt="<?php echo $attraction['name']; ?>" class="attraction-image">
                <div class="attraction-content">
                    <span class="attraction-type"><?php echo ucfirst($attraction['type']); ?></span>
                    <h3 class="attraction-title"><?php echo $attraction['name']; ?></h3>
                    <p class="attraction-description"><?php echo $attraction['description']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Local Cuisine -->
<?php 
$cuisine = json_decode($city['local_cuisine'], true);
if (!empty($cuisine)): 
?>
<section class="section section-light">
    <div class="container">
        <h2 class="section-title">Yerel Mutfak</h2>
        <p class="section-subtitle"><?php echo $city['name']; ?>'nın geleneksel lezzetleri</p>
        
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
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Special Flavors -->
<?php 
$specialFlavors = json_decode($city['special_flavors'], true);
if (!empty($specialFlavors)): 
?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Özel Lezzetler</h2>
        <p class="section-subtitle"><?php echo $city['name']; ?>'ya özgü tatlar ve içecekler</p>
        
        <div class="cuisine-grid">
            <?php foreach ($specialFlavors as $flavor): ?>
            <div class="cuisine-card">
                <img src="/assets/images/flavors/<?php echo $flavor['image']; ?>" alt="<?php echo $flavor['name']; ?>" class="cuisine-image">
                <div class="cuisine-content">
                    <h3 class="cuisine-title"><?php echo $flavor['name']; ?></h3>
                    <p class="cuisine-description"><?php echo $flavor['description']; ?></p>
                    <span class="cuisine-type"><?php echo ucfirst($flavor['type']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Districts -->
<?php if (!empty($districts)): ?>
<section class="section section-light">
    <div class="container">
        <h2 class="section-title">İlçeler</h2>
        <p class="section-subtitle"><?php echo $city['name']; ?>'nın ilçelerini keşfedin</p>
        
        <div class="grid grid-3">
            <?php foreach ($districts as $district): ?>
            <div class="card">
                <img src="/assets/images/districts/<?php echo $district['image']; ?>" alt="<?php echo $district['name']; ?>" class="card-image">
                <div class="card-content">
                    <h3 class="card-title">
                        <a href="/sehir/<?php echo $city['slug']; ?>/<?php echo $district['slug']; ?>"><?php echo $district['name']; ?></a>
                    </h3>
                    <p class="card-description"><?php echo truncateText($district['description'], 100); ?></p>
                    <div class="card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo $district['name']; ?></span>
                        <a href="/sehir/<?php echo $city['slug']; ?>/<?php echo $district['slug']; ?>" class="btn btn-primary">Keşfet</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog Posts -->
<?php if (!empty($cityPosts)): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title"><?php echo $city['name']; ?> Hakkında Blog Yazıları</h2>
        <p class="section-subtitle">Bu şehir hakkında yazılmış en güncel içerikler</p>
        
        <div class="blog-grid">
            <?php foreach ($cityPosts as $post): ?>
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