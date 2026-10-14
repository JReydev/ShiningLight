<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ' . SITE_URL . '/admin/contacts.php'); exit; }
$pdo = getDB();
$pdo->prepare("UPDATE contact_submissions SET is_read = 1 WHERE id = ?")->execute([$id]);
$stmt = $pdo->prepare("SELECT * FROM contact_submissions WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();
if (!$c) { header('Location: ' . SITE_URL . '/admin/contacts.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <p><a href="<?php echo SITE_URL; ?>/admin/contacts.php">&larr; Contacts</a></p>
    <div class="content-block">
        <p><strong>From:</strong> <?php echo e($c['name']); ?> &lt;<a href="mailto:<?php echo e($c['email']); ?>"><?php echo e($c['email']); ?></a>&gt;</p>
        <p><strong>Phone:</strong> <?php echo e($c['phone'] ?: '-'); ?></p>
        <p><strong>Subject:</strong> <?php echo e($c['subject'] ?: '-'); ?></p>
        <p><strong>Date:</strong> <?php echo date('F j, Y g:i A', strtotime($c['submitted_at'])); ?></p>
        <hr>
        <div><?php echo nl2br(e($c['message'])); ?></div>
    </div>
</div>
</body>
</html>
