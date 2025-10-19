<?php
session_start();
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: auth.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: auth.php');
    exit;
}

// Get current page
$page = $_GET['page'] ?? 'dashboard';

// Get user info
$userId = $_SESSION['admin_user_id'];
$userRole = $_SESSION['admin_role'];
$userName = $_SESSION['admin_name'];

// Check permissions
function hasPermission($requiredRole) {
    global $userRole;
    $roles = ['author' => 1, 'editor' => 2, 'admin' => 3];
    return $roles[$userRole] >= $roles[$requiredRole];
}

// Get statistics
$stats = [
    'cities' => $db->query("SELECT COUNT(*) as count FROM cities WHERE is_active = 1")->fetch()['count'],
    'districts' => $db->query("SELECT COUNT(*) as count FROM districts WHERE is_active = 1")->fetch()['count'],
    'blog_posts' => $db->query("SELECT COUNT(*) as count FROM blog_posts WHERE is_published = 1")->fetch()['count'],
    'contact_messages' => $db->query("SELECT COUNT(*) as count FROM contact_messages")->fetch()['count'],
    'unread_messages' => $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0")->fetch()['count']
];

// Include the appropriate page
$allowedPages = ['dashboard', 'cities', 'districts', 'blog', 'contact', 'users', 'settings'];
if (!in_array($page, $allowedPages)) {
    $page = 'dashboard';
}

// Check page permissions
if ($page === 'users' && !hasPermission('admin')) {
    $page = 'dashboard';
}
if ($page === 'settings' && !hasPermission('admin')) {
    $page = 'dashboard';
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-color: #2c5530;
            --secondary-color: #8B4513;
            --accent-color: #FFD700;
            --text-color: #333;
            --text-light: #666;
            --background: #fff;
            --background-light: #f8f9fa;
            --border-color: #e9ecef;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --shadow: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-medium: 0 4px 8px rgba(0,0,0,0.12);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-light);
            color: var(--text-color);
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: var(--primary-color);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h2 {
            color: var(--accent-color);
            margin-bottom: 0.5rem;
        }
        
        .sidebar-header p {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            list-style: none;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }
        
        .header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            margin: -2rem -2rem 2rem -2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: var(--primary-color);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            font-weight: 600;
        }
        
        .logout-btn {
            background: var(--danger);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .content-area {
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        
        .btn-warning {
            background-color: var(--warning);
            color: var(--text-color);
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-secondary {
            background-color: var(--text-light);
            color: white;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table th {
            background-color: var(--background-light);
            font-weight: 600;
        }
        
        .table tr:hover {
            background-color: var(--background-light);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-input,
        .form-textarea,
        .form-select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .form-textarea {
            height: 100px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cog"></i> Admin Panel</h2>
                <p>Hoş geldiniz, <?php echo $userName; ?></p>
            </div>
            
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="?page=dashboard" class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=cities" class="nav-link <?php echo $page === 'cities' ? 'active' : ''; ?>">
                        <i class="fas fa-city"></i>
                        Şehirler
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=districts" class="nav-link <?php echo $page === 'districts' ? 'active' : ''; ?>">
                        <i class="fas fa-map-marker-alt"></i>
                        İlçeler
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=blog" class="nav-link <?php echo $page === 'blog' ? 'active' : ''; ?>">
                        <i class="fas fa-blog"></i>
                        Blog Yazıları
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=contact" class="nav-link <?php echo $page === 'contact' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i>
                        İletişim Mesajları
                        <?php if ($stats['unread_messages'] > 0): ?>
                        <span class="badge badge-danger"><?php echo $stats['unread_messages']; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if (hasPermission('admin')): ?>
                <li class="nav-item">
                    <a href="?page=users" class="nav-link <?php echo $page === 'users' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i>
                        Kullanıcılar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=settings" class="nav-link <?php echo $page === 'settings' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        Ayarlar
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1><?php echo ucfirst($page); ?></h1>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($userName, 0, 1)); ?>
                    </div>
                    <span><?php echo $userName; ?> (<?php echo ucfirst($userRole); ?>)</span>
                    <a href="?logout=1" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Çıkış
                    </a>
                </div>
            </div>
            
            <?php
            // Include the appropriate page content
            $pageFile = "pages/{$page}.php";
            if (file_exists($pageFile)) {
                include $pageFile;
            } else {
                echo '<div class="content-area"><h2>Sayfa bulunamadı</h2></div>';
            }
            ?>
        </main>
    </div>
    
    <script>
        // Mobile menu toggle
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('open');
        }
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
        
        // Confirm delete actions
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-danger') && e.target.textContent.includes('Sil')) {
                if (!confirm('Bu öğeyi silmek istediğinizden emin misiniz?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>