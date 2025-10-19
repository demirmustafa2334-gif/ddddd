<?php
$postSlug = $_GET['postSlug'] ?? '';

// Get blog post
$post = getBlogPostBySlug($db, $postSlug);

if (!$post) {
    include 'pages/404.php';
    exit;
}

// Increment view count
$stmt = $db->prepare("UPDATE blog_posts SET view_count = view_count + 1 WHERE id = ?");
$stmt->execute([$post['id']]);

// Get related districts if city is specified
$relatedDistricts = [];
if ($post['city_id']) {
    $relatedDistricts = getRelatedDistricts($db, $post['city_id']);
}

// Get related posts
$relatedPosts = getBlogPosts($db, 3, 0, $post['category'], $post['city_id']);
$relatedPosts = array_filter($relatedPosts, function($p) use ($post) {
    return $p['id'] != $post['id'];
});
$relatedPosts = array_slice($relatedPosts, 0, 3);

// Generate meta tags
$meta = generateMetaTags(
    $post['title'],
    $post['meta_description'] ?: $post['excerpt'],
    $post['seo_keywords'] ?: $post['title'] . ', blog, türkiye, turizm',
    SITE_URL . '/assets/images/blog/' . $post['featured_image']
);

include 'includes/header.php';
?>

<!-- Blog Post Header -->
<section class="city-header">
    <div class="container">
        <div class="text-center">
            <div class="blog-category"><?php echo ucfirst($post['category']); ?></div>
            <h1 class="city-title"><?php echo $post['title']; ?></h1>
            <div class="blog-meta">
                <span><i class="fas fa-calendar"></i> <?php echo formatDate($post['published_at']); ?></span>
                <?php if ($post['city_name']): ?>
                <span><i class="fas fa-map-marker-alt"></i> <?php echo $post['city_name']; ?></span>
                <?php endif; ?>
                <span><i class="fas fa-eye"></i> <?php echo $post['view_count']; ?> görüntüleme</span>
            </div>
        </div>
    </div>
</section>

<!-- Blog Post Content -->
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 3rem;">
            <!-- Main Content -->
            <div class="blog-content">
                <img src="/assets/images/blog/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>" class="card-image" style="margin-bottom: 2rem;">
                
                <div class="blog-post-content">
                    <?php echo $post['content']; ?>
                </div>
                
                <!-- Related Districts -->
                <?php if (!empty($relatedDistricts)): ?>
                <div class="mt-5">
                    <h3>İlgili İlçeler</h3>
                    <p>Bu şehirdeki diğer ilçeleri de keşfedin:</p>
                    <div class="grid grid-3">
                        <?php foreach ($relatedDistricts as $district): ?>
                        <div class="card">
                            <img src="/assets/images/districts/<?php echo $district['image']; ?>" alt="<?php echo $district['name']; ?>" class="card-image">
                            <div class="card-content">
                                <h4 class="card-title">
                                    <a href="/sehir/<?php echo $post['city_slug']; ?>/<?php echo $district['slug']; ?>"><?php echo $district['name']; ?></a>
                                </h4>
                                <p class="card-description"><?php echo truncateText($district['description'], 80); ?></p>
                                <a href="/sehir/<?php echo $post['city_slug']; ?>/<?php echo $district['slug']; ?>" class="btn btn-primary">Keşfet</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="blog-sidebar">
                <!-- Author Info -->
                <div class="card">
                    <div class="card-content">
                        <h3>Yazar Hakkında</h3>
                        <p>Bu yazı Yerel Tanıtım editörleri tarafından hazırlanmıştır.</p>
                    </div>
                </div>
                
                <!-- Related Posts -->
                <?php if (!empty($relatedPosts)): ?>
                <div class="card">
                    <div class="card-content">
                        <h3>İlgili Yazılar</h3>
                        <?php foreach ($relatedPosts as $relatedPost): ?>
                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                            <h4 style="font-size: 1rem; margin-bottom: 0.5rem;">
                                <a href="/blog/<?php echo $relatedPost['slug']; ?>"><?php echo $relatedPost['title']; ?></a>
                            </h4>
                            <p style="font-size: 0.9rem; color: var(--text-light);"><?php echo truncateText($relatedPost['excerpt'], 80); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Categories -->
                <div class="card">
                    <div class="card-content">
                        <h3>Kategoriler</h3>
                        <div class="footer-links">
                            <a href="/blog?category=tourism" class="footer-link">Turizm</a>
                            <a href="/blog?category=cuisine" class="footer-link">Mutfak</a>
                            <a href="/blog?category=culture" class="footer-link">Kültür</a>
                            <a href="/blog?category=history" class="footer-link">Tarih</a>
                            <a href="/blog?category=nature" class="footer-link">Doğa</a>
                            <a href="/blog?category=events" class="footer-link">Etkinlikler</a>
                            <a href="/blog?category=travel_tips" class="footer-link">Seyahat İpuçları</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Posts -->
<?php if (!empty($relatedPosts)): ?>
<section class="section section-light">
    <div class="container">
        <h2 class="section-title">İlgili Yazılar</h2>
        <p class="section-subtitle">Bu konuyla ilgili diğer yazılarımız</p>
        
        <div class="blog-grid">
            <?php foreach ($relatedPosts as $relatedPost): ?>
            <article class="blog-card">
                <img src="/assets/images/blog/<?php echo $relatedPost['featured_image']; ?>" alt="<?php echo $relatedPost['title']; ?>" class="blog-image">
                <div class="blog-content">
                    <div class="blog-category"><?php echo ucfirst($relatedPost['category']); ?></div>
                    <h3 class="blog-title">
                        <a href="/blog/<?php echo $relatedPost['slug']; ?>"><?php echo $relatedPost['title']; ?></a>
                    </h3>
                    <p class="blog-excerpt"><?php echo $relatedPost['excerpt']; ?></p>
                    <div class="blog-meta">
                        <span><i class="fas fa-calendar"></i> <?php echo formatDate($relatedPost['published_at']); ?></span>
                        <span><i class="fas fa-eye"></i> <?php echo $relatedPost['view_count']; ?></span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.blog-post-content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.blog-post-content h2 {
    color: var(--primary-color);
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.blog-post-content h3 {
    color: var(--secondary-color);
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.blog-post-content p {
    margin-bottom: 1.5rem;
}

.blog-post-content ul,
.blog-post-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.blog-post-content li {
    margin-bottom: 0.5rem;
}

.blog-post-content blockquote {
    border-left: 4px solid var(--accent-color);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: var(--text-light);
}

.blog-sidebar {
    position: sticky;
    top: 100px;
}

@media (max-width: 768px) {
    .grid.grid-2 {
        grid-template-columns: 1fr;
    }
    
    .blog-sidebar {
        position: static;
        margin-top: 2rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>