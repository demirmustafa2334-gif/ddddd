<?php
// Get recent activities
$recentPosts = $db->query("
    SELECT bp.*, c.name as city_name 
    FROM blog_posts bp 
    LEFT JOIN cities c ON bp.city_id = c.id 
    ORDER BY bp.created_at DESC 
    LIMIT 5
")->fetchAll();

$recentMessages = $db->query("
    SELECT * FROM contact_messages 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();

$recentCities = $db->query("
    SELECT * FROM cities 
    WHERE is_active = 1 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();
?>

<div class="content-area">
    <h2>Dashboard</h2>
    <p>Yerel Tanıtım yönetim paneline hoş geldiniz. Aşağıda site istatistiklerini ve son aktiviteleri görebilirsiniz.</p>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['cities']; ?></div>
            <div class="stat-label">Aktif Şehir</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['districts']; ?></div>
            <div class="stat-label">Aktif İlçe</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['blog_posts']; ?></div>
            <div class="stat-label">Yayınlanan Blog Yazısı</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['contact_messages']; ?></div>
            <div class="stat-label">Toplam İletişim Mesajı</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: var(--danger);"><?php echo $stats['unread_messages']; ?></div>
            <div class="stat-label">Okunmamış Mesaj</div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <!-- Recent Blog Posts -->
        <div>
            <h3><i class="fas fa-blog"></i> Son Blog Yazıları</h3>
            <div class="table-container" style="background: white; border-radius: 8px; box-shadow: var(--shadow); overflow: hidden;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Başlık</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPosts as $post): ?>
                        <tr>
                            <td>
                                <a href="?page=blog&action=edit&id=<?php echo $post['id']; ?>" style="text-decoration: none;">
                                    <?php echo truncateText($post['title'], 30); ?>
                                </a>
                                <?php if ($post['city_name']): ?>
                                <br><small style="color: var(--text-light);"><?php echo $post['city_name']; ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($post['is_published']): ?>
                                <span class="badge badge-success">Yayında</span>
                                <?php else: ?>
                                <span class="badge badge-warning">Taslak</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo formatDate($post['created_at']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 1rem;">
                <a href="?page=blog" class="btn btn-primary">Tüm Blog Yazıları</a>
            </div>
        </div>
        
        <!-- Recent Contact Messages -->
        <div>
            <h3><i class="fas fa-envelope"></i> Son İletişim Mesajları</h3>
            <div class="table-container" style="background: white; border-radius: 8px; box-shadow: var(--shadow); overflow: hidden;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Gönderen</th>
                            <th>Konu</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentMessages as $message): ?>
                        <tr>
                            <td>
                                <?php echo $message['name']; ?>
                                <br><small style="color: var(--text-light);"><?php echo $message['email']; ?></small>
                            </td>
                            <td><?php echo truncateText($message['subject'], 20); ?></td>
                            <td>
                                <?php if ($message['is_read']): ?>
                                <span class="badge badge-success">Okundu</span>
                                <?php else: ?>
                                <span class="badge badge-danger">Okunmadı</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo formatDate($message['created_at']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 1rem;">
                <a href="?page=contact" class="btn btn-primary">Tüm Mesajlar</a>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div style="margin-top: 2rem;">
        <h3><i class="fas fa-bolt"></i> Hızlı İşlemler</h3>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
            <a href="?page=blog&action=add" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Blog Yazısı
            </a>
            <a href="?page=cities&action=add" class="btn btn-success">
                <i class="fas fa-plus"></i> Yeni Şehir
            </a>
            <a href="?page=districts&action=add" class="btn btn-warning">
                <i class="fas fa-plus"></i> Yeni İlçe
            </a>
            <a href="/" target="_blank" class="btn btn-secondary">
                <i class="fas fa-external-link-alt"></i> Siteyi Görüntüle
            </a>
        </div>
    </div>
    
    <!-- System Info -->
    <div style="margin-top: 2rem;">
        <h3><i class="fas fa-info-circle"></i> Sistem Bilgileri</h3>
        <div style="background: white; border-radius: 8px; box-shadow: var(--shadow); padding: 1.5rem; margin-top: 1rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <strong>PHP Sürümü:</strong> <?php echo PHP_VERSION; ?>
                </div>
                <div>
                    <strong>Veritabanı:</strong> MySQL
                </div>
                <div>
                    <strong>Sunucu:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor'; ?>
                </div>
                <div>
                    <strong>Son Güncelleme:</strong> <?php echo date('d.m.Y H:i'); ?>
                </div>
            </div>
        </div>
    </div>
</div>