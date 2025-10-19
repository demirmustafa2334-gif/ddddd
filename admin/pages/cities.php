<?php
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $image = $_FILES['image']['name'] ?? '';
    $population = (int)($_POST['population'] ?? 0);
    $area = (float)($_POST['area'] ?? 0);
    $established_year = (int)($_POST['established_year'] ?? 0);
    $seo_keywords = sanitizeInput($_POST['seo_keywords'] ?? '');
    $meta_description = sanitizeInput($_POST['meta_description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle tourist attractions, local cuisine, special flavors
    $tourist_attractions = [];
    $local_cuisine = [];
    $special_flavors = [];
    
    if (isset($_POST['attraction_name'])) {
        for ($i = 0; $i < count($_POST['attraction_name']); $i++) {
            if (!empty($_POST['attraction_name'][$i])) {
                $tourist_attractions[] = [
                    'name' => sanitizeInput($_POST['attraction_name'][$i]),
                    'description' => sanitizeInput($_POST['attraction_description'][$i] ?? ''),
                    'type' => sanitizeInput($_POST['attraction_type'][$i] ?? 'historical')
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
                    'ingredients' => array_filter(array_map('sanitizeInput', $_POST['cuisine_ingredients'][$i] ?? []))
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
                    'type' => sanitizeInput($_POST['flavor_type'][$i] ?? 'dessert')
                ];
            }
        }
    }
    
    $slug = createSlug($name);
    
    if ($action === 'add') {
        // Handle image upload
        $imagePath = '';
        if (!empty($image)) {
            $imagePath = uploadImage($_FILES['image'], '../assets/images/cities/');
            if (!$imagePath) {
                $error = 'Resim yüklenirken hata oluştu.';
            }
        }
        
        if (empty($error)) {
            $sql = "INSERT INTO cities (name, slug, description, image, population, area, established_year, tourist_attractions, local_cuisine, special_flavors, seo_keywords, meta_description, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            if ($stmt->execute([
                $name, $slug, $description, $imagePath, $population, $area, $established_year,
                json_encode($tourist_attractions), json_encode($local_cuisine), json_encode($special_flavors),
                $seo_keywords, $meta_description, $is_active
            ])) {
                $success = 'Şehir başarıyla eklendi.';
                $action = 'list';
            } else {
                $error = 'Şehir eklenirken hata oluştu.';
            }
        }
    } elseif ($action === 'edit' && $id) {
        // Handle image upload
        $imagePath = '';
        if (!empty($image)) {
            $imagePath = uploadImage($_FILES['image'], '../assets/images/cities/');
            if (!$imagePath) {
                $error = 'Resim yüklenirken hata oluştu.';
            }
        }
        
        if (empty($error)) {
            $sql = "UPDATE cities SET name = ?, slug = ?, description = ?, population = ?, area = ?, established_year = ?, tourist_attractions = ?, local_cuisine = ?, special_flavors = ?, seo_keywords = ?, meta_description = ?, is_active = ?";
            $params = [$name, $slug, $description, $population, $area, $established_year, json_encode($tourist_attractions), json_encode($local_cuisine), json_encode($special_flavors), $seo_keywords, $meta_description, $is_active];
            
            if ($imagePath) {
                $sql .= ", image = ?";
                $params[] = $imagePath;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            
            if ($stmt->execute($params)) {
                $success = 'Şehir başarıyla güncellendi.';
                $action = 'list';
            } else {
                $error = 'Şehir güncellenirken hata oluştu.';
            }
        }
    } elseif ($action === 'delete' && $id) {
        $stmt = $db->prepare("UPDATE cities SET is_active = 0 WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success = 'Şehir başarıyla silindi.';
        } else {
            $error = 'Şehir silinirken hata oluştu.';
        }
        $action = 'list';
    }
}

// Get city data for edit
$city = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM cities WHERE id = ?");
    $stmt->execute([$id]);
    $city = $stmt->fetch();
}

// Get cities list
if ($action === 'list') {
    $cities = $db->query("SELECT * FROM cities ORDER BY name ASC")->fetchAll();
}
?>

