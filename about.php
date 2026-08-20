<?php
// about.php - Dynamic About Us Page
$page_slug = 'about';
require_once __DIR__ . '/includes/header.php';

$about = get_about_content();
$principal = get_principal_info();
$committee = get_committee_members(true);
$school_name = get_setting('school_name', 'DV Niketan Boarding School');
?>

<!-- Page Banner -->
<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">About Our Institution</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;"><?= e($school_name) ?> — Birendranagar-7, ITRAM, Surkhet</p>
    </div>
</section>

<!-- Introduction & History -->
<section class="section">
    <div class="container">
        <?php if (!empty($about['intro'])): ?>
        <div class="about-grid" style="margin-bottom: 60px;">
            <div>
                <span class="section-badge">Our Foundation</span>
                <h2 class="section-title"><?= e($about['intro']['title']) ?></h2>
                <div style="font-size:1.05rem; color:var(--text-muted); line-height:1.8;">
                    <?= nl2br(e($about['intro']['content'])) ?>
                </div>
            </div>
            <div class="about-img-box">
                <?php if (!empty($about['intro']['image_path']) && file_exists(BASE_DIR . '/' . $about['intro']['image_path'])): ?>
                <img src="<?= e($about['intro']['image_path']) ?>" alt="<?= e($about['intro']['title']) ?>">
                <?php else: ?>
                <div style="height:360px;background:linear-gradient(135deg,#0d47a1,#1e293b);border-radius:var(--radius-xl);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3.5rem;">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($about['history'])): ?>
        <div style="background:var(--bg-alt); padding:40px; border-radius:var(--radius-xl); border:1px solid var(--border-color); margin-bottom:60px;">
            <h3 style="font-size:1.6rem; color:var(--primary-color); margin-bottom:15px;"><?= e($about['history']['title']) ?></h3>
            <p style="font-size:1.05rem; color:var(--text-muted); line-height:1.8;"><?= nl2br(e($about['history']['content'])) ?></p>
        </div>
        <?php endif; ?>

        <!-- Vision, Mission & Core Values Grid -->
        <div class="facilities-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <?php if (!empty($about['vision'])): ?>
            <div class="facility-card" style="text-align:left;">
                <div class="facility-icon-wrap" style="margin:0 0 16px 0;"><i class="bi bi-eye-fill"></i></div>
                <h3 class="facility-title"><?= e($about['vision']['title']) ?></h3>
                <p class="facility-desc"><?= nl2br(e($about['vision']['content'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($about['mission'])): ?>
            <div class="facility-card" style="text-align:left;">
                <div class="facility-icon-wrap" style="margin:0 0 16px 0;"><i class="bi bi-compass-fill"></i></div>
                <h3 class="facility-title"><?= e($about['mission']['title']) ?></h3>
                <p class="facility-desc"><?= nl2br(e($about['mission']['content'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($about['core_values'])): ?>
            <div class="facility-card" style="text-align:left;">
                <div class="facility-icon-wrap" style="margin:0 0 16px 0;"><i class="bi bi-star-fill"></i></div>
                <h3 class="facility-title"><?= e($about['core_values']['title']) ?></h3>
                <p class="facility-desc"><?= nl2br(e($about['core_values']['content'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Principal Section -->
<?php if (!empty($principal)): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Leadership</span>
            <h2 class="section-title">Message from the Principal</h2>
        </div>
        <div class="principal-box">
            <div class="principal-photo-wrap">
                <?php if (!empty($principal['photo']) && file_exists(BASE_DIR . '/' . $principal['photo'])): ?>
                <img src="<?= e($principal['photo']) ?>" alt="<?= e($principal['name']) ?>">
                <?php else: ?>
                <div style="width:200px;height:220px;background:#cbd5e1;border-radius:var(--radius-lg);margin:0 auto 15px;display:flex;align-items:center;justify-content:center;font-size:3.5rem;color:#64748b;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <?php endif; ?>
                <h3 class="principal-name"><?= e($principal['name']) ?></h3>
                <div class="principal-designation"><?= e($principal['designation']) ?></div>
                <?php if (!empty($principal['qualification'])): ?>
                <small class="text-muted d-block"><?= e($principal['qualification']) ?></small>
                <?php endif; ?>
            </div>
            <div class="principal-message-content">
                <div class="quote-icon"><i class="bi bi-quote"></i></div>
                <p><?= nl2br(e($principal['message'])) ?></p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Management Committee -->
<?php if (!empty($committee)): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Governance</span>
            <h2 class="section-title">School Management Committee</h2>
            <p class="section-subtitle">Dedicated leaders guiding the vision, integrity, and future of <?= e($school_name) ?>.</p>
        </div>
        <div class="teachers-grid">
            <?php foreach ($committee as $member): ?>
            <div class="teacher-card">
                <div class="teacher-avatar">
                    <?php if (!empty($member['photo']) && file_exists(BASE_DIR . '/' . $member['photo'])): ?>
                    <img src="<?= e($member['photo']) ?>" alt="<?= e($member['name']) ?>">
                    <?php else: ?>
                    <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:3.5rem;color:#94a3b8;background:#f1f5f9;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="teacher-info">
                    <h4 class="teacher-name"><?= e($member['name']) ?></h4>
                    <div class="teacher-designation"><?= e($member['position']) ?></div>
                    <?php if (!empty($member['qualification'])): ?>
                    <div class="teacher-dept"><?= e($member['qualification']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($member['description'])): ?>
                    <p style="font-size:0.82rem;color:var(--text-muted);margin-top:6px;"><?= e($member['description']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
