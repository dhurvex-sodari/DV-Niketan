<?php
// admin/menu_manager.php - Navigation & Dropdown Menu Manager
$page_title = 'Navigation Menu Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

// Handle Create / Update Menu Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_menu'])) {
    $action = $_POST['action_menu'];
    $menu_id = isset($_POST['menu_id']) ? (int)$_POST['menu_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $parent_id = (int)($_POST['parent_id'] ?? 0);
    $display_order = (int)($_POST['display_order'] ?? 0);
    $open_new_tab = isset($_POST['open_new_tab']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO menus (title, url, parent_id, display_order, open_new_tab, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $url, $parent_id, $display_order, $open_new_tab, $is_active]);
        $message = "Menu item added successfully!";
    } elseif ($action === 'update' && $menu_id > 0) {
        $stmt = $db->prepare("UPDATE menus SET title = ?, url = ?, parent_id = ?, display_order = ?, open_new_tab = ?, is_active = ? WHERE id = ?");
        $stmt->execute([$title, $url, $parent_id, $display_order, $open_new_tab, $is_active, $menu_id]);
        $message = "Menu item updated successfully!";
    }
}

// Handle Delete
if (isset($_GET['delete_menu'])) {
    $del_id = (int)$_GET['delete_menu'];
    $db->prepare("DELETE FROM menus WHERE id = ? OR parent_id = ?")->execute([$del_id, $del_id]);
    $message = "Menu item deleted!";
}

// Fetch all menus
$all_menus = $db->query("SELECT * FROM menus ORDER BY display_order ASC, id ASC")->fetchAll();
$parent_menus = $db->query("SELECT * FROM menus WHERE parent_id = 0 ORDER BY display_order ASC")->fetchAll();

// Edit Target
$edit_menu = null;
if (isset($_GET['edit_menu'])) {
    $edit_id = (int)$_GET['edit_menu'];
    $stmt = $db->prepare("SELECT * FROM menus WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_menu = $stmt->fetch();
}
?>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-auto-dismiss">
    <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Navigation & Menu Items</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage header navigation links, custom URLs, and nested dropdown items.</p>
        </div>
    </div>

    <!-- Menu Item Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_menu ? 'Edit Menu Item' : 'Add New Menu Item' ?></h4>
        <form action="menu_manager.php" method="POST">
            <input type="hidden" name="action_menu" value="<?= $edit_menu ? 'update' : 'create' ?>">
            <?php if ($edit_menu): ?>
            <input type="hidden" name="menu_id" value="<?= $edit_menu['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Menu Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= e($edit_menu['title'] ?? '') ?>" placeholder="e.g. Facilities">
                </div>
                <div class="form-group">
                    <label class="form-label">Target URL *</label>
                    <input type="text" name="url" class="form-control" required value="<?= e($edit_menu['url'] ?? '') ?>" placeholder="e.g. facilities.php or https://...">
                </div>
                <div class="form-group">
                    <label class="form-label">Parent Menu (for Dropdown)</label>
                    <select name="parent_id" class="form-control">
                        <option value="0">-- None (Top Level Item) --</option>
                        <?php foreach ($parent_menus as $p): 
                            if ($edit_menu && $edit_menu['id'] === $p['id']) continue;
                        ?>
                        <option value="<?= $p['id'] ?>" <?= ($edit_menu && $edit_menu['parent_id'] == $p['id']) ? 'selected' : '' ?>>
                            <?= e($p['title']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= e($edit_menu['display_order'] ?? 1) ?>">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:24px; margin-top:28px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="open_new_tab" value="1" <?= ($edit_menu && $edit_menu['open_new_tab']) ? 'checked' : '' ?>>
                        <span>Open in New Tab</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_menu || $edit_menu['is_active']) ? 'checked' : '' ?>>
                        <span>Active / Visible</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_menu ? 'Update Menu Item' : 'Add Menu Item' ?>
                </button>
                <?php if ($edit_menu): ?>
                <a href="menu_manager.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Menus Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Menu Title</th>
                    <th>Target Link</th>
                    <th>Hierarchy</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_menus as $m): ?>
                <tr>
                    <td><strong>#<?= $m['display_order'] ?></strong></td>
                    <td>
                        <?= $m['parent_id'] > 0 ? '— ' : '' ?><strong><?= e($m['title']) ?></strong>
                    </td>
                    <td><code><?= e($m['url']) ?></code></td>
                    <td>
                        <span class="badge <?= $m['parent_id'] == 0 ? 'badge-info' : 'badge-warning' ?>">
                            <?= $m['parent_id'] == 0 ? 'Main Nav Item' : 'Dropdown Child' ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $m['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $m['is_active'] ? 'Active' : 'Hidden' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="menu_manager.php?edit_menu=<?= $m['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="menu_manager.php?delete_menu=<?= $m['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
