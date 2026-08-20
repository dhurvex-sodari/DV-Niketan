<?php
// admin/homepage.php - Homepage Hero, Stats & Why Choose Us Manager
$page_title = 'Homepage Hero & Content Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';
$error = '';

// --- 1. HERO SLIDES ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_slide'])) {
    $action = $_POST['action_slide'];
    $slide_id = isset($_POST['slide_id']) ? (int)$_POST['slide_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $btn1_text = trim($_POST['btn1_text'] ?? '');
    $btn1_link = trim($_POST['btn1_link'] ?? '');
    $btn2_text = trim($_POST['btn2_text'] ?? '');
    $btn2_link = trim($_POST['btn2_link'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_path = null;
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = upload_file($_FILES['image_file'], 'media', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO hero_slides (title, subtitle, image_path, btn1_text, btn1_link, btn2_text, btn2_link, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $subtitle, $image_path, $btn1_text, $btn1_link, $btn2_text, $btn2_link, $display_order, $is_active]);
        $message = "Hero slide added successfully!";
    } elseif ($action === 'update' && $slide_id > 0) {
        if ($image_path) {
            $stmt = $db->prepare("UPDATE hero_slides SET title = ?, subtitle = ?, image_path = ?, btn1_text = ?, btn1_link = ?, btn2_text = ?, btn2_link = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $subtitle, $image_path, $btn1_text, $btn1_link, $btn2_text, $btn2_link, $display_order, $is_active, $slide_id]);
        } else {
            $stmt = $db->prepare("UPDATE hero_slides SET title = ?, subtitle = ?, btn1_text = ?, btn1_link = ?, btn2_text = ?, btn2_link = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $subtitle, $btn1_text, $btn1_link, $btn2_text, $btn2_link, $display_order, $is_active, $slide_id]);
        }
        $message = "Hero slide updated successfully!";
    }
}

// Delete Slide
if (isset($_GET['delete_slide'])) {
    $del_id = (int)$_GET['delete_slide'];
    $db->prepare("DELETE FROM hero_slides WHERE id = ?")->execute([$del_id]);
    $message = "Hero slide deleted successfully!";
}

// --- 2. STATS ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_stat'])) {
    $stat_id = isset($_POST['stat_id']) ? (int)$_POST['stat_id'] : 0;
    $number_val = trim($_POST['number_value'] ?? '');
    $label = trim($_POST['label'] ?? '');
    $icon = trim($_POST['icon'] ?? 'bi-award');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($_POST['action_stat'] === 'create') {
        $stmt = $db->prepare("INSERT INTO homepage_stats (number_value, label, icon, display_order, is_active) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$number_val, $label, $icon, $display_order, $is_active]);
        $message = "Statistic counter added!";
    } elseif ($_POST['action_stat'] === 'update' && $stat_id > 0) {
        $stmt = $db->prepare("UPDATE homepage_stats SET number_value = ?, label = ?, icon = ?, display_order = ?, is_active = ? WHERE id = ?");
        $stmt->execute([$number_val, $label, $icon, $display_order, $is_active, $stat_id]);
        $message = "Statistic counter updated!";
    }
}

if (isset($_GET['delete_stat'])) {
    $del_id = (int)$_GET['delete_stat'];
    $db->prepare("DELETE FROM homepage_stats WHERE id = ?")->execute([$del_id]);
    $message = "Statistic counter deleted!";
}

// Fetch lists
$slides = $db->query("SELECT * FROM hero_slides ORDER BY display_order ASC, id ASC")->fetchAll();
$stats = $db->query("SELECT * FROM homepage_stats ORDER BY display_order ASC, id ASC")->fetchAll();
$why_us = $db->query("SELECT * FROM why_choose_us ORDER BY display_order ASC, id ASC")->fetchAll();

// Edit Slide Target
$edit_slide = null;
if (isset($_GET['edit_slide'])) {
    $edit_id = (int)$_GET['edit_slide'];
    $stmt = $db->prepare("SELECT * FROM hero_slides WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_slide = $stmt->fetch();
}

// Edit Stat Target
$edit_stat = null;
if (isset($_GET['edit_stat'])) {
    $edit_id = (int)$_GET['edit_stat'];
    $stmt = $db->prepare("SELECT * FROM homepage_stats WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_stat = $stmt->fetch();
}
?>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-auto-dismiss">
    <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
</div>
<?php endif; ?>

<!-- ========================================== -->
<!-- SECTION 1: HERO SLIDES -->
<!-- ========================================== -->
<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Hero Banner Carousel</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage main homepage rotating banners, action buttons, and background images.</p>
        </div>
    </div>

    <!-- Slide Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_slide ? 'Edit Hero Slide' : 'Add New Hero Slide' ?></h4>
        <form action="homepage.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_slide" value="<?= $edit_slide ? 'update' : 'create' ?>">
            <?php if ($edit_slide): ?>
            <input type="hidden" name="slide_id" value="<?= $edit_slide['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Hero Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= e($edit_slide['title'] ?? '') ?>" placeholder="e.g. Welcome to DV Niketan Boarding School">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Hero Subtitle</label>
                    <textarea name="subtitle" rows="2" class="form-control" placeholder="Short description..."><?= e($edit_slide['subtitle'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Button 1 Text</label>
                    <input type="text" name="btn1_text" class="form-control" value="<?= e($edit_slide['btn1_text'] ?? 'Explore Programs') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Button 1 Link</label>
                    <input type="text" name="btn1_link" class="form-control" value="<?= e($edit_slide['btn1_link'] ?? 'academics.php') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Button 2 Text</label>
                    <input type="text" name="btn2_text" class="form-control" value="<?= e($edit_slide['btn2_text'] ?? 'Admissions') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Button 2 Link</label>
                    <input type="text" name="btn2_link" class="form-control" value="<?= e($edit_slide['btn2_link'] ?? 'contact.php') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= e($edit_slide['display_order'] ?? 1) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Background Image</label>
                    <input type="file" name="image_file" class="form-control" data-preview-target="#slidePreview" accept="image/*">
                    <?php if ($edit_slide && !empty($edit_slide['image_path'])): ?>
                    <div class="image-preview-box">
                        <img id="slidePreview" src="../<?= e($edit_slide['image_path']) ?>" alt="Slide Image">
                    </div>
                    <?php else: ?>
                    <div class="image-preview-box">
                        <img id="slidePreview" src="" style="display:none;" alt="Slide Image">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top:28px;">
                    <label class="switch">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_slide || $edit_slide['is_active']) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                    <span style="font-weight:600; font-size:0.88rem;">Active / Published</span>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_slide ? 'Update Slide' : 'Add Slide' ?>
                </button>
                <?php if ($edit_slide): ?>
                <a href="homepage.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Slides List Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Image</th>
                    <th>Title & Subtitle</th>
                    <th>Buttons</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($slides as $slide): ?>
                <tr>
                    <td><strong>#<?= $slide['display_order'] ?></strong></td>
                    <td>
                        <?php if (!empty($slide['image_path']) && file_exists(BASE_DIR . '/' . $slide['image_path'])): ?>
                        <img src="../<?= e($slide['image_path']) ?>" alt="Hero" style="width:70px; height:45px; object-fit:cover; border-radius:4px;">
                        <?php else: ?>
                        <span style="font-size:0.75rem; color:#94a3b8;">Default Gradient</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= e($slide['title']) ?></strong>
                        <div style="font-size:0.8rem; color:var(--admin-text-muted);"><?= e(mb_strimwidth($slide['subtitle'] ?? '', 0, 50, '...')) ?></div>
                    </td>
                    <td>
                        <small><?= e($slide['btn1_text']) ?> & <?= e($slide['btn2_text']) ?></small>
                    </td>
                    <td>
                        <span class="badge <?= $slide['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $slide['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="homepage.php?edit_slide=<?= $slide['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="homepage.php?delete_slide=<?= $slide['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- SECTION 2: HOMEPAGE STATS COUNTER -->
<!-- ========================================== -->
<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Homepage Statistics Counters</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage key metric highlights (e.g. 1500+ Students, 100% Pass Rate).</p>
        </div>
    </div>

    <!-- Stat Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_stat ? 'Edit Statistic' : 'Add Statistic Counter' ?></h4>
        <form action="homepage.php" method="POST">
            <input type="hidden" name="action_stat" value="<?= $edit_stat ? 'update' : 'create' ?>">
            <?php if ($edit_stat): ?>
            <input type="hidden" name="stat_id" value="<?= $edit_stat['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Number Value (e.g. 1500+, 100%, 25+) *</label>
                    <input type="text" name="number_value" class="form-control" required value="<?= e($edit_stat['number_value'] ?? '') ?>" placeholder="1500+">
                </div>
                <div class="form-group">
                    <label class="form-label">Label Description *</label>
                    <input type="text" name="label" class="form-control" required value="<?= e($edit_stat['label'] ?? '') ?>" placeholder="Enrolled Students">
                </div>
                <div class="form-group">
                    <label class="form-label">Bootstrap Icon Class</label>
                    <input type="text" name="icon" class="form-control" value="<?= e($edit_stat['icon'] ?? 'bi-award') ?>" placeholder="bi-award, bi-mortarboard, bi-stars">
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= e($edit_stat['display_order'] ?? 1) ?>">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top:28px;">
                    <label class="switch">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_stat || $edit_stat['is_active']) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                    <span style="font-weight:600; font-size:0.88rem;">Active / Visible</span>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_stat ? 'Update Stat' : 'Add Stat' ?>
                </button>
                <?php if ($edit_stat): ?>
                <a href="homepage.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Stats Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Icon</th>
                    <th>Number</th>
                    <th>Label</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats as $st): ?>
                <tr>
                    <td><strong>#<?= $st['display_order'] ?></strong></td>
                    <td><i class="bi <?= e($st['icon']) ?> text-primary" style="font-size:1.3rem;"></i></td>
                    <td><strong style="font-size:1.1rem;"><?= e($st['number_value']) ?></strong></td>
                    <td><?= e($st['label']) ?></td>
                    <td>
                        <span class="badge <?= $st['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $st['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="homepage.php?edit_stat=<?= $st['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="homepage.php?delete_stat=<?= $st['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
