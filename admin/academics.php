<?php
// admin/academics.php - Academic Programs & Curriculum CRUD Manager
$page_title = 'Academic Programs Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_program'])) {
    $action = $_POST['action_program'];
    $program_id = isset($_POST['program_id']) ? (int)$_POST['program_id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }
    $level = trim($_POST['level'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $requirements = trim($_POST['requirements'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $syllabus = trim($_POST['syllabus'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_path = null;
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = upload_file($_FILES['image_file'], 'programs', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO academic_programs (name, slug, level, duration, requirements, description, syllabus, image_path, display_order, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $level, $duration, $requirements, $description, $syllabus, $image_path, $display_order, $is_featured, $is_active]);
        $message = "Academic program added successfully!";
    } elseif ($action === 'update' && $program_id > 0) {
        if ($image_path) {
            $stmt = $db->prepare("UPDATE academic_programs SET name = ?, slug = ?, level = ?, duration = ?, requirements = ?, description = ?, syllabus = ?, image_path = ?, display_order = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $level, $duration, $requirements, $description, $syllabus, $image_path, $display_order, $is_featured, $is_active, $program_id]);
        } else {
            $stmt = $db->prepare("UPDATE academic_programs SET name = ?, slug = ?, level = ?, duration = ?, requirements = ?, description = ?, syllabus = ?, display_order = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $level, $duration, $requirements, $description, $syllabus, $display_order, $is_featured, $is_active, $program_id]);
        }
        $message = "Academic program updated successfully!";
    }
}

if (isset($_GET['delete_program'])) {
    $del_id = (int)$_GET['delete_program'];
    $db->prepare("DELETE FROM academic_programs WHERE id = ?")->execute([$del_id]);
    $message = "Academic program removed!";
}

$programs = $db->query("SELECT * FROM academic_programs ORDER BY display_order ASC, id ASC")->fetchAll();

$edit_prog = null;
if (isset($_GET['edit_program'])) {
    $edit_id = (int)$_GET['edit_program'];
    $stmt = $db->prepare("SELECT * FROM academic_programs WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_prog = $stmt->fetch();
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
            <h3>Academic Programs & Levels</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage existing programs (+2 Science, +2 Management) or create future programs (e.g. +2 Humanities, School Levels) dynamically.</p>
        </div>
    </div>

    <!-- Program Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_prog ? 'Edit Academic Program' : 'Add New Academic Program' ?></h4>
        <form action="academics.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_program" value="<?= $edit_prog ? 'update' : 'create' ?>">
            <?php if ($edit_prog): ?>
            <input type="hidden" name="program_id" value="<?= $edit_prog['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Program Name *</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($edit_prog['name'] ?? '') ?>" placeholder="e.g. +2 Science, +2 Management, +2 Humanities">
                </div>
                <div class="form-group">
                    <label class="form-label">URL Slug (Auto-generated if empty)</label>
                    <input type="text" name="slug" class="form-control" value="<?= e($edit_prog['slug'] ?? '') ?>" placeholder="e.g. plus-two-science">
                </div>
                <div class="form-group">
                    <label class="form-label">Academic Level *</label>
                    <input type="text" name="level" class="form-control" required value="<?= e($edit_prog['level'] ?? 'Higher Secondary (+2 NEB)') ?>" placeholder="e.g. Higher Secondary (+2 NEB)">
                </div>
                <div class="form-group">
                    <label class="form-label">Program Duration</label>
                    <input type="text" name="duration" class="form-control" value="<?= e($edit_prog['duration'] ?? '2 Years') ?>" placeholder="e.g. 2 Years">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Program Description *</label>
                    <textarea name="description" rows="4" class="form-control" required><?= e($edit_prog['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Entry Requirements / Eligibility</label>
                    <textarea name="requirements" rows="2" class="form-control" placeholder="e.g. SEE with minimum GPA 2.8..."><?= e($edit_prog['requirements'] ?? '') ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Syllabus & Subject Combinations</label>
                    <textarea name="syllabus" rows="3" class="form-control" placeholder="e.g. Compulsory: English, Nepali..."><?= e($edit_prog['syllabus'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Featured Image</label>
                    <input type="file" name="image_file" class="form-control" data-preview-target="#progPreview" accept="image/*">
                    <?php if ($edit_prog && !empty($edit_prog['image_path'])): ?>
                    <div class="image-preview-box">
                        <img id="progPreview" src="../<?= e($edit_prog['image_path']) ?>" alt="Program Image">
                    </div>
                    <?php else: ?>
                    <div class="image-preview-box">
                        <img id="progPreview" src="" style="display:none;" alt="Program Image">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= e($edit_prog['display_order'] ?? 1) ?>">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:20px; margin-top:28px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?= ($edit_prog && $edit_prog['is_featured']) ? 'checked' : '' ?>>
                        <span>Feature on Homepage</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_prog || $edit_prog['is_active']) ? 'checked' : '' ?>>
                        <span>Active / Published</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_prog ? 'Update Program' : 'Add Academic Program' ?>
                </button>
                <?php if ($edit_prog): ?>
                <a href="academics.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Programs Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Image</th>
                    <th>Program Name</th>
                    <th>Level</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($programs as $p): ?>
                <tr>
                    <td><strong>#<?= $p['display_order'] ?></strong></td>
                    <td>
                        <?php if (!empty($p['image_path']) && file_exists(BASE_DIR . '/' . $p['image_path'])): ?>
                        <img src="../<?= e($p['image_path']) ?>" alt="<?= e($p['name']) ?>" style="width:60px; height:40px; border-radius:4px; object-fit:cover;">
                        <?php else: ?>
                        <div style="width:60px; height:40px; border-radius:4px; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:1.2rem;">
                            <i class="bi bi-book"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= e($p['name']) ?></strong>
                        <div style="font-size:0.75rem; color:var(--admin-text-muted);">Slug: <code><?= e($p['slug']) ?></code></div>
                    </td>
                    <td><span class="badge badge-info"><?= e($p['level']) ?></span></td>
                    <td><?= e($p['duration'] ?: '—') ?></td>
                    <td>
                        <span class="badge <?= $p['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $p['is_active'] ? 'Active' : 'Hidden' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="academics.php?edit_program=<?= $p['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="academics.php?delete_program=<?= $p['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
