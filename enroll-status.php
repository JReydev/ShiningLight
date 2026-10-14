<?php
$current_page = 'enroll';
$page_title = 'Enrollment Status | Shining Light Christian College Inc.';
$meta_desc = 'Check your enrollment application status.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$found = null;
$app_number = isset($_GET['app']) ? trim($_GET['app']) : '';
if ($app_number !== '') {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT application_number, status, student_first_name, student_last_name, grade_level, school_year, submitted_at FROM enrollments WHERE application_number = ?");
        $stmt->execute([$app_number]);
        $found = $stmt->fetch();
    } catch (Exception $e) {}
}
?>
<section class="page-hero">
    <h1>Enrollment Status</h1>
    <p>Check your application status using your application number</p>
</section>

<div class="section">
    <div class="content-block status-panel" style="max-width: 560px; margin: 0 auto;">
        <h2>Track your application</h2>
        <p>Enter the application number from your confirmation email to view its latest status.</p>
        <form method="get" action="">
            <div class="form-group">
                <label for="app">Application Number</label>
                <input type="text" id="app" name="app" class="form-control" value="<?php echo e($app_number); ?>" placeholder="e.g. SL2025-ABC123" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Check Status</button>
        </form>

        <?php if ($app_number !== ''): ?>
            <?php if ($found): ?>
            <div class="alert alert-info status-panel">
                <strong>Applicant:</strong> <?php echo e($found['student_first_name'] . ' ' . $found['student_last_name']); ?><br>
                <strong>Grade Level:</strong> <?php echo e($found['grade_level']); ?> — <?php echo e($found['school_year']); ?><br>
                <strong>Submitted:</strong> <?php echo date('F j, Y g:i A', strtotime($found['submitted_at'])); ?><br>
                <strong>Status:</strong>
                <span class="badge badge-<?php echo $found['status']; ?>"><?php echo e(ucfirst(str_replace('_', ' ', $found['status']))); ?></span>
            </div>
            <?php else: ?>
            <div class="alert alert-error mt-2">No application found with that application number. Please check and try again.</div>
            <?php endif; ?>
        <?php endif; ?>
        <p class="text-center mt-2">Still deciding? <a href="<?php echo SITE_URL; ?>/admissions.php">Explore admissions</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
