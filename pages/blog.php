<?php
$meta = generateMetaTags(
    'Blog',
    'Türkiye hakkında en güncel bilgiler, rehberler ve deneyimler. Şehirler, ilçeler, turistik yerler ve yerel mutfak hakkında yazılar.',
    'türkiye blog, gezi yazıları, turizm rehberi, şehir tanıtımı, yerel mutfak'
);

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = $_GET['category'] ?? null;
$city = $_GET['city'] ?? null;
$limit = POSTS_PER_PAGE;
$offset = ($page - 1) * $limit;

// Get blog posts
$posts = getBlogPosts($db, $limit, $offset, $category, $city);

// Get total count for pagination
$totalSql = "SELECT COUNT(*) as total FROM blog_posts WHERE is_published = 1";
$params = [];
if ($category) {
    $totalSql .= " AND category = ?";
    $params[] = $category;
}
if ($city) {
    $totalSql .= " AND city_id = ?";
    $params[] = $city;
}
$stmt = $db->prepare($totalSql);
$stmt->execute($params);
$totalPosts = $stmt->fetch()['total'];
$totalPages = ceil($totalPosts / $limit);

// Get categories
$categories = ['tourism', 'cuisine', 'culture', 'history', 'nature', 'events', 'travel_tips'];

// Get cities for filter
$cities = getCities($db);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="city-header">
    <div class="container">
        <h1 class="city-title">Blog</h1>
        <p class="city-description">
            Türkiye hakkında en güncel bilgiler, rehberler ve deneyimler. 
            Şehirler, ilçeler, turistik yerler ve yerel mutfak hakkında yazılar.
        </p>
    </div>
</section>

<!-- Filters -->
<section class="section section-light">
    <div class="container">
        <div class="text-center">
            <h3>Filtrele</h3>
            <div class="hero-buttons">
                <a href="/blog" class="btn <?php echo !$category ? 'btn-primary' : 'btn-secondary'; ?>">
                    Tümü
                </a>
                <?php foreach ($categories as $cat): ?>
                <a href="/blog?category=<?php echo $cat; ?>" class="btn <?php echo $category === $cat ? 'btn-primary' : 'btn-secondary'; ?>">
                    <?php echo ucfirst($cat); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts -->
<section class="section">
    <div class="container">
        <?php if (!empty($posts)): ?>
        <div class="blog-grid">
            <?php foreach ($posts as $post): ?>
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
                        <span><i class="fas fa-eye"></i> <?php echo $post['view_count']; ?></span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $category ? '&category=' . $category : ''; ?><?php echo $city ? '&city=' . $city : ''; ?>" class="btn btn-secondary">Önceki</a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
            <a href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . $category : ''; ?><?php echo $city ? '&city=' . $city : ''; ?>" class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-secondary'; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php echo $category ? '&category=' . $category : ''; ?><?php echo $city ? '&city=' . $city : ''; ?>" class="btn btn-secondary">Sonraki</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="text-center">
            <h3>Henüz blog yazısı bulunmuyor</h3>
            <p>Yakında burada harika içerikler olacak!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>