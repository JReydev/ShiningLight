<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
$stmt = $pdo->query("SELECT id, page_slug, title, updated_at FROM page_content ORDER BY page_slug");
$list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pages | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <h1>Page Content</h1>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Title</th>
                    <th>Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?php echo e($row['page_slug']); ?></td>
                    <td><?php echo e($row['title']); ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['updated_at'])); ?></td>
                    <td><a href="<?php echo SITE_URL; ?>/admin/page-edit.php?slug=<?php echo urlencode($row['page_slug']); ?>" class="btn btn-primary" style="padding:0.35rem 0.75rem;">Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
