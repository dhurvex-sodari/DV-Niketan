<?php
// gallery.php - Dynamic Photo Gallery by Albums
$page_slug = 'gallery';
require_once __DIR__ . '/includes/header.php';

$selected_album = isset($_GET['album']) ? (int)$_GET['album'] : null;
$albums = get_gallery_albums(true);
$photos = get_gallery_photos($selected_album);
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Photo Gallery</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Visual journey of academics, achievements, celebrations, and campus life.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Album Tabs -->
        <div style="display:flex; justify-content:center; gap:10px; margin-bottom:40px; flex-wrap:wrap;">
            <a href="gallery.php" class="btn <?= empty($selected_album) ? 'btn-primary' : 'btn-secondary' ?>" style="font-size:0.85rem; padding:8px 18px;">All Photos</a>
            <?php foreach ($albums as $album): ?>
            <a href="gallery.php?album=<?= $album['id'] ?>" class="btn <?= $selected_album === (int)$album['id'] ? 'btn-primary' : 'btn-secondary' ?>" style="font-size:0.85rem; padding:8px 18px;">
                <?= e($album['title']) ?> (<?= $album['photo_count'] ?>)
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($photos)): ?>
        <div style="text-align:center; padding:50px; background:#fff; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
            <h3>No photos available in this album.</h3>
        </div>
        <?php else: ?>
        <div class="gallery-grid">
            <?php foreach ($photos as $photo): ?>
            <div class="gallery-item" data-caption="<?= e($photo['caption'] ?: $photo['album_title']) ?>">
                <img src="<?= e($photo['image_path']) ?>" alt="<?= e($photo['caption'] ?: 'DV Niketan Gallery') ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22220%22><rect width=%22100%%22 height=%22100%%22 fill=%22%23cbd5e1%22/><text x=%2250%%22 y=%2250%%22 fill=%22%23475569%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22>Gallery Image</text></svg>'">
                <div class="gallery-overlay">
                    <i class="bi bi-zoom-in"></i>
                    <p style="font-size:0.9rem; font-weight:600;"><?= e($photo['caption'] ?: $photo['album_title']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
