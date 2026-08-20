<?php
// news.php - Dynamic News & Campus Articles
$page_slug = 'news';
require_once __DIR__ . '/includes/header.php';

$news_list = get_news(true);
$single_slug = $_GET['read'] ?? null;
$single_news = null;

if ($single_slug) {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM news WHERE slug = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$single_slug]);
    $single_news = $stmt->fetch();
}
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">News & Campus Stories</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Celebrations, achievements, and educational highlights from DV Niketan.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($single_news): ?>
        <!-- Single News Article View -->
        <div style="max-width:850px; margin:0 auto; background:#fff; padding:40px; border-radius:var(--radius-xl); border:1px solid var(--border-color); box-shadow:var(--shadow-md);">
            <a href="news.php" class="btn btn-secondary" style="font-size:0.85rem; padding:6px 16px; margin-bottom:20px;"><i class="bi bi-arrow-left"></i> Back to News</a>
            <span class="section-badge"><?= e($single_news['category']) ?></span>
            <h1 style="font-size:2.2rem; color:var(--text-main); margin:10px 0 15px;"><?= e($single_news['title']) ?></h1>
            <div style="color:var(--text-muted); font-size:0.88rem; margin-bottom:25px;"><i class="bi bi-calendar-event"></i> Published on <?= date('F d, Y', strtotime($single_news['publish_date'])) ?></div>
            
            <?php if (!empty($single_news['image_path']) && file_exists(BASE_DIR . '/' . $single_news['image_path'])): ?>
            <img src="<?= e($single_news['image_path']) ?>" alt="<?= e($single_news['title']) ?>" style="width:100%; max-height:450px; object-fit:cover; border-radius:var(--radius-lg); margin-bottom:30px;">
            <?php endif; ?>

            <div style="font-size:1.05rem; color:var(--text-muted); line-height:1.9;">
                <?= nl2br(e($single_news['description'])) ?>
            </div>
        </div>

        <?php else: ?>
        <!-- News List Grid -->
        <div class="programs-grid">
            <?php foreach ($news_list as $news): ?>
            <div class="program-card">
                <div class="program-image">
                    <?php if (!empty($news['image_path']) && file_exists(BASE_DIR . '/' . $news['image_path'])): ?>
                    <img src="<?= e($news['image_path']) ?>" alt="<?= e($news['title']) ?>">
                    <?php else: ?>
                    <div style="height:100%;background:linear-gradient(135deg,var(--primary-color),#1e293b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem;">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <?php endif; ?>
                    <span class="program-level-tag"><?= e($news['category']) ?></span>
                </div>
                <div class="program-body">
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-bottom:8px;"><i class="bi bi-calendar-event"></i> <?= date('M d, Y', strtotime($news['publish_date'])) ?></div>
                    <h3 class="program-title" style="font-size:1.2rem;"><?= e($news['title']) ?></h3>
                    <p class="program-desc"><?= e(mb_strimwidth($news['description'], 0, 130, '...')) ?></p>
                    <a href="news.php?read=<?= urlencode($news['slug']) ?>" class="btn btn-primary" style="margin-top:auto;">Read Full Article <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
