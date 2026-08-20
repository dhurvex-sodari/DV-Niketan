<?php
// admin/seo_manager.php - Search Engine Optimization & Social Meta Manager
$page_title = 'SEO & Metadata Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_seo'])) {
    $seo_id = (int)$_POST['seo_id'];
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_keywords = trim($_POST['meta_keywords'] ?? '');
    $og_title = trim($_POST['og_title'] ?? '');
    $og_description = trim($_POST['og_description'] ?? '');
    $canonical_url = trim($_POST['canonical_url'] ?? '');

    $stmt = $db->prepare("UPDATE seo_pages SET meta_title = ?, meta_description = ?, meta_keywords = ?, og_title = ?, og_description = ?, canonical_url = ? WHERE id = ?");
    $stmt->execute([$meta_title, $meta_description, $meta_keywords, $og_title, $og_description, $canonical_url, $seo_id]);
    $message = "SEO metadata saved successfully!";
}

$pages = $db->query("SELECT * FROM seo_pages ORDER BY id ASC")->fetchAll();

$edit_page = null;
if (isset($_GET['edit_seo'])) {
    $edit_id = (int)$_GET['edit_seo'];
    $stmt = $db->prepare("SELECT * FROM seo_pages WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_page = $stmt->fetch();
}
?>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-auto-dismiss">
    <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
</div>
<?php endif; ?>

<?php if ($edit_page): ?>
<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Editing SEO for: <?= e($edit_page['page_name']) ?></h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Route Slug: <code><?= e($edit_page['page_slug']) ?></code></p>
        </div>
        <a href="seo_manager.php" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i> Back to Page List</a>
    </div>

    <form action="seo_manager.php" method="POST">
        <input type="hidden" name="save_seo" value="1">
        <input type="hidden" name="seo_id" value="<?= $edit_page['id'] ?>">

        <div class="form-grid">
            <div class="form-group full-width">
                <label class="form-label">Meta Browser Title (50-60 chars recommended) *</label>
                <input type="text" name="meta_title" class="form-control" required value="<?= e($edit_page['meta_title'] ?? '') ?>">
            </div>
            <div class="form-group full-width">
                <label class="form-label">Meta Description (150-160 chars recommended)</label>
                <textarea name="meta_description" rows="3" class="form-control"><?= e($edit_page['meta_description'] ?? '') ?></textarea>
            </div>
            <div class="form-group full-width">
                <label class="form-label">Meta Keywords (Comma separated)</label>
                <input type="text" name="meta_keywords" class="form-control" value="<?= e($edit_page['meta_keywords'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Open Graph (Facebook/Social) Title</label>
                <input type="text" name="og_title" class="form-control" value="<?= e($edit_page['og_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Canonical URL</label>
                <input type="text" name="canonical_url" class="form-control" value="<?= e($edit_page['canonical_url'] ?? '') ?>" placeholder="https://dvniketan.edu.np/...">
            </div>
            <div class="form-group full-width">
                <label class="form-label">Open Graph Description</label>
                <textarea name="og_description" rows="2" class="form-control"><?= e($edit_page['og_description'] ?? '') ?></textarea>
            </div>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="bi bi-save-fill"></i> Update SEO Settings
            </button>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Search Engine Optimization (SEO) per Page</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Customize Meta Title, Descriptions, Keywords, and Social OpenGraph tags for every route.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page Name</th>
                    <th>Slug</th>
                    <th>Meta Title</th>
                    <th>Meta Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                <tr>
                    <td><strong><?= e($p['page_name']) ?></strong></td>
                    <td><code><?= e($p['page_slug']) ?></code></td>
                    <td><small><?= e(mb_strimwidth($p['meta_title'] ?? '', 0, 35, '...')) ?></small></td>
                    <td><small><?= e(mb_strimwidth($p['meta_description'] ?? '', 0, 45, '...')) ?></small></td>
                    <td>
                        <a href="seo_manager.php?edit_seo=<?= $p['id'] ?>" class="btn-icon edit" title="Edit SEO"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
