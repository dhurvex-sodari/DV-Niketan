<?php
// admin/contacts.php - Contact Form Inquiries & Messages Viewer
$page_title = 'Contact Inquiries';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();
$message = '';

// Toggle Read / Unread
if (isset($_GET['mark_read'])) {
    $msg_id = (int)$_GET['mark_read'];
    $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$msg_id]);
    $message = "Marked as read!";
}

// Delete Message
if (isset($_GET['delete_msg'])) {
    $del_id = (int)$_GET['delete_msg'];
    $db->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$del_id]);
    $message = "Message deleted!";
}

$messages = $db->query("SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC")->fetchAll();

$view_msg = null;
if (isset($_GET['view'])) {
    $view_id = (int)$_GET['view'];
    $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$view_id]);
    $view_msg = $stmt->fetch();
    // Auto mark as read
    if ($view_msg && !$view_msg['is_read']) {
        $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$view_id]);
    }
}
?>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-auto-dismiss">
    <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
</div>
<?php endif; ?>

<?php if ($view_msg): ?>
<div class="admin-card" style="border-left:4px solid var(--admin-primary);">
    <div class="card-header-flex">
        <div>
            <h3>Inquiry Details</h3>
            <small class="text-muted">Received on <?= date('F d, Y \a\t h:i A', strtotime($view_msg['created_at'])) ?></small>
        </div>
        <a href="contacts.php" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i> Back to All Messages</a>
    </div>

    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:20px;">
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:16px;">
            <div>
                <strong>Sender Name:</strong>
                <div><?= e($view_msg['name']) ?></div>
            </div>
            <div>
                <strong>Email Address:</strong>
                <div><a href="mailto:<?= e($view_msg['email']) ?>" style="color:#0284c7;"><?= e($view_msg['email']) ?></a></div>
            </div>
            <div>
                <strong>Phone Number:</strong>
                <div><?= e($view_msg['phone'] ?: '—') ?></div>
            </div>
            <div>
                <strong>Subject:</strong>
                <div><strong><?= e($view_msg['subject']) ?></strong></div>
            </div>
        </div>

        <hr style="border:0; border-top:1px solid var(--admin-border); margin:16px 0;">

        <strong>Message Body:</strong>
        <div style="margin-top:10px; font-size:1rem; line-height:1.8; color:var(--admin-text-main);">
            <?= nl2br(e($view_msg['message'])) ?>
        </div>
    </div>

    <div style="display:flex; gap:10px;">
        <a href="mailto:<?= e($view_msg['email']) ?>?subject=Re: <?= urlencode($view_msg['subject']) ?>" class="admin-btn admin-btn-primary">
            <i class="bi bi-reply-fill"></i> Reply via Email
        </a>
        <a href="contacts.php?delete_msg=<?= $view_msg['id'] ?>" class="admin-btn admin-btn-danger confirm-delete">
            <i class="bi bi-trash"></i> Delete Inquiry
        </a>
    </div>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>All Contact Inquiries & Feedback</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Messages submitted through the public website contact form.</p>
        </div>
    </div>

    <?php if (empty($messages)): ?>
    <p style="color:var(--admin-text-muted);">No inquiries in the database.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Sender Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                <tr style="<?= !$msg['is_read'] ? 'font-weight:700; background:#f0fdf4;' : '' ?>">
                    <td>
                        <span class="badge <?= $msg['is_read'] ? 'badge-info' : 'badge-danger' ?>">
                            <?= $msg['is_read'] ? 'Read' : 'New Unread' ?>
                        </span>
                    </td>
                    <td><small><?= date('M d, Y', strtotime($msg['created_at'])) ?></small></td>
                    <td><?= e($msg['name']) ?></td>
                    <td><?= e($msg['email']) ?></td>
                    <td><?= e(mb_strimwidth($msg['subject'], 0, 30, '...')) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="contacts.php?view=<?= $msg['id'] ?>" class="btn-icon edit" title="View Details"><i class="bi bi-eye"></i></a>
                            <a href="contacts.php?delete_msg=<?= $msg['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
