<?php
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'reply' && $id) {
        $reply_message = sanitizeInput($_POST['reply_message'] ?? '');
        
        if (!empty($reply_message)) {
            $stmt = $db->prepare("UPDATE contact_messages SET reply_message = ?, is_replied = 1, replied_at = NOW(), replied_by = ? WHERE id = ?");
            if ($stmt->execute([$reply_message, $userId, $id])) {
                $success = 'Yanıt başarıyla gönderildi.';
            } else {
                $error = 'Yanıt gönderilirken hata oluştu.';
            }
        } else {
            $error = 'Yanıt mesajı gereklidir.';
        }
    } elseif ($action === 'mark_read' && $id) {
        $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success = 'Mesaj okundu olarak işaretlendi.';
        } else {
            $error = 'Mesaj işaretlenirken hata oluştu.';
        }
    } elseif ($action === 'delete' && $id) {
        $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success = 'Mesaj başarıyla silindi.';
        } else {
            $error = 'Mesaj silinirken hata oluştu.';
        }
    }
}

// Get contact message for view/reply
$message = null;
if ($action === 'view' || $action === 'reply') {
    $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $message = $stmt->fetch();
}

// Get contact messages list
if ($action === 'list') {
    $page = (int)($_GET['page'] ?? 1);
    $limit = 20;
    $offset = ($page - 1) * $limit;
    $filter = $_GET['filter'] ?? 'all';
    
    $whereClause = '';
    $params = [];
    
    if ($filter === 'unread') {
        $whereClause = 'WHERE is_read = 0';
    } elseif ($filter === 'replied') {
        $whereClause = 'WHERE is_replied = 1';
    }
    
    $messages = $db->query("
        SELECT * FROM contact_messages 
        $whereClause 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset
    ")->fetchAll();
    
    $totalMessages = $db->query("SELECT COUNT(*) as count FROM contact_messages $whereClause")->fetch()['count'];
    $totalPages = ceil($totalMessages / $limit);
}

// Get statistics
$stats = [
    'total' => $db->query("SELECT COUNT(*) as count FROM contact_messages")->fetch()['count'],
    'unread' => $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0")->fetch()['count'],
    'replied' => $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_replied = 1")->fetch()['count']
];
?>

<div class="content-area">
    <?php if ($action === 'list'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>İletişim Mesajları</h2>
        <div style="display: flex; gap: 1rem;">
            <a href="?page=contact&filter=all" class="btn <?php echo $filter === 'all' ? 'btn-primary' : 'btn-secondary'; ?>">
                Tümü (<?php echo $stats['total']; ?>)
            </a>
            <a href="?page=contact&filter=unread" class="btn <?php echo $filter === 'unread' ? 'btn-primary' : 'btn-secondary'; ?>">
                Okunmamış (<?php echo $stats['unread']; ?>)
            </a>
            <a href="?page=contact&filter=replied" class="btn <?php echo $filter === 'replied' ? 'btn-primary' : 'btn-secondary'; ?>">
                Yanıtlanmış (<?php echo $stats['replied']; ?>)
            </a>
        </div>
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
                <th>Gönderen</th>
                <th>E-posta</th>
                <th>Konu</th>
                <th>Şehir/İlçe</th>
                <th>Durum</th>
                <th>Tarih</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $msg): ?>
            <tr style="<?php echo !$msg['is_read'] ? 'background-color: #fff3cd;' : ''; ?>">
                <td>
                    <strong><?php echo $msg['name']; ?></strong>
                    <?php if ($msg['phone']): ?>
                    <br><small style="color: var(--text-light);"><?php echo $msg['phone']; ?></small>
                    <?php endif; ?>
                </td>
                <td><?php echo $msg['email']; ?></td>
                <td><?php echo truncateText($msg['subject'], 30); ?></td>
                <td>
                    <?php if ($msg['city']): ?>
                    <?php echo $msg['city']; ?>
                    <?php if ($msg['district']): ?>
                    <br><small style="color: var(--text-light);"><?php echo $msg['district']; ?></small>
                    <?php endif; ?>
                    <?php else: ?>
                    -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($msg['is_read']): ?>
                    <span class="badge badge-success">Okundu</span>
                    <?php else: ?>
                    <span class="badge badge-danger">Okunmadı</span>
                    <?php endif; ?>
                    <?php if ($msg['is_replied']): ?>
                    <br><span class="badge badge-info">Yanıtlandı</span>
                    <?php endif; ?>
                </td>
                <td><?php echo formatDate($msg['created_at']); ?></td>
                <td>
                    <a href="?page=contact&action=view&id=<?php echo $msg['id']; ?>" class="btn btn-info" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-eye"></i> Görüntüle
                    </a>
                    <?php if (!$msg['is_read']): ?>
                    <a href="?page=contact&action=mark_read&id=<?php echo $msg['id']; ?>" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                        <i class="fas fa-check"></i> Okundu İşaretle
                    </a>
                    <?php endif; ?>
                    <a href="?page=contact&action=delete&id=<?php echo $msg['id']; ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" onclick="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')">
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
        <a href="?page=contact&p=<?php echo $i; ?>&filter=<?php echo $filter; ?>" class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-secondary'; ?>" style="margin: 0 0.25rem;">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    
    <?php elseif ($action === 'view' || $action === 'reply'): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Mesaj Detayı</h2>
        <a href="?page=contact" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
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
    
    <?php if ($message): ?>
    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow); margin-bottom: 2rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h3>Gönderen Bilgileri</h3>
                <p><strong>Ad Soyad:</strong> <?php echo $message['name']; ?></p>
                <p><strong>E-posta:</strong> <a href="mailto:<?php echo $message['email']; ?>"><?php echo $message['email']; ?></a></p>
                <?php if ($message['phone']): ?>
                <p><strong>Telefon:</strong> <a href="tel:<?php echo $message['phone']; ?>"><?php echo $message['phone']; ?></a></p>
                <?php endif; ?>
            </div>
            <div>
                <h3>Mesaj Bilgileri</h3>
                <p><strong>Konu:</strong> <?php echo $message['subject']; ?></p>
                <p><strong>Tarih:</strong> <?php echo formatDate($message['created_at'], 'd.m.Y H:i'); ?></p>
                <?php if ($message['city']): ?>
                <p><strong>Şehir:</strong> <?php echo $message['city']; ?></p>
                <?php endif; ?>
                <?php if ($message['district']): ?>
                <p><strong>İlçe:</strong> <?php echo $message['district']; ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div>
            <h3>Mesaj İçeriği</h3>
            <div style="background: var(--background-light); padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
            </div>
        </div>
        
        <?php if ($message['reply_message']): ?>
        <div>
            <h3>Yanıt</h3>
            <div style="background: #d4edda; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                <?php echo nl2br(htmlspecialchars($message['reply_message'])); ?>
            </div>
            <p><small><strong>Yanıt Tarihi:</strong> <?php echo formatDate($message['replied_at'], 'd.m.Y H:i'); ?></small></p>
        </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <?php if (!$message['is_read']): ?>
            <a href="?page=contact&action=mark_read&id=<?php echo $message['id']; ?>" class="btn btn-warning">
                <i class="fas fa-check"></i> Okundu İşaretle
            </a>
            <?php endif; ?>
            
            <?php if (!$message['is_replied']): ?>
            <a href="?page=contact&action=reply&id=<?php echo $message['id']; ?>" class="btn btn-primary">
                <i class="fas fa-reply"></i> Yanıtla
            </a>
            <?php endif; ?>
            
            <a href="mailto:<?php echo $message['email']; ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" class="btn btn-info">
                <i class="fas fa-envelope"></i> E-posta Gönder
            </a>
        </div>
    </div>
    
    <?php if ($action === 'reply'): ?>
    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
        <h3>Yanıt Gönder</h3>
        <form method="POST">
            <div class="form-group">
                <label for="reply_message" class="form-label">Yanıt Mesajı *</label>
                <textarea id="reply_message" name="reply_message" class="form-textarea" rows="6" required placeholder="Yanıtınızı buraya yazın..."></textarea>
            </div>
            <div style="text-align: center;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Yanıt Gönder
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> Mesaj bulunamadı.
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>