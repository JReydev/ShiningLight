<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if ($slug === '') { header('Location: ' . SITE_URL . '/news.php'); exit; }
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id, title, content, featured_image, published_at FROM news WHERE slug = ? AND is_published = 1");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
} catch (Exception $e) { $post = null; }
if (!$post) { header('Location: ' . SITE_URL . '/news.php'); exit; }

$current_page = 'news';
$page_title = $post['title'] . ' | ' . SITE_NAME;
$meta_desc = mb_substr(strip_tags($post['content']), 0, 160);
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <h1><?php echo e($post['title']); ?></h1>
    <p><?php echo date('F j, Y', strtotime($post['published_at'])); ?></p>
</section>

<div class="section">
    <div class="content-block" style="max-width: 720px; margin: 0 auto;">
        <?php if (!empty($post['featured_image'])): ?>
        <img src="<?php echo SITE_URL . '/uploads/' . e($post['featured_image']); ?>" alt="" style="width:100%; border-radius: var(--radius); margin-bottom: 1.5rem;">
        <?php endif; ?>
        <div class="content-body"><?php echo $post['content']; ?></div>
        <p class="mt-2"><a href="<?php echo SITE_URL; ?>/news.php">&larr; Back to News</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
