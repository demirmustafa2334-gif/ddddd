<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($meta['title']) ? $meta['title'] : SITE_NAME . ' - ' . SITE_DESCRIPTION; ?></title>
    
    <!-- Meta Tags -->
    <meta name="description" content="<?php echo isset($meta['description']) ? $meta['description'] : SITE_DESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo isset($meta['keywords']) ? $meta['keywords'] : SITE_KEYWORDS; ?>">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo isset($meta['og_url']) ? $meta['og_url'] : SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo isset($meta['og_title']) ? $meta['og_title'] : SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo isset($meta['og_description']) ? $meta['og_description'] : SITE_DESCRIPTION; ?>">
    <meta property="og:image" content="<?php echo isset($meta['og_image']) ? $meta['og_image'] : SITE_URL . '/assets/images/og-default.jpg'; ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo isset($meta['og_url']) ? $meta['og_url'] : SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo isset($meta['twitter_title']) ? $meta['twitter_title'] : SITE_NAME; ?>">
    <meta property="twitter:description" content="<?php echo isset($meta['twitter_description']) ? $meta['twitter_description'] : SITE_DESCRIPTION; ?>">
    <meta property="twitter:image" content="<?php echo isset($meta['twitter_image']) ? $meta['twitter_image'] : SITE_URL . '/assets/images/og-default.jpg'; ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "<?php echo SITE_NAME; ?>",
        "url": "<?php echo SITE_URL; ?>",
        "description": "<?php echo SITE_DESCRIPTION; ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo SITE_URL; ?>/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="/">
                        <i class="fas fa-map-marked-alt"></i>
                        <span><?php echo SITE_NAME; ?></span>
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="/" class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '') ? 'active' : ''; ?>">
                                <i class="fas fa-home"></i>
                                Ana Sayfa
                            </a>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a href="/sehirler" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/sehirler') === 0 ? 'active' : ''; ?>">
                                <i class="fas fa-city"></i>
                                Şehirler
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-content">
                                    <?php 
                                    $cities = getCities($db, 20);
                                    foreach ($cities as $city): 
                                    ?>
                                    <a href="/sehir/<?php echo $city['slug']; ?>" class="dropdown-item">
                                        <?php echo $city['name']; ?>
                                    </a>
                                    <?php endforeach; ?>
                                    <a href="/sehirler" class="dropdown-item view-all">
                                        Tüm Şehirleri Gör <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a href="/blog" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/blog') === 0 ? 'active' : ''; ?>">
                                <i class="fas fa-blog"></i>
                                Blog
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="/iletisim" class="nav-link <?php echo $_SERVER['REQUEST_URI'] == '/iletisim' ? 'active' : ''; ?>">
                                <i class="fas fa-envelope"></i>
                                İletişim
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- Search -->
                <div class="search-container">
                    <form class="search-form" action="/search" method="GET">
                        <input type="text" name="q" class="search-input" placeholder="Şehir, ilçe veya yer ara..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-content">
            <div class="mobile-menu-header">
                <h3>Menü</h3>
                <button class="mobile-menu-close" id="mobileMenuClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="mobile-nav">
                <a href="/" class="mobile-nav-link">
                    <i class="fas fa-home"></i>
                    Ana Sayfa
                </a>
                <a href="/sehirler" class="mobile-nav-link">
                    <i class="fas fa-city"></i>
                    Şehirler
                </a>
                <a href="/blog" class="mobile-nav-link">
                    <i class="fas fa-blog"></i>
                    Blog
                </a>
                <a href="/iletisim" class="mobile-nav-link">
                    <i class="fas fa-envelope"></i>
                    İletişim
                </a>
            </nav>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="main-content">