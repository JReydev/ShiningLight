<?php
$current_page = 'academics';
$page_title = 'Academics | Shining Light Christian College Inc.';
$meta_desc = 'Programs, curriculum, and academic calendar at Shining Light Christian College Inc.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT id, title, description, level, duration FROM programs WHERE is_active = 1 ORDER BY sort_order, title");
    $programs = $stmt->fetchAll();
    $stmt = $pdo->query("SELECT title, event_date, end_date, description, event_type FROM calendar_events ORDER BY event_date ASC LIMIT 24");
    $calendar = $stmt->fetchAll();
} catch (Exception $e) {
    $programs = [];
    $calendar = [];
}
?>
<section class="page-hero">
    <h1>Academics</h1>
    <p>Build knowledge, grow in character, and discover the confidence to take your next step.</p>
</section>

<div class="section">
    <h2 class="section-title">Programs</h2>
    <p class="section-intro">Explore the learning options available at Shining Light. Contact the school for current grade-level offerings and program details.</p>
    <div class="card-grid">
        <?php foreach ($programs as $p): ?>
        <div class="card">
            <div class="card-body">
                <span class="card-eyebrow"><?php echo !empty($p['level']) ? e($p['level']) : 'Learning pathway'; ?></span>
                <h3><?php echo e($p['title']); ?></h3>
                <?php if (!empty($p['duration'])): ?><p><strong>Duration:</strong> <?php echo e($p['duration']); ?></p><?php endif; ?>
                <p><?php echo e($p['description']); ?></p>
                <a href="<?php echo SITE_URL; ?>/contact.php" class="card-more">Ask about this program <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if (empty($programs)): ?>
    <p class="section-intro">A strong foundation in every subject, with room to grow in character, creativity, and confidence.</p>
    <div class="card-grid">
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">✎</span><h3>Elementary</h3><p>Build essential skills, curiosity, and a love of learning in a supportive environment.</p></div></article>
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">⌘</span><h3>Junior High School</h3><p>Strengthen academic foundations while discovering new interests and abilities.</p></div></article>
        <article class="card feature-card"><div class="card-body"><span class="feature-icon" aria-hidden="true">✦</span><h3>Senior High School</h3><p>Prepare for the next step through focused learning, personal growth, and guidance.</p></div></article>
    </div>
    <div class="content-block mt-2">
        <h2>Learn about our curriculum</h2>
        <p>Program details and grade-level offerings may vary. Our Admissions team can help you find the right fit and answer questions about current availability.</p>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Ask about programs</a>
    </div>
    <?php endif; ?>

    <h2 class="section-title">Learning for the whole student</h2>
    <p class="section-intro">Strong academics matter. So do the curiosity, confidence, and care students bring to the world around them.</p>
    <div class="card-grid">
        <article class="card feature-card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">⌘</span>
                <span class="card-eyebrow">Build a foundation</span>
                <h3>Knowledge and skills</h3>
                <p>Develop essential skills, ask thoughtful questions, and connect classroom learning to everyday life.</p>
            </div>
        </article>
        <article class="card feature-card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">✦</span>
                <span class="card-eyebrow">Grow in purpose</span>
                <h3>Faith and character</h3>
                <p>Practice responsibility, compassion, perseverance, and respect as part of the learning journey.</p>
            </div>
        </article>
        <article class="card feature-card">
            <div class="card-body">
                <span class="feature-icon" aria-hidden="true">↗</span>
                <span class="card-eyebrow">Look ahead</span>
                <h3>Confidence for what’s next</h3>
                <p>Build the habits and self-belief to keep learning and prepare for future opportunities.</p>
            </div>
        </article>
    </div>

    <div class="editorial-feature">
        <div class="editorial-feature-image" role="img" aria-label="Students learning together in a classroom" style="background-image:url('https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&amp;fit=crop&amp;w=1200&amp;q=85')"></div>
        <div class="editorial-feature-copy">
            <span class="card-eyebrow">Take the next step</span>
            <h2>Find a learning path that fits.</h2>
            <p>We can help you understand grade-level options, curriculum questions, and the enrollment process for your learner.</p>
            <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Ask our school team</a>
        </div>
    </div>

    <h2 class="section-title">Academic Calendar</h2>
    <p class="section-intro">Keep an eye on important dates and school events. Contact the office for the latest information.</p>
    <?php if (!empty($calendar)): ?>
    <div class="content-block table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Event</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($calendar as $ev): ?>
                <tr>
                    <td><?php echo date('M j, Y', strtotime($ev['event_date'])); ?><?php if (!empty($ev['end_date']) && $ev['end_date'] !== $ev['event_date']) echo ' – ' . date('M j, Y', strtotime($ev['end_date'])); ?></td>
                    <td><?php echo e($ev['title']); ?><?php if (!empty($ev['description'])) echo ' <small>(' . e(mb_substr($ev['description'], 0, 80)) . '…)</small>'; ?></td>
                    <td><?php echo e($ev['event_type'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="card empty-state">
        <img src="https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Calendar and notebook on a desk" loading="lazy">
        <div class="card-body">
            <h3>Plan your school year</h3>
            <p>Calendar dates are shared by the school as they are confirmed. Contact the office for the latest term dates, holidays, and event information.</p>
            <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Contact the school</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
