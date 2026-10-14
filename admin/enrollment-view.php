<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'editor']);

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ' . SITE_URL . '/admin/enrollments.php'); exit; }
$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM enrollments WHERE id = ?");
$stmt->execute([$id]);
$e = $stmt->fetch();
if (!$e) { header('Location: ' . SITE_URL . '/admin/enrollments.php'); exit; }

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    if (in_array($new_status, ['pending', 'under_review', 'approved', 'rejected'])) {
        $admin_notes = trim($_POST['admin_notes'] ?? '');
        $stmt = $pdo->prepare("UPDATE enrollments SET status = ?, admin_notes = ?, reviewed_at = NOW(), reviewed_by = ? WHERE id = ?");
        $stmt->execute([$new_status, $admin_notes, $_SESSION['admin_id'], $id]);
        $e['status'] = $new_status;
        $e['admin_notes'] = $admin_notes;

        $parent_email = $e['parent1_email'];
        $student_name = $e['student_first_name'] . ' ' . $e['student_last_name'];
        $app = $e['application_number'];
        if ($new_status === 'approved') {
            $subj = 'Enrollment Approved - ' . SITE_NAME;
            $body = '<p>Dear Parent/Guardian,</p><p>We are pleased to inform you that the enrollment application for <strong>' . htmlspecialchars($student_name) . '</strong> (Application #' . htmlspecialchars($app) . ') has been <strong>approved</strong>.</p><p>Please contact our office for next steps.</p><p>Blessings,<br>' . SITE_NAME . '</p>';
        } elseif ($new_status === 'rejected') {
            $subj = 'Enrollment Application Update - ' . SITE_NAME;
            $body = '<p>Dear Parent/Guardian,</p><p>Regarding the enrollment application for <strong>' . htmlspecialchars($student_name) . '</strong> (Application #' . htmlspecialchars($app) . '): after review, we are unable to approve the application at this time.</p><p>If you have questions, please contact our Admissions Office.</p><p>' . SITE_NAME . '</p>';
        } else {
            $subj = 'Enrollment Status Update - ' . SITE_NAME;
            $body = '<p>Your application #' . htmlspecialchars($app) . ' for ' . htmlspecialchars($student_name) . ' is now: ' . htmlspecialchars(ucfirst(str_replace('_', ' ', $new_status))) . '.</p><p>Check status: ' . SITE_URL . '/enroll-status.php?app=' . urlencode($app) . '</p><p>' . SITE_NAME . '</p>';
        }
        sendMail($parent_email, $subj, '<!DOCTYPE html><html><body>' . $body . '</body></html>');
    }
    header('Location: ' . SITE_URL . '/admin/enrollment-view.php?id=' . $id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment #<?php echo e($e['application_number']); ?> | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <p><a href="<?php echo SITE_URL; ?>/admin/enrollments.php">&larr; Back to Enrollments</a></p>
    <h1>Application: <?php echo e($e['application_number']); ?></h1>
    <p>Status: <span class="badge badge-<?php echo e($e['status']); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $e['status']))); ?></span> &nbsp; Submitted: <?php echo date('F j, Y g:i A', strtotime($e['submitted_at'])); ?></p>

    <div class="content-block">
        <h2>Student</h2>
        <p><strong><?php echo e($e['student_first_name'] . ' ' . $e['student_last_name'] . ($e['student_middle_name'] ? ' ' . $e['student_middle_name'] : '')); ?></strong><br>
        DOB: <?php echo e($e['date_of_birth']); ?> &nbsp; Gender: <?php echo e(ucfirst($e['gender'])); ?><br>
        Grade: <?php echo e($e['grade_level']); ?> &nbsp; School Year: <?php echo e($e['school_year']); ?><br>
        Previous School: <?php echo e($e['previous_school'] ?: '-'); ?></p>
    </div>
    <div class="content-block">
        <h2>Parent/Guardian 1</h2>
        <p><?php echo e($e['parent1_name']); ?> (<?php echo e($e['parent1_relation']); ?>)<br>
        Phone: <?php echo e($e['parent1_phone']); ?> &nbsp; Email: <?php echo e($e['parent1_email']); ?><br>
        Address: <?php echo nl2br(e($e['parent1_address'] ?: '-')); ?></p>
    </div>
    <?php if (!empty($e['parent2_name'])): ?>
    <div class="content-block">
        <h2>Parent/Guardian 2</h2>
        <p><?php echo e($e['parent2_name']); ?> (<?php echo e($e['parent2_relation']); ?>)<br>
        Phone: <?php echo e($e['parent2_phone']); ?> &nbsp; Email: <?php echo e($e['parent2_email']); ?></p>
    </div>
    <?php endif; ?>
    <div class="content-block">
        <h2>Documents</h2>
        <p>
            <a href="<?php echo SITE_URL; ?>/uploads/<?php echo e($e['document_birth_cert']); ?>" target="_blank">Birth Certificate</a> &nbsp;
            <a href="<?php echo SITE_URL; ?>/uploads/<?php echo e($e['document_report_card']); ?>" target="_blank">Report Card</a> &nbsp;
            <a href="<?php echo SITE_URL; ?>/uploads/<?php echo e($e['document_photo']); ?>" target="_blank">ID Photo</a>
            <?php if (!empty($e['document_other'])): ?> &nbsp; <a href="<?php echo SITE_URL; ?>/uploads/<?php echo e($e['document_other']); ?>" target="_blank">Other</a><?php endif; ?>
        </p>
    </div>
    <?php if (!empty($e['notes'])): ?>
    <div class="content-block"><h2>Applicant Notes</h2><p><?php echo nl2br(e($e['notes'])); ?></p></div>
    <?php endif; ?>

    <div class="content-block">
        <h2>Update Status</h2>
        <form method="post">
            <div class="form-group">
                <label>Admin notes</label>
                <textarea name="admin_notes" class="form-control" rows="2"><?php echo e($e['admin_notes'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="pending" <?php echo $e['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="under_review" <?php echo $e['status'] === 'under_review' ? 'selected' : ''; ?>>Under Review</option>
                    <option value="approved" <?php echo $e['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="rejected" <?php echo $e['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update &amp; Notify</button>
        </form>
    </div>
</div>
</body>
</html>
