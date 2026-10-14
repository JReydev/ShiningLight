<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);
$pdo = getDB();

$filter = $_GET['status'] ?? '';
$where = $filter !== '' ? "WHERE status = " . $pdo->quote($filter) : "";
$stmt = $pdo->query("SELECT id, application_number, status, student_first_name, student_last_name, grade_level, school_year, submitted_at FROM enrollments $where ORDER BY submitted_at DESC");
$list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <h1>Enrollments</h1>
    <p>Filter: <a href="?">All</a> | <a href="?status=pending">Pending</a> | <a href="?status=under_review">Under Review</a> | <a href="?status=approved">Approved</a> | <a href="?status=rejected">Rejected</a></p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>App #</th>
                    <th>Student</th>
                    <th>Grade / Year</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?php echo e($row['application_number']); ?></td>
                    <td><?php echo e($row['student_first_name'] . ' ' . $row['student_last_name']); ?></td>
                    <td><?php echo e($row['grade_level'] . ' / ' . $row['school_year']); ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['submitted_at'])); ?></td>
                    <td><span class="badge badge-<?php echo e($row['status']); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $row['status']))); ?></span></td>
                    <td><a href="<?php echo SITE_URL; ?>/admin/enrollment-view.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-primary" style="padding:0.35rem 0.75rem;">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if (empty($list)): ?><p>No enrollments found.</p><?php endif; ?>
</div>
</body>
</html>
