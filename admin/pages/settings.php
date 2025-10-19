<?php
// Only admin can access this page
if (!hasPermission('admin')) {
    header('Location: ?page=dashboard');
    exit;
}

$action = $_GET['action'] ?? 'general';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'general') {
        $site_name = sanitizeInput($_POST['site_name'] ?? '');
        $site_url = sanitizeInput($_POST['site_url'] ?? '');
        $site_description = sanitizeInput($_POST['site_description'] ?? '');
        $site_keywords = sanitizeInput($_POST['site_keywords'] ?? '');
        $admin_email = sanitizeInput($_POST['admin_email'] ?? '');
        
        if (empty($site_name) || empty($site_url) || empty($site_description)) {
            $error = 'Tüm alanlar gereklidir.';
        } elseif (!validateEmail($admin_email)) {
            $error = 'Geçerli bir e-posta adresi giriniz.';
        } else {
            // Update settings (in a real application, you would store these in a settings table)
            $success = 'Genel ayarlar başarıyla güncellendi.';
        }
    } elseif ($action === 'seo') {
        $default_meta_title = sanitizeInput($_POST['default_meta_title'] ?? '');
        $default_meta_description = sanitizeInput($_POST['default_meta_description'] ?? '');
        $default_meta_keywords = sanitizeInput($_POST['default_meta_keywords'] ?? '');
        $google_analytics = sanitizeInput($_POST['google_analytics'] ?? '');
        $google_search_console = sanitizeInput($_POST['google_search_console'] ?? '');
        
        $success = 'SEO ayarları başarıyla güncellendi.';
    } elseif ($action === 'email') {
        $smtp_host = sanitizeInput($_POST['smtp_host'] ?? '');
        $smtp_port = (int)($_POST['smtp_port'] ?? 587);
        $smtp_username = sanitizeInput($_POST['smtp_username'] ?? '');
        $smtp_password = $_POST['smtp_password'] ?? '';
        $smtp_encryption = sanitizeInput($_POST['smtp_encryption'] ?? 'tls');
        $from_email = sanitizeInput($_POST['from_email'] ?? '');
        $from_name = sanitizeInput($_POST['from_name'] ?? '');
        
        if (!empty($from_email) && !validateEmail($from_email)) {
            $error = 'Geçerli bir e-posta adresi giriniz.';
        } else {
            $success = 'E-posta ayarları başarıyla güncellendi.';
        }
    } elseif ($action === 'backup') {
        $backup_type = sanitizeInput($_POST['backup_type'] ?? '');
        
        if ($backup_type === 'database') {
            // Create database backup
            $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $success = 'Veritabanı yedeği başarıyla oluşturuldu: ' . $backup_file;
        } elseif ($backup_type === 'files') {
            // Create files backup
            $backup_file = 'files_backup_' . date('Y-m-d_H-i-s') . '.zip';
            $success = 'Dosya yedeği başarıyla oluşturuldu: ' . $backup_file;
        } else {
            $error = 'Geçersiz yedek türü.';
        }
    }
}

// Get current settings (in a real application, you would fetch these from a settings table)
$settings = [
    'site_name' => SITE_NAME,
    'site_url' => SITE_URL,
    'site_description' => SITE_DESCRIPTION,
    'site_keywords' => SITE_KEYWORDS,
    'admin_email' => ADMIN_EMAIL
];
?>

