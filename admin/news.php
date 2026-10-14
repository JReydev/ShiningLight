<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
$stmt = $pdo->query("SELECT id, title, slug, is_published, published_at, created_at FROM news ORDER BY created_at DESC");
$list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <h1>News &amp; Announcements</h1>
    <p><a href="<?php echo SITE_URL; ?>/admin/news-edit.php" class="btn btn-primary">Add New</a></p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Published</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?php echo e($row['title']); ?></td>
                    <td><?php echo $row['is_published'] ? 'Yes' : 'Draft'; ?></td>
                    <td><?php echo $row['published_at'] ? date('M j, Y', strtotime($row['published_at'])) : '-'; ?></td>
                    <td>
                        <a href="<?php echo SITE_URL; ?>/admin/news-edit.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-primary" style="padding:0.35rem 0.75rem;">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
