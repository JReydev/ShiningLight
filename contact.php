<?php
$current_page = 'contact';
$page_title = 'Contact Us | Shining Light Christian College Inc.';
$meta_desc = 'Contact Shining Light Christian College Inc. Visit us or send a message.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$sent = isset($_GET['sent']) && $_GET['sent'] === '1';
$address = getSetting('address', DEFAULT_ADDRESS);
$phone = getSetting('phone', DEFAULT_PHONE);
$facebook_url = defined('FACEBOOK_URL') ? FACEBOOK_URL : '';
?>
<section class="page-hero">
    <h1>Contact Us</h1>
    <p>We'd love to hear from you</p>
</section>

<div class="section">
    <?php if ($sent): ?>
    <div class="alert alert-success">Thank you. Your message has been sent. We will get back to you soon.</div>
    <?php endif; ?>

    <div class="contact-layout">
        <div class="content-block">
            <h2>Visit Us</h2>
            <p id="visit"><strong>Address</strong><br><?php echo nl2br(e($address)); ?></p>
            <p><strong>Phone</strong><br><a href="tel:09976199710"><?php echo e($phone); ?></a></p>
            <p><strong>Email</strong><br><a href="mailto:<?php echo e(ADMIN_EMAIL); ?>"><?php echo e(ADMIN_EMAIL); ?></a></p>
            <?php if ($facebook_url): ?>
            <p><strong>Facebook</strong><br><a href="<?php echo e($facebook_url); ?>" target="_blank" rel="noopener noreferrer">SLCC Eagles</a></p>
            <?php endif; ?>
        </div>
        <div class="content-block">
            <h2>Send a Message</h2>
            <form action="<?php echo SITE_URL; ?>/contact-submit.php" method="post">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control">
                </div>
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
