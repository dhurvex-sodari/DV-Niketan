<?php
// downloads.php - Dynamic Downloads & Document Center
$page_slug = 'downloads';
require_once __DIR__ . '/includes/header.php';

$selected_cat = $_GET['cat'] ?? null;
$downloads = get_downloads(true, $selected_cat);

// Get unique download categories
$db = get_db();
$categories = $db->query("SELECT DISTINCT category FROM downloads WHERE is_active = 1 ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Downloads & Forms</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Official prospectus, admission forms, academic calendars, and syllabi.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Category Filter -->
        <div style="display:flex; justify-content:center; gap:10px; margin-bottom:40px; flex-wrap:wrap;">
            <a href="downloads.php" class="btn <?= empty($selected_cat) ? 'btn-primary' : 'btn-secondary' ?>" style="font-size:0.85rem; padding:8px 18px;">All Files</a>
            <?php foreach ($categories as $cat): ?>
            <a href="downloads.php?cat=<?= urlencode($cat) ?>" class="btn <?= $selected_cat === $cat ? 'btn-primary' : 'btn-secondary' ?>" style="font-size:0.85rem; padding:8px 18px;">
                <?= e($cat) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($downloads)): ?>
        <div style="text-align:center; padding:50px; background:#fff; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
            <h3>No documents found in this section.</h3>
        </div>
        <?php else: ?>
        <div class="notices-list">
            <?php foreach ($downloads as $doc): ?>
            <div class="notice-item">
                <div class="notice-left">
                    <div style="width:52px; height:52px; border-radius:var(--radius-md); background:var(--primary-glow); color:var(--primary-color); display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <div>
                        <span class="notice-category-badge"><?= e($doc['category']) ?></span>
                        <h4 class="notice-title"><?= e($doc['title']) ?></h4>
                        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:3px;">Format: <?= e($doc['file_type'] ?: 'PDF') ?> <?= !empty($doc['file_size']) ? '• ' . e($doc['file_size']) : '' ?></div>
                    </div>
                </div>
                <div>
                    <a href="<?= e($doc['file_path']) ?>" target="_blank" class="btn btn-primary" style="padding:8px 22px; font-size:0.88rem; white-space:nowrap;"><i class="bi bi-download"></i> Download File</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
