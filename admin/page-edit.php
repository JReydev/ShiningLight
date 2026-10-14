<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: ' . SITE_URL . '/admin/pages.php'); exit; }
$stmt = $pdo->prepare("SELECT * FROM page_content WHERE page_slug = ?");
$stmt->execute([$slug]);
$item = $stmt->fetch();
if (!$item) { header('Location: ' . SITE_URL . '/admin/pages.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $stmt = $pdo->prepare("UPDATE page_content SET title = ?, content = ?, meta_description = ? WHERE page_slug = ?");
    $stmt->execute([$title, $content, $meta_description, $slug]);
    header('Location: ' . SITE_URL . '/admin/pages.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo e($item['title']); ?> | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <p><a href="<?php echo SITE_URL; ?>/admin/pages.php">&larr; Pages</a></p>
    <h1>Edit: <?php echo e($item['title']); ?></h1>
    <form method="post" class="content-block">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo e($item['title']); ?>">
        </div>
        <div class="form-group">
            <label>Content (HTML allowed)</label>
            <textarea name="content" class="form-control" rows="12"><?php echo e($item['content'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Meta description</label>
            <input type="text" name="meta_description" class="form-control" value="<?php echo e($item['meta_description'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
</body>
</html>
