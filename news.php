<?php
$current_page = 'news';
$page_title = 'News & Announcements | Shining Light Christian College Inc.';
$meta_desc = 'Latest news and announcements from Shining Light Christian College Inc.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT id, title, excerpt, slug, featured_image, published_at FROM news WHERE is_published = 1 ORDER BY published_at DESC");
    $items = $stmt->fetchAll();
} catch (Exception $e) { $items = []; }
?>
<section class="page-hero">
    <h1>News &amp; Announcements</h1>
    <p>Stories, school notices, and moments from the Shining Light community.</p>
</section>

<div class="section">
    <h2 class="section-title"><?php echo empty($items) ? 'Stay connected' : 'From our community'; ?></h2>
    <p class="section-intro">Visit this page for the latest information shared by the school. If you need an update right away, our team is happy to help.</p>
    <div class="card-grid">
        <?php foreach ($items as $item): ?>
        <article class="card news-card">
            <a class="news-card-image" href="<?php echo SITE_URL; ?>/news-single.php?slug=<?php echo e($item['slug']); ?>">
                <img src="<?php echo !empty($item['featured_image']) ? SITE_URL . '/uploads/' . e($item['featured_image']) : 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&amp;fit=crop&amp;w=900&amp;q=80'; ?>" alt="" loading="lazy">
            </a>
            <div class="card-body">
                <span class="card-eyebrow"><?php echo date('F j, Y', strtotime($item['published_at'])); ?></span>
                <h3><a href="<?php echo SITE_URL; ?>/news-single.php?slug=<?php echo e($item['slug']); ?>"><?php echo e($item['title']); ?></a></h3>
                <p><?php echo e($item['excerpt'] ? mb_substr(strip_tags($item['excerpt']), 0, 160) : mb_substr(strip_tags($item['title']), 0, 160)); ?><?php echo mb_strlen(strip_tags($item['excerpt'] ?: $item['title'])) > 160 ? '…' : ''; ?></p>
                <a class="card-more" href="<?php echo SITE_URL; ?>/news-single.php?slug=<?php echo e($item['slug']); ?>">Read the story <span aria-hidden="true">&rarr;</span></a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php if (empty($items)): ?>
    <div class="card-grid news-guides">
        <article class="card">
            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Notebook and pen ready for planning" loading="lazy">
            <div class="card-body">
                <span class="card-eyebrow">For families</span>
                <h3>Admissions updates</h3>
                <p>Find practical information about requirements, fees, and the steps to apply.</p>
                <a href="<?php echo SITE_URL; ?>/admissions.php" class="card-more">Explore admissions <span aria-hidden="true">&rarr;</span></a>
            </div>
        </article>
        <article class="card">
            <img src="https://images.unsplash.com/photo-1529390079861-591de354faf5?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Students sharing a moment together" loading="lazy">
            <div class="card-body">
                <span class="card-eyebrow">Around campus</span>
                <h3>Student life</h3>
                <p>Discover activities and opportunities to learn, contribute, and connect.</p>
                <a href="<?php echo SITE_URL; ?>/student-life.php" class="card-more">Explore student life <span aria-hidden="true">&rarr;</span></a>
            </div>
        </article>
        <article class="card">
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Learner studying in a bright classroom" loading="lazy">
            <div class="card-body">
                <span class="card-eyebrow">Keep learning</span>
                <h3>Academic information</h3>
                <p>Review learning pathways and see where to ask questions about the school year.</p>
                <a href="<?php echo SITE_URL; ?>/academics.php" class="card-more">Explore academics <span aria-hidden="true">&rarr;</span></a>
            </div>
        </article>
    </div>
    <div class="news-contact-banner">
        <div>
            <span class="card-eyebrow">Need information now?</span>
            <h2>We’re just a message away.</h2>
            <p>Contact the school team for questions about enrollment, programs, or upcoming activities.</p>
        </div>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Contact the school</a>
    </div>
    <?php else: ?>
    <div class="news-contact-banner">
        <div>
            <span class="card-eyebrow">Stay in the loop</span>
            <h2>Have a question about a school update?</h2>
            <p>Contact our team if you need additional information about a story or announcement.</p>
        </div>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Contact the school</a>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
