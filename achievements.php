<?php
// achievements.php - Dynamic Achievements & Honors
$page_slug = 'achievements';
require_once __DIR__ . '/includes/header.php';

$achievements = get_achievements(true);
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Achievements & Honors</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Celebrating outstanding performances in academics, sports, and leadership.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($achievements)): ?>
        <div style="text-align:center; padding:50px; background:#fff; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
            <h3>No achievements listed yet.</h3>
        </div>
        <?php else: ?>
        <div class="programs-grid">
            <?php foreach ($achievements as $ach): ?>
            <div class="program-card">
                <div class="program-image" style="height:210px;">
                    <?php if (!empty($ach['image_path']) && file_exists(BASE_DIR . '/' . $ach['image_path'])): ?>
                    <img src="<?= e($ach['image_path']) ?>" alt="<?= e($ach['title']) ?>">
                    <?php else: ?>
                    <div style="height:100%;background:linear-gradient(135deg,#e65100,#f59e0b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3.5rem;">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <?php endif; ?>
                    <span class="program-level-tag" style="background:#e65100;"><?= e($ach['category']) ?></span>
                </div>
                <div class="program-body">
                    <h3 class="program-title" style="font-size:1.25rem;"><?= e($ach['title']) ?></h3>
                    <?php if (!empty($ach['recipient_name'])): ?>
                    <div style="font-size:0.88rem; color:var(--primary-color); font-weight:700; margin-bottom:10px;"><i class="bi bi-person-circle"></i> Recipient: <?= e($ach['recipient_name']) ?></div>
                    <?php endif; ?>
                    <p class="program-desc"><?= nl2br(e($ach['description'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
