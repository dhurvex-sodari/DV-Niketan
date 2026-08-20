<?php
// admin/gallery.php - Photo Gallery & Album Manager
$page_title = 'Gallery & Albums Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

// 1. Handle Album Create / Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_album'])) {
    $action = $_POST['action_album'];
    $album_id = isset($_POST['album_id']) ? (int)$_POST['album_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) . '-' . time();
    }
    $description = trim($_POST['description'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $cover_path = null;
    if (!empty($_FILES['cover_file']['name'])) {
        $cover_path = upload_file($_FILES['cover_file'], 'gallery', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO gallery_albums (title, slug, description, cover_image, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $description, $cover_path, $display_order, $is_active]);
        $message = "Gallery album created successfully!";
    } elseif ($action === 'update' && $album_id > 0) {
        if ($cover_path) {
            $stmt = $db->prepare("UPDATE gallery_albums SET title = ?, slug = ?, description = ?, cover_image = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $description, $cover_path, $display_order, $is_active, $album_id]);
        } else {
            $stmt = $db->prepare("UPDATE gallery_albums SET title = ?, slug = ?, description = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $description, $display_order, $is_active, $album_id]);
        }
        $message = "Album updated successfully!";
    }
}

// 2. Handle Photo Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_photos'])) {
    $album_id = (int)$_POST['album_id'];
    $caption = trim($_POST['caption'] ?? '');

    if (!empty($_FILES['photo_files']['name'][0]) && $album_id > 0) {
        $count = count($_FILES['photo_files']['name']);
        $uploaded_count = 0;
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['photo_files']['error'][$i] === UPLOAD_ERR_OK) {
                $file_array = [
                    'name' => $_FILES['photo_files']['name'][$i],
                    'type' => $_FILES['photo_files']['type'][$i],
                    'tmp_name' => $_FILES['photo_files']['tmp_name'][$i],
                    'error' => $_FILES['photo_files']['error'][$i],
                    'size' => $_FILES['photo_files']['size'][$i]
                ];
                $path = upload_file($file_array, 'gallery', ['jpg', 'jpeg', 'png', 'webp']);
                if ($path) {
                    $stmt = $db->prepare("INSERT INTO gallery_photos (album_id, image_path, caption) VALUES (?, ?, ?)");
                    $stmt->execute([$album_id, $path, $caption]);
                    $uploaded_count++;
                }
            }
        }
        $message = "Successfully uploaded $uploaded_count photo(s) into album!";
    }
}

// Delete Album
if (isset($_GET['delete_album'])) {
    $del_id = (int)$_GET['delete_album'];
    $db->prepare("DELETE FROM gallery_albums WHERE id = ?")->execute([$del_id]);
    $message = "Album and its photos deleted!";
}

// Delete Single Photo
if (isset($_GET['delete_photo'])) {
    $del_photo_id = (int)$_GET['delete_photo'];
    $db->prepare("DELETE FROM gallery_photos WHERE id = ?")->execute([$del_photo_id]);
    $message = "Photo removed from gallery!";
}

$albums = get_gallery_albums(false);
$photos = $db->query("SELECT p.*, a.title as album_title FROM gallery_photos p JOIN gallery_albums a ON p.album_id = a.id ORDER BY p.id DESC LIMIT 40")->fetchAll();
?>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-auto-dismiss">
    <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
</div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
    <!-- Album Creator -->
    <div class="admin-card">
        <div class="card-header-flex">
            <h3>Create New Album</h3>
        </div>
        <form action="gallery.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_album" value="create">
            <div class="form-group">
                <label class="form-label">Album Title *</label>
                <input type="text" name="title" class="form-control" required placeholder="e.g. Science Exhibition 2026">
            </div>
            <div class="form-group">
                <label class="form-label">Album Description</label>
                <textarea name="description" rows="2" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Cover Photo (Optional)</label>
                <input type="file" name="cover_file" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">Display Order</label>
                <input type="number" name="display_order" class="form-control" value="1">
            </div>
            <button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-folder-plus"></i> Create Album</button>
        </form>
    </div>

    <!-- Multi Photo Uploader -->
    <div class="admin-card">
        <div class="card-header-flex">
            <h3>Upload Photos into Album</h3>
        </div>
        <form action="gallery.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Select Destination Album *</label>
                <select name="album_id" class="form-control" required>
                    <option value="">-- Choose Album --</option>
                    <?php foreach ($albums as $a): ?>
                    <option value="<?= $a['id'] ?>"><?= e($a['title']) ?> (<?= $a['photo_count'] ?> photos)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Select Photos (Multiple Selection Allowed) *</label>
                <input type="file" name="photo_files[]" class="form-control" multiple required accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">Photo Caption (Optional)</label>
                <input type="text" name="caption" class="form-control" placeholder="e.g. Laboratory demonstration">
            </div>
            <button type="submit" name="upload_photos" class="admin-btn admin-btn-primary"><i class="bi bi-cloud-arrow-up-fill"></i> Upload Photos</button>
        </form>
    </div>
</div>

<!-- Albums Table -->
<div class="admin-card">
    <div class="card-header-flex">
        <h3>Existing Photo Albums</h3>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Cover</th>
                    <th>Photo Count</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($albums as $alb): ?>
                <tr>
                    <td><strong><?= e($alb['title']) ?></strong></td>
                    <td>
                        <?php if (!empty($alb['cover_image']) && file_exists(BASE_DIR . '/' . $alb['cover_image'])): ?>
                        <img src="../<?= e($alb['cover_image']) ?>" alt="Cover" style="width:50px; height:35px; border-radius:4px; object-fit:cover;">
                        <?php else: ?>
                        <span style="font-size:0.75rem; color:#94a3b8;">Default</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge badge-info"><?= (int)$alb['photo_count'] ?> Photos</span></td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <a href="gallery.php?delete_album=<?= $alb['id'] ?>" class="btn-icon delete confirm-delete" title="Delete Album"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Photos Grid -->
<div class="admin-card">
    <div class="card-header-flex">
        <h3>Recent Uploaded Photos</h3>
    </div>
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap:14px;">
        <?php foreach ($photos as $p): ?>
        <div style="position:relative; border-radius:8px; overflow:hidden; border:1px solid var(--admin-border); height:110px;">
            <img src="../<?= e($p['image_path']) ?>" alt="Photo" style="width:100%; height:100%; object-fit:cover;">
            <a href="gallery.php?delete_photo=<?= $p['id'] ?>" class="confirm-delete" style="position:absolute; top:4px; right:4px; background:rgba(220,38,38,0.85); color:#fff; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; text-decoration:none;">
                <i class="bi bi-x"></i>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
