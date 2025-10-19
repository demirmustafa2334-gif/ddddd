<?php
http_response_code(404);

$meta = generateMetaTags(
    'Sayfa Bulunamadı - 404',
    'Aradığınız sayfa bulunamadı. Ana sayfaya dönmek için tıklayın.',
    '404, sayfa bulunamadı, hata'
);

include 'includes/header.php';
?>

<!-- 404 Section -->
<section class="city-header">
    <div class="container text-center">
        <h1 class="city-title" style="font-size: 6rem; color: var(--accent-color); margin-bottom: 1rem;">404</h1>
        <h2 class="city-title">Sayfa Bulunamadı</h2>
        <p class="city-description">
            Aradığınız sayfa mevcut değil veya taşınmış olabilir. 
            Ana sayfaya dönmek için aşağıdaki butona tıklayabilirsiniz.
        </p>
        
        <div class="hero-buttons">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Ana Sayfaya Dön
            </a>
            <a href="/sehirler" class="btn btn-secondary">
                <i class="fas fa-city"></i>
                Şehirleri Keşfet
            </a>
        </div>
    </div>
</section>

<!-- Popular Links -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Popüler Sayfalar</h2>
        <p class="section-subtitle">Belki aradığınız içerik burada olabilir</p>
        
        <div class="grid grid-3">
            <div class="card">
                <div class="card-content">
                    <h3 class="card-title">
                        <a href="/sehirler">Tüm Şehirler</a>
                    </h3>
                    <p class="card-description">Türkiye'nin 81 ilini keşfedin</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3 class="card-title">
                        <a href="/blog">Blog Yazıları</a>
                    </h3>
                    <p class="card-description">En güncel gezi yazıları ve rehberler</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3 class="card-title">
                        <a href="/iletisim">İletişim</a>
                    </h3>
                    <p class="card-description">Bizimle iletişime geçin</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>