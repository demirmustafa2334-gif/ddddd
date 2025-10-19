<?php
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $excerpt = sanitizeInput($_POST['excerpt'] ?? '');
    $featured_image = $_FILES['featured_image']['name'] ?? '';
    $city_id = (int)($_POST['city_id'] ?? 0);
    $district_id = (int)($_POST['district_id'] ?? 0);
    $category = sanitizeInput($_POST['category'] ?? 'tourism');
    $tags = sanitizeInput($_POST['tags'] ?? '');
    $seo_keywords = sanitizeInput($_POST['seo_keywords'] ?? '');
    $meta_description = sanitizeInput($_POST['meta_description'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $slug = createSlug($title);
    
    if ($action === 'add') {
        // Handle image upload
        $imagePath = '';
        if (!empty($featured_image)) {
            $imagePath = uploadImage($_FILES['featured_image'], '../assets/images/blog/');
            if (!$imagePath) {
                $error = 'Resim yüklenirken hata oluştu.';
            }
        }
        
        if (empty($error)) {
            $sql = "INSERT INTO blog_posts (title, slug, content, excerpt, featured_image, author_id, city_id, district_id, category, tags, seo_keywords, meta_description, is_published, is_featured, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            $publishedAt = $is_published ? date('Y-m-d H:i:s') : null;
            $tagsJson = json_encode(array_map('trim', explode(',', $tags)));
            
            if ($stmt->execute([
                $title, $slug, $content, $excerpt, $imagePath, $userId, $city_id, $district_id,
                $category, $tagsJson, $seo_keywords, $meta_description, $is_published, $is_featured, $publishedAt
            ])) {
                $success = 'Blog yazısı başarıyla eklendi.';
                $action = 'list';
            } else {
                $error = 'Blog yazısı eklenirken hata oluştu.';
            }
        }
    } elseif ($action === 'edit' && $id) {
        // Handle image upload
        $imagePath = '';
        if (!empty($featured_image)) {
            $imagePath = uploadImage($_FILES['featured_image'], '../assets/images/blog/');
            if (!$imagePath) {
                $error = 'Resim yüklenirken hata oluştu.';
            }
        }
        
        if (empty($error)) {
            $sql = "UPDATE blog_posts SET title = ?, slug = ?, content = ?, excerpt = ?, city_id = ?, district_id = ?, category = ?, tags = ?, seo_keywords = ?, meta_description = ?, is_published = ?, is_featured = ?";
            $params = [$title, $slug, $content, $excerpt, $city_id, $district_id, $category, json_encode(array_map('trim', explode(',', $tags))), $seo_keywords, $meta_description, $is_published, $is_featured];
            
            if ($imagePath) {
                $sql .= ", featured_image = ?";
                $params[] = $imagePath;
            }
            
            if ($is_published && !$post['is_published']) {
                $sql .= ", published_at = ?";
                $params[] = date('Y-m-d H:i:s');
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            
            if ($stmt->execute($params)) {
                $success = 'Blog yazısı başarıyla güncellendi.';
                $action = 'list';
            } else {
                $error = 'Blog yazısı güncellenirken hata oluştu.';
            }
        }
    } elseif ($action === 'delete' && $id) {
        $stmt = $db->prepare("DELETE FROM blog_posts WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success = 'Blog yazısı başarıyla silindi.';
        } else {
            $error = 'Blog yazısı silinirken hata oluştu.';
        }
        $action = 'list';
    }
}

// Get blog post data for edit
$post = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

// Get blog posts list
if ($action === 'list') {
    $page = (int)($_GET['page'] ?? 1);
    $limit = 10;
    $offset = ($page - 1) * $limit;
    
    $posts = $db->query("
        SELECT bp.*, c.name as city_name, d.name as district_name 
        FROM blog_posts bp 
        LEFT JOIN cities c ON bp.city_id = c.id 
        LEFT JOIN districts d ON bp.district_id = d.id 
        ORDER BY bp.created_at DESC 
        LIMIT $limit OFFSET $offset
    ")->fetchAll();
    
    $totalPosts = $db->query("SELECT COUNT(*) as count FROM blog_posts")->fetch()['count'];
    $totalPages = ceil($totalPosts / $limit);
}

// Get cities and districts for dropdowns
$cities = $db->query("SELECT * FROM cities WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
$districts = $db->query("SELECT * FROM districts WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
?>

<div class="content-area">
    <?php if ($action === 'list'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Blog Yazıları</h2>
        <a href="?page=blog&action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Blog Yazısı
        </a>
    </div>
    
    <?php if (isset($success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <table class="table">
        <thead>
            <tr>
                <th>Resim</th>
                <th>Başlık</th>
                <th>Kategori</th>
                <th>Şehir/İlçe</th>
                <th>Durum</th>
                <th>Görüntüleme</th>
                <th>Tarih</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td>
                    <?php if ($post['featured_image']): ?>
                    <img src="../assets/images/blog/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                    <?php else: ?>
                    <div style="width: 50px; height: 50px; background: var(--border-color); border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image" style="color: var(--text-light);"></i>
                    </div>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo truncateText($post['title'], 40); ?></strong>
                    <br><small style="color: var(--text-light);"><?php echo $post['slug']; ?></small>
                </td>
                <td>
                    <span class="badge badge-info"><?php echo ucfirst($post['category']); ?></span>
                </td>
                <td>
                    <?php if ($post['city_name']): ?>
                    <?php echo $post['city_name']; ?>
                    <?php if ($post['district_name']): ?>
                    <br><small style="color: var(--text-light);"><?php echo $post['district_name']; ?></small>
                    <?php endif; ?>
                    <?php else: ?>
                    -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($post['is_published']): ?>
                    <span class="badge badge-success">Yayında</span>
                    <?php else: ?>
                    <span class="badge badge-warning">Taslak</span>
                    <?php endif; ?>
                    <?php if ($post['is_featured']): ?>
                    <br><span class="badge badge-info">Öne Çıkan</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $post['view_count']; ?></td>
                <td><?php echo formatDate($post['created_at']); ?></td>
                <td>
                    <a href="/blog/<?php echo $post['slug']; ?>" target="_blank" class="btn btn-info" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-eye"></i> Görüntüle
                    </a>
                    <a href="?page=blog&action=edit&id=<?php echo $post['id']; ?>" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                    <a href="?page=blog&action=delete&id=<?php echo $post['id']; ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" onclick="return confirm('Bu blog yazısını silmek istediğinizden emin misiniz?')">
                        <i class="fas fa-trash"></i> Sil
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination" style="margin-top: 2rem; text-align: center;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=blog&p=<?php echo $i; ?>" class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-secondary'; ?>" style="margin: 0 0.25rem;">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><?php echo $action === 'add' ? 'Yeni Blog Yazısı' : 'Blog Yazısı Düzenle'; ?></h2>
        <a href="?page=blog" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
        <div class="form-row">
            <div class="form-group">
                <label for="title" class="form-label">Başlık *</label>
                <input type="text" id="title" name="title" class="form-input" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="category" class="form-label">Kategori *</label>
                <select id="category" name="category" class="form-select" required>
                    <option value="tourism" <?php echo ($post['category'] ?? '') === 'tourism' ? 'selected' : ''; ?>>Turizm</option>
                    <option value="cuisine" <?php echo ($post['category'] ?? '') === 'cuisine' ? 'selected' : ''; ?>>Mutfak</option>
                    <option value="culture" <?php echo ($post['category'] ?? '') === 'culture' ? 'selected' : ''; ?>>Kültür</option>
                    <option value="history" <?php echo ($post['category'] ?? '') === 'history' ? 'selected' : ''; ?>>Tarih</option>
                    <option value="nature" <?php echo ($post['category'] ?? '') === 'nature' ? 'selected' : ''; ?>>Doğa</option>
                    <option value="events" <?php echo ($post['category'] ?? '') === 'events' ? 'selected' : ''; ?>>Etkinlikler</option>
                    <option value="travel_tips" <?php echo ($post['category'] ?? '') === 'travel_tips' ? 'selected' : ''; ?>>Seyahat İpuçları</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="city_id" class="form-label">Şehir</label>
                <select id="city_id" name="city_id" class="form-select" onchange="loadDistricts()">
                    <option value="">Şehir Seçiniz</option>
                    <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $city['id']; ?>" <?php echo ($post['city_id'] ?? '') == $city['id'] ? 'selected' : ''; ?>>
                        <?php echo $city['name']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="district_id" class="form-label">İlçe</label>
                <select id="district_id" name="district_id" class="form-select">
                    <option value="">İlçe Seçiniz</option>
                    <?php if ($post && $post['city_id']): ?>
                    <?php
                    $cityDistricts = $db->query("SELECT * FROM districts WHERE city_id = {$post['city_id']} AND is_active = 1 ORDER BY name ASC")->fetchAll();
                    foreach ($cityDistricts as $district):
                    ?>
                    <option value="<?php echo $district['id']; ?>" <?php echo ($post['district_id'] ?? '') == $district['id'] ? 'selected' : ''; ?>>
                        <?php echo $district['name']; ?>
                    </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="excerpt" class="form-label">Özet *</label>
            <textarea id="excerpt" name="excerpt" class="form-textarea" rows="3" required><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="content" class="form-label">İçerik *</label>
            <textarea id="content" name="content" class="form-textarea" rows="15" required><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="featured_image" class="form-label">Öne Çıkan Resim</label>
            <input type="file" id="featured_image" name="featured_image" class="form-input" accept="image/*">
            <?php if ($post && $post['featured_image']): ?>
            <small>Mevcut resim: <?php echo $post['featured_image']; ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="tags" class="form-label">Etiketler (virgülle ayırın)</label>
                <input type="text" id="tags" name="tags" class="form-input" value="<?php echo htmlspecialchars($post['tags'] ? implode(', ', json_decode($post['tags'], true)) : ''); ?>">
            </div>
            <div class="form-group">
                <label for="seo_keywords" class="form-label">SEO Anahtar Kelimeler</label>
                <input type="text" id="seo_keywords" name="seo_keywords" class="form-input" value="<?php echo htmlspecialchars($post['seo_keywords'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="meta_description" class="form-label">Meta Açıklama</label>
            <textarea id="meta_description" name="meta_description" class="form-textarea" rows="2"><?php echo htmlspecialchars($post['meta_description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_published" <?php echo ($post['is_published'] ?? false) ? 'checked' : ''; ?>>
                    Yayınla
                </label>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_featured" <?php echo ($post['is_featured'] ?? false) ? 'checked' : ''; ?>>
                    Öne Çıkan
                </label>
            </div>
        </div>
        
        <div style="margin-top: 2rem; text-align: center;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Blog Yazısı Ekle' : 'Güncelle'; ?>
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
function loadDistricts() {
    const cityId = document.getElementById('city_id').value;
    const districtSelect = document.getElementById('district_id');
    
    // Clear existing options
    districtSelect.innerHTML = '<option value="">İlçe Seçiniz</option>';
    
    if (cityId) {
        // In a real implementation, you would make an AJAX call here
        // For now, we'll just show a message
        console.log('Loading districts for city:', cityId);
    }
}
</script>