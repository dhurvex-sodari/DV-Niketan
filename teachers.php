<?php
// teachers.php - Dynamic Faculty & Staff Directory
$page_slug = 'teachers';
require_once __DIR__ . '/includes/header.php';

$selected_dept = $_GET['dept'] ?? null;
$teachers = get_teachers(true, $selected_dept);

// Get unique departments for filter tabs
$db = get_db();
$departments = $db->query("SELECT DISTINCT department FROM teachers WHERE is_active = 1 ORDER BY department ASC")->fetchAll(PDO::FETCH_COLUMN);
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Our Faculty & Staff</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Dedicated educators inspiring curiosity, innovation, and character.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Department Filter -->
        <div style="display:flex; justify-content:center; gap:10px; margin-bottom:40px; flex-wrap:wrap;">
            <a href="teachers.php" class="btn <?= empty($selected_dept) ? 'btn-primary' : 'btn-secondary' ?>" style="font-size:0.85rem; padding:8px 18px;">All Departments</a>
            <?php foreach ($departments as $dept): ?>
            <a href="teachers.php?dept=<?= urlencode($dept) ?>" class="btn <?= $selected_dept === $dept ? 'btn-primary' : 'btn-secondary' ?>" style="font-size:0.85rem; padding:8px 18px;">
                <?= e($dept) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($teachers)): ?>
        <div style="text-align:center; padding:50px; background:#fff; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
            <h3>No faculty members found in this category.</h3>
        </div>
        <?php else: ?>
        <div class="teachers-grid">
            <?php foreach ($teachers as $teacher): ?>
            <div class="teacher-card">
                <div class="teacher-avatar">
                    <?php if (!empty($teacher['photo']) && file_exists(BASE_DIR . '/' . $teacher['photo'])): ?>
                    <img src="<?= e($teacher['photo']) ?>" alt="<?= e($teacher['name']) ?>">
                    <?php else: ?>
                    <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:3.5rem;color:#94a3b8;background:#f1f5f9;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="teacher-info">
                    <h4 class="teacher-name"><?= e($teacher['name']) ?></h4>
                    <div class="teacher-designation"><?= e($teacher['designation']) ?></div>
                    <div class="teacher-dept"><?= e($teacher['department']) ?> <?= !empty($teacher['subject']) ? '• ' . e($teacher['subject']) : '' ?></div>
                    <?php if (!empty($teacher['qualification'])): ?>
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-bottom:8px;"><strong>Qual:</strong> <?= e($teacher['qualification']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($teacher['bio'])): ?>
                    <p style="font-size:0.82rem; color:var(--text-muted); margin-bottom:12px;"><?= e(mb_strimwidth($teacher['bio'], 0, 100, '...')) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
