<?php
$current_page = 'student-life';
$page_title = 'Student Life | Shining Light Christian College Inc.';
$meta_desc = 'Clubs, sports, and gallery of campus life at Shining Light Christian College Inc.';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$clubs = getSetting('student_life_clubs', '• Bible Study Club<br>• Music &amp; Worship<br>• Student Government<br>• Science Club<br>• Arts &amp; Drama');
$sports = getSetting('student_life_sports', '• Basketball<br>• Volleyball<br>• Swimming<br>• Track &amp; Field');

$activities = [];
$activityGroups = [
    [
        'type' => 'club',
        'items' => preg_split('/<br\s*\/?>|[\r\n]+/i', $clubs),
        'details' => [
            'bible' => ['icon' => '✦', 'description' => 'Explore faith, reflect on meaningful questions, and encourage one another through scripture and conversation.'],
            'worship' => ['icon' => '♫', 'description' => 'Discover music, singing, and creative ways to contribute to a joyful, faith-filled community.'],
            'government' => ['icon' => '⚑', 'description' => 'Build leadership and teamwork while helping share student ideas and support school activities.'],
            'science' => ['icon' => '⚗', 'description' => 'Ask questions, test ideas, and make space for curiosity through hands-on discovery.'],
            'arts' => ['icon' => '✎', 'description' => 'Try creative projects, performance, and storytelling while learning to share your point of view.'],
        ],
    ],
    [
        'type' => 'sport',
        'items' => preg_split('/<br\s*\/?>|[\r\n]+/i', $sports),
        'details' => [
            'basketball' => ['icon' => '◉', 'description' => 'Practice coordination, communication, and team play on and off the court.'],
            'volleyball' => ['icon' => '◎', 'description' => 'Build confidence and quick teamwork through movement, strategy, and shared play.'],
            'swimming' => ['icon' => '≈', 'description' => 'Develop water confidence, fitness, and respect for safe, active participation.'],
            'track' => ['icon' => '➜', 'description' => 'Set personal goals, celebrate progress, and encourage teammates through active challenges.'],
        ],
    ],
];

foreach ($activityGroups as $group) {
    foreach ($group['items'] as $item) {
        $name = trim(html_entity_decode(strip_tags($item), ENT_QUOTES, 'UTF-8'));
        $name = preg_replace('/^[\s•·\-]+/u', '', $name);
        if ($name === '') continue;
        $key = strtolower($name);
        $details = ['icon' => $group['type'] === 'club' ? '✧' : '✦', 'description' => 'Find a welcoming way to explore your interests, grow new skills, and encourage your classmates.'];
        foreach ($group['details'] as $keyword => $matchedDetails) {
            if (strpos($key, $keyword) !== false) {
                $details = $matchedDetails;
                break;
            }
        }
        $activities[] = [
            'name' => $name,
            'type' => $group['type'],
            'icon' => $details['icon'],
            'description' => $details['description'],
        ];
    }
}
try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT id, title, description, image_path, category FROM gallery ORDER BY sort_order, id DESC LIMIT 24");
    $gallery = $stmt->fetchAll();
} catch (Exception $e) { $gallery = []; }
?>
<section class="page-hero">
    <h1>Student Life</h1>
    <p>Find your people, try something new, and let your gifts shine—in and beyond the classroom.</p>
</section>

<div class="section">
    <h2 class="section-title">Make school yours</h2>
    <p class="section-intro">Whether you love creating, leading, learning, or getting active, student activities are a great way to build friendships and discover what you can do.</p>

    <div class="activity-toolbar" role="group" aria-label="Filter student activities">
        <span class="activity-toolbar-label">Find your next adventure</span>
        <div class="activity-filters">
            <button type="button" class="activity-filter is-active" data-activity-filter="all" aria-pressed="true">All activities</button>
            <button type="button" class="activity-filter" data-activity-filter="club" aria-pressed="false">Clubs &amp; organizations</button>
            <button type="button" class="activity-filter" data-activity-filter="sport" aria-pressed="false">Sports &amp; athletics</button>
        </div>
    </div>

    <div class="card-grid activity-grid" id="activities">
        <?php foreach ($activities as $activity): ?>
        <article class="card activity-card" data-activity-type="<?php echo e($activity['type']); ?>">
            <div class="card-body">
                <div class="activity-card-top">
                    <span class="feature-icon" aria-hidden="true"><?php echo e($activity['icon']); ?></span>
                    <span class="activity-tag"><?php echo $activity['type'] === 'club' ? 'Clubs &amp; organizations' : 'Sports &amp; athletics'; ?></span>
                </div>
                <h3><?php echo e($activity['name']); ?></h3>
                <p><?php echo e($activity['description']); ?></p>
                <a class="activity-link" href="<?php echo SITE_URL; ?>/contact.php">Ask about getting involved <span aria-hidden="true">&rarr;</span></a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <p class="activity-note">Activities and availability may vary by school year. Reach out to learn what students can join this year.</p>

    <section class="student-life-feature">
        <div class="student-life-feature-image" role="img" aria-label="Students learning and sharing ideas together"></div>
        <div class="student-life-feature-copy">
            <span class="eyebrow">A place to grow together</span>
            <h2>Big moments start with showing up.</h2>
            <p>Join a team, lend a hand, share an idea, or cheer on a friend. Every experience is a chance to practice kindness, courage, and the joy of being part of something bigger.</p>
            <a href="<?php echo SITE_URL; ?>/admissions.php" class="btn btn-primary">Discover Shining Light</a>
        </div>
    </section>

    <h2 class="section-title">Gallery</h2>
    <p class="section-intro">A few moments that capture the energy of learning, friendship, and growing together.</p>
    <div class="gallery-grid card-grid">
        <?php foreach ($gallery as $img): ?>
        <div class="card gallery-card">
            <a href="<?php echo SITE_URL . '/uploads/' . e($img['image_path']); ?>" target="_blank" rel="noopener">
                <img src="<?php echo SITE_URL . '/uploads/' . e($img['image_path']); ?>" alt="<?php echo e($img['title'] ?? 'Gallery'); ?>" loading="lazy">
            </a>
            <?php if (!empty($img['title']) || !empty($img['description'])): ?>
            <div class="card-body">
                <?php if (!empty($img['title'])): ?><h3><?php echo e($img['title']); ?></h3><?php endif; ?>
                <?php if (!empty($img['description'])): ?><p><?php echo e(mb_substr($img['description'], 0, 100)); ?><?php echo mb_strlen($img['description']) > 100 ? '…' : ''; ?></p><?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if (empty($gallery)): ?>
    <div class="gallery-grid card-grid">
        <article class="card gallery-card">
            <img src="https://images.unsplash.com/photo-1529390079861-591de354faf5?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Students enjoying time together outdoors" loading="lazy">
            <div class="card-body"><h3>Friendship &amp; belonging</h3><p>Make memories and find your place in the community.</p></div>
        </article>
        <article class="card gallery-card">
            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Students learning together in a classroom" loading="lazy">
            <div class="card-body"><h3>Curiosity in action</h3><p>Learning continues through projects and shared discovery.</p></div>
        </article>
        <article class="card gallery-card">
            <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Students celebrating and connecting as a group" loading="lazy">
            <div class="card-body"><h3>Grow together</h3><p>Explore a school experience built around connection.</p></div>
        </article>
    </div>
    <p class="activity-note">These photos illustrate the spirit of student life. Contact us to learn about current activities and events.</p>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('[data-activity-filter]').forEach(function (button) {
    button.addEventListener('click', function () {
        var filter = button.getAttribute('data-activity-filter');
        document.querySelectorAll('[data-activity-filter]').forEach(function (filterButton) {
            var isActive = filterButton === button;
            filterButton.classList.toggle('is-active', isActive);
            filterButton.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
        document.querySelectorAll('[data-activity-type]').forEach(function (card) {
            var isVisible = filter === 'all' || card.getAttribute('data-activity-type') === filter;
            card.hidden = !isVisible;
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
