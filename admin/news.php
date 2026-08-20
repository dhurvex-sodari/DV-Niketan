<?php
// admin/news.php - News & Campus Articles CRUD Manager
$page_title = 'News & Articles Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_news'])) {
    $action = $_POST['action_news'];
    $news_id = isset($_POST['news_id']) ? (int)$_POST['news_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) . '-' . time();
    }
    $category = trim($_POST['category'] ?? 'School News');
    $description = trim($_POST['description'] ?? '');
    $publish_date = trim($_POST['publish_date'] ?? date('Y-m-d'));
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_path = null;
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = upload_file($_FILES['image_file'], 'news', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO news (title, slug, category, description, image_path, publish_date, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $description, $image_path, $publish_date, $is_featured, $is_active]);
        $message = "News article published successfully!";
    } elseif ($action === 'update' && $news_id > 0) {
        if ($image_path) {
            $stmt = $db->prepare("UPDATE news SET title = ?, slug = ?, category = ?, description = ?, image_path = ?, publish_date = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $category, $description, $image_path, $publish_date, $is_featured, $is_active, $news_id]);
        } else {
            $stmt = $db->prepare("UPDATE news SET title = ?, slug = ?, category = ?, description = ?, publish_date = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $category, $description, $publish_date, $is_featured, $is_active, $news_id]);
        }
        $message = "News article updated successfully!";
    }
}

if (isset($_GET['delete_news'])) {
    $del_id = (int)$_GET['delete_news'];
    $db->prepare("DELETE FROM news WHERE id = ?")->execute([$del_id]);
    $message = "News article deleted!";
}

$news_list = $db->query("SELECT * FROM news ORDER BY publish_date DESC, id DESC")->fetchAll();

$edit_news = null;
if (isset($_GET['edit_news'])) {
    $edit_id = (int)$_GET['edit_news'];
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_news = $stmt->fetch();
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
            <h3>Campus News & Articles</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage blog stories, exhibitions, academic seminars, and press releases.</p>
        </div>
    </div>

    <!-- News Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_news ? 'Edit Article' : 'Write News Article' ?></h4>
        <form action="news.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_news" value="<?= $edit_news ? 'update' : 'create' ?>">
            <?php if ($edit_news): ?>
            <input type="hidden" name="news_id" value="<?= $edit_news['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Article Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= e($edit_news['title'] ?? '') ?>" placeholder="e.g. Annual Science Exhibition Celebrated">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="<?= e($edit_news['category'] ?? 'Campus Event') ?>" placeholder="e.g. Academic, Sports, Achievements">
                </div>
                <div class="form-group">
                    <label class="form-label">Publish Date</label>
                    <input type="date" name="publish_date" class="form-control" value="<?= e($edit_news['publish_date'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Article Content / Description *</label>
                    <textarea name="description" rows="5" class="form-control" required><?= e($edit_news['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Article Image</label>
                    <input type="file" name="image_file" class="form-control" data-preview-target="#newsPreview" accept="image/*">
                    <?php if ($edit_news && !empty($edit_news['image_path'])): ?>
                    <div class="image-preview-box">
                        <img id="newsPreview" src="../<?= e($edit_news['image_path']) ?>" alt="News Image">
                    </div>
                    <?php else: ?>
                    <div class="image-preview-box">
                        <img id="newsPreview" src="" style="display:none;" alt="News Image">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:20px; margin-top:28px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?= ($edit_news && $edit_news['is_featured']) ? 'checked' : '' ?>>
                        <span>Feature Article</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_news || $edit_news['is_active']) ? 'checked' : '' ?>>
                        <span>Published</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_news ? 'Update Article' : 'Publish Article' ?>
                </button>
                <?php if ($edit_news): ?>
                <a href="news.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
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
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news_list as $n): ?>
                <tr>
                    <td><small><?= date('M d, Y', strtotime($n['publish_date'])) ?></small></td>
                    <td>
                        <?php if (!empty($n['image_path']) && file_exists(BASE_DIR . '/' . $n['image_path'])): ?>
                        <img src="../<?= e($n['image_path']) ?>" alt="<?= e($n['title']) ?>" style="width:60px; height:40px; border-radius:4px; object-fit:cover;">
                        <?php else: ?>
                        <span style="font-size:0.75rem; color:#94a3b8;">No Image</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= e($n['title']) ?></strong></td>
                    <td><span class="badge badge-info"><?= e($n['category']) ?></span></td>
                    <td>
                        <span class="badge <?= $n['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $n['is_active'] ? 'Published' : 'Draft' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="news.php?edit_news=<?= $n['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="news.php?delete_news=<?= $n['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
