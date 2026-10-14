<?php
if (!function_exists('currentAdmin')) { require_once __DIR__ . '/../includes/auth.php'; }
$admin = currentAdmin();
?>
<header class="admin-header-bar">
    <div class="admin-header-inner">
        <a href="<?php echo SITE_URL; ?>/admin/"><?php echo e(SITE_NAME); ?> Admin</a>
        <nav class="admin-nav">
            <a href="<?php echo SITE_URL; ?>/admin/">Dashboard</a>
            <a href="<?php echo SITE_URL; ?>/admin/enrollments.php">Enrollments</a>
            <a href="<?php echo SITE_URL; ?>/admin/news.php">News</a>
            <a href="<?php echo SITE_URL; ?>/admin/faculty.php">Faculty</a>
            <a href="<?php echo SITE_URL; ?>/admin/gallery.php">Gallery</a>
            <a href="<?php echo SITE_URL; ?>/admin/pages.php">Pages</a>
            <a href="<?php echo SITE_URL; ?>/admin/contacts.php">Contacts</a>
            <a href="<?php echo SITE_URL; ?>/admin/settings.php">Settings</a>
            <span class="admin-user"><?php echo e($admin['full_name']); ?></span>
            <a href="<?php echo SITE_URL; ?>/admin/logout.php">Logout</a>
        </nav>
    </div>
</header>
