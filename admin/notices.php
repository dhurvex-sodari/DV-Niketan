<?php
// admin/notices.php - Notices & Announcements CRUD Manager
$page_title = 'Notices & Circulars Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_notice'])) {
    $action = $_POST['action_notice'];
    $notice_id = isset($_POST['notice_id']) ? (int)$_POST['notice_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? 'General');
    $description = trim($_POST['description'] ?? '');
    $publish_date = trim($_POST['publish_date'] ?? date('Y-m-d'));
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $file_path = null;
    if (!empty($_FILES['pdf_file']['name'])) {
        $file_path = upload_file($_FILES['pdf_file'], 'documents', ['pdf', 'doc', 'docx']);
    }

    $image_path = null;
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = upload_file($_FILES['image_file'], 'media', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO notices (title, category, description, file_path, featured_image, publish_date, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $description, $file_path, $image_path, $publish_date, $is_featured, $is_active]);
        $message = "Notice published successfully!";
    } elseif ($action === 'update' && $notice_id > 0) {
        $existing_stmt = $db->prepare("SELECT file_path, featured_image FROM notices WHERE id = ?");
        $existing_stmt->execute([$notice_id]);
        $existing = $existing_stmt->fetch();

        $final_file = $file_path ?: ($existing['file_path'] ?? null);
        $final_image = $image_path ?: ($existing['featured_image'] ?? null);

        $stmt = $db->prepare("UPDATE notices SET title = ?, category = ?, description = ?, file_path = ?, featured_image = ?, publish_date = ?, is_featured = ?, is_active = ? WHERE id = ?");
        $stmt->execute([$title, $category, $description, $final_file, $final_image, $publish_date, $is_featured, $is_active, $notice_id]);
        $message = "Notice updated successfully!";
    }
}

if (isset($_GET['delete_notice'])) {
    $del_id = (int)$_GET['delete_notice'];
    $db->prepare("DELETE FROM notices WHERE id = ?")->execute([$del_id]);
    $message = "Notice deleted!";
}

$notices = $db->query("SELECT * FROM notices ORDER BY publish_date DESC, id DESC")->fetchAll();

$edit_notice = null;
if (isset($_GET['edit_notice'])) {
    $edit_id = (int)$_GET['edit_notice'];
    $stmt = $db->prepare("SELECT * FROM notices WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_notice = $stmt->fetch();
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
            <h3>School Notices & Circulars</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Publish announcements, examination dates, schedules, and attach PDF documents.</p>
        </div>
    </div>

    <!-- Notice Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_notice ? 'Edit Notice' : 'Post New Notice' ?></h4>
        <form action="notices.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_notice" value="<?= $edit_notice ? 'update' : 'create' ?>">
            <?php if ($edit_notice): ?>
            <input type="hidden" name="notice_id" value="<?= $edit_notice['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Notice Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= e($edit_notice['title'] ?? '') ?>" placeholder="e.g. Terminal Exam Routine & Guidelines">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="<?= e($edit_notice['category'] ?? 'General') ?>" placeholder="e.g. Admissions, Examinations, Holiday">
                </div>
                <div class="form-group">
                    <label class="form-label">Publish Date</label>
                    <input type="date" name="publish_date" class="form-control" value="<?= e($edit_notice['publish_date'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Notice Description / Content</label>
                    <textarea name="description" rows="3" class="form-control"><?= e($edit_notice['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Attach Document (PDF / DOC)</label>
                    <input type="file" name="pdf_file" class="form-control" accept=".pdf,.doc,.docx">
                    <?php if ($edit_notice && !empty($edit_notice['file_path'])): ?>
                    <div style="font-size:0.8rem; margin-top:6px;"><a href="../<?= e($edit_notice['file_path']) ?>" target="_blank"><i class="bi bi-file-earmark-pdf"></i> View Current Attached File</a></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Featured Image (Optional)</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:20px; margin-top:28px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?= ($edit_notice && $edit_notice['is_featured']) ? 'checked' : '' ?>>
                        <span>Mark as Featured Notice</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_notice || $edit_notice['is_active']) ? 'checked' : '' ?>>
                        <span>Published</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_notice ? 'Update Notice' : 'Publish Notice' ?>
                </button>
                <?php if ($edit_notice): ?>
                <a href="notices.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notices as $n): ?>
                <tr>
                    <td><small><?= date('M d, Y', strtotime($n['publish_date'])) ?></small></td>
                    <td>
                        <strong><?= e($n['title']) ?></strong>
                        <?php if ($n['is_featured']): ?>
                        <span class="badge badge-warning" style="margin-left:6px;">Featured</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge badge-info"><?= e($n['category']) ?></span></td>
                    <td>
                        <?php if (!empty($n['file_path'])): ?>
                        <a href="../<?= e($n['file_path']) ?>" target="_blank" class="badge badge-success"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                        <?php else: ?>
                        <span style="color:#94a3b8; font-size:0.75rem;">None</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?= $n['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $n['is_active'] ? 'Published' : 'Draft' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="notices.php?edit_notice=<?= $n['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="notices.php?delete_notice=<?= $n['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
