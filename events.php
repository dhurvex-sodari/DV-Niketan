<?php
// events.php - Dynamic Events & Calendar
$page_slug = 'events';
require_once __DIR__ . '/includes/header.php';

$events = get_events(true);
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Events & Calendar</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Upcoming academic milestones, cultural festivities, and sports competitions.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($events)): ?>
        <div style="text-align:center; padding:50px; background:#fff; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
            <h3>No events currently scheduled.</h3>
        </div>
        <?php else: ?>
        <div class="programs-grid">
            <?php foreach ($events as $event): 
                $date_obj = date_create($event['event_date']);
            ?>
            <div class="program-card">
                <div class="program-image" style="height:200px;">
                    <?php if (!empty($event['image_path']) && file_exists(BASE_DIR . '/' . $event['image_path'])): ?>
                    <img src="<?= e($event['image_path']) ?>" alt="<?= e($event['title']) ?>">
                    <?php else: ?>
                    <div style="height:100%;background:linear-gradient(135deg,#e65100,#f59e0b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem;">
                        <i class="bi bi-calendar4-event"></i>
                    </div>
                    <?php endif; ?>
                    <span class="program-level-tag" style="background:var(--secondary-color);"><?= e($event['status'] ?: 'Upcoming') ?></span>
                </div>
                <div class="program-body">
                    <div style="display:flex; gap:14px; margin-bottom:12px; font-size:0.85rem; color:var(--text-muted); font-weight:600;">
                        <span><i class="bi bi-calendar-check text-primary"></i> <?= date_format($date_obj, 'M d, Y') ?></span>
                        <?php if (!empty($event['event_time'])): ?>
                        <span><i class="bi bi-clock text-warning"></i> <?= e($event['event_time']) ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="program-title" style="font-size:1.2rem;"><?= e($event['title']) ?></h3>
                    <?php if (!empty($event['location'])): ?>
                    <div style="font-size:0.85rem; color:var(--primary-color); font-weight:600; margin-bottom:10px;"><i class="bi bi-geo-alt-fill"></i> <?= e($event['location']) ?></div>
                    <?php endif; ?>
                    <p class="program-desc"><?= nl2br(e($event['description'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
