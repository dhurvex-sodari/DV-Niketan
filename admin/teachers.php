<?php
// admin/teachers.php - Teachers & Staff CRUD Manager
$page_title = 'Teachers & Staff Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_teacher'])) {
    $action = $_POST['action_teacher'];
    $teacher_id = isset($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $qualification = trim($_POST['qualification'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $photo_path = null;
    if (!empty($_FILES['photo']['name'])) {
        $photo_path = upload_file($_FILES['photo'], 'teachers', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO teachers (name, photo, designation, department, subject, qualification, experience, bio, phone, email, display_order, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $photo_path, $designation, $department, $subject, $qualification, $experience, $bio, $phone, $email, $display_order, $is_featured, $is_active]);
        $message = "Teacher added successfully!";
    } elseif ($action === 'update' && $teacher_id > 0) {
        if ($photo_path) {
            $stmt = $db->prepare("UPDATE teachers SET name = ?, photo = ?, designation = ?, department = ?, subject = ?, qualification = ?, experience = ?, bio = ?, phone = ?, email = ?, display_order = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $photo_path, $designation, $department, $subject, $qualification, $experience, $bio, $phone, $email, $display_order, $is_featured, $is_active, $teacher_id]);
        } else {
            $stmt = $db->prepare("UPDATE teachers SET name = ?, designation = ?, department = ?, subject = ?, qualification = ?, experience = ?, bio = ?, phone = ?, email = ?, display_order = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $designation, $department, $subject, $qualification, $experience, $bio, $phone, $email, $display_order, $is_featured, $is_active, $teacher_id]);
        }
        $message = "Teacher details updated successfully!";
    }
}

if (isset($_GET['delete_teacher'])) {
    $del_id = (int)$_GET['delete_teacher'];
    $db->prepare("DELETE FROM teachers WHERE id = ?")->execute([$del_id]);
    $message = "Teacher removed!";
}

$teachers = $db->query("SELECT * FROM teachers ORDER BY display_order ASC, id ASC")->fetchAll();

$edit_teacher = null;
if (isset($_GET['edit_teacher'])) {
    $edit_id = (int)$_GET['edit_teacher'];
    $stmt = $db->prepare("SELECT * FROM teachers WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_teacher = $stmt->fetch();
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
            <h3>Faculty & Staff Directory</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Add, edit, reorder, or publish educators and administration staff.</p>
        </div>
    </div>

    <!-- Teacher Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_teacher ? 'Edit Faculty Member' : 'Add New Teacher / Staff' ?></h4>
        <form action="teachers.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_teacher" value="<?= $edit_teacher ? 'update' : 'create' ?>">
            <?php if ($edit_teacher): ?>
            <input type="hidden" name="teacher_id" value="<?= $edit_teacher['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($edit_teacher['name'] ?? '') ?>" placeholder="Teacher Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Designation *</label>
                    <input type="text" name="designation" class="form-control" required value="<?= e($edit_teacher['designation'] ?? '') ?>" placeholder="e.g. Senior Lecturer, Head of Department">
                </div>
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <input type="text" name="department" class="form-control" required value="<?= e($edit_teacher['department'] ?? '') ?>" placeholder="e.g. Science Department, Management Department, Primary">
                </div>
                <div class="form-group">
                    <label class="form-label">Subject / Specialization</label>
                    <input type="text" name="subject" class="form-control" value="<?= e($edit_teacher['subject'] ?? '') ?>" placeholder="e.g. Physics, Chemistry, Accountancy">
                </div>
                <div class="form-group">
                    <label class="form-label">Qualifications</label>
                    <input type="text" name="qualification" class="form-control" value="<?= e($edit_teacher['qualification'] ?? '') ?>" placeholder="e.g. M.Sc. Physics, B.Ed.">
                </div>
                <div class="form-group">
                    <label class="form-label">Experience</label>
                    <input type="text" name="experience" class="form-control" value="<?= e($edit_teacher['experience'] ?? '') ?>" placeholder="e.g. 10+ Years">
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= e($edit_teacher['display_order'] ?? 1) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Photo</label>
                    <input type="file" name="photo" class="form-control" data-preview-target="#teacherPreview" accept="image/*">
                    <?php if ($edit_teacher && !empty($edit_teacher['photo'])): ?>
                    <div class="image-preview-box">
                        <img id="teacherPreview" src="../<?= e($edit_teacher['photo']) ?>" alt="Teacher Photo">
                    </div>
                    <?php else: ?>
                    <div class="image-preview-box">
                        <img id="teacherPreview" src="" style="display:none;" alt="Teacher Photo">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Short Biography</label>
                    <textarea name="bio" rows="2" class="form-control"><?= e($edit_teacher['bio'] ?? '') ?></textarea>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:20px; margin-top:10px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?= ($edit_teacher && $edit_teacher['is_featured']) ? 'checked' : '' ?>>
                        <span>Feature on Homepage</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_teacher || $edit_teacher['is_active']) ? 'checked' : '' ?>>
                        <span>Active / Published</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_teacher ? 'Update Teacher' : 'Add Teacher' ?>
                </button>
                <?php if ($edit_teacher): ?>
                <a href="teachers.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
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
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $t): ?>
                <tr>
                    <td><strong>#<?= $t['display_order'] ?></strong></td>
                    <td>
                        <?php if (!empty($t['photo']) && file_exists(BASE_DIR . '/' . $t['photo'])): ?>
                        <img src="../<?= e($t['photo']) ?>" alt="<?= e($t['name']) ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                        <div style="width:40px; height:40px; border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b;">
                            <i class="bi bi-person"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= e($t['name']) ?></strong></td>
                    <td><?= e($t['designation']) ?></td>
                    <td><span class="badge badge-info"><?= e($t['department']) ?></span></td>
                    <td><?= e($t['subject'] ?: '—') ?></td>
                    <td>
                        <span class="badge <?= $t['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $t['is_active'] ? 'Active' : 'Hidden' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="teachers.php?edit_teacher=<?= $t['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="teachers.php?delete_teacher=<?= $t['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
