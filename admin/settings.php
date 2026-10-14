<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('admin');
$pdo = getDB();

$settings_keys = ['address', 'phone', 'tuition_info', 'admissions_requirements', 'student_life_clubs', 'student_life_sports'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_site'])) {
    foreach ($settings_keys as $k) {
        $v = trim($_POST[$k] ?? '');
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$k, $v, $v]);
    }
    header('Location: ' . SITE_URL . '/admin/settings.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_verse'])) {
    $verse_text = trim($_POST['verse_text'] ?? '');
    $reference = trim($_POST['reference'] ?? '');
    if ($verse_text && $reference) {
        $pdo->prepare("INSERT INTO bible_verses (verse_text, reference, is_active, sort_order) VALUES (?, ?, 1, 0)")->execute([$verse_text, $reference]);
    }
    header('Location: ' . SITE_URL . '/admin/settings.php');
    exit;
}

$settings = [];
foreach ($settings_keys as $k) $settings[$k] = getSetting($k, '');
$verses = $pdo->query("SELECT id, verse_text, reference FROM bible_verses ORDER BY sort_order, id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<?php include __DIR__ . '/admin-header.php'; ?>
<div class="admin-main">
    <h1>Settings</h1>

    <div class="content-block">
        <h2>Site &amp; Contact</h2>
        <form method="post">
            <input type="hidden" name="save_site" value="1">
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"><?php echo e($settings['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo e($settings['phone']); ?>">
            </div>
            <div class="form-group">
                <label>Tuition info (HTML)</label>
                <textarea name="tuition_info" class="form-control" rows="4"><?php echo e($settings['tuition_info']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Admissions requirements (HTML)</label>
                <textarea name="admissions_requirements" class="form-control" rows="4"><?php echo e($settings['admissions_requirements']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Student life – Clubs (HTML, e.g. &lt;br&gt; separated)</label>
                <textarea name="student_life_clubs" class="form-control" rows="3"><?php echo e($settings['student_life_clubs']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Student life – Sports (HTML)</label>
                <textarea name="student_life_sports" class="form-control" rows="3"><?php echo e($settings['student_life_sports']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <div class="content-block">
        <h2>Footer Bible Verses</h2>
        <p>Add a verse to rotate in the footer. One is shown at random.</p>
        <form method="post">
            <input type="hidden" name="save_verse" value="1">
            <div class="form-group">
                <label>Verse text</label>
                <textarea name="verse_text" class="form-control" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label>Reference (e.g. Matthew 5:16)</label>
                <input type="text" name="reference" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Verse</button>
        </form>
        <ul style="margin-top:1rem;">
            <?php foreach ($verses as $v): ?>
            <li><?php echo e($v['verse_text']); ?> — <em><?php echo e($v['reference']); ?></em></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
</body>
</html>
