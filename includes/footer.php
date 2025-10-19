    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Site Info -->
                <div class="footer-section">
                    <h3><?php echo SITE_NAME; ?></h3>
                    <p>
                        Türkiye'nin tüm şehir ve ilçelerini keşfedin. Turistik yerler, 
                        yerel mutfak, kültürel özellikler ve daha fazlası için kapsamlı rehber.
                    </p>
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
                
                <!-- Quick Links -->
                <div class="footer-section">
                    <h3>Hızlı Linkler</h3>
                    <div class="footer-links">
                        <a href="/" class="footer-link">Ana Sayfa</a>
                        <a href="/sehirler" class="footer-link">Tüm Şehirler</a>
                        <a href="/blog" class="footer-link">Blog</a>
                        <a href="/iletisim" class="footer-link">İletişim</a>
                        <a href="/admin" class="footer-link">Admin Panel</a>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="footer-section">
                    <h3>İletişim Bilgileri</h3>
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>İstanbul, Türkiye</span>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-phone"></i>
                        <span>+90 (212) 123 45 67</span>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i>
                        <span>info@yereltanitim.com</span>
                    </div>
                </div>
                
                <!-- Popular Cities -->
                <div class="footer-section">
                    <h3>Popüler Şehirler</h3>
                    <div class="footer-links">
                        <a href="/sehir/istanbul" class="footer-link">İstanbul</a>
                        <a href="/sehir/ankara" class="footer-link">Ankara</a>
                        <a href="/sehir/izmir" class="footer-link">İzmir</a>
                        <a href="/sehir/antalya" class="footer-link">Antalya</a>
                        <a href="/sehir/bursa" class="footer-link">Bursa</a>
                        <a href="/sehir/konya" class="footer-link">Konya</a>
                    </div>
                </div>
            </div>
            
            <!-- SEO Cities -->
            <div class="footer-section">
                <h3>Türkiye Şehirleri (SEO)</h3>
                <div class="cities-grid">
                    <?php 
                    global $turkish_cities;
                    foreach ($turkish_cities as $city): 
                    ?>
                    <span class="city-tag"><?php echo $city; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>
                    © <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Tüm hakları saklıdır. | 
                    <a href="/gizlilik" class="footer-link">Gizlilik Politikası</a> | 
                    <a href="/kullanim-kosullari" class="footer-link">Kullanım Koşulları</a>
                </p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.add('active');
        });
        
        document.getElementById('mobileMenuClose').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.remove('active');
        });
        
        // Close mobile menu when clicking outside
        document.getElementById('mobileMenu').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
        
        // Search form enhancement
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            const query = this.querySelector('.search-input').value.trim();
            if (!query) {
                e.preventDefault();
                alert('Lütfen arama terimi giriniz.');
            }
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Form validation
        function validateForm(form) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--danger)';
                    isValid = false;
                } else {
                    field.style.borderColor = 'var(--border-color)';
                }
            });
            
            return isValid;
        }
        
        // Contact form validation
        const contactForm = document.querySelector('.contact-form form');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                    alert('Lütfen tüm gerekli alanları doldurunuz.');
                }
            });
        }
        
        // Lazy loading for images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
        
        // Add loading state to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.type === 'submit') {
                    this.innerHTML = '<span class="loading"></span> Gönderiliyor...';
                    this.disabled = true;
                }
            });
        });
    </script>
</body>
</html>