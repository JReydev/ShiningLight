<?php
$current_page = 'about';
$page_title = 'About Us | Shining Light Christian College Inc.';
$meta_desc = 'Learn about our history, mission, vision, and meet our faculty at Shining Light Christian College Inc.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT page_slug, title, content FROM page_content WHERE page_slug IN ('about_history','about_mission','about_vision') ORDER BY page_slug");
    $pages = [];
    while ($row = $stmt->fetch()) $pages[$row['page_slug']] = $row;
    $stmt = $pdo->query("SELECT full_name, title, department, bio, photo, email FROM faculty WHERE is_active = 1 ORDER BY sort_order, full_name");
    $faculty = $stmt->fetchAll();
} catch (Exception $e) {
    $pages = [];
    $faculty = [];
}
$history = $pages['about_history'] ?? ['title' => 'Our Story', 'content' => '<p>Shining Light Christian College is a learning community rooted in faith and committed to helping every student discover their potential. We bring families and educators together to build strong foundations for lifelong learning.</p>'];
$mission = $pages['about_mission'] ?? ['title' => 'Our Mission', 'content' => '<p>To provide a caring, Christ-centered education that develops each learner\'s character, knowledge, and gifts in service to others.</p>'];
$vision = $pages['about_vision'] ?? ['title' => 'Our Vision', 'content' => '<p>To be a community where students grow into compassionate, confident, and responsible leaders, prepared to make a positive difference in the world.</p>'];
?>
<section class="page-hero">
    <h1>About Us</h1>
    <p>Our story, mission, and the people who make it possible</p>
</section>

<div class="section">
    <p class="section-intro">We are a faith-centered learning community where students are known, encouraged, and challenged to shine.</p>
    <div class="content-block">
        <h2><?php echo e($history['title']); ?></h2>
        <div class="content-body"><?php echo $history['content']; ?></div>
    </div>
    <div class="content-block">
        <h2><?php echo e($mission['title']); ?></h2>
        <div class="content-body"><?php echo $mission['content']; ?></div>
    </div>
    <div class="content-block">
        <h2><?php echo e($vision['title']); ?></h2>
        <div class="content-body"><?php echo $vision['content']; ?></div>
    </div>

    <h2 class="section-title">The values we live</h2>
    <p class="section-intro">The way we learn and grow is shaped by the values we practice together every day.</p>
    <div class="card-grid">
        <article class="card feature-card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">♡</span>
                <span class="card-eyebrow">Care</span>
                <h3>Every student matters</h3>
                <p>We work to create a welcoming community where learners are treated with respect and encouraged to do their best.</p>
            </div>
        </article>
        <article class="card feature-card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">✦</span>
                <span class="card-eyebrow">Character</span>
                <h3>Grow with integrity</h3>
                <p>Faith, responsibility, kindness, and honesty help students build habits that carry beyond the classroom.</p>
            </div>
        </article>
        <article class="card feature-card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">↗</span>
                <span class="card-eyebrow">Possibility</span>
                <h3>Keep discovering</h3>
                <p>We encourage curiosity, effort, and confidence so students can recognize their gifts and keep learning.</p>
            </div>
        </article>
    </div>

    <h2 class="section-title">Our Faculty</h2>
    <p class="section-intro">Meet the educators and mentors who help make learning meaningful.</p>
    <div class="card-grid">
        <?php foreach ($faculty as $f): ?>
        <div class="card">
            <?php if (!empty($f['photo'])): ?>
            <img src="<?php echo SITE_URL . '/uploads/' . e($f['photo']); ?>" alt="<?php echo e($f['full_name']); ?>" loading="lazy">
            <?php else: ?>
            <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Teacher welcoming students in a classroom" loading="lazy">
            <?php endif; ?>
            <div class="card-body">
                <span class="card-eyebrow"><?php echo e($f['department'] ?: 'Our faculty'); ?></span>
                <h3><?php echo e($f['full_name']); ?></h3>
                <p><strong><?php echo e($f['title']); ?></strong><?php if (!empty($f['department'])) echo ' &middot; ' . e($f['department']); ?></p>
                <?php if (!empty($f['bio'])): ?><p><?php echo e(mb_substr($f['bio'], 0, 150)); ?><?php echo mb_strlen($f['bio']) > 150 ? '…' : ''; ?></p><?php endif; ?>
                <?php if (!empty($f['email'])): ?><a class="card-more" href="mailto:<?php echo e($f['email']); ?>">Email faculty <span aria-hidden="true">&rarr;</span></a><?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if (empty($faculty)): ?>
    <div class="card empty-state">
        <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Teacher welcoming students in a classroom" loading="lazy">
        <div class="card-body">
            <h3>Learning starts with people</h3>
            <p>Our teachers partner with students and families to create a welcoming, encouraging place to learn. Get in touch to learn more about our school community.</p>
            <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Meet our community</a>
        </div>
    </div>
    <?php endif; ?>

    <div class="content-block">
        <h2>Get to know Shining Light</h2>
        <p>We welcome families who want to learn more about our learning community, programs, and enrollment process.</p>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Talk with our team</a>
        <a href="<?php echo SITE_URL; ?>/admissions.php" class="btn btn-outline">Explore admissions</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
