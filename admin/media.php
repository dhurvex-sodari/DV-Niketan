<?php
// admin/media.php - Media Library & File Manager
$page_title = 'Media Library';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';
$error = '';

// Handle File Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_media'])) {
    if (!empty($_FILES['media_files']['name'][0])) {
        $count = count($_FILES['media_files']['name']);
        $uploaded_count = 0;
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['media_files']['error'][$i] === UPLOAD_ERR_OK) {
                $file_array = [
                    'name' => $_FILES['media_files']['name'][$i],
                    'type' => $_FILES['media_files']['type'][$i],
                    'tmp_name' => $_FILES['media_files']['tmp_name'][$i],
                    'error' => $_FILES['media_files']['error'][$i],
                    'size' => $_FILES['media_files']['size'][$i]
                ];
                $path = upload_file($file_array, 'media', ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx']);
                if ($path) {
                    $uploaded_count++;
                }
            }
        }
        $message = "Uploaded $uploaded_count file(s) into media library!";
    }
}

// Delete Media File
if (isset($_GET['delete_media'])) {
    $del_id = (int)$_GET['delete_media'];
    $stmt = $db->prepare("SELECT file_path FROM media_library WHERE id = ?");
    $stmt->execute([$del_id]);
    $file = $stmt->fetch();
    if ($file) {
        $full_path = BASE_DIR . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file['file_path']);
        if (file_exists($full_path)) {
            @unlink($full_path);
        }
        $db->prepare("DELETE FROM media_library WHERE id = ?")->execute([$del_id]);
        $message = "Media file deleted!";
    }
}

// Search Filter
$search = trim($_GET['q'] ?? '');
if (!empty($search)) {
    $stmt = $db->prepare("SELECT * FROM media_library WHERE file_name LIKE ? ORDER BY id DESC");
    $stmt->execute(['%' . $search . '%']);
    $media_items = $stmt->fetchAll();
} else {
    $media_items = $db->query("SELECT * FROM media_library ORDER BY id DESC")->fetchAll();
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
            <h3>Media & Asset Library</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Central repository for uploaded school photos, brochures, and document assets.</p>
        </div>
    </div>

    <!-- Upload Box & Search -->
    <div style="display:grid; grid-template-columns: 1.2fr 1fr; gap:20px; margin-bottom:24px;">
        <div style="background:var(--admin-body-bg); padding:18px; border-radius:8px; border:1px solid var(--admin-border);">
            <h4 style="font-size:1rem; margin-bottom:10px;"><i class="bi bi-cloud-upload-fill"></i> Upload New Assets</h4>
            <form action="media.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="media_files[]" multiple required class="form-control" accept="image/*,.pdf,.doc,.docx" style="margin-bottom:10px;">
                <button type="submit" name="upload_media" class="admin-btn admin-btn-primary" style="padding:8px 18px; font-size:0.85rem;">
                    <i class="bi bi-upload"></i> Upload to Library
                </button>
            </form>
        </div>

        <div style="background:var(--admin-body-bg); padding:18px; border-radius:8px; border:1px solid var(--admin-border);">
            <h4 style="font-size:1rem; margin-bottom:10px;"><i class="bi bi-search"></i> Search Media Files</h4>
            <form action="media.php" method="GET">
                <input type="text" name="q" class="form-control" value="<?= e($search) ?>" placeholder="Search by filename..." style="margin-bottom:10px;">
                <div style="display:flex; gap:8px;">
                    <button type="submit" class="admin-btn admin-btn-secondary" style="padding:8px 18px; font-size:0.85rem;"><i class="bi bi-search"></i> Search</button>
                    <?php if (!empty($search)): ?>
                    <a href="media.php" class="admin-btn admin-btn-secondary" style="padding:8px 14px; font-size:0.85rem;">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Media Grid -->
    <?php if (empty($media_items)): ?>
    <p style="color:var(--admin-text-muted); text-align:center; padding:30px;">No media files found.</p>
    <?php else: ?>
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap:18px;">
        <?php foreach ($media_items as $item): 
            $is_img = in_array(strtolower($item['file_type']), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        ?>
        <div style="border:1px solid var(--admin-border); border-radius:8px; overflow:hidden; background:#fff; display:flex; flex-direction:column; box-shadow:var(--admin-shadow);">
            <div style="height:120px; background:#f8fafc; display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative;">
                <?php if ($is_img && file_exists(BASE_DIR . '/' . $item['file_path'])): ?>
                <img src="../<?= e($item['file_path']) ?>" alt="<?= e($item['file_name']) ?>" style="width:100%; height:100%; object-fit:cover;">
                <?php else: ?>
                <div style="font-size:2.5rem; color:#64748b;"><i class="bi bi-file-earmark-pdf"></i></div>
                <?php endif; ?>
            </div>
            <div style="padding:10px; flex-grow:1; display:flex; flex-direction:column;">
                <div style="font-size:0.82rem; font-weight:600; margin-bottom:4px; word-break:break-all;"><?= e(mb_strimwidth($item['file_name'], 0, 22, '...')) ?></div>
                <div style="font-size:0.72rem; color:var(--admin-text-muted); margin-bottom:8px;"><?= e($item['file_size']) ?> • <?= date('M d', strtotime($item['created_at'])) ?></div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:auto; padding-top:8px; border-top:1px solid var(--admin-border);">
                    <a href="../<?= e($item['file_path']) ?>" target="_blank" class="badge badge-info" title="Open File"><i class="bi bi-box-arrow-up-right"></i> Open</a>
                    <a href="media.php?delete_media=<?= $item['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
