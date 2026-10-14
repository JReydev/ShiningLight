<?php
$current_page = 'home';
$page_title = 'Shining Light Christian College Inc. | Faith-Centered Education';
$meta_desc = 'Shining Light Christian College Inc. - Excellence in faith-centered education. Enroll now, visit us, or learn more about our programs.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="hero-inner">
        <h1>Shining Light Christian College Inc.</h1>
        <p class="tagline">Faith-centered education that nurtures mind, body, and spirit</p>
        <div class="hero-ctas">
            <a href="<?php echo SITE_URL; ?>/enroll.php" class="btn btn-primary">Enroll Now</a>
            <a href="<?php echo SITE_URL; ?>/contact.php#visit" class="btn btn-secondary">Visit Us</a>
            <a href="<?php echo SITE_URL; ?>/about.php" class="btn btn-outline">Learn More</a>
        </div>
    </div>
</section>

<section class="section">
    <h2 class="section-title">Why Choose Shining Light?</h2>
    <p class="section-intro">A school experience centered on learning, faith, and the steady growth of every student.</p>
    <div class="card-grid">
        <div class="card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">✦</span>
                <span class="card-eyebrow">Purposeful learning</span>
                <h3>Faith-Centered Learning</h3>
                <p>Our curriculum integrates Christian values with academic excellence, preparing students to lead with integrity.</p>
                <a href="<?php echo SITE_URL; ?>/academics.php" class="card-more">Explore academics <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">✧</span>
                <span class="card-eyebrow">Room to thrive</span>
                <h3>Dedicated Faculty</h3>
                <p>Experienced educators who are committed to nurturing each student's God-given potential.</p>
                <a href="<?php echo SITE_URL; ?>/about.php" class="card-more">Meet our community <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">♡</span>
                <span class="card-eyebrow">Belong together</span>
                <h3>Thriving Community</h3>
                <p>Clubs, sports, and activities that build character and lasting friendships in a supportive environment.</p>
                <a href="<?php echo SITE_URL; ?>/student-life.php" class="card-more">Discover student life <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--white);">
    <h2 class="section-title">Find your next step</h2>
    <p class="section-intro">Whether you are exploring schools or ready to apply, find the information and support you need.</p>
    <div class="card-grid">
        <div class="card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">⌘</span>
                <span class="card-eyebrow">Discover</span>
                <h3>Academics</h3>
                <p>Explore learning pathways, academic information, and the school calendar.</p>
                <a href="<?php echo SITE_URL; ?>/academics.php" class="card-more">View academics <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">✎</span>
                <span class="card-eyebrow">Get ready</span>
                <h3>Admissions</h3>
                <p>Requirements, tuition, and FAQs for prospective families.</p>
                <a href="<?php echo SITE_URL; ?>/admissions.php" class="card-more">Explore admissions <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">♡</span>
                <span class="card-eyebrow">Get involved</span>
                <h3>Student Life</h3>
                <p>Clubs, sports, and gallery of campus life.</p>
                <a href="<?php echo SITE_URL; ?>/student-life.php" class="card-more">Discover more <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="editorial-feature">
        <div class="editorial-feature-image" role="img" aria-label="A welcoming classroom ready for learning" style="background-image:url('https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&amp;fit=crop&amp;w=1200&amp;q=85')"></div>
        <div class="editorial-feature-copy">
            <span class="card-eyebrow">A bright beginning</span>
            <h2>Let your next chapter start here.</h2>
            <p>Choosing a school is a meaningful decision. Get to know our approach, ask questions, and take the next step when your family is ready.</p>
            <a href="<?php echo SITE_URL; ?>/admissions.php" class="btn btn-primary">Start with admissions</a>
        </div>
    </div>
</section>

<?php
try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT id, title, excerpt, slug, featured_image, published_at FROM news WHERE is_published = 1 ORDER BY published_at DESC LIMIT 3");
    $news = $stmt->fetchAll();
} catch (Exception $e) { $news = []; }
if (!empty($news)) {
?>
<section class="section">
    <h2 class="section-title">Latest News &amp; Announcements</h2>
    <p class="section-intro">Keep up with important information and the latest stories shared by our school community.</p>
    <div class="card-grid">
        <?php foreach ($news as $item): ?>
        <div class="card">
            <?php if (!empty($item['featured_image'])): ?><img src="<?php echo SITE_URL . '/uploads/' . e($item['featured_image']); ?>" alt="" loading="lazy"><?php else: ?><img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="" loading="lazy"><?php endif; ?>
            <div class="card-body">
                <span class="card-eyebrow"><?php echo date('M j, Y', strtotime($item['published_at'])); ?></span>
                <h3><a href="<?php echo SITE_URL; ?>/news-single.php?slug=<?php echo e($item['slug']); ?>"><?php echo e($item['title']); ?></a></h3>
                <p><?php echo e(mb_substr(strip_tags($item['excerpt'] ?: $item['title']), 0, 120)); ?>…</p>
                <a class="card-more" href="<?php echo SITE_URL; ?>/news-single.php?slug=<?php echo e($item['slug']); ?>">Read the story <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <p class="text-center mt-2"><a href="<?php echo SITE_URL; ?>/news.php" class="btn btn-primary">View All News</a></p>
</section>
<?php } else { ?>
<section class="section">
    <h2 class="section-title">A brighter future starts here</h2>
    <p class="section-intro">Discover a caring school community where students are encouraged to grow in wisdom, confidence, and faith.</p>
    <div class="card-grid">
        <a class="image-card" href="<?php echo SITE_URL; ?>/academics.php" style="background-image:url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&amp;fit=crop&amp;w=900&amp;q=80')">
            <div class="image-card-content"><h3>Learning with purpose</h3><p>Explore our academic pathways and approach.</p></div>
        </a>
        <a class="image-card" href="<?php echo SITE_URL; ?>/student-life.php" style="background-image:url('https://images.unsplash.com/photo-1529390079861-591de354faf5?auto=format&amp;fit=crop&amp;w=900&amp;q=80')">
            <div class="image-card-content"><h3>Belong and grow</h3><p>See the experiences that make up student life.</p></div>
        </a>
        <a class="image-card" href="<?php echo SITE_URL; ?>/admissions.php" style="background-image:url('https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&amp;fit=crop&amp;w=900&amp;q=80')">
            <div class="image-card-content"><h3>Your next chapter</h3><p>Get started with admissions and enrollment.</p></div>
        </a>
    </div>
</section>
<?php } ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