<div class="content-area">
    <?php if ($action === 'list'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Şehirler</h2>
        <a href="?page=cities&action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Şehir Ekle
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
                <th>Şehir Adı</th>
                <th>Açıklama</th>
                <th>Nüfus</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cities as $city): ?>
            <tr>
                <td>
                    <?php if ($city['image']): ?>
                    <img src="../assets/images/cities/<?php echo $city['image']; ?>" alt="<?php echo $city['name']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                    <?php else: ?>
                    <div style="width: 50px; height: 50px; background: var(--border-color); border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image" style="color: var(--text-light);"></i>
                    </div>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo $city['name']; ?></strong>
                    <br><small style="color: var(--text-light);"><?php echo $city['slug']; ?></small>
                </td>
                <td><?php echo truncateText($city['description'], 50); ?></td>
                <td><?php echo $city['population'] ? number_format($city['population']) : '-'; ?></td>
                <td>
                    <?php if ($city['is_active']): ?>
                    <span class="badge badge-success">Aktif</span>
                    <?php else: ?>
                    <span class="badge badge-danger">Pasif</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="?page=cities&action=edit&id=<?php echo $city['id']; ?>" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                    <a href="?page=cities&action=delete&id=<?php echo $city['id']; ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" onclick="return confirm('Bu şehri silmek istediğinizden emin misiniz?')">
                        <i class="fas fa-trash"></i> Sil
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><?php echo $action === 'add' ? 'Yeni Şehir Ekle' : 'Şehir Düzenle'; ?></h2>
        <a href="?page=cities" class="btn btn-secondary">
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
                <label for="name" class="form-label">Şehir Adı *</label>
                <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($city['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Resim</label>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
                <?php if ($city && $city['image']): ?>
                <small>Mevcut resim: <?php echo $city['image']; ?></small>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description" class="form-label">Açıklama *</label>
            <textarea id="description" name="description" class="form-textarea" rows="4" required><?php echo htmlspecialchars($city['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="population" class="form-label">Nüfus</label>
                <input type="number" id="population" name="population" class="form-input" value="<?php echo $city['population'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="area" class="form-label">Alan (km²)</label>
                <input type="number" id="area" name="area" class="form-input" step="0.01" value="<?php echo $city['area'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="established_year" class="form-label">Kuruluş Yılı</label>
                <input type="number" id="established_year" name="established_year" class="form-input" value="<?php echo $city['established_year'] ?? ''; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="seo_keywords" class="form-label">SEO Anahtar Kelimeler</label>
            <input type="text" id="seo_keywords" name="seo_keywords" class="form-input" value="<?php echo htmlspecialchars($city['seo_keywords'] ?? ''); ?>" placeholder="anahtar, kelimeler, virgülle, ayrılmış">
        </div>
        
        <div class="form-group">
            <label for="meta_description" class="form-label">Meta Açıklama</label>
            <textarea id="meta_description" name="meta_description" class="form-textarea" rows="2"><?php echo htmlspecialchars($city['meta_description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_active" <?php echo ($city['is_active'] ?? true) ? 'checked' : ''; ?>>
                Aktif
            </label>
        </div>
        
        <!-- Tourist Attractions -->
        <div style="margin-top: 2rem;">
            <h3>Turistik Yerler</h3>
            <div id="attractions-container">
                <?php
                $attractions = $city ? json_decode($city['tourist_attractions'], true) : [];
                if (empty($attractions)) {
                    $attractions = [['name' => '', 'description' => '', 'type' => 'historical']];
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
                $cuisine = $city ? json_decode($city['local_cuisine'], true) : [];
                if (empty($cuisine)) {
                    $cuisine = [['name' => '', 'description' => '', 'ingredients' => []]];
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
                $flavors = $city ? json_decode($city['special_flavors'], true) : [];
                if (empty($flavors)) {
                    $flavors = [['name' => '', 'description' => '', 'type' => 'dessert']];
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
        
        <div style="margin-top: 2rem; text-align: center;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Şehir Ekle' : 'Güncelle'; ?>
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
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
        <button type="button" class="btn btn-danger" onclick="removeAttraction(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
            <i class="fas fa-trash"></i> Kaldır
        </button>
    `;
    container.appendChild(div);
}

function removeAttraction(button) {
    button.parentElement.remove();
}

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
        <button type="button" class="btn btn-danger" onclick="removeFlavor(this)" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
            <i class="fas fa-trash"></i> Kaldır
        </button>
    `;
    container.appendChild(div);
}

function removeFlavor(button) {
    button.parentElement.remove();
}
</script>