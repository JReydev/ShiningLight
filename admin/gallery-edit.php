<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
$id = (int)($_GET['id'] ?? 0);
$item = null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $image_path = $item['image_path'] ?? '';
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        list($ok, $path) = safeUpload($_FILES['image'], 'gallery');
        if ($ok) $image_path = $path;
    }
    if ($id && $item && $image_path) {
        $stmt = $pdo->prepare("UPDATE gallery SET title=?, description=?, category=?, image_path=?, sort_order=? WHERE id=?");
        $stmt->execute([$title, $description, $category, $image_path, $sort_order, $id]);
    } elseif (!$id && $image_path) {
        $stmt = $pdo->prepare("INSERT INTO gallery (title, description, category, image_path, sort_order) VALUES (?,?,?,?,?)");
        $stmt->execute([$title, $description, $category, $image_path, $sort_order]);
    }
    if ($id || $image_path) {
        header('Location: ' . SITE_URL . '/admin/gallery.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $item ? 'Edit' : 'Add'; ?> Gallery | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <p><a href="<?php echo SITE_URL; ?>/admin/gallery.php">&larr; Gallery</a></p>
    <h1><?php echo $item ? 'Edit' : 'Add'; ?> Image</h1>
    <form method="post" enctype="multipart/form-data" class="content-block">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo e($item['title'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="2"><?php echo e($item['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="<?php echo e($item['category'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Image <?php echo $item ? '(leave empty to keep current)' : '*'; ?></label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif" <?php echo $item ? '' : 'required'; ?>>
            <?php if (!empty($item['image_path'])): ?><br><img src="<?php echo SITE_URL . '/uploads/' . e($item['image_path']); ?>" alt="" style="max-width:200px;"><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Sort order</label>
            <input type="number" name="sort_order" class="form-control" value="<?php echo (int)($item['sort_order'] ?? 0); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
</body>
</html>
