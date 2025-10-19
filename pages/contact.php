<?php
$meta = generateMetaTags(
    'İletişim',
    'Yerel Tanıtım ile iletişime geçin. Sorularınız, önerileriniz ve geri bildirimleriniz için bize ulaşın.',
    'iletişim, yerel tanıtım, geri bildirim, öneri, soru'
);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    $city = sanitizeInput($_POST['city'] ?? '');
    $district = sanitizeInput($_POST['district'] ?? '');
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Lütfen tüm gerekli alanları doldurunuz.';
    } elseif (!validateEmail($email)) {
        $error = 'Geçerli bir e-posta adresi giriniz.';
    } else {
        // Insert contact message
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, city, district) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute([$name, $email, $phone, $subject, $message, $city, $district])) {
            $success = 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.';
            
            // Clear form
            $name = $email = $phone = $subject = $message = $city = $district = '';
        } else {
            $error = 'Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyiniz.';
        }
    }
}

// Get cities for dropdown
$cities = getCities($db);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="city-header">
    <div class="container">
        <h1 class="city-title">İletişim</h1>
        <p class="city-description">
            Sorularınız, önerileriniz ve geri bildirimleriniz için bizimle iletişime geçin. 
            Size en kısa sürede dönüş yapacağız.
        </p>
    </div>
</section>

<!-- Contact Form -->
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 3rem;">
            <!-- Contact Form -->
            <div>
                <h2>Bize Mesaj Gönderin</h2>
                
                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <form class="contact-form" method="POST" action="">
                    <div class="form-group">
                        <label for="name" class="form-label">Ad Soyad *</label>
                        <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">E-posta *</label>
                        <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">Telefon</label>
                        <input type="tel" id="phone" name="phone" class="form-input" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject" class="form-label">Konu *</label>
                        <input type="text" id="subject" name="subject" class="form-input" value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="city" class="form-label">Şehir</label>
                        <select id="city" name="city" class="form-select">
                            <option value="">Şehir Seçiniz</option>
                            <?php foreach ($cities as $cityOption): ?>
                            <option value="<?php echo $cityOption['name']; ?>" <?php echo ($city === $cityOption['name']) ? 'selected' : ''; ?>>
                                <?php echo $cityOption['name']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="district" class="form-label">İlçe</label>
                        <input type="text" id="district" name="district" class="form-input" value="<?php echo htmlspecialchars($district ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Mesaj *</label>
                        <textarea id="message" name="message" class="form-textarea" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Mesaj Gönder
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h2>İletişim Bilgileri</h2>
                
                <div class="card">
                    <div class="card-content">
                        <h3>Adres</h3>
                        <div class="contact-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>İstanbul, Türkiye</span>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-content">
                        <h3>Telefon</h3>
                        <div class="contact-info">
                            <i class="fas fa-phone"></i>
                            <span>+90 (212) 123 45 67</span>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-content">
                        <h3>E-posta</h3>
                        <div class="contact-info">
                            <i class="fas fa-envelope"></i>
                            <span>info@yereltanitim.com</span>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-content">
                        <h3>Çalışma Saatleri</h3>
                        <div class="contact-info">
                            <i class="fas fa-clock"></i>
                            <span>Pazartesi - Cuma: 09:00 - 18:00</span>
                        </div>
                        <div class="contact-info">
                            <i class="fas fa-clock"></i>
                            <span>Cumartesi: 10:00 - 16:00</span>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-content">
                        <h3>Sosyal Medya</h3>
                        <div class="social-links">
                            <a href="#" class="social-link" aria-label="Facebook">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section section-light">
    <div class="container">
        <h2 class="section-title">Sık Sorulan Sorular</h2>
        
        <div class="grid grid-2">
            <div class="card">
                <div class="card-content">
                    <h3>Site nasıl kullanılır?</h3>
                    <p>Ana sayfadan şehirleri keşfedebilir, blog yazılarını okuyabilir ve istediğiniz şehir hakkında detaylı bilgi alabilirsiniz.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3>İçerik önerisi nasıl yapabilirim?</h3>
                    <p>İletişim formunu kullanarak önerilerinizi bize iletebilirsiniz. En kısa sürede değerlendirip size dönüş yapacağız.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3>Blog yazısı nasıl yazabilirim?</h3>
                    <p>Admin paneline giriş yaparak yeni blog yazıları ekleyebilir veya mevcut yazıları düzenleyebilirsiniz.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3>Şehir bilgileri güncel mi?</h3>
                    <p>Evet, tüm şehir ve ilçe bilgileri düzenli olarak güncellenmektedir. Güncel olmayan bilgi fark ederseniz lütfen bize bildirin.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>