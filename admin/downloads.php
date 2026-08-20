<?php
// admin/downloads.php - Downloads & Document Center CRUD Manager
$page_title = 'Downloads & Forms Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_download'])) {
    $action = $_POST['action_download'];
    $doc_id = isset($_POST['doc_id']) ? (int)$_POST['doc_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? 'Forms');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $file_path = null;
    $file_size = null;
    $file_type = 'PDF';

    if (!empty($_FILES['doc_file']['name'])) {
        $file_size = format_bytes($_FILES['doc_file']['size']);
        $file_type = strtoupper(pathinfo($_FILES['doc_file']['name'], PATHINFO_EXTENSION));
        $file_path = upload_file($_FILES['doc_file'], 'documents', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip']);
    }

    if ($action === 'create' && $file_path) {
        $stmt = $db->prepare("INSERT INTO downloads (title, category, file_path, file_size, file_type, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $file_path, $file_size, $file_type, $display_order, $is_active]);
        $message = "Document uploaded successfully!";
    } elseif ($action === 'update' && $doc_id > 0) {
        if ($file_path) {
            $stmt = $db->prepare("UPDATE downloads SET title = ?, category = ?, file_path = ?, file_size = ?, file_type = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $category, $file_path, $file_size, $file_type, $display_order, $is_active, $doc_id]);
        } else {
            $stmt = $db->prepare("UPDATE downloads SET title = ?, category = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $category, $display_order, $is_active, $doc_id]);
        }
        $message = "Document updated!";
    }
}

if (isset($_GET['delete_doc'])) {
    $del_id = (int)$_GET['delete_doc'];
    $db->prepare("DELETE FROM downloads WHERE id = ?")->execute([$del_id]);
    $message = "Document removed!";
}

$downloads = $db->query("SELECT * FROM downloads ORDER BY display_order ASC, id DESC")->fetchAll();

$edit_doc = null;
if (isset($_GET['edit_doc'])) {
    $edit_id = (int)$_GET['edit_doc'];
    $stmt = $db->prepare("SELECT * FROM downloads WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_doc = $stmt->fetch();
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
            <h3>Download Center & Public Documents</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Upload downloadable files (prospectus, registration forms, syllabus, academic calendar).</p>
        </div>
    </div>

    <!-- Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_doc ? 'Edit Document' : 'Upload New Document' ?></h4>
        <form action="downloads.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_download" value="<?= $edit_doc ? 'update' : 'create' ?>">
            <?php if ($edit_doc): ?>
            <input type="hidden" name="doc_id" value="<?= $edit_doc['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Document Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= e($edit_doc['title'] ?? '') ?>" placeholder="e.g. +2 Science Prospectus 2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="<?= e($edit_doc['category'] ?? 'Forms') ?>" placeholder="e.g. Prospectus, Admission, Academic">
                </div>
                <div class="form-group">
                    <label class="form-label">File (PDF, DOCX, ZIP) <?= $edit_doc ? '(Leave empty to keep existing)' : '*' ?></label>
                    <input type="file" name="doc_file" class="form-control" <?= $edit_doc ? '' : 'required' ?> accept=".pdf,.doc,.docx,.xls,.xlsx,.zip">
                    <?php if ($edit_doc && !empty($edit_doc['file_path'])): ?>
                    <small style="display:block; margin-top:4px;"><a href="../<?= e($edit_doc['file_path']) ?>" target="_blank"><i class="bi bi-file-earmark-arrow-down"></i> Current File (<?= e($edit_doc['file_size']) ?>)</a></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= e($edit_doc['display_order'] ?? 1) ?>">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top:28px;">
                    <label class="switch">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_doc || $edit_doc['is_active']) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                    <span style="font-weight:600; font-size:0.88rem;">Active / Available</span>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-cloud-arrow-up-fill"></i> <?= $edit_doc ? 'Update Document' : 'Upload Document' ?>
                </button>
                <?php if ($edit_doc): ?>
                <a href="downloads.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
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
                    <th>Document Title</th>
                    <th>Category</th>
                    <th>File Format & Size</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($downloads as $d): ?>
                <tr>
                    <td><strong>#<?= $d['display_order'] ?></strong></td>
                    <td><strong><?= e($d['title']) ?></strong></td>
                    <td><span class="badge badge-info"><?= e($d['category']) ?></span></td>
                    <td>
                        <a href="../<?= e($d['file_path']) ?>" target="_blank" class="badge badge-success">
                            <i class="bi bi-download"></i> <?= e($d['file_type']) ?> (<?= e($d['file_size'] ?: 'File') ?>)
                        </a>
                    </td>
                    <td>
                        <span class="badge <?= $d['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $d['is_active'] ? 'Active' : 'Hidden' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="downloads.php?edit_doc=<?= $d['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="downloads.php?delete_doc=<?= $d['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
