<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
$id = (int)($_GET['id'] ?? 0);
$item = null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM faculty WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $photo = $item['photo'] ?? '';
    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        list($ok, $path) = safeUpload($_FILES['photo'], 'faculty');
        if ($ok) $photo = $path;
    }
    if ($id && $item) {
        $stmt = $pdo->prepare("UPDATE faculty SET full_name=?, title=?, department=?, bio=?, email=?, photo=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([$full_name, $title, $department, $bio, $email, $photo, $sort_order, $is_active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO faculty (full_name, title, department, bio, email, photo, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$full_name, $title, $department, $bio, $email, $photo, $sort_order, $is_active]);
    }
    header('Location: ' . SITE_URL . '/admin/faculty.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $item ? 'Edit' : 'Add'; ?> Faculty | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <p><a href="<?php echo SITE_URL; ?>/admin/faculty.php">&larr; Faculty</a></p>
    <h1><?php echo $item ? 'Edit' : 'Add'; ?> Faculty Member</h1>
    <form method="post" enctype="multipart/form-data" class="content-block">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="full_name" class="form-control" value="<?php echo e($item['full_name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo e($item['title'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="<?php echo e($item['department'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" class="form-control" rows="4"><?php echo e($item['bio'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo e($item['email'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Photo</label>
            <input type="file" name="photo" accept=".jpg,.jpeg,.png,.gif">
            <?php if (!empty($item['photo'])): ?><br><img src="<?php echo SITE_URL . '/uploads/' . e($item['photo']); ?>" alt="" style="max-width:120px;"><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Sort order</label>
            <input type="number" name="sort_order" class="form-control" value="<?php echo (int)($item['sort_order'] ?? 0); ?>">
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="is_active" value="1" <?php echo ($item['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
</body>
</html>
