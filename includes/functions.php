<?php
// Database helper functions
function getCities($db, $limit = null) {
    $sql = "SELECT * FROM cities WHERE is_active = 1 ORDER BY name ASC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCityBySlug($db, $slug) {
    $sql = "SELECT * FROM cities WHERE slug = ? AND is_active = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getDistrictsByCity($db, $cityId) {
    $sql = "SELECT * FROM districts WHERE city_id = ? AND is_active = 1 ORDER BY name ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute([$cityId]);
    return $stmt->fetchAll();
}

function getDistrictBySlug($db, $citySlug, $districtSlug) {
    $sql = "SELECT d.*, c.name as city_name, c.slug as city_slug 
            FROM districts d 
            JOIN cities c ON d.city_id = c.id 
            WHERE d.slug = ? AND c.slug = ? AND d.is_active = 1 AND c.is_active = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([$districtSlug, $citySlug]);
    return $stmt->fetch();
}

function getBlogPosts($db, $limit = null, $offset = 0, $category = null, $cityId = null) {
    $sql = "SELECT bp.*, c.name as city_name, c.slug as city_slug 
            FROM blog_posts bp 
            LEFT JOIN cities c ON bp.city_id = c.id 
            WHERE bp.is_published = 1";
    
    $params = [];
    
    if ($category) {
        $sql .= " AND bp.category = ?";
        $params[] = $category;
    }
    
    if ($cityId) {
        $sql .= " AND bp.city_id = ?";
        $params[] = $cityId;
    }
    
    $sql .= " ORDER BY bp.published_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getBlogPostBySlug($db, $slug) {
    $sql = "SELECT bp.*, c.name as city_name, c.slug as city_slug 
            FROM blog_posts bp 
            LEFT JOIN cities c ON bp.city_id = c.id 
            WHERE bp.slug = ? AND bp.is_published = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getRelatedDistricts($db, $cityId, $excludeId = null) {
    $sql = "SELECT * FROM districts WHERE city_id = ? AND is_active = 1";
    $params = [$cityId];
    
    if ($excludeId) {
        $sql .= " AND id != ?";
        $params[] = $excludeId;
    }
    
    $sql .= " ORDER BY RAND() LIMIT 3";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getContactMessages($db, $limit = null, $offset = 0) {
    $sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
    }
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUnreadContactCount($db) {
    $sql = "SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['count'];
}

// SEO helper functions
function generateMetaTags($title, $description, $keywords = '', $image = '') {
    $siteName = SITE_NAME;
    $siteUrl = SITE_URL;
    
    if (!$image) {
        $image = $siteUrl . '/assets/images/og-default.jpg';
    }
    
    $meta = [
        'title' => $title . ' - ' . $siteName,
        'description' => $description,
        'keywords' => $keywords ?: SITE_KEYWORDS,
        'og_title' => $title . ' - ' . $siteName,
        'og_description' => $description,
        'og_image' => $image,
        'og_url' => $siteUrl . $_SERVER['REQUEST_URI'],
        'twitter_title' => $title . ' - ' . $siteName,
        'twitter_description' => $description,
        'twitter_image' => $image
    ];
    
    return $meta;
}

// Security functions
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// File upload functions
function uploadImage($file, $directory = 'uploads/') {
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $filepath = $directory . $filename;
    
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filepath;
    }
    
    return false;
}

// Pagination helper
function generatePagination($currentPage, $totalPages, $baseUrl) {
    $pagination = [];
    
    if ($totalPages <= 1) {
        return $pagination;
    }
    
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($currentPage > 1) {
        $pagination[] = [
            'page' => $currentPage - 1,
            'url' => $baseUrl . '?page=' . ($currentPage - 1),
            'text' => 'Önceki',
            'active' => false
        ];
    }
    
    for ($i = $start; $i <= $end; $i++) {
        $pagination[] = [
            'page' => $i,
            'url' => $baseUrl . '?page=' . $i,
            'text' => $i,
            'active' => $i == $currentPage
        ];
    }
    
    if ($currentPage < $totalPages) {
        $pagination[] = [
            'page' => $currentPage + 1,
            'url' => $baseUrl . '?page=' . ($currentPage + 1),
            'text' => 'Sonraki',
            'active' => false
        ];
    }
    
    return $pagination;
}
?>