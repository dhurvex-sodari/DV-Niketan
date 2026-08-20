<?php
// admin/principal_manager.php - Principal Profile & Message Manager
$page_title = 'Principal Profile & Message';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_principal'])) {
    $name = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? 'Principal');
    $qualification = trim($_POST['qualification'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $principal_message = trim($_POST['message'] ?? '');

    $photo_path = null;
    if (!empty($_FILES['photo']['name'])) {
        $photo_path = upload_file($_FILES['photo'], 'media', ['jpg', 'jpeg', 'png', 'webp']);
    }

    $signature_path = null;
    if (!empty($_FILES['signature']['name'])) {
        $signature_path = upload_file($_FILES['signature'], 'media', ['png', 'webp', 'jpg']);
    }

    $existing = get_principal_info();
    if ($existing) {
        $final_photo = $photo_path ?: $existing['photo'];
        $final_sig = $signature_path ?: $existing['signature_image'];
        $stmt = $db->prepare("UPDATE principal_info SET name = ?, designation = ?, photo = ?, qualification = ?, experience = ?, message = ?, signature_image = ? WHERE id = ?");
        $stmt->execute([$name, $designation, $final_photo, $qualification, $experience, $principal_message, $final_sig, $existing['id']]);
    } else {
        $stmt = $db->prepare("INSERT INTO principal_info (name, designation, photo, qualification, experience, message, signature_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $designation, $photo_path, $qualification, $experience, $principal_message, $signature_path]);
    }

    $message = "Principal's message and profile updated successfully!";
}

$principal = get_principal_info();
?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Principal Profile & Official Message</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage principal bio, photo, qualification credentials, and welcome message displayed across the website.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
    <div class="alert alert-success alert-auto-dismiss">
        <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
    </div>
    <?php endif; ?>

    <form action="principal_manager.php" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Principal Full Name *</label>
                <input type="text" name="name" class="form-control" required value="<?= e($principal['name'] ?? '') ?>" placeholder="e.g. Dr. John Doe">
            </div>
            <div class="form-group">
                <label class="form-label">Designation / Title</label>
                <input type="text" name="designation" class="form-control" value="<?= e($principal['designation'] ?? 'Principal') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Qualifications</label>
                <input type="text" name="qualification" class="form-control" value="<?= e($principal['qualification'] ?? '') ?>" placeholder="e.g. M.Sc., M.Ed., Ph.D.">
            </div>
            <div class="form-group">
                <label class="form-label">Leadership Experience</label>
                <input type="text" name="experience" class="form-control" value="<?= e($principal['experience'] ?? '') ?>" placeholder="e.g. 20+ Years in Educational Leadership">
            </div>
            <div class="form-group full-width">
                <label class="form-label">Principal's Welcome Message *</label>
                <textarea name="message" rows="6" class="form-control" required><?= e($principal['message'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Principal Photo</label>
                <input type="file" name="photo" class="form-control" data-preview-target="#principalPhotoPreview" accept="image/*">
                <?php if (!empty($principal['photo']) && file_exists(BASE_DIR . '/' . $principal['photo'])): ?>
                <div class="image-preview-box">
                    <img id="principalPhotoPreview" src="../<?= e($principal['photo']) ?>" alt="Principal Photo">
                </div>
                <?php else: ?>
                <div class="image-preview-box">
                    <img id="principalPhotoPreview" src="" style="display:none;" alt="Principal Photo">
                </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="form-label">Signature Image (Transparent PNG recommended)</label>
                <input type="file" name="signature" class="form-control" data-preview-target="#principalSigPreview" accept="image/*">
                <?php if (!empty($principal['signature_image']) && file_exists(BASE_DIR . '/' . $principal['signature_image'])): ?>
                <div class="image-preview-box">
                    <img id="principalSigPreview" src="../<?= e($principal['signature_image']) ?>" alt="Signature" style="max-height:60px;">
                </div>
                <?php else: ?>
                <div class="image-preview-box">
                    <img id="principalSigPreview" src="" style="display:none;" alt="Signature" style="max-height:60px;">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="margin-top:25px; border-top:1px solid var(--admin-border); padding-top:20px;">
            <button type="submit" name="save_principal" class="admin-btn admin-btn-primary">
                <i class="bi bi-save-fill"></i> Update Principal Profile
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
