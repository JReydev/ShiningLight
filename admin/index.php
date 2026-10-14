<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

try {
    $pdo = getDB();
    $pending = $pdo->query("SELECT COUNT(*) FROM enrollments WHERE status = 'pending'")->fetchColumn();
    $news_count = $pdo->query("SELECT COUNT(*) FROM news WHERE is_published = 1")->fetchColumn();
    $contacts = $pdo->query("SELECT COUNT(*) FROM contact_submissions WHERE is_read = 0")->fetchColumn();
} catch (Exception $e) { $pending = $news_count = $contacts = 0; }

$page_title = 'Admin Dashboard | ' . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <h1>Dashboard</h1>
    <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
        <div class="card">
            <div class="card-body">
                <h3>Pending Enrollments</h3>
                <p style="font-size:1.5rem; font-weight:700; color:var(--gold);"><?php echo (int)$pending; ?></p>
                <a href="<?php echo SITE_URL; ?>/admin/enrollments.php">View all</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Published News</h3>
                <p style="font-size:1.5rem; font-weight:700;"><?php echo (int)$news_count; ?></p>
                <a href="<?php echo SITE_URL; ?>/admin/news.php">Manage</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Unread Messages</h3>
                <p style="font-size:1.5rem; font-weight:700;"><?php echo (int)$contacts; ?></p>
                <a href="<?php echo SITE_URL; ?>/admin/contacts.php">View</a>
            </div>
        </div>
    </div>
    <p><a href="<?php echo SITE_URL; ?>/" class="btn btn-outline">View Site</a></p>
</div>
</body>
</html>
