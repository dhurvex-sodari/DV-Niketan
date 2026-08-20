<?php
// academics.php - Dynamic Academics & Programs Page
$page_slug = 'academics';
require_once __DIR__ . '/includes/header.php';

$programs = get_academic_programs(true);
$school_name = get_setting('school_name', 'DV Niketan Boarding School');
?>

<!-- Page Banner -->
<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Academic Programs</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Comprehensive curricula fostering theoretical mastery and practical skills.</p>
    </div>
</section>

<!-- Programs Detailed List -->
<section class="section">
    <div class="container">
        <?php if (empty($programs)): ?>
        <div style="text-align:center; padding:50px; background:#fff; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
            <h3>No academic programs listed yet.</h3>
            <p class="text-muted">Please check back soon.</p>
        </div>
        <?php else: ?>
            <?php foreach ($programs as $index => $prog): ?>
            <div id="<?= e($prog['slug']) ?>" style="background:#fff; border-radius:var(--radius-xl); border:1px solid var(--border-color); box-shadow:var(--shadow-md); padding:35px; margin-bottom:40px; scroll-margin-top:100px;">
                <div style="display:grid; grid-template-columns: 1fr 340px; gap:40px; align-items:start;">
                    <div>
                        <span class="section-badge"><?= e($prog['level']) ?></span>
                        <h2 style="font-size:2rem; color:var(--primary-color); margin-bottom:12px;"><?= e($prog['name']) ?></h2>
                        <div style="display:flex; gap:20px; margin-bottom:20px; font-size:0.9rem; color:var(--text-muted); font-weight:600;">
                            <?php if (!empty($prog['duration'])): ?>
                            <span><i class="bi bi-clock-fill text-warning"></i> Duration: <?= e($prog['duration']) ?></span>
                            <?php endif; ?>
                            <span><i class="bi bi-mortarboard-fill text-primary"></i> Board: National Examination Board (NEB)</span>
                        </div>

                        <div style="font-size:1rem; color:var(--text-muted); line-height:1.8; margin-bottom:25px;">
                            <?= nl2br(e($prog['description'])) ?>
                        </div>

                        <?php if (!empty($prog['requirements'])): ?>
                        <div style="background:var(--bg-alt); padding:20px; border-radius:var(--radius-md); margin-bottom:25px; border-left:4px solid var(--primary-color);">
                            <h4 style="font-size:1.05rem; margin-bottom:6px; color:var(--text-main);"><i class="bi bi-check2-circle text-success"></i> Eligibility / Entry Requirements:</h4>
                            <p style="font-size:0.92rem; color:var(--text-muted);"><?= nl2br(e($prog['requirements'])) ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($prog['syllabus'])): ?>
                        <div>
                            <h4 style="font-size:1.1rem; margin-bottom:10px; color:var(--text-main);"><i class="bi bi-book text-primary"></i> Curriculum & Subjects Offered:</h4>
                            <div style="background:#f8fafc; padding:18px 24px; border-radius:var(--radius-md); border:1px solid var(--border-color); font-size:0.92rem; color:var(--text-muted); line-height:1.7;">
                                <?= nl2br(e($prog['syllabus'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div style="margin-top:25px;">
                            <a href="contact.php" class="btn btn-primary">Apply For Admission <i class="bi bi-arrow-right"></i></a>
                            <a href="downloads.php" class="btn btn-secondary" style="margin-left:10px;"><i class="bi bi-download"></i> Download Syllabus / Form</a>
                        </div>
                    </div>

                    <div>
                        <?php if (!empty($prog['image_path']) && file_exists(BASE_DIR . '/' . $prog['image_path'])): ?>
                        <img src="<?= e($prog['image_path']) ?>" alt="<?= e($prog['name']) ?>" style="border-radius:var(--radius-lg); width:100%; height:260px; object-fit:cover; box-shadow:var(--shadow-md);">
                        <?php else: ?>
                        <div style="height:260px; background:linear-gradient(135deg, var(--primary-color), var(--primary-light)); border-radius:var(--radius-lg); display:flex; align-items:center; justify-content:center; color:#fff; font-size:3.5rem;">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <?php endif; ?>

                        <div style="margin-top:20px; background:var(--bg-alt); padding:20px; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                            <h4 style="font-size:1rem; margin-bottom:10px;">Why Study <?= e($prog['name']) ?> at <?= e($school_name) ?>?</h4>
                            <ul style="list-style:none; font-size:0.88rem; color:var(--text-muted); line-height:1.8;">
                                <li><i class="bi bi-check-lg text-success"></i> Highly Qualified Faculties</li>
                                <li><i class="bi bi-check-lg text-success"></i> Well-equipped Practical Labs</li>
                                <li><i class="bi bi-check-lg text-success"></i> Regular Terminal Tests & Mock Board Exams</li>
                                <li><i class="bi bi-check-lg text-success"></i> Career Guidance & Entrance Preparation</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
