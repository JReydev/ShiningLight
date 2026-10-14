<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
$stmt = $pdo->query("SELECT id, full_name, title, department, email, is_active, sort_order FROM faculty ORDER BY sort_order, full_name");
$list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <h1>Faculty &amp; Staff</h1>
    <p><a href="<?php echo SITE_URL; ?>/admin/faculty-edit.php" class="btn btn-primary">Add Member</a></p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?php echo e($row['full_name']); ?></td>
                    <td><?php echo e($row['title'] ?? '-'); ?></td>
                    <td><?php echo e($row['department'] ?? '-'); ?></td>
                    <td><?php echo $row['is_active'] ? 'Yes' : 'No'; ?></td>
                    <td><a href="<?php echo SITE_URL; ?>/admin/faculty-edit.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-primary" style="padding:0.35rem 0.75rem;">Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
