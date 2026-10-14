<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $did = (int)$_POST['delete_id'];
    $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([$did]);
    header('Location: ' . SITE_URL . '/admin/gallery.php');
    exit;
}
$stmt = $pdo->query("SELECT id, title, image_path, category, sort_order FROM gallery ORDER BY sort_order, id DESC");
$list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <h1>Gallery</h1>
    <p><a href="<?php echo SITE_URL; ?>/admin/gallery-edit.php" class="btn btn-primary">Add Image</a></p>
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap:1rem;">
        <?php foreach ($list as $row): ?>
        <div class="card">
            <img src="<?php echo SITE_URL . '/uploads/' . e($row['image_path']); ?>" alt="" style="width:100%; height:140px; object-fit:cover;">
            <div class="card-body">
                <strong><?php echo e($row['title'] ?: 'Image #' . $row['id']); ?></strong>
                <form method="post" style="margin-top:0.5rem;">
                    <input type="hidden" name="delete_id" value="<?php echo (int)$row['id']; ?>">
                    <a href="<?php echo SITE_URL; ?>/admin/gallery-edit.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-primary" style="padding:0.25rem 0.5rem;">Edit</a>
                    <button type="submit" class="btn" style="padding:0.25rem 0.5rem; background:#fee2e2; color:#b91c1c;" onclick="return confirm('Delete this image?');">Delete</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
