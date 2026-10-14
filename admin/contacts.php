<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();
if (isset($_GET['mark']) && isset($_GET['id'])) {
    $pdo->prepare("UPDATE contact_submissions SET is_read = 1 WHERE id = ?")->execute([(int)$_GET['id']]);
    header('Location: ' . SITE_URL . '/admin/contacts.php');
    exit;
}
$stmt = $pdo->query("SELECT id, name, email, phone, subject, message, is_read, submitted_at FROM contact_submissions ORDER BY submitted_at DESC");
$list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <h1>Contact Submissions</h1>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Read</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr style="<?php echo !$row['is_read'] ? 'background:#fef3c7;' : ''; ?>">
                    <td><?php echo date('M j, Y g:i A', strtotime($row['submitted_at'])); ?></td>
                    <td><?php echo e($row['name']); ?></td>
                    <td><a href="mailto:<?php echo e($row['email']); ?>"><?php echo e($row['email']); ?></a></td>
                    <td><?php echo e($row['subject'] ?? '-'); ?></td>
                    <td><?php echo $row['is_read'] ? 'Yes' : 'No'; ?></td>
                    <td>
                        <a href="<?php echo SITE_URL; ?>/admin/contact-view.php?id=<?php echo (int)$row['id']; ?>">View</a>
                        <?php if (!$row['is_read']): ?> | <a href="?mark=1&id=<?php echo (int)$row['id']; ?>">Mark read</a><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
