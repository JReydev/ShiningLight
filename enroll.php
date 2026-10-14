<?php
session_start();
$current_page = 'enroll';
$page_title = 'Online Enrollment | Shining Light Christian College Inc.';
$meta_desc = 'Submit your enrollment application online. Student details, parent information, and document uploads.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
if (empty($_SESSION['enroll_csrf'])) $_SESSION['enroll_csrf'] = bin2hex(random_bytes(16));
require_once __DIR__ . '/includes/header.php';

$success = isset($_GET['success']) && $_GET['success'] === '1';
$app_number = isset($_GET['app']) ? trim($_GET['app']) : '';
?>
<section class="page-hero">
    <h1>Online Enrollment</h1>
    <p>Take the first step toward joining our learning community. You can complete and submit your application online.</p>
</section>

<div class="section">
    <?php if ($success && $app_number): ?>
    <div class="alert alert-success">
        <strong>Application submitted successfully.</strong><br>
        Your application number is: <strong><?php echo e($app_number); ?></strong>. Please save this number to check your enrollment status. A confirmation email has been sent to the parent/guardian email you provided.
        <p class="mt-2"><a href="<?php echo SITE_URL; ?>/enroll-status.php">Check enrollment status</a></p>
    </div>
    <h2 class="section-title">What happens next?</h2>
    <div class="card-grid">
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">✓</span><span class="card-eyebrow">Application received</span><h3>Keep your number</h3><p>Your application number helps you return to the status page and find your submission.</p><a class="card-more" href="<?php echo SITE_URL; ?>/enroll-status.php">Track your application <span aria-hidden="true">&rarr;</span></a></div></article>
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">✉</span><span class="card-eyebrow">Stay in touch</span><h3>Check your email</h3><p>Look for the confirmation at the parent or guardian email address entered on the form.</p><a class="card-more" href="<?php echo SITE_URL; ?>/contact.php">Contact the school <span aria-hidden="true">&rarr;</span></a></div></article>
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">♡</span><span class="card-eyebrow">We can help</span><h3>Have a question?</h3><p>Reach out to the school if you need assistance or have questions about your submission.</p><a class="card-more" href="<?php echo SITE_URL; ?>/contact.php">Get in touch <span aria-hidden="true">&rarr;</span></a></div></article>
    </div>
    <?php else: ?>
    <?php
    if (!empty($_SESSION['enroll_errors'])) {
        echo '<div class="alert alert-error">' . e(implode('<br>', $_SESSION['enroll_errors'])) . '</div>';
        unset($_SESSION['enroll_errors']);
    }
    ?>
    <div class="content-block enrollment-intro">
        <h2>Before you begin</h2>
        <p>Set aside a few minutes to enter student and parent or guardian details. You’ll also need clear digital copies of the required documents to upload with your application.</p>
        <ul class="checklist">
            <li>Birth certificate, latest report card, and 2x2 ID photo</li>
            <li>PDF or image files, up to <?php echo MAX_UPLOAD_MB; ?>MB each</li>
            <li>Parent or guardian email address for the application confirmation</li>
        </ul>
    </div>

    <form action="<?php echo SITE_URL; ?>/enroll-submit.php" method="post" enctype="multipart/form-data" class="content-block enrollment-form">
        <input type="hidden" name="csrf" value="<?php echo e($_SESSION['enroll_csrf'] ?? ''); ?>">

        <p class="enrollment-required-note">Fields marked with <strong>*</strong> are required. Your application details help our team follow up with your family.</p>
        <h2>Student Information</h2>
        <div class="form-group">
            <label for="student_first_name">First Name *</label>
            <input type="text" id="student_first_name" name="student_first_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="student_last_name">Last Name *</label>
            <input type="text" id="student_last_name" name="student_last_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="student_middle_name">Middle Name</label>
            <input type="text" id="student_middle_name" name="student_middle_name" class="form-control">
        </div>
        <div class="form-group">
            <label for="date_of_birth">Date of Birth *</label>
            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender *</label>
            <select id="gender" name="gender" class="form-control" required>
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="grade_level">Grade Level *</label>
            <input type="text" id="grade_level" name="grade_level" class="form-control" placeholder="e.g. Grade 7" required>
        </div>
        <div class="form-group">
            <label for="school_year">School Year *</label>
            <input type="text" id="school_year" name="school_year" class="form-control" placeholder="e.g. 2025-2026" required>
        </div>
        <div class="form-group">
            <label for="previous_school">Previous School</label>
            <input type="text" id="previous_school" name="previous_school" class="form-control">
        </div>

        <h2>Parent / Guardian 1</h2>
        <div class="form-group">
            <label for="parent1_name">Full Name *</label>
            <input type="text" id="parent1_name" name="parent1_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="parent1_relation">Relation *</label>
            <input type="text" id="parent1_relation" name="parent1_relation" class="form-control" placeholder="e.g. Mother, Father" required>
        </div>
        <div class="form-group">
            <label for="parent1_phone">Phone *</label>
            <input type="tel" id="parent1_phone" name="parent1_phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="parent1_email">Email *</label>
            <input type="email" id="parent1_email" name="parent1_email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="parent1_address">Address</label>
            <textarea id="parent1_address" name="parent1_address" class="form-control" rows="2"></textarea>
        </div>

        <h2>Parent / Guardian 2 (Optional)</h2>
        <div class="form-group">
            <label for="parent2_name">Full Name</label>
            <input type="text" id="parent2_name" name="parent2_name" class="form-control">
        </div>
        <div class="form-group">
            <label for="parent2_relation">Relation</label>
            <input type="text" id="parent2_relation" name="parent2_relation" class="form-control">
        </div>
        <div class="form-group">
            <label for="parent2_phone">Phone</label>
            <input type="tel" id="parent2_phone" name="parent2_phone" class="form-control">
        </div>
        <div class="form-group">
            <label for="parent2_email">Email</label>
            <input type="email" id="parent2_email" name="parent2_email" class="form-control">
        </div>

        <h2>Documents (PDF or image, max <?php echo MAX_UPLOAD_MB; ?>MB each)</h2>
        <div class="form-group">
            <label for="doc_birth_cert">Birth Certificate *</label>
            <input type="file" id="doc_birth_cert" name="doc_birth_cert" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
        </div>
        <div class="form-group">
            <label for="doc_report_card">Latest Report Card *</label>
            <input type="file" id="doc_report_card" name="doc_report_card" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
        </div>
        <div class="form-group">
            <label for="doc_photo">2x2 ID Photo *</label>
            <input type="file" id="doc_photo" name="doc_photo" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
        </div>
        <div class="form-group">
            <label for="doc_other">Other Document (optional)</label>
            <input type="file" id="doc_other" name="doc_other" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif">
        </div>

        <div class="form-group">
            <label for="notes">Additional Notes</label>
            <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Submit Application</button>
    </form>
    <p class="enrollment-help">Need help before you submit? <a href="<?php echo SITE_URL; ?>/contact.php">Contact the school</a> or review the <a href="<?php echo SITE_URL; ?>/admissions.php">admissions information</a>.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
