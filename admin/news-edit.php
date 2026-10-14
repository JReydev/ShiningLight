<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
$id = (int)($_GET['id'] ?? 0);
$item = null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $slug = $id && $item ? $item['slug'] : slugify($title);
    if ($id && $item) {
        $stmt = $pdo->prepare("UPDATE news SET title = ?, slug = ?, excerpt = ?, content = ?, is_published = ?, published_at = IF(? = 1 AND (published_at IS NULL), NOW(), published_at), author_id = ? WHERE id = ?");
        $stmt->execute([$title, $slug, $excerpt, $content, $is_published, $is_published, $_SESSION['admin_id'], $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO news (title, slug, excerpt, content, is_published, published_at, author_id) VALUES (?, ?, ?, ?, ?, IF(? = 1, NOW(), NULL), ?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $is_published, $is_published, $_SESSION['admin_id']]);
    }
    if (!empty($_FILES['featured_image']['name']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        list($ok, $path) = safeUpload($_FILES['featured_image'], 'news');
        if ($ok) {
            $nid = $id ?: $pdo->lastInsertId();
            $pdo->prepare("UPDATE news SET featured_image = ? WHERE id = ?")->execute([$path, $nid]);
        }
    }
    header('Location: ' . SITE_URL . '/admin/news.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $item ? 'Edit' : 'Add'; ?> News | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <p><a href="<?php echo SITE_URL; ?>/admin/news.php">&larr; News</a></p>
    <h1><?php echo $item ? 'Edit' : 'Add'; ?> Article</h1>
    <form method="post" enctype="multipart/form-data" class="content-block">
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="<?php echo e($item['title'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Excerpt</label>
            <textarea name="excerpt" class="form-control" rows="2"><?php echo e($item['excerpt'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Content *</label>
            <textarea name="content" class="form-control" rows="12" required><?php echo e($item['content'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Featured image</label>
            <input type="file" name="featured_image" accept=".jpg,.jpeg,.png,.gif">
            <?php if (!empty($item['featured_image'])): ?><br><img src="<?php echo SITE_URL . '/uploads/' . e($item['featured_image']); ?>" alt="" style="max-width:200px; margin-top:0.5rem;"><?php endif; ?>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="is_published" value="1" <?php echo ($item['is_published'] ?? 0) ? 'checked' : ''; ?>> Published</label>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
</body>
</html>
