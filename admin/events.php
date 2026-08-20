<?php
// admin/events.php - Events & Calendar CRUD Manager
$page_title = 'Events & Calendar Manager';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_event'])) {
    $action = $_POST['action_event'];
    $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $event_date = trim($_POST['event_date'] ?? date('Y-m-d'));
    $event_time = trim($_POST['event_time'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = trim($_POST['status'] ?? 'Upcoming');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_path = null;
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = upload_file($_FILES['image_file'], 'events', ['jpg', 'jpeg', 'png', 'webp']);
    }

    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO events (title, event_date, event_time, location, description, image_path, status, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $event_date, $event_time, $location, $description, $image_path, $status, $is_featured, $is_active]);
        $message = "Event added to calendar!";
    } elseif ($action === 'update' && $event_id > 0) {
        if ($image_path) {
            $stmt = $db->prepare("UPDATE events SET title = ?, event_date = ?, event_time = ?, location = ?, description = ?, image_path = ?, status = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $event_date, $event_time, $location, $description, $image_path, $status, $is_featured, $is_active, $event_id]);
        } else {
            $stmt = $db->prepare("UPDATE events SET title = ?, event_date = ?, event_time = ?, location = ?, description = ?, status = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $event_date, $event_time, $location, $description, $status, $is_featured, $is_active, $event_id]);
        }
        $message = "Event updated!";
    }
}

if (isset($_GET['delete_event'])) {
    $del_id = (int)$_GET['delete_event'];
    $db->prepare("DELETE FROM events WHERE id = ?")->execute([$del_id]);
    $message = "Event deleted!";
}

$events = $db->query("SELECT * FROM events ORDER BY event_date ASC, id DESC")->fetchAll();

$edit_event = null;
if (isset($_GET['edit_event'])) {
    $edit_id = (int)$_GET['edit_event'];
    $stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_event = $stmt->fetch();
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
            <h3>Events & Academic Calendar</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage upcoming celebrations, sports meets, parents-teachers gatherings, and ceremonies.</p>
        </div>
    </div>

    <!-- Event Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_event ? 'Edit Event' : 'Add New Event' ?></h4>
        <form action="events.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_event" value="<?= $edit_event ? 'update' : 'create' ?>">
            <?php if ($edit_event): ?>
            <input type="hidden" name="event_id" value="<?= $edit_event['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Event Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= e($edit_event['title'] ?? '') ?>" placeholder="e.g. Annual Sports Meet 2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Event Date *</label>
                    <input type="date" name="event_date" class="form-control" required value="<?= e($edit_event['event_date'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Event Time</label>
                    <input type="text" name="event_time" class="form-control" value="<?= e($edit_event['event_time'] ?? '') ?>" placeholder="e.g. 10:00 AM - 2:00 PM">
                </div>
                <div class="form-group">
                    <label class="form-label">Location / Venue</label>
                    <input type="text" name="location" class="form-control" value="<?= e($edit_event['location'] ?? 'School Main Auditorium') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Event Status</label>
                    <select name="status" class="form-control">
                        <option value="Upcoming" <?= ($edit_event && $edit_event['status'] === 'Upcoming') ? 'selected' : '' ?>>Upcoming</option>
                        <option value="Ongoing" <?= ($edit_event && $edit_event['status'] === 'Ongoing') ? 'selected' : '' ?>>Ongoing</option>
                        <option value="Completed" <?= ($edit_event && $edit_event['status'] === 'Completed') ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Event Description *</label>
                    <textarea name="description" rows="3" class="form-control" required><?= e($edit_event['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Event Banner Image</label>
                    <input type="file" name="image_file" class="form-control" data-preview-target="#eventPreview" accept="image/*">
                    <?php if ($edit_event && !empty($edit_event['image_path'])): ?>
                    <div class="image-preview-box">
                        <img id="eventPreview" src="../<?= e($edit_event['image_path']) ?>" alt="Event Image">
                    </div>
                    <?php else: ?>
                    <div class="image-preview-box">
                        <img id="eventPreview" src="" style="display:none;" alt="Event Image">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:20px; margin-top:28px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?= ($edit_event && $edit_event['is_featured']) ? 'checked' : '' ?>>
                        <span>Feature Event</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.88rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_event || $edit_event['is_active']) ? 'checked' : '' ?>>
                        <span>Published</span>
                    </label>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_event ? 'Update Event' : 'Add Event' ?>
                </button>
                <?php if ($edit_event): ?>
                <a href="events.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
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
                    <th>Time</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $ev): ?>
                <tr>
                    <td><small><strong><?= date('M d, Y', strtotime($ev['event_date'])) ?></strong></small></td>
                    <td><strong><?= e($ev['title']) ?></strong></td>
                    <td><?= e($ev['event_time'] ?: '—') ?></td>
                    <td><small><?= e($ev['location'] ?: '—') ?></small></td>
                    <td><span class="badge badge-info"><?= e($ev['status']) ?></span></td>
                    <td>
                        <div class="action-btns">
                            <a href="events.php?edit_event=<?= $ev['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="events.php?delete_event=<?= $ev['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
