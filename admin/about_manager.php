<?php
// admin/about_manager.php - About Page Content & Vision/Mission Manager
$page_title = 'About Page Content Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_about_content'])) {
    $sections = ['intro', 'history', 'vision', 'mission', 'objectives', 'core_values'];

    foreach ($sections as $sec) {
        $title = trim($_POST[$sec . '_title'] ?? '');
        $content = trim($_POST[$sec . '_content'] ?? '');

        // Check if image uploaded for intro or history
        $img_path = null;
        if (!empty($_FILES[$sec . '_image']['name'])) {
            $img_path = upload_file($_FILES[$sec . '_image'], 'media', ['jpg', 'jpeg', 'png', 'webp']);
        }

        if ($img_path) {
            $stmt = $db->prepare("INSERT INTO about_content (section_key, title, content, image_path) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE title = VALUES(title), content = VALUES(content), image_path = VALUES(image_path)");
            $stmt->execute([$sec, $title, $content, $img_path]);
        } else {
            $stmt = $db->prepare("INSERT INTO about_content (section_key, title, content) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE title = VALUES(title), content = VALUES(content)");
            $stmt->execute([$sec, $title, $content]);
        }
    }
    $message = "About page content updated successfully!";
}

$about = get_about_content();
?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>About Page Content, Vision & Mission</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage school introduction, legacy, vision, mission statements, and institutional values.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
    <div class="alert alert-success alert-auto-dismiss">
        <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
    </div>
    <?php endif; ?>

    <form action="about_manager.php" method="POST" enctype="multipart/form-data">
        <!-- 1. School Introduction -->
        <h4 style="margin:20px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-building"></i> 1. School Introduction
        </h4>
        <div class="form-grid">
            <div class="form-group full-width">
                <label class="form-label">Intro Section Title</label>
                <input type="text" name="intro_title" class="form-control" value="<?= e($about['intro']['title'] ?? 'About DV Niketan Boarding School') ?>">
            </div>
            <div class="form-group full-width">
                <label class="form-label">Introduction Content</label>
                <textarea name="intro_content" rows="4" class="form-control"><?= e($about['intro']['content'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Intro Image</label>
                <input type="file" name="intro_image" class="form-control" data-preview-target="#introPreview" accept="image/*">
                <?php if (!empty($about['intro']['image_path'])): ?>
                <div class="image-preview-box">
                    <img id="introPreview" src="../<?= e($about['intro']['image_path']) ?>" alt="Intro Image">
                </div>
                <?php else: ?>
                <div class="image-preview-box">
                    <img id="introPreview" src="" style="display:none;" alt="Intro Image">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. History & Legacy -->
        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-hourglass-split"></i> 2. History & Background
        </h4>
        <div class="form-grid">
            <div class="form-group full-width">
                <label class="form-label">History Section Title</label>
                <input type="text" name="history_title" class="form-control" value="<?= e($about['history']['title'] ?? 'Our History & Legacy') ?>">
            </div>
            <div class="form-group full-width">
                <label class="form-label">History Content</label>
                <textarea name="history_content" rows="4" class="form-control"><?= e($about['history']['content'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- 3. Vision & Mission -->
        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-eye-fill"></i> 3. Vision & Mission
        </h4>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Vision Title</label>
                <input type="text" name="vision_title" class="form-control" value="<?= e($about['vision']['title'] ?? 'Our Vision') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Mission Title</label>
                <input type="text" name="mission_title" class="form-control" value="<?= e($about['mission']['title'] ?? 'Our Mission') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Vision Statement</label>
                <textarea name="vision_content" rows="4" class="form-control"><?= e($about['vision']['content'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Mission Statement</label>
                <textarea name="mission_content" rows="4" class="form-control"><?= e($about['mission']['content'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- 4. Objectives & Values -->
        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-star-fill"></i> 4. Objectives & Core Values
        </h4>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Objectives Title</label>
                <input type="text" name="objectives_title" class="form-control" value="<?= e($about['objectives']['title'] ?? 'Core Objectives') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Core Values Title</label>
                <input type="text" name="core_values_title" class="form-control" value="<?= e($about['core_values']['title'] ?? 'Our Core Values') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Objectives Description</label>
                <textarea name="objectives_content" rows="4" class="form-control"><?= e($about['objectives']['content'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Core Values List</label>
                <textarea name="core_values_content" rows="4" class="form-control"><?= e($about['core_values']['content'] ?? '') ?></textarea>
            </div>
        </div>

        <div style="margin-top:30px; border-top:1px solid var(--admin-border); padding-top:20px;">
            <button type="submit" name="save_about_content" class="admin-btn admin-btn-primary" style="font-size:1rem; padding:12px 28px;">
                <i class="bi bi-save-fill"></i> Save About Page Content
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
