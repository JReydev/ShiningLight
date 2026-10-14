<?php
$current_page = 'admissions';
$page_title = 'Admissions | Shining Light Christian College Inc.';
$meta_desc = 'Admissions requirements, tuition, and FAQs for Shining Light Christian College Inc.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$requirements = getSetting('admissions_requirements', '• Completed application form<br>• Birth certificate<br>• Latest report card<br>• 2x2 ID photo<br>• Good moral character');
$tuition = getSetting('tuition_info', 'Tuition and fees vary by grade level. Please contact our Admissions Office for the current fee structure and payment plans.');
?>
<section class="page-hero">
    <h1>Admissions</h1>
    <p>We’re glad you’re exploring Shining Light. Here’s what to expect as you consider joining our school community.</p>
</section>

<div class="section">
    <h2 class="section-title">Your application, made simple</h2>
    <p class="section-intro">Our admissions team can guide your family from the first question through application follow-up.</p>
    <div class="card-grid mb-2">
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">1</span><span class="card-eyebrow">Step one</span><h3>Prepare</h3><p>Review the requirements, ask questions, and gather your learner’s documents before starting the form.</p><a class="card-more" href="#requirements">See what to prepare <span aria-hidden="true">&rarr;</span></a></div></article>
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">2</span><span class="card-eyebrow">Step two</span><h3>Apply online</h3><p>Complete the enrollment form with student and parent or guardian information, then upload the required files.</p><a class="card-more" href="<?php echo SITE_URL; ?>/enroll.php">Start an application <span aria-hidden="true">&rarr;</span></a></div></article>
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">3</span><span class="card-eyebrow">Step three</span><h3>Stay connected</h3><p>Keep your application number handy and return to the site to check for status updates.</p><a class="card-more" href="<?php echo SITE_URL; ?>/enroll-status.php">Check application status <span aria-hidden="true">&rarr;</span></a></div></article>
    </div>
    <div class="content-block" id="requirements">
        <h2>Admissions Requirements</h2>
        <div class="content-body"><?php echo $requirements; ?></div>
        <p class="form-hint mt-2">Prepare clear scans or photos of required documents before you begin. The enrollment form accepts PDF and common image formats.</p>
    </div>
    <div class="content-block">
        <h2>Tuition &amp; Fees</h2>
        <div class="content-body"><?php echo $tuition; ?></div>
        <p class="mt-2">For the most current fee schedule, payment options, and grade-level details, please contact the school directly.</p>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="card-more">Ask about tuition <span aria-hidden="true">&rarr;</span></a>
    </div>
    <div class="content-block">
        <h2>How to Apply</h2>
        <p>Complete our <strong>Online Enrollment</strong> form. You will need to prepare the required documents for upload. After submission, you will receive a confirmation email and can track your application status.</p>
        <a href="<?php echo SITE_URL; ?>/enroll.php" class="btn btn-primary">Go to Online Enrollment</a>
        <a href="<?php echo SITE_URL; ?>/enroll-status.php" class="btn btn-outline">Check Application Status</a>
    </div>
    <div class="content-block">
        <h2>FAQs</h2>
        <dl style="margin:0;">
            <dt style="font-weight:600; margin-top:1rem;">When is the enrollment period?</dt>
            <dd>We accept applications year-round. The school year typically begins in June. Check our Academic Calendar for exact dates.</dd>
            <dt style="font-weight:600; margin-top:1rem;">Can I pay in installments?</dt>
            <dd>Yes. Payment plans are available. Inquire with the Finance Office.</dd>
            <dt style="font-weight:600; margin-top:1rem;">Is there financial aid?</dt>
            <dd>Scholarships and financial aid may be available for qualified students. Contact Admissions for details.</dd>
            <dt style="font-weight:600; margin-top:1rem;">How do I check my enrollment status?</dt>
            <dd>After submitting the online form, you will receive an application number. Use our <a href="<?php echo SITE_URL; ?>/enroll-status.php">Enrollment Status</a> page to check your application.</dd>
            <dt style="font-weight:600; margin-top:1rem;">Can I ask questions before I apply?</dt>
            <dd>Absolutely. Contact the school with questions about programs, documents, fees, or the application process before submitting a form.</dd>
        </dl>
    </div>
    <div class="editorial-feature">
        <div class="editorial-feature-image" role="img" aria-label="A welcoming school community" style="background-image:url('https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&amp;fit=crop&amp;w=1200&amp;q=85')"></div>
        <div class="editorial-feature-copy">
            <span class="card-eyebrow">Here to help</span>
            <h2>Let’s make the next step clear.</h2>
            <p>Our team can help you understand what to prepare and how to complete your application.</p>
            <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Contact admissions</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
