<?php
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city_id = (int)($_POST['city_id'] ?? 0);
    $name = sanitizeInput($_POST['name'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $image = $_FILES['image']['name'] ?? '';
    $population = (int)($_POST['population'] ?? 0);
    $area = (float)($_POST['area'] ?? 0);
    $seo_keywords = sanitizeInput($_POST['seo_keywords'] ?? '');
    $meta_description = sanitizeInput($_POST['meta_description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle JSON data
    $tourist_attractions = [];
    $local_cuisine = [];
    $special_flavors = [];
    $cultural_highlights = [];
    $accommodation = [];
    $transportation = [];
    
    if (isset($_POST['attraction_name'])) {
        for ($i = 0; $i < count($_POST['attraction_name']); $i++) {
            if (!empty($_POST['attraction_name'][$i])) {
                $tourist_attractions[] = [
                    'name' => sanitizeInput($_POST['attraction_name'][$i]),
                    'description' => sanitizeInput($_POST['attraction_description'][$i] ?? ''),
                    'type' => sanitizeInput($_POST['attraction_type'][$i] ?? 'historical'),
                    'address' => sanitizeInput($_POST['attraction_address'][$i] ?? ''),
                    'opening_hours' => sanitizeInput($_POST['attraction_hours'][$i] ?? ''),
                    'entry_fee' => sanitizeInput($_POST['attraction_fee'][$i] ?? '')
                ];
            }
        }
    }
    
    if (isset($_POST['cuisine_name'])) {
        for ($i = 0; $i < count($_POST['cuisine_name']); $i++) {
            if (!empty($_POST['cuisine_name'][$i])) {
                $local_cuisine[] = [
                    'name' => sanitizeInput($_POST['cuisine_name'][$i]),
                    'description' => sanitizeInput($_POST['cuisine_description'][$i] ?? ''),
                    'ingredients' => array_filter(array_map('sanitizeInput', $_POST['cuisine_ingredients'][$i] ?? [])),
                    'preparation' => sanitizeInput($_POST['cuisine_preparation'][$i] ?? ''),
                    'restaurant_recommendations' => array_filter(array_map('sanitizeInput', $_POST['cuisine_restaurants'][$i] ?? []))
                ];
            }
        }
    }
    
    if (isset($_POST['flavor_name'])) {
        for ($i = 0; $i < count($_POST['flavor_name']); $i++) {
            if (!empty($_POST['flavor_name'][$i])) {
                $special_flavors[] = [
                    'name' => sanitizeInput($_POST['flavor_name'][$i]),
                    'description' => sanitizeInput($_POST['flavor_description'][$i] ?? ''),
                    'type' => sanitizeInput($_POST['flavor_type'][$i] ?? 'dessert'),
                    'where_to_find' => array_filter(array_map('sanitizeInput', $_POST['flavor_where'][$i] ?? []))
                ];
            }
        }
    }
    
    if (isset($_POST['culture_title'])) {
        for ($i = 0; $i < count($_POST['culture_title']); $i++) {
            if (!empty($_POST['culture_title'][$i])) {
                $cultural_highlights[] = [
                    'title' => sanitizeInput($_POST['culture_title'][$i]),
                    'description' => sanitizeInput($_POST['culture_description'][$i] ?? ''),
                    'type' => sanitizeInput($_POST['culture_type'][$i] ?? 'festival')
                ];
            }
        }
    }
    
    if (isset($_POST['accommodation_name'])) {
        for ($i = 0; $i < count($_POST['accommodation_name']); $i++) {
            if (!empty($_POST['accommodation_name'][$i])) {
                $accommodation[] = [
                    'name' => sanitizeInput($_POST['accommodation_name'][$i]),
                    'type' => sanitizeInput($_POST['accommodation_type'][$i] ?? 'hotel'),
                    'description' => sanitizeInput($_POST['accommodation_description'][$i] ?? ''),
                    'price_range' => sanitizeInput($_POST['accommodation_price'][$i] ?? ''),
                    'contact' => sanitizeInput($_POST['accommodation_contact'][$i] ?? '')
                ];
            }
        }
    }
    
    $transportation = [
        'how_to_reach' => sanitizeInput($_POST['transport_how'] ?? ''),
        'local_transport' => sanitizeInput($_POST['transport_local'] ?? ''),
        'car_rental' => sanitizeInput($_POST['transport_car'] ?? '')
    ];
    
    $slug = createSlug($name);
    
    if ($action === 'add') {
        // Handle image upload
        $imagePath = '';
        if (!empty($image)) {
            $imagePath = uploadImage($_FILES['image'], '../assets/images/districts/');
            if (!$imagePath) {
                $error = 'Resim yüklenirken hata oluştu.';
            }
        }
        
        if (empty($error)) {
            $sql = "INSERT INTO districts (city_id, name, slug, description, image, population, area, tourist_attractions, local_cuisine, special_flavors, cultural_highlights, accommodation, transportation, seo_keywords, meta_description, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            if ($stmt->execute([
                $city_id, $name, $slug, $description, $imagePath, $population, $area,
                json_encode($tourist_attractions), json_encode($local_cuisine), json_encode($special_flavors),
                json_encode($cultural_highlights), json_encode($accommodation), json_encode($transportation),
                $seo_keywords, $meta_description, $is_active
            ])) {
                $success = 'İlçe başarıyla eklendi.';
                $action = 'list';
            } else {
                $error = 'İlçe eklenirken hata oluştu.';
            }
        }
    } elseif ($action === 'edit' && $id) {
        // Handle image upload
        $imagePath = '';
        if (!empty($image)) {
            $imagePath = uploadImage($_FILES['image'], '../assets/images/districts/');
            if (!$imagePath) {
                $error = 'Resim yüklenirken hata oluştu.';
            }
        }
        
        if (empty($error)) {
            $sql = "UPDATE districts SET city_id = ?, name = ?, slug = ?, description = ?, population = ?, area = ?, tourist_attractions = ?, local_cuisine = ?, special_flavors = ?, cultural_highlights = ?, accommodation = ?, transportation = ?, seo_keywords = ?, meta_description = ?, is_active = ?";
            $params = [$city_id, $name, $slug, $description, $population, $area, json_encode($tourist_attractions), json_encode($local_cuisine), json_encode($special_flavors), json_encode($cultural_highlights), json_encode($accommodation), json_encode($transportation), $seo_keywords, $meta_description, $is_active];
            
            if ($imagePath) {
                $sql .= ", image = ?";
                $params[] = $imagePath;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            
            if ($stmt->execute($params)) {
                $success = 'İlçe başarıyla güncellendi.';
                $action = 'list';
            } else {
                $error = 'İlçe güncellenirken hata oluştu.';
            }
        }
    } elseif ($action === 'delete' && $id) {
        $stmt = $db->prepare("UPDATE districts SET is_active = 0 WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success = 'İlçe başarıyla silindi.';
        } else {
            $error = 'İlçe silinirken hata oluştu.';
        }
        $action = 'list';
    }
}

// Get district data for edit
$district = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM districts WHERE id = ?");
    $stmt->execute([$id]);
    $district = $stmt->fetch();
}

