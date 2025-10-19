<?php
// Only admin can access this page
if (!hasPermission('admin')) {
    header('Location: ?page=dashboard');
    exit;
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $first_name = sanitizeInput($_POST['first_name'] ?? '');
    $last_name = sanitizeInput($_POST['last_name'] ?? '');
    $role = sanitizeInput($_POST['role'] ?? 'author');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($action === 'add') {
        if (empty($username) || empty($email) || empty($password) || empty($first_name) || empty($last_name)) {
            $error = 'Tüm alanlar gereklidir.';
        } elseif (!validateEmail($email)) {
            $error = 'Geçerli bir e-posta adresi giriniz.';
        } else {
            // Check if username or email already exists
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM admin_users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $exists = $stmt->fetch()['count'];
            
            if ($exists > 0) {
                $error = 'Kullanıcı adı veya e-posta zaten kullanılıyor.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO admin_users (username, email, password, first_name, last_name, role, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                
                if ($stmt->execute([$username, $email, $hashedPassword, $first_name, $last_name, $role, $is_active])) {
                    $success = 'Kullanıcı başarıyla eklendi.';
                    $action = 'list';
                } else {
                    $error = 'Kullanıcı eklenirken hata oluştu.';
                }
            }
        }
    } elseif ($action === 'edit' && $id) {
        if (empty($username) || empty($email) || empty($first_name) || empty($last_name)) {
            $error = 'Tüm alanlar gereklidir.';
        } elseif (!validateEmail($email)) {
            $error = 'Geçerli bir e-posta adresi giriniz.';
        } else {
            // Check if username or email already exists (excluding current user)
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM admin_users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$username, $email, $id]);
            $exists = $stmt->fetch()['count'];
            
            if ($exists > 0) {
                $error = 'Kullanıcı adı veya e-posta zaten kullanılıyor.';
            } else {
                $sql = "UPDATE admin_users SET username = ?, email = ?, first_name = ?, last_name = ?, role = ?, is_active = ?";
                $params = [$username, $email, $first_name, $last_name, $role, $is_active];
                
                // Update password only if provided
                if (!empty($password)) {
                    $sql .= ", password = ?";
                    $params[] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE id = ?";
                $params[] = $id;
                
                $stmt = $db->prepare($sql);
                
                if ($stmt->execute($params)) {
                    $success = 'Kullanıcı başarıyla güncellendi.';
                    $action = 'list';
                } else {
                    $error = 'Kullanıcı güncellenirken hata oluştu.';
                }
            }
        }
    } elseif ($action === 'delete' && $id) {
        // Prevent deleting own account
        if ($id == $userId) {
            $error = 'Kendi hesabınızı silemezsiniz.';
        } else {
            $stmt = $db->prepare("DELETE FROM admin_users WHERE id = ?");
            if ($stmt->execute([$id])) {
                $success = 'Kullanıcı başarıyla silindi.';
            } else {
                $error = 'Kullanıcı silinirken hata oluştu.';
            }
        }
        $action = 'list';
    }
}

// Get user data for edit
$user = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
}

// Get users list
if ($action === 'list') {
    $users = $db->query("SELECT * FROM admin_users ORDER BY created_at DESC")->fetchAll();
}
?>

<div class="content-area">
    <?php if ($action === 'list'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Kullanıcılar</h2>
        <a href="?page=users&action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Kullanıcı Ekle
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
                <th>Kullanıcı Adı</th>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Rol</th>
                <th>Durum</th>
                <th>Son Giriş</th>
                <th>Kayıt Tarihi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td>
                    <strong><?php echo $user['username']; ?></strong>
                    <?php if ($user['id'] == $userId): ?>
                    <br><small style="color: var(--primary-color);">(Siz)</small>
                    <?php endif; ?>
                </td>
                <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <?php
                    $roleColors = [
                        'admin' => 'badge-danger',
                        'editor' => 'badge-warning',
                        'author' => 'badge-info'
                    ];
                    $roleLabels = [
                        'admin' => 'Admin',
                        'editor' => 'Editör',
                        'author' => 'Yazar'
                    ];
                    ?>
                    <span class="badge <?php echo $roleColors[$user['role']]; ?>">
                        <?php echo $roleLabels[$user['role']]; ?>
                    </span>
                </td>
                <td>
                    <?php if ($user['is_active']): ?>
                    <span class="badge badge-success">Aktif</span>
                    <?php else: ?>
                    <span class="badge badge-danger">Pasif</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $user['last_login'] ? formatDate($user['last_login'], 'd.m.Y H:i') : 'Hiç giriş yapmamış'; ?>
                </td>
                <td><?php echo formatDate($user['created_at']); ?></td>
                <td>
                    <a href="?page=users&action=edit&id=<?php echo $user['id']; ?>" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                    <?php if ($user['id'] != $userId): ?>
                    <a href="?page=users&action=delete&id=<?php echo $user['id']; ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">
                        <i class="fas fa-trash"></i> Sil
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><?php echo $action === 'add' ? 'Yeni Kullanıcı Ekle' : 'Kullanıcı Düzenle'; ?></h2>
        <a href="?page=users" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
        <div class="form-row">
            <div class="form-group">
                <label for="username" class="form-label">Kullanıcı Adı *</label>
                <input type="text" id="username" name="username" class="form-input" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">E-posta *</label>
                <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name" class="form-label">Ad *</label>
                <input type="text" id="first_name" name="first_name" class="form-input" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name" class="form-label">Soyad *</label>
                <input type="text" id="last_name" name="last_name" class="form-input" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="password" class="form-label">Şifre <?php echo $action === 'add' ? '*' : '(boş bırakırsanız değişmez)'; ?></label>
                <input type="password" id="password" name="password" class="form-input" <?php echo $action === 'add' ? 'required' : ''; ?>>
            </div>
            <div class="form-group">
                <label for="role" class="form-label">Rol *</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="author" <?php echo ($user['role'] ?? '') === 'author' ? 'selected' : ''; ?>>Yazar</option>
                    <option value="editor" <?php echo ($user['role'] ?? '') === 'editor' ? 'selected' : ''; ?>>Editör</option>
                    <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_active" <?php echo ($user['is_active'] ?? true) ? 'checked' : ''; ?>>
                Aktif
            </label>
        </div>
        
        <div style="margin-top: 2rem; text-align: center;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Kullanıcı Ekle' : 'Güncelle'; ?>
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>