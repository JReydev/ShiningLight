<?php
if (!defined('SITE_NAME')) require_once __DIR__ . '/../config/database.php';
$current_page = $current_page ?? 'home';
$page_title = $page_title ?? SITE_NAME . ' - Faith-Centered Education';
$meta_desc = $meta_desc ?? 'Shining Light Christian College Inc. - Excellence in faith-centered education.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($meta_desc); ?>">
    <title><?php echo e($page_title); ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body class="page-<?php echo e($current_page); ?>">
    <header class="site-header">
        <div class="header-inner">
            <a href="<?php echo SITE_URL; ?>/" class="logo">
                <span class="logo-icon">✧</span>
                <span class="logo-text"><?php echo e(SITE_NAME); ?></span>
            </a>
            <button type="button" class="nav-toggle" aria-label="Toggle menu" aria-controls="primary-navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
            <nav class="main-nav" id="primary-navigation">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/" class="<?php echo $current_page === 'home' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about.php" class="<?php echo $current_page === 'about' ? 'active' : ''; ?>">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/academics.php" class="<?php echo $current_page === 'academics' ? 'active' : ''; ?>">Academics</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/admissions.php" class="<?php echo $current_page === 'admissions' ? 'active' : ''; ?>">Admissions</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/enroll.php" class="<?php echo $current_page === 'enroll' ? 'active' : ''; ?>">Enroll</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/student-life.php" class="<?php echo $current_page === 'student-life' ? 'active' : ''; ?>">Student Life</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/news.php" class="<?php echo $current_page === 'news' ? 'active' : ''; ?>">News</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact.php" class="<?php echo $current_page === 'contact' ? 'active' : ''; ?>">Contact</a></li>
                    <?php if (defined('FACEBOOK_URL') && FACEBOOK_URL): ?>
                    <li><a href="<?php echo e(FACEBOOK_URL); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="nav-fb">Facebook</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
