<?php
// admin/committee.php - Management Committee Members CRUD
$page_title = 'Management Committee';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_member'])) {
    $action = $_POST['action_member'];
    $member_id = isset($_POST['member_id']) ? (int)$_POST['member_id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $qualification = trim($_POST['qualification'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $photo_path = null;
    if (!empty($_FILES['photo']['name'])) {
        $photo_path = upload_file($_FILES['photo'], 'teachers', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO committee_members (name, position, photo, qualification, description, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $position, $photo_path, $qualification, $description, $display_order, $is_active]);
        $message = "Committee member added successfully!";
    } elseif ($action === 'update' && $member_id > 0) {
        if ($photo_path) {
            $stmt = $db->prepare("UPDATE committee_members SET name = ?, position = ?, photo = ?, qualification = ?, description = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $position, $photo_path, $qualification, $description, $display_order, $is_active, $member_id]);
        } else {
            $stmt = $db->prepare("UPDATE committee_members SET name = ?, position = ?, qualification = ?, description = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $position, $qualification, $description, $display_order, $is_active, $member_id]);
        }
        $message = "Committee member updated successfully!";
    }
}

if (isset($_GET['delete_member'])) {
    $del_id = (int)$_GET['delete_member'];
    $db->prepare("DELETE FROM committee_members WHERE id = ?")->execute([$del_id]);
    $message = "Committee member deleted successfully!";
}

$members = $db->query("SELECT * FROM committee_members ORDER BY display_order ASC, id ASC")->fetchAll();

$edit_member = null;
if (isset($_GET['edit_member'])) {
    $edit_id = (int)$_GET['edit_member'];
    $stmt = $db->prepare("SELECT * FROM committee_members WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_member = $stmt->fetch();
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
            <h3>School Management Committee Members</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage governance committee members, designations, qualifications, and profile photos.</p>
        </div>
    </div>

    <!-- Member Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_member ? 'Edit Member Profile' : 'Add Committee Member' ?></h4>
        <form action="committee.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_member" value="<?= $edit_member ? 'update' : 'create' ?>">
            <?php if ($edit_member): ?>
            <input type="hidden" name="member_id" value="<?= $edit_member['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Member Full Name *</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($edit_member['name'] ?? '') ?>" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Committee Position / Role *</label>
                    <input type="text" name="position" class="form-control" required value="<?= e($edit_member['position'] ?? '') ?>" placeholder="e.g. Chairman, Vice Chairman, Member Secretary">
                </div>
                <div class="form-group">
                    <label class="form-label">Qualification / Degree</label>
                    <input type="text" name="qualification" class="form-control" value="<?= e($edit_member['qualification'] ?? '') ?>" placeholder="e.g. M.A., M.B.A.">
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= e($edit_member['display_order'] ?? 1) ?>">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Short Description / Bio</label>
                    <textarea name="description" rows="2" class="form-control"><?= e($edit_member['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Member Photo</label>
                    <input type="file" name="photo" class="form-control" data-preview-target="#memberPreview" accept="image/*">
                    <?php if ($edit_member && !empty($edit_member['photo'])): ?>
                    <div class="image-preview-box">
                        <img id="memberPreview" src="../<?= e($edit_member['photo']) ?>" alt="Member Photo">
                    </div>
                    <?php else: ?>
                    <div class="image-preview-box">
                        <img id="memberPreview" src="" style="display:none;" alt="Member Photo">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top:28px;">
                    <label class="switch">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_member || $edit_member['is_active']) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                    <span style="font-weight:600; font-size:0.88rem;">Active / Visible</span>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_member ? 'Update Member' : 'Add Member' ?>
                </button>
                <?php if ($edit_member): ?>
                <a href="committee.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
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
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Qualification</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $m): ?>
                <tr>
                    <td><strong>#<?= $m['display_order'] ?></strong></td>
                    <td>
                        <?php if (!empty($m['photo']) && file_exists(BASE_DIR . '/' . $m['photo'])): ?>
                        <img src="../<?= e($m['photo']) ?>" alt="<?= e($m['name']) ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                        <div style="width:40px; height:40px; border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b;">
                            <i class="bi bi-person"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= e($m['name']) ?></strong></td>
                    <td><?= e($m['position']) ?></td>
                    <td><small><?= e($m['qualification']) ?></small></td>
                    <td>
                        <span class="badge <?= $m['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $m['is_active'] ? 'Active' : 'Hidden' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="committee.php?edit_member=<?= $m['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="committee.php?delete_member=<?= $m['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
