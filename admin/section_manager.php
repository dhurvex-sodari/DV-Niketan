<?php
// admin/section_manager.php - Website Section Visibility Manager
$page_title = 'Section Visibility Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_sections'])) {
    $visible_sections = $_POST['sections'] ?? [];
    $orders = $_POST['display_orders'] ?? [];

    $all_sections = $db->query("SELECT section_key FROM section_visibility")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($all_sections as $sec_key) {
        $is_vis = in_array($sec_key, $visible_sections) ? 1 : 0;
        $ord = isset($orders[$sec_key]) ? (int)$orders[$sec_key] : 0;
        $stmt = $db->prepare("UPDATE section_visibility SET is_visible = ?, display_order = ? WHERE section_key = ?");
        $stmt->execute([$is_vis, $ord, $sec_key]);
    }

    $message = "Section visibility updated successfully! Active sections are live.";
}

$sections = $db->query("SELECT * FROM section_visibility ORDER BY display_order ASC, id ASC")->fetchAll();
?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Website Section Visibility & Ordering</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Enable or disable sections on the homepage and across the public portal.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
    <div class="alert alert-success alert-auto-dismiss">
        <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
    </div>
    <?php endif; ?>

    <form action="section_manager.php" method="POST">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Show/Hide</th>
                        <th>Section Title</th>
                        <th>Section Identifier</th>
                        <th style="width:140px;">Display Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sections as $sec): ?>
                    <tr>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="sections[]" value="<?= e($sec['section_key']) ?>" <?= $sec['is_visible'] ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <strong><?= e($sec['section_title']) ?></strong>
                        </td>
                        <td>
                            <code><?= e($sec['section_key']) ?></code>
                        </td>
                        <td>
                            <input type="number" name="display_orders[<?= e($sec['section_key']) ?>]" class="form-control" value="<?= (int)$sec['display_order'] ?>" style="width:90px;">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:25px;">
            <button type="submit" name="save_sections" class="admin-btn admin-btn-primary">
                <i class="bi bi-save-fill"></i> Save Visibility Settings
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
