<?php
// admin/school_info.php - School Information Manager
$page_title = 'School Profile & Information';
require_once __DIR__ . '/includes/admin_header.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_school_info'])) {
    $fields = [
        'school_name' => 'school_info',
        'school_type' => 'school_info',
        'tagline' => 'school_info',
        'full_address' => 'school_info',
        'municipality' => 'school_info',
        'ward' => 'school_info',
        'district' => 'school_info',
        'province' => 'school_info',
        'country' => 'school_info',
        'phone_numbers' => 'school_info',
        'email_addresses' => 'school_info',
        'office_hours' => 'school_info',
        'google_maps_url' => 'school_info',
        'facebook_url' => 'social',
        'instagram_url' => 'social',
        'youtube_url' => 'social',
        'twitter_url' => 'social',
        'linkedin_url' => 'social',
    ];

    foreach ($fields as $key => $group) {
        $val = trim($_POST[$key] ?? '');
        update_setting($key, $val, $group);
    }

    // Handle Logo Upload
    if (!empty($_FILES['logo_file']['name'])) {
        $uploaded_logo = upload_file($_FILES['logo_file'], 'media', ['jpg', 'jpeg', 'png', 'webp', 'svg']);
        if ($uploaded_logo) {
            update_setting('logo_url', $uploaded_logo, 'appearance');
        }
    }

    // Handle Favicon Upload
    if (!empty($_FILES['favicon_file']['name'])) {
        $uploaded_favicon = upload_file($_FILES['favicon_file'], 'media', ['ico', 'png', 'webp', 'svg']);
        if ($uploaded_favicon) {
            update_setting('favicon_url', $uploaded_favicon, 'appearance');
        }
    }

    $message = "School Information updated successfully! Changes are live on the public website.";
}

// Fetch all settings fresh
$s = get_all_settings();
?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>School Profile & Contact Details</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage all institutional metadata, addresses, contacts, and social media channels.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
    <div class="alert alert-success alert-auto-dismiss">
        <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
    </div>
    <?php endif; ?>

    <form action="school_info.php" method="POST" enctype="multipart/form-data">
        <h4 style="margin:20px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-mortarboard-fill"></i> Basic School Identity
        </h4>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">School Name *</label>
                <input type="text" name="school_name" class="form-control" required value="<?= e($s['school_name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">School Type / Category</label>
                <input type="text" name="school_type" class="form-control" value="<?= e($s['school_type'] ?? '') ?>" placeholder="e.g. Secondary & Higher Secondary Boarding School">
            </div>
            <div class="form-group full-width">
                <label class="form-label">Motto / Tagline</label>
                <input type="text" name="tagline" class="form-control" value="<?= e($s['tagline'] ?? '') ?>" placeholder="e.g. Empowering Minds, Shaping Future Leaders">
            </div>
        </div>

        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-geo-alt-fill"></i> Institutional Address & Location
        </h4>
        <div class="form-grid">
            <div class="form-group full-width">
                <label class="form-label">Full Display Address *</label>
                <input type="text" name="full_address" class="form-control" required value="<?= e($s['full_address'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Municipality / Local Body</label>
                <input type="text" name="municipality" class="form-control" value="<?= e($s['municipality'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Ward No.</label>
                <input type="text" name="ward" class="form-control" value="<?= e($s['ward'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">District</label>
                <input type="text" name="district" class="form-control" value="<?= e($s['district'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Province</label>
                <input type="text" name="province" class="form-control" value="<?= e($s['province'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" value="<?= e($s['country'] ?? '') ?>">
            </div>
            <div class="form-group full-width">
                <label class="form-label">Google Maps Embed URL</label>
                <input type="text" name="google_maps_url" class="form-control" value="<?= e($s['google_maps_url'] ?? '') ?>" placeholder="https://maps.google.com/maps?q=...">
            </div>
        </div>

        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-telephone-fill"></i> Contact & Office Information
        </h4>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Phone Numbers</label>
                <input type="text" name="phone_numbers" class="form-control" value="<?= e($s['phone_numbers'] ?? '') ?>" placeholder="e.g. +977-83-XXXXXX, 98XXXXXXXX">
            </div>
            <div class="form-group">
                <label class="form-label">Email Addresses</label>
                <input type="text" name="email_addresses" class="form-control" value="<?= e($s['email_addresses'] ?? '') ?>" placeholder="e.g. info@dvniketan.edu.np">
            </div>
            <div class="form-group full-width">
                <label class="form-label">Office & Administration Hours</label>
                <input type="text" name="office_hours" class="form-control" value="<?= e($s['office_hours'] ?? '') ?>" placeholder="e.g. Sun - Fri: 9:00 AM - 4:30 PM (Saturday Closed)">
            </div>
        </div>

        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-share-fill"></i> Social Media Profiles
        </h4>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label"><i class="bi bi-facebook text-primary"></i> Facebook Page URL</label>
                <input type="url" name="facebook_url" class="form-control" value="<?= e($s['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/...">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-instagram text-danger"></i> Instagram Profile URL</label>
                <input type="url" name="instagram_url" class="form-control" value="<?= e($s['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/...">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-youtube text-danger"></i> YouTube Channel URL</label>
                <input type="url" name="youtube_url" class="form-control" value="<?= e($s['youtube_url'] ?? '') ?>" placeholder="https://youtube.com/...">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-twitter-x"></i> Twitter / X URL</label>
                <input type="url" name="twitter_url" class="form-control" value="<?= e($s['twitter_url'] ?? '') ?>" placeholder="https://twitter.com/...">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-linkedin text-primary"></i> LinkedIn URL</label>
                <input type="url" name="linkedin_url" class="form-control" value="<?= e($s['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/...">
            </div>
        </div>

        <h4 style="margin:25px 0 15px; color:var(--admin-primary); border-bottom:1px solid var(--admin-border); padding-bottom:8px;">
            <i class="bi bi-image"></i> Brand Assets (Logo & Favicon)
        </h4>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">School Logo</label>
                <input type="file" name="logo_file" class="form-control" data-preview-target="#logoPreview" accept="image/*">
                <?php if (!empty($s['logo_url']) && file_exists(BASE_DIR . '/' . $s['logo_url'])): ?>
                <div class="image-preview-box">
                    <img id="logoPreview" src="../<?= e($s['logo_url']) ?>" alt="Logo Preview">
                </div>
                <?php else: ?>
                <div class="image-preview-box">
                    <img id="logoPreview" src="" style="display:none;" alt="Logo Preview">
                </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="form-label">Favicon Icon</label>
                <input type="file" name="favicon_file" class="form-control" data-preview-target="#faviconPreview" accept="image/*">
                <?php if (!empty($s['favicon_url']) && file_exists(BASE_DIR . '/' . $s['favicon_url'])): ?>
                <div class="image-preview-box">
                    <img id="faviconPreview" src="../<?= e($s['favicon_url']) ?>" alt="Favicon Preview" style="max-height:48px;">
                </div>
                <?php else: ?>
                <div class="image-preview-box">
                    <img id="faviconPreview" src="" style="display:none;" alt="Favicon Preview" style="max-height:48px;">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="margin-top:30px; border-top:1px solid var(--admin-border); padding-top:20px;">
            <button type="submit" name="save_school_info" class="admin-btn admin-btn-primary" style="font-size:1rem; padding:12px 28px;">
                <i class="bi bi-save-fill"></i> Save All Changes
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
