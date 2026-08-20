<?php
// admin/facilities.php - Campus Facilities CRUD Manager
$page_title = 'Facilities Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_facility'])) {
    $action = $_POST['action_facility'];
    $fac_id = isset($_POST['fac_id']) ? (int)$_POST['fac_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? 'bi-building');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_path = null;
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = upload_file($_FILES['image_file'], 'facilities', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO facilities (title, description, image_path, icon, display_order, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $image_path, $icon, $display_order, $is_featured, $is_active]);
        $message = "Facility added successfully!";
    } elseif ($action === 'update' && $fac_id > 0) {
        if ($image_path) {
            $stmt = $db->prepare("UPDATE facilities SET title = ?, description = ?, image_path = ?, icon = ?, display_order = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $description, $image_path, $icon, $display_order, $is_featured, $is_active, $fac_id]);
        } else {
            $stmt = $db->prepare("UPDATE facilities SET title = ?, description = ?, icon = ?, display_order = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $description, $icon, $display_order, $is_featured, $is_active, $fac_id]);
        }
        $message = "Facility updated successfully!";
    }
}

if (isset($_GET['delete_fac'])) {
    $del_id = (int)$_GET['delete_fac'];
    $db->prepare("DELETE FROM facilities WHERE id = ?")->execute([$del_id]);
    $message = "Facility deleted!";
}

$facilities = $db->query("SELECT * FROM facilities ORDER BY display_order ASC, id ASC")->fetchAll();

$edit_fac = null;
if (isset($_GET['edit_fac'])) {
    $edit_id = (int)$_GET['edit_fac'];
    $stmt = $db->prepare("SELECT * FROM facilities WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_fac = $stmt->fetch();
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
            <h3>Campus Facilities & Infrastructure</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage science labs, computer rooms, library, sports grounds, and student amenities.</p>
        </div>
    </div>

    <!-- Facility Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_fac ? 'Edit Facility' : 'Add New Facility' ?></h4>
        <form action="facilities.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_facility" value="<?= $edit_fac ? 'update' : 'create' ?>">
            <?php if ($edit_fac): ?>
            <input type="hidden" name="fac_id" value="<?= $edit_fac['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Facility Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= e($edit_fac['title'] ?? '') ?>" placeholder="e.g. Science Laboratories, IT Lab">
                </div>
                <div class="form-group">
                    <label class="form-label">Bootstrap Icon Class</label>
                    <input type="text" name="icon" class="form-control" value="<?= e($edit_fac['icon'] ?? 'bi-building') ?>" placeholder="e.g. bi-pc-display, bi-book-half, bi-dribbble">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Description *</label>
                    <textarea name="description" rows="3" class="form-control" required><?= e($edit_fac['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Facility Photo</label>
                    <input type="file" name="image_file" class="form-control" data-preview-target="#facPreview" accept="image/*">
                    <?php if ($edit_fac && !empty($edit_fac['image_path'])): ?>
                    <div class="image-preview-box">
                        <img id="facPreview" src="../<?= e($edit_fac['image_path']) ?>" alt="Facility Photo">
                    </div>
                    <?php else: ?>
                    <div class="image-preview-box">
                        <img id="facPreview" src="" style="display:none;" alt="Facility Photo">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= e($edit_fac['display_order'] ?? 1) ?>">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:20px; margin-top:28px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?= ($edit_fac && $edit_fac['is_featured']) ? 'checked' : '' ?>>
                        <span>Feature on Homepage</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_fac || $edit_fac['is_active']) ? 'checked' : '' ?>>
                        <span>Active / Visible</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_fac ? 'Update Facility' : 'Add Facility' ?>
                </button>
                <?php if ($edit_fac): ?>
                <a href="facilities.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Icon</th>
                    <th>Title</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facilities as $f): ?>
                <tr>
                    <td><strong>#<?= $f['display_order'] ?></strong></td>
                    <td><i class="bi <?= e($f['icon']) ?> text-primary" style="font-size:1.3rem;"></i></td>
                    <td><strong><?= e($f['title']) ?></strong></td>
                    <td>
                        <?php if (!empty($f['image_path']) && file_exists(BASE_DIR . '/' . $f['image_path'])): ?>
                        <img src="../<?= e($f['image_path']) ?>" alt="<?= e($f['title']) ?>" style="width:60px; height:40px; border-radius:4px; object-fit:cover;">
                        <?php else: ?>
                        <span style="font-size:0.75rem; color:#94a3b8;">No Photo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?= $f['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $f['is_active'] ? 'Active' : 'Hidden' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="facilities.php?edit_fac=<?= $f['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="facilities.php?delete_fac=<?= $f['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
