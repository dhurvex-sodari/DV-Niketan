<?php
// facilities.php - Dynamic Facilities Showcase
$page_slug = 'facilities';
require_once __DIR__ . '/includes/header.php';

$facilities = get_facilities(true);
$school_name = get_setting('school_name', 'DV Niketan Boarding School');
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Campus & Facilities</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Modern infrastructure designed for holistic learning and development.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($facilities)): ?>
        <div style="text-align:center; padding:50px; background:#fff; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
            <h3>No facilities listed yet.</h3>
        </div>
        <?php else: ?>
        <div class="programs-grid">
            <?php foreach ($facilities as $fac): ?>
            <div class="program-card">
                <div class="program-image" style="height:220px;">
                    <?php if (!empty($fac['image_path']) && file_exists(BASE_DIR . '/' . $fac['image_path'])): ?>
                    <img src="<?= e($fac['image_path']) ?>" alt="<?= e($fac['title']) ?>">
                    <?php else: ?>
                    <div style="height:100%;background:linear-gradient(135deg,var(--primary-color),#1e293b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem;">
                        <i class="bi <?= e($fac['icon'] ?: 'bi-building') ?>"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="program-body">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                        <div style="width:36px; height:36px; border-radius:50%; background:var(--primary-glow); color:var(--primary-color); display:flex; align-items:center; justify-content:center; font-size:1.1rem;">
                            <i class="bi <?= e($fac['icon'] ?: 'bi-check2') ?>"></i>
                        </div>
                        <h3 class="program-title" style="margin:0;"><?= e($fac['title']) ?></h3>
                    </div>
                    <p class="program-desc"><?= nl2br(e($fac['description'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