// Get districts list
if ($action === 'list') {
    $page = (int)($_GET['page'] ?? 1);
    $limit = 20;
    $offset = ($page - 1) * $limit;
    
    $districts = $db->query("
        SELECT d.*, c.name as city_name 
        FROM districts d 
        LEFT JOIN cities c ON d.city_id = c.id 
        ORDER BY d.name ASC 
        LIMIT $limit OFFSET $offset
    ")->fetchAll();
    
    $totalDistricts = $db->query("SELECT COUNT(*) as count FROM districts")->fetch()['count'];
    $totalPages = ceil($totalDistricts / $limit);
}

// Get cities for dropdown
$cities = $db->query("SELECT * FROM cities WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
?>

<div class="content-area">
    <?php if ($action === 'list'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>İlçeler</h2>
        <a href="?page=districts&action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni İlçe Ekle
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
                <th>İlçe Adı</th>
                <th>Şehir</th>
                <th>Açıklama</th>
                <th>Nüfus</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($districts as $district): ?>
            <tr>
                <td>
                    <?php if ($district['image']): ?>
                    <img src="../assets/images/districts/<?php echo $district['image']; ?>" alt="<?php echo $district['name']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                    <?php else: ?>
                    <div style="width: 50px; height: 50px; background: var(--border-color); border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image" style="color: var(--text-light);"></i>
                    </div>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo $district['name']; ?></strong>
                    <br><small style="color: var(--text-light);"><?php echo $district['slug']; ?></small>
                </td>
                <td><?php echo $district['city_name']; ?></td>
                <td><?php echo truncateText($district['description'], 50); ?></td>
                <td><?php echo $district['population'] ? number_format($district['population']) : '-'; ?></td>
                <td>
                    <?php if ($district['is_active']): ?>
                    <span class="badge badge-success">Aktif</span>
                    <?php else: ?>
                    <span class="badge badge-danger">Pasif</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="?page=districts&action=edit&id=<?php echo $district['id']; ?>" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                    <a href="?page=districts&action=delete&id=<?php echo $district['id']; ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" onclick="return confirm('Bu ilçeyi silmek istediğinizden emin misiniz?')">
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
        <a href="?page=districts&p=<?php echo $i; ?>" class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-secondary'; ?>" style="margin: 0 0.25rem;">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><?php echo $action === 'add' ? 'Yeni İlçe Ekle' : 'İlçe Düzenle'; ?></h2>
        <a href="?page=districts" class="btn btn-secondary">
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
                <label for="city_id" class="form-label">Şehir *</label>
                <select id="city_id" name="city_id" class="form-select" required>
                    <option value="">Şehir Seçiniz</option>
                    <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $city['id']; ?>" <?php echo ($district['city_id'] ?? '') == $city['id'] ? 'selected' : ''; ?>>
                        <?php echo $city['name']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name" class="form-label">İlçe Adı *</label>
                <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($district['name'] ?? ''); ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="image" class="form-label">Resim</label>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
                <?php if ($district && $district['image']): ?>
                <small>Mevcut resim: <?php echo $district['image']; ?></small>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description" class="form-label">Açıklama *</label>
            <textarea id="description" name="description" class="form-textarea" rows="4" required><?php echo htmlspecialchars($district['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="population" class="form-label">Nüfus</label>
                <input type="number" id="population" name="population" class="form-input" value="<?php echo $district['population'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="area" class="form-label">Alan (km²)</label>
                <input type="number" id="area" name="area" class="form-input" step="0.01" value="<?php echo $district['area'] ?? ''; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="seo_keywords" class="form-label">SEO Anahtar Kelimeler</label>
            <input type="text" id="seo_keywords" name="seo_keywords" class="form-input" value="<?php echo htmlspecialchars($district['seo_keywords'] ?? ''); ?>" placeholder="anahtar, kelimeler, virgülle, ayrılmış">
        </div>
        
        <div class="form-group">
            <label for="meta_description" class="form-label">Meta Açıklama</label>
            <textarea id="meta_description" name="meta_description" class="form-textarea" rows="2"><?php echo htmlspecialchars($district['meta_description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_active" <?php echo ($district['is_active'] ?? true) ? 'checked' : ''; ?>>
                Aktif
            </label>
        </div>
        
        <!-- Tourist Attractions -->
        <div style="margin-top: 2rem;">
            <h3>Turistik Yerler</h3>
            <div id="attractions-container">
                <?php
                $attractions = $district ? json_decode($district['tourist_attractions'], true) : [];
                if (empty($attractions)) {
                    $attractions = [['name' => '', 'description' => '', 'type' => 'historical', 'address' => '', 'opening_hours' => '', 'entry_fee' => '']];
                }
                foreach ($attractions as $index => $attraction):
                ?>
                <div class="attraction-item" style="border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Yer Adı</label>
                            <input type="text" name="attraction_name[]" class="form-input" value="<?php echo htmlspecialchars($attraction['name']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tür</label>
                            <select name="attraction_type[]" class="form-select">
                                <option value="historical" <?php echo $attraction['type'] === 'historical' ? 'selected' : ''; ?>>Tarihi</option>
                                <option value="natural" <?php echo $attraction['type'] === 'natural' ? 'selected' : ''; ?>>Doğal</option>
                                <option value="cultural" <?php echo $attraction['type'] === 'cultural' ? 'selected' : ''; ?>>Kültürel</option>
                                <option value="religious" <?php echo $attraction['type'] === 'religious' ? 'selected' : ''; ?>>Dini</option>
                                <option value="modern" <?php echo $attraction['type'] === 'modern' ? 'selected' : ''; ?>>Modern</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Açıklama</label>
                        <textarea name="attraction_description[]" class="form-textarea" rows="2"><?php echo htmlspecialchars($attraction['description']); ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Adres</label>
                            <input type="text" name="attraction_address[]" class="form-input" value="<?php echo htmlspecialchars($attraction['address'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Çalışma Saatleri</label>
                            <input type="text" name="attraction_hours[]" class="form-input" value="<?php echo htmlspecialchars($attraction['opening_hours'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giriş Ücreti</label>
                            <input type="text" name="attraction_fee[]" class="form-input" value="<?php echo htmlspecialchars($attraction['entry_fee'] ?? ''); ?>">
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" onclick="removeAttraction(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-trash"></i> Kaldır
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-success" onclick="addAttraction()">
                <i class="fas fa-plus"></i> Turistik Yer Ekle
            </button>
        </div>
        
        <!-- Local Cuisine -->
        <div style="margin-top: 2rem;">
            <h3>Yerel Mutfak</h3>
            <div id="cuisine-container">
                <?php
                $cuisine = $district ? json_decode($district['local_cuisine'], true) : [];
                if (empty($cuisine)) {
                    $cuisine = [['name' => '', 'description' => '', 'ingredients' => [], 'preparation' => '', 'restaurant_recommendations' => []]];
                }
                foreach ($cuisine as $index => $dish):
                ?>
                <div class="cuisine-item" style="border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Yemek Adı</label>
                            <input type="text" name="cuisine_name[]" class="form-input" value="<?php echo htmlspecialchars($dish['name']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Açıklama</label>
                        <textarea name="cuisine_description[]" class="form-textarea" rows="2"><?php echo htmlspecialchars($dish['description']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Malzemeler (virgülle ayırın)</label>
                        <input type="text" name="cuisine_ingredients[]" class="form-input" value="<?php echo htmlspecialchars(implode(', ', $dish['ingredients'])); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hazırlanışı</label>
                        <textarea name="cuisine_preparation[]" class="form-textarea" rows="2"><?php echo htmlspecialchars($dish['preparation'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Önerilen Restoranlar (virgülle ayırın)</label>
                        <input type="text" name="cuisine_restaurants[]" class="form-input" value="<?php echo htmlspecialchars(implode(', ', $dish['restaurant_recommendations'] ?? [])); ?>">
                    </div>
                    <button type="button" class="btn btn-danger" onclick="removeCuisine(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-trash"></i> Kaldır
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-success" onclick="addCuisine()">
                <i class="fas fa-plus"></i> Yemek Ekle
            </button>
        </div>
        
        <!-- Special Flavors -->
        <div style="margin-top: 2rem;">
            <h3>Özel Lezzetler</h3>
            <div id="flavors-container">
                <?php
                $flavors = $district ? json_decode($district['special_flavors'], true) : [];
                if (empty($flavors)) {
                    $flavors = [['name' => '', 'description' => '', 'type' => 'dessert', 'where_to_find' => []]];
                }
                foreach ($flavors as $index => $flavor):
                ?>
                <div class="flavor-item" style="border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Lezzet Adı</label>
                            <input type="text" name="flavor_name[]" class="form-input" value="<?php echo htmlspecialchars($flavor['name']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tür</label>
                            <select name="flavor_type[]" class="form-select">
                                <option value="dessert" <?php echo $flavor['type'] === 'dessert' ? 'selected' : ''; ?>>Tatlı</option>
                                <option value="drink" <?php echo $flavor['type'] === 'drink' ? 'selected' : ''; ?>>İçecek</option>
                                <option value="snack" <?php echo $flavor['type'] === 'snack' ? 'selected' : ''; ?>>Atıştırmalık</option>
                                <option value="main_dish" <?php echo $flavor['type'] === 'main_dish' ? 'selected' : ''; ?>>Ana Yemek</option>
                                <option value="appetizer" <?php echo $flavor['type'] === 'appetizer' ? 'selected' : ''; ?>>Meze</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Açıklama</label>
                        <textarea name="flavor_description[]" class="form-textarea" rows="2"><?php echo htmlspecialchars($flavor['description']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nerede Bulunur (virgülle ayırın)</label>
                        <input type="text" name="flavor_where[]" class="form-input" value="<?php echo htmlspecialchars(implode(', ', $flavor['where_to_find'] ?? [])); ?>">
                    </div>
                    <button type="button" class="btn btn-danger" onclick="removeFlavor(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-trash"></i> Kaldır
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-success" onclick="addFlavor()">
                <i class="fas fa-plus"></i> Lezzet Ekle
            </button>
        </div>
        
        <!-- Cultural Highlights -->
        <div style="margin-top: 2rem;">
            <h3>Kültürel Özellikler</h3>
            <div id="culture-container">
                <?php
                $culture = $district ? json_decode($district['cultural_highlights'], true) : [];
                if (empty($culture)) {
                    $culture = [['title' => '', 'description' => '', 'type' => 'festival']];
                }
                foreach ($culture as $index => $item):
                ?>
                <div class="culture-item" style="border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Başlık</label>
                            <input type="text" name="culture_title[]" class="form-input" value="<?php echo htmlspecialchars($item['title']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tür</label>
                            <select name="culture_type[]" class="form-select">
                                <option value="festival" <?php echo $item['type'] === 'festival' ? 'selected' : ''; ?>>Festival</option>
                                <option value="tradition" <?php echo $item['type'] === 'tradition' ? 'selected' : ''; ?>>Gelenek</option>
                                <option value="art" <?php echo $item['type'] === 'art' ? 'selected' : ''; ?>>Sanat</option>
                                <option value="music" <?php echo $item['type'] === 'music' ? 'selected' : ''; ?>>Müzik</option>
                                <option value="dance" <?php echo $item['type'] === 'dance' ? 'selected' : ''; ?>>Dans</option>
                                <option value="craft" <?php echo $item['type'] === 'craft' ? 'selected' : ''; ?>>El Sanatı</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Açıklama</label>
                        <textarea name="culture_description[]" class="form-textarea" rows="2"><?php echo htmlspecialchars($item['description']); ?></textarea>
                    </div>
                    <button type="button" class="btn btn-danger" onclick="removeCulture(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-trash"></i> Kaldır
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-success" onclick="addCulture()">
                <i class="fas fa-plus"></i> Kültürel Özellik Ekle
            </button>
        </div>
        
        <!-- Accommodation -->
        <div style="margin-top: 2rem;">
            <h3>Konaklama</h3>
            <div id="accommodation-container">
                <?php
                $accommodation = $district ? json_decode($district['accommodation'], true) : [];
                if (empty($accommodation)) {
                    $accommodation = [['name' => '', 'type' => 'hotel', 'description' => '', 'price_range' => '', 'contact' => '']];
                }
                foreach ($accommodation as $index => $place):
                ?>
                <div class="accommodation-item" style="border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Yer Adı</label>
                            <input type="text" name="accommodation_name[]" class="form-input" value="<?php echo htmlspecialchars($place['name']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tür</label>
                            <select name="accommodation_type[]" class="form-select">
                                <option value="hotel" <?php echo $place['type'] === 'hotel' ? 'selected' : ''; ?>>Otel</option>
                                <option value="pension" <?php echo $place['type'] === 'pension' ? 'selected' : ''; ?>>Pansiyon</option>
                                <option value="apartment" <?php echo $place['type'] === 'apartment' ? 'selected' : ''; ?>>Apartman</option>
                                <option value="villa" <?php echo $place['type'] === 'villa' ? 'selected' : ''; ?>>Villa</option>
                                <option value="camping" <?php echo $place['type'] === 'camping' ? 'selected' : ''; ?>>Kamp</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Açıklama</label>
                        <textarea name="accommodation_description[]" class="form-textarea" rows="2"><?php echo htmlspecialchars($place['description']); ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Fiyat Aralığı</label>
                            <input type="text" name="accommodation_price[]" class="form-input" value="<?php echo htmlspecialchars($place['price_range'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">İletişim</label>
                            <input type="text" name="accommodation_contact[]" class="form-input" value="<?php echo htmlspecialchars($place['contact'] ?? ''); ?>">
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" onclick="removeAccommodation(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-trash"></i> Kaldır
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-success" onclick="addAccommodation()">
                <i class="fas fa-plus"></i> Konaklama Ekle
            </button>
        </div>
        
        <!-- Transportation -->
        <div style="margin-top: 2rem;">
            <h3>Ulaşım</h3>
            <div style="border: 1px solid var(--border-color); padding: 1rem; border-radius: 5px;">
                <div class="form-group">
                    <label for="transport_how" class="form-label">Nasıl Gidilir?</label>
                    <textarea id="transport_how" name="transport_how" class="form-textarea" rows="3"><?php echo htmlspecialchars($district ? json_decode($district['transportation'], true)['how_to_reach'] ?? '' : ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="transport_local" class="form-label">Yerel Ulaşım</label>
                    <textarea id="transport_local" name="transport_local" class="form-textarea" rows="3"><?php echo htmlspecialchars($district ? json_decode($district['transportation'], true)['local_transport'] ?? '' : ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="transport_car" class="form-label">Araç Kiralama</label>
                    <textarea id="transport_car" name="transport_car" class="form-textarea" rows="3"><?php echo htmlspecialchars($district ? json_decode($district['transportation'], true)['car_rental'] ?? '' : ''); ?></textarea>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 2rem; text-align: center;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'İlçe Ekle' : 'Güncelle'; ?>
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
// Similar JavaScript functions as in cities.php but for districts
function addAttraction() {
    const container = document.getElementById('attractions-container');
    const div = document.createElement('div');
    div.className = 'attraction-item';
    div.style.cssText = 'border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;';
    div.innerHTML = `
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Yer Adı</label>
                <input type="text" name="attraction_name[]" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Tür</label>
                <select name="attraction_type[]" class="form-select">
                    <option value="historical">Tarihi</option>
                    <option value="natural">Doğal</option>
                    <option value="cultural">Kültürel</option>
                    <option value="religious">Dini</option>
                    <option value="modern">Modern</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Açıklama</label>
            <textarea name="attraction_description[]" class="form-textarea" rows="2"></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Adres</label>
                <input type="text" name="attraction_address[]" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Çalışma Saatleri</label>
                <input type="text" name="attraction_hours[]" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Giriş Ücreti</label>
                <input type="text" name="attraction_fee[]" class="form-input">
            </div>
        </div>
        <button type="button" class="btn btn-danger" onclick="removeAttraction(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
            <i class="fas fa-trash"></i> Kaldır
        </button>
    `;
    container.appendChild(div);
}

function removeAttraction(button) {
    button.parentElement.remove();
}

// Add similar functions for cuisine, flavors, culture, accommodation
function addCuisine() {
    const container = document.getElementById('cuisine-container');
    const div = document.createElement('div');
    div.className = 'cuisine-item';
    div.style.cssText = 'border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;';
    div.innerHTML = `
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Yemek Adı</label>
                <input type="text" name="cuisine_name[]" class="form-input">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Açıklama</label>
            <textarea name="cuisine_description[]" class="form-textarea" rows="2"></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Malzemeler (virgülle ayırın)</label>
            <input type="text" name="cuisine_ingredients[]" class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label">Hazırlanışı</label>
            <textarea name="cuisine_preparation[]" class="form-textarea" rows="2"></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Önerilen Restoranlar (virgülle ayırın)</label>
            <input type="text" name="cuisine_restaurants[]" class="form-input">
        </div>
        <button type="button" class="btn btn-danger" onclick="removeCuisine(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
            <i class="fas fa-trash"></i> Kaldır
        </button>
    `;
    container.appendChild(div);
}

function removeCuisine(button) {
    button.parentElement.remove();
}

function addFlavor() {
    const container = document.getElementById('flavors-container');
    const div = document.createElement('div');
    div.className = 'flavor-item';
    div.style.cssText = 'border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;';
    div.innerHTML = `
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Lezzet Adı</label>
                <input type="text" name="flavor_name[]" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Tür</label>
                <select name="flavor_type[]" class="form-select">
                    <option value="dessert">Tatlı</option>
                    <option value="drink">İçecek</option>
                    <option value="snack">Atıştırmalık</option>
                    <option value="main_dish">Ana Yemek</option>
                    <option value="appetizer">Meze</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Açıklama</label>
            <textarea name="flavor_description[]" class="form-textarea" rows="2"></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Nerede Bulunur (virgülle ayırın)</label>
            <input type="text" name="flavor_where[]" class="form-input">
        </div>
        <button type="button" class="btn btn-danger" onclick="removeFlavor(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
            <i class="fas fa-trash"></i> Kaldır
        </button>
    `;
    container.appendChild(div);
}

function removeFlavor(button) {
    button.parentElement.remove();
}

function addCulture() {
    const container = document.getElementById('culture-container');
    const div = document.createElement('div');
    div.className = 'culture-item';
    div.style.cssText = 'border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;';
    div.innerHTML = `
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Başlık</label>
                <input type="text" name="culture_title[]" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Tür</label>
                <select name="culture_type[]" class="form-select">
                    <option value="festival">Festival</option>
                    <option value="tradition">Gelenek</option>
                    <option value="art">Sanat</option>
                    <option value="music">Müzik</option>
                    <option value="dance">Dans</option>
                    <option value="craft">El Sanatı</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Açıklama</label>
            <textarea name="culture_description[]" class="form-textarea" rows="2"></textarea>
        </div>
        <button type="button" class="btn btn-danger" onclick="removeCulture(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
            <i class="fas fa-trash"></i> Kaldır
        </button>
    `;
    container.appendChild(div);
}

function removeCulture(button) {
    button.parentElement.remove();
}

function addAccommodation() {
    const container = document.getElementById('accommodation-container');
    const div = document.createElement('div');
    div.className = 'accommodation-item';
    div.style.cssText = 'border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1rem; border-radius: 5px;';
    div.innerHTML = `
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Yer Adı</label>
                <input type="text" name="accommodation_name[]" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Tür</label>
                <select name="accommodation_type[]" class="form-select">
                    <option value="hotel">Otel</option>
                    <option value="pension">Pansiyon</option>
                    <option value="apartment">Apartman</option>
                    <option value="villa">Villa</option>
                    <option value="camping">Kamp</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Açıklama</label>
            <textarea name="accommodation_description[]" class="form-textarea" rows="2"></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Fiyat Aralığı</label>
                <input type="text" name="accommodation_price[]" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">İletişim</label>
                <input type="text" name="accommodation_contact[]" class="form-input">
            </div>
        </div>
        <button type="button" class="btn btn-danger" onclick="removeAccommodation(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
            <i class="fas fa-trash"></i> Kaldır
        </button>
    `;
    container.appendChild(div);
}

function removeAccommodation(button) {
    button.parentElement.remove();
}
</script>