<?php
$meta = generateMetaTags(
    'Tüm Şehirler',
    'Türkiye\'nin 81 ilini keşfedin. Her şehrin kendine özgü kültürü, tarihi ve lezzetleri.',
    'türkiye şehirleri, 81 il, şehir rehberi, turizm, yerel kültür'
);

// Get all cities
$cities = getCities($db);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="city-header">
    <div class="container">
        <h1 class="city-title">Türkiye'nin 81 İli</h1>
        <p class="city-description">
            Türkiye'nin her köşesinde sizi bekleyen güzellikleri keşfedin. 
            Her şehrin kendine özgü kültürü, tarihi ve lezzetleri ile tanışın.
        </p>
    </div>
</section>

<!-- Cities Grid -->
<section class="section">
    <div class="container">
        <div class="grid grid-4">
            <?php foreach ($cities as $city): ?>
            <div class="card">
                <img src="/assets/images/cities/<?php echo $city['image']; ?>" alt="<?php echo $city['name']; ?>" class="card-image">
                <div class="card-content">
                    <h3 class="card-title">
                        <a href="/sehir/<?php echo $city['slug']; ?>"><?php echo $city['name']; ?></a>
                    </h3>
                    <p class="card-description"><?php echo truncateText($city['description'], 100); ?></p>
                    <div class="card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo $city['name']; ?></span>
                        <a href="/sehir/<?php echo $city['slug']; ?>" class="btn btn-primary">Keşfet</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SEO Content -->
<section class="section section-light">
    <div class="container">
        <h2 class="section-title">Türkiye'nin Tüm Şehirleri</h2>
        <div class="text-center">
            <p>
                Türkiye, 81 ili ile zengin bir kültürel ve coğrafi çeşitliliğe sahiptir. 
                Her şehir kendine özgü tarihi, kültürel ve doğal güzellikleri ile ziyaretçilerini büyüler.
            </p>
            <p>
                <strong>Bölgelere Göre Şehirler:</strong><br>
                <strong>Marmara Bölgesi:</strong> İstanbul, Bursa, Kocaeli, Sakarya, Tekirdağ, Edirne, Kırklareli<br>
                <strong>Ege Bölgesi:</strong> İzmir, Manisa, Aydın, Denizli, Muğla, Uşak, Afyonkarahisar<br>
                <strong>Akdeniz Bölgesi:</strong> Antalya, Mersin, Adana, Hatay, Kahramanmaraş, Osmaniye<br>
                <strong>İç Anadolu Bölgesi:</strong> Ankara, Konya, Kayseri, Sivas, Yozgat, Aksaray, Niğde, Nevşehir, Kırıkkale, Kırşehir<br>
                <strong>Karadeniz Bölgesi:</strong> Trabzon, Samsun, Ordu, Giresun, Rize, Artvin, Gümüşhane, Bayburt<br>
                <strong>Doğu Anadolu Bölgesi:</strong> Erzurum, Van, Malatya, Elazığ, Tunceli, Bingöl, Muş, Bitlis, Ağrı, Kars, Iğdır, Ardahan<br>
                <strong>Güneydoğu Anadolu Bölgesi:</strong> Gaziantep, Şanlıurfa, Diyarbakır, Mardin, Batman, Siirt, Şırnak, Kilis, Adıyaman
            </p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>