<?php
// admin/settings.php - Website Theme, Colors & Maintenance Settings
$page_title = 'Theme & Website Settings';
require_once __DIR__ . '/includes/admin_header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $primary_color = trim($_POST['primary_color'] ?? '#0d47a1');
    $secondary_color = trim($_POST['secondary_color'] ?? '#e65100');
    $accent_color = trim($_POST['accent_color'] ?? '#1976d2');
    $footer_about = trim($_POST['footer_about'] ?? '');
    $footer_copyright = trim($_POST['footer_copyright'] ?? '');
    $developer_credit = trim($_POST['developer_credit'] ?? '');
    $maintenance_mode = isset($_POST['maintenance_mode']) ? '1' : '0';
    $enable_loader = isset($_POST['enable_loader']) ? '1' : '0';

    update_setting('primary_color', $primary_color, 'appearance');
    update_setting('secondary_color', $secondary_color, 'appearance');
    update_setting('accent_color', $accent_color, 'appearance');
    update_setting('footer_about', $footer_about, 'footer');
    update_setting('footer_copyright', $footer_copyright, 'footer');
    update_setting('developer_credit', $developer_credit, 'footer');
    update_setting('maintenance_mode', $maintenance_mode, 'system');
    update_setting('enable_loader', $enable_loader, 'appearance');

    $message = "Website settings and styling updated successfully!";
}

$s = get_all_settings();
?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Website Styling & System Settings</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Customize theme colors, footer text, developer credits, and maintenance status.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
    <div class="alert alert-success alert-auto-dismiss">
        <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
    </div>
    <?php endif; ?>

    <form action="settings.php" method="POST">
        <!-- 1. Color Palette Customizer -->
        <h4 style="margin:20px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-palette-fill"></i> 1. Color Theme & Brand Palette
        </h4>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Primary Brand Color (Main headers, buttons, accents)</label>
                <div style="display:flex; align-items:center; gap:12px;">
                    <input type="color" name="primary_color" value="<?= e($s['primary_color'] ?? '#0d47a1') ?>" style="width:50px; height:42px; border:none; border-radius:6px; cursor:pointer;">
                    <input type="text" class="form-control" value="<?= e($s['primary_color'] ?? '#0d47a1') ?>" readonly style="max-width:140px;">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Secondary / Action Color (CTA buttons, highlights)</label>
                <div style="display:flex; align-items:center; gap:12px;">
                    <input type="color" name="secondary_color" value="<?= e($s['secondary_color'] ?? '#e65100') ?>" style="width:50px; height:42px; border:none; border-radius:6px; cursor:pointer;">
                    <input type="text" class="form-control" value="<?= e($s['secondary_color'] ?? '#e65100') ?>" readonly style="max-width:140px;">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Accent / Light Highlight Color</label>
                <div style="display:flex; align-items:center; gap:12px;">
                    <input type="color" name="accent_color" value="<?= e($s['accent_color'] ?? '#1976d2') ?>" style="width:50px; height:42px; border:none; border-radius:6px; cursor:pointer;">
                    <input type="text" class="form-control" value="<?= e($s['accent_color'] ?? '#1976d2') ?>" readonly style="max-width:140px;">
                </div>
            </div>
        </div>

        <!-- 2. Footer Settings -->
        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-layout-text-window"></i> 2. Footer Information & Credits
        </h4>
        <div class="form-grid">
            <div class="form-group full-width">
                <label class="form-label">Footer About Text</label>
                <textarea name="footer_about" rows="3" class="form-control"><?= e($s['footer_about'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Footer Copyright Text</label>
                <input type="text" name="footer_copyright" class="form-control" value="<?= e($s['footer_copyright'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Developer Credit Text</label>
                <input type="text" name="developer_credit" class="form-control" value="<?= e($s['developer_credit'] ?? '') ?>">
            </div>
        </div>

        <!-- 3. System & Maintenance -->
        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-wrench-adjustable-circle-fill"></i> 3. System & Maintenance
        </h4>
        <div class="form-grid">
            <div class="form-group" style="display:flex; align-items:center; gap:12px; margin-top:10px;">
                <label class="switch">
                    <input type="checkbox" name="maintenance_mode" value="1" <?= ($s['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
                <div>
                    <span style="font-weight:700; font-size:0.9rem; color:#dc2626;">Maintenance Mode</span>
                    <div style="font-size:0.75rem; color:var(--admin-text-muted);">Displays maintenance notice to public visitors (Admins can still view).</div>
                </div>
            </div>
            <div class="form-group" style="display:flex; align-items:center; gap:12px; margin-top:10px;">
                <label class="switch">
                    <input type="checkbox" name="enable_loader" value="1" <?= ($s['enable_loader'] ?? '1') === '1' ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
                <div>
                    <span style="font-weight:700; font-size:0.9rem;">Smooth Page Loading Effects</span>
                    <div style="font-size:0.75rem; color:var(--admin-text-muted);">Enable dynamic micro-interactions and transitions.</div>
                </div>
            </div>
        </div>

        <div style="margin-top:30px; border-top:1px solid var(--admin-border); padding-top:20px;">
            <button type="submit" name="save_settings" class="admin-btn admin-btn-primary" style="font-size:1rem; padding:12px 28px;">
                <i class="bi bi-save-fill"></i> Save Settings & Styling
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
