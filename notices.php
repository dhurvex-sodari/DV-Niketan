<?php
// notices.php - Dynamic Notices & Announcements
$page_slug = 'notices';
require_once __DIR__ . '/includes/header.php';

$selected_cat = $_GET['cat'] ?? null;
$notices = get_notices(true, null, $selected_cat);

// Get unique notice categories
$db = get_db();
$categories = $db->query("SELECT DISTINCT category FROM notices WHERE is_active = 1 ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Notice Board & Circulars</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Official notices, exam schedules, and administrative announcements.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Category Filter -->
        <div style="display:flex; justify-content:center; gap:10px; margin-bottom:40px; flex-wrap:wrap;">
            <a href="notices.php" class="btn <?= empty($selected_cat) ? 'btn-primary' : 'btn-secondary' ?>" style="font-size:0.85rem; padding:8px 18px;">All Notices</a>
            <?php foreach ($categories as $cat): ?>
            <a href="notices.php?cat=<?= urlencode($cat) ?>" class="btn <?= $selected_cat === $cat ? 'btn-primary' : 'btn-secondary' ?>" style="font-size:0.85rem; padding:8px 18px;">
                <?= e($cat) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($notices)): ?>
        <div style="text-align:center; padding:50px; background:#fff; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
            <h3>No notices posted in this category.</h3>
        </div>
        <?php else: ?>
        <div class="notices-list">
            <?php foreach ($notices as $notice): 
                $date_obj = date_create($notice['publish_date']);
            ?>
            <div class="notice-item">
                <div class="notice-left">
                    <div class="notice-date-box">
                        <span class="day"><?= date_format($date_obj, 'd') ?></span>
                        <span class="month"><?= date_format($date_obj, 'M') ?></span>
                    </div>
                    <div>
                        <span class="notice-category-badge"><?= e($notice['category']) ?></span>
                        <h3 class="notice-title" style="font-size:1.2rem; margin-bottom:6px;"><?= e($notice['title']) ?></h3>
                        <?php if (!empty($notice['description'])): ?>
                        <div style="font-size:0.92rem; color:var(--text-muted); line-height:1.6;"><?= nl2br(e($notice['description'])) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <?php if (!empty($notice['file_path'])): ?>
                    <a href="<?= e($notice['file_path']) ?>" target="_blank" class="btn btn-primary" style="padding:8px 20px; font-size:0.88rem; white-space:nowrap;"><i class="bi bi-file-earmark-pdf"></i> Download PDF</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
