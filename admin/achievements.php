<?php
// admin/achievements.php - Achievements & Awards CRUD Manager
$page_title = 'Achievements Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_achievement'])) {
    $action = $_POST['action_achievement'];
    $ach_id = isset($_POST['ach_id']) ? (int)$_POST['ach_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $recipient_name = trim($_POST['recipient_name'] ?? '');
    $category = trim($_POST['category'] ?? 'Academic');
    $description = trim($_POST['description'] ?? '');
    $date = trim($_POST['date'] ?? date('Y-m-d'));
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_path = null;
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = upload_file($_FILES['image_file'], 'achievements', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO achievements (title, recipient_name, category, description, date, image_path, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $recipient_name, $category, $description, $date, $image_path, $is_featured, $is_active]);
        $message = "Achievement recorded successfully!";
    } elseif ($action === 'update' && $ach_id > 0) {
        if ($image_path) {
            $stmt = $db->prepare("UPDATE achievements SET title = ?, recipient_name = ?, category = ?, description = ?, date = ?, image_path = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $recipient_name, $category, $description, $date, $image_path, $is_featured, $is_active, $ach_id]);
        } else {
            $stmt = $db->prepare("UPDATE achievements SET title = ?, recipient_name = ?, category = ?, description = ?, date = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $recipient_name, $category, $description, $date, $is_featured, $is_active, $ach_id]);
        }
        $message = "Achievement updated successfully!";
    }
}

if (isset($_GET['delete_ach'])) {
    $del_id = (int)$_GET['delete_ach'];
    $db->prepare("DELETE FROM achievements WHERE id = ?")->execute([$del_id]);
    $message = "Achievement removed!";
}

$achievements = $db->query("SELECT * FROM achievements ORDER BY date DESC, id DESC")->fetchAll();

$edit_ach = null;
if (isset($_GET['edit_ach'])) {
    $edit_id = (int)$_GET['edit_ach'];
    $stmt = $db->prepare("SELECT * FROM achievements WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_ach = $stmt->fetch();
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
            <h3>Student & School Achievements</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Showcase district board toppers, sports championships, debate prizes, and institutional recognitions.</p>
        </div>
    </div>

    <!-- Achievement Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_ach ? 'Edit Achievement' : 'Add New Achievement' ?></h4>
        <form action="achievements.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_achievement" value="<?= $edit_ach ? 'update' : 'create' ?>">
            <?php if ($edit_ach): ?>
            <input type="hidden" name="ach_id" value="<?= $edit_ach['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Achievement Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= e($edit_ach['title'] ?? '') ?>" placeholder="e.g. Top Board Result in Surkhet District (+2 Science)">
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient / Student / Team Name</label>
                    <input type="text" name="recipient_name" class="form-control" value="<?= e($edit_ach['recipient_name'] ?? '') ?>" placeholder="e.g. Ram Bahadur Thapa">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="<?= e($edit_ach['category'] ?? 'Academic Excellence') ?>" placeholder="e.g. Academic Excellence, Sports, Debate">
                </div>
                <div class="form-group">
                    <label class="form-label">Date Awarded</label>
                    <input type="date" name="date" class="form-control" value="<?= e($edit_ach['date'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Description *</label>
                    <textarea name="description" rows="3" class="form-control" required><?= e($edit_ach['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Photo / Certificate</label>
                    <input type="file" name="image_file" class="form-control" data-preview-target="#achPreview" accept="image/*">
                    <?php if ($edit_ach && !empty($edit_ach['image_path'])): ?>
                    <div class="image-preview-box">
                        <img id="achPreview" src="../<?= e($edit_ach['image_path']) ?>" alt="Achievement Photo">
                    </div>
                    <?php else: ?>
                    <div class="image-preview-box">
                        <img id="achPreview" src="" style="display:none;" alt="Achievement Photo">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:20px; margin-top:28px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?= ($edit_ach && $edit_ach['is_featured']) ? 'checked' : '' ?>>
                        <span>Feature on Homepage</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_ach || $edit_ach['is_active']) ? 'checked' : '' ?>>
                        <span>Active / Published</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_ach ? 'Update Achievement' : 'Save Achievement' ?>
                </button>
                <?php if ($edit_ach): ?>
                <a href="achievements.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
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
                    <th>Image</th>
                    <th>Title</th>
                    <th>Recipient</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($achievements as $a): ?>
                <tr>
                    <td><small><?= $a['date'] ? date('M d, Y', strtotime($a['date'])) : '—' ?></small></td>
                    <td>
                        <?php if (!empty($a['image_path']) && file_exists(BASE_DIR . '/' . $a['image_path'])): ?>
                        <img src="../<?= e($a['image_path']) ?>" alt="<?= e($a['title']) ?>" style="width:50px; height:35px; border-radius:4px; object-fit:cover;">
                        <?php else: ?>
                        <span style="font-size:0.75rem; color:#94a3b8;">No Image</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= e($a['title']) ?></strong></td>
                    <td><?= e($a['recipient_name'] ?: '—') ?></td>
                    <td><span class="badge badge-info"><?= e($a['category']) ?></span></td>
                    <td>
                        <span class="badge <?= $a['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $a['is_active'] ? 'Published' : 'Hidden' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="achievements.php?edit_ach=<?= $a['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="achievements.php?delete_ach=<?= $a['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