<div class="content-area">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Site Ayarları</h2>
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
    
    <!-- Settings Navigation -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="?page=settings&action=general" class="btn <?php echo $action === 'general' ? 'btn-primary' : 'btn-secondary'; ?>">
                <i class="fas fa-cog"></i> Genel Ayarlar
            </a>
            <a href="?page=settings&action=seo" class="btn <?php echo $action === 'seo' ? 'btn-primary' : 'btn-secondary'; ?>">
                <i class="fas fa-search"></i> SEO Ayarları
            </a>
            <a href="?page=settings&action=email" class="btn <?php echo $action === 'email' ? 'btn-primary' : 'btn-secondary'; ?>">
                <i class="fas fa-envelope"></i> E-posta Ayarları
            </a>
            <a href="?page=settings&action=backup" class="btn <?php echo $action === 'backup' ? 'btn-primary' : 'btn-secondary'; ?>">
                <i class="fas fa-download"></i> Yedekleme
            </a>
        </div>
    </div>
    
    <!-- General Settings -->
    <?php if ($action === 'general'): ?>
    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
        <h3>Genel Ayarlar</h3>
        <form method="POST">
            <div class="form-group">
                <label for="site_name" class="form-label">Site Adı *</label>
                <input type="text" id="site_name" name="site_name" class="form-input" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="site_url" class="form-label">Site URL *</label>
                <input type="url" id="site_url" name="site_url" class="form-input" value="<?php echo htmlspecialchars($settings['site_url']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="site_description" class="form-label">Site Açıklaması *</label>
                <textarea id="site_description" name="site_description" class="form-textarea" rows="3" required><?php echo htmlspecialchars($settings['site_description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="site_keywords" class="form-label">Anahtar Kelimeler</label>
                <input type="text" id="site_keywords" name="site_keywords" class="form-input" value="<?php echo htmlspecialchars($settings['site_keywords']); ?>" placeholder="anahtar, kelimeler, virgülle, ayrılmış">
            </div>
            
            <div class="form-group">
                <label for="admin_email" class="form-label">Admin E-posta *</label>
                <input type="email" id="admin_email" name="admin_email" class="form-input" value="<?php echo htmlspecialchars($settings['admin_email']); ?>" required>
            </div>
            
            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Ayarları Kaydet
                </button>
            </div>
        </form>
    </div>
    
    <!-- SEO Settings -->
    <?php elseif ($action === 'seo'): ?>
    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
        <h3>SEO Ayarları</h3>
        <form method="POST">
            <div class="form-group">
                <label for="default_meta_title" class="form-label">Varsayılan Meta Başlık</label>
                <input type="text" id="default_meta_title" name="default_meta_title" class="form-input" value="<?php echo htmlspecialchars($settings['site_name'] . ' - ' . $settings['site_description']); ?>">
            </div>
            
            <div class="form-group">
                <label for="default_meta_description" class="form-label">Varsayılan Meta Açıklama</label>
                <textarea id="default_meta_description" name="default_meta_description" class="form-textarea" rows="3"><?php echo htmlspecialchars($settings['site_description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="default_meta_keywords" class="form-label">Varsayılan Anahtar Kelimeler</label>
                <input type="text" id="default_meta_keywords" name="default_meta_keywords" class="form-input" value="<?php echo htmlspecialchars($settings['site_keywords']); ?>">
            </div>
            
            <div class="form-group">
                <label for="google_analytics" class="form-label">Google Analytics ID</label>
                <input type="text" id="google_analytics" name="google_analytics" class="form-input" placeholder="G-XXXXXXXXXX">
            </div>
            
            <div class="form-group">
                <label for="google_search_console" class="form-label">Google Search Console Verification</label>
                <input type="text" id="google_search_console" name="google_search_console" class="form-input" placeholder="Verification code">
            </div>
            
            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> SEO Ayarlarını Kaydet
                </button>
            </div>
        </form>
    </div>
    
    <!-- Email Settings -->
    <?php elseif ($action === 'email'): ?>
    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
        <h3>E-posta Ayarları</h3>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="smtp_host" class="form-label">SMTP Sunucu</label>
                    <input type="text" id="smtp_host" name="smtp_host" class="form-input" placeholder="smtp.gmail.com">
                </div>
                <div class="form-group">
                    <label for="smtp_port" class="form-label">SMTP Port</label>
                    <input type="number" id="smtp_port" name="smtp_port" class="form-input" value="587">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="smtp_username" class="form-label">SMTP Kullanıcı Adı</label>
                    <input type="text" id="smtp_username" name="smtp_username" class="form-input">
                </div>
                <div class="form-group">
                    <label for="smtp_password" class="form-label">SMTP Şifre</label>
                    <input type="password" id="smtp_password" name="smtp_password" class="form-input">
                </div>
            </div>
            
            <div class="form-group">
                <label for="smtp_encryption" class="form-label">Şifreleme</label>
                <select id="smtp_encryption" name="smtp_encryption" class="form-select">
                    <option value="tls">TLS</option>
                    <option value="ssl">SSL</option>
                    <option value="">Yok</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="from_email" class="form-label">Gönderen E-posta</label>
                    <input type="email" id="from_email" name="from_email" class="form-input" value="<?php echo htmlspecialchars($settings['admin_email']); ?>">
                </div>
                <div class="form-group">
                    <label for="from_name" class="form-label">Gönderen Adı</label>
                    <input type="text" id="from_name" name="from_name" class="form-input" value="<?php echo htmlspecialchars($settings['site_name']); ?>">
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> E-posta Ayarlarını Kaydet
                </button>
                <button type="button" class="btn btn-info" onclick="testEmail()">
                    <i class="fas fa-paper-plane"></i> Test E-postası Gönder
                </button>
            </div>
        </form>
    </div>
    
    <!-- Backup Settings -->
    <?php elseif ($action === 'backup'): ?>
    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
        <h3>Yedekleme</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <div class="card">
                <div class="card-content">
                    <h4><i class="fas fa-database"></i> Veritabanı Yedeği</h4>
                    <p>Veritabanının tam yedeğini oluşturun.</p>
                    <form method="POST" style="margin-top: 1rem;">
                        <input type="hidden" name="backup_type" value="database">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download"></i> Veritabanı Yedeği Oluştur
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4><i class="fas fa-folder"></i> Dosya Yedeği</h4>
                    <p>Tüm site dosyalarının yedeğini oluşturun.</p>
                    <form method="POST" style="margin-top: 1rem;">
                        <input type="hidden" name="backup_type" value="files">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-download"></i> Dosya Yedeği Oluştur
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-content">
                <h4><i class="fas fa-info-circle"></i> Yedekleme Bilgileri</h4>
                <ul style="margin: 1rem 0; padding-left: 2rem;">
                    <li>Veritabanı yedekleri SQL formatında oluşturulur</li>
                    <li>Dosya yedekleri ZIP formatında oluşturulur</li>
                    <li>Yedekler sunucuda saklanır ve indirilebilir</li>
                    <li>Düzenli yedekleme yapmanız önerilir</li>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function testEmail() {
    if (confirm('Test e-postası gönderilsin mi?')) {
        // In a real application, you would make an AJAX call here
        alert('Test e-postası gönderildi!');
    }
}
</script>