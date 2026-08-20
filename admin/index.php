<?php
// admin/index.php - Admin Dashboard Overview
$page_title = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';

$db = get_db();

// Counts for quick stats
$count_programs = $db->query("SELECT COUNT(*) FROM academic_programs")->fetchColumn();
$count_teachers = $db->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
$count_notices = $db->query("SELECT COUNT(*) FROM notices")->fetchColumn();
$count_messages = $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
$count_gallery = $db->query("SELECT COUNT(*) FROM gallery_photos")->fetchColumn();
$count_downloads = $db->query("SELECT COUNT(*) FROM downloads")->fetchColumn();

// Recent messages
$recent_messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<!-- Metric Cards Grid -->
<div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 30px;">
    <div class="admin-card" style="margin-bottom:0; display:flex; align-items:center; gap:16px;">
        <div style="width:50px; height:50px; border-radius:12px; background:#e0f2fe; color:#0284c7; display:flex; align-items:center; justify-content:center; font-size:1.6rem;">
            <i class="bi bi-book-half"></i>
        </div>
        <div>
            <div style="font-size:1.8rem; font-weight:800; font-family:'Outfit',sans-serif;"><?= $count_programs ?></div>
            <div style="font-size:0.85rem; color:var(--admin-text-muted);">Academic Programs</div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:0; display:flex; align-items:center; gap:16px;">
        <div style="width:50px; height:50px; border-radius:12px; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; font-size:1.6rem;">
            <i class="bi bi-person-workspace"></i>
        </div>
        <div>
            <div style="font-size:1.8rem; font-weight:800; font-family:'Outfit',sans-serif;"><?= $count_teachers ?></div>
            <div style="font-size:0.85rem; color:var(--admin-text-muted);">Teachers & Staff</div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:0; display:flex; align-items:center; gap:16px;">
        <div style="width:50px; height:50px; border-radius:12px; background:#dcfce7; color:#16a34a; display:flex; align-items:center; justify-content:center; font-size:1.6rem;">
            <i class="bi bi-megaphone-fill"></i>
        </div>
        <div>
            <div style="font-size:1.8rem; font-weight:800; font-family:'Outfit',sans-serif;"><?= $count_notices ?></div>
            <div style="font-size:0.85rem; color:var(--admin-text-muted);">Published Notices</div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:0; display:flex; align-items:center; gap:16px;">
        <div style="width:50px; height:50px; border-radius:12px; background:#fee2e2; color:#dc2626; display:flex; align-items:center; justify-content:center; font-size:1.6rem;">
            <i class="bi bi-envelope-exclamation-fill"></i>
        </div>
        <div>
            <div style="font-size:1.8rem; font-weight:800; font-family:'Outfit',sans-serif;"><?= $count_messages ?></div>
            <div style="font-size:0.85rem; color:var(--admin-text-muted);">Unread Inquiries</div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Messages -->
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
    <!-- Quick Management Shortuts -->
    <div class="admin-card">
        <div class="card-header-flex">
            <h3>Quick Website Controls</h3>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
            <a href="school_info.php" class="admin-btn admin-btn-secondary" style="justify-content:flex-start;">
                <i class="bi bi-building"></i> Edit School Info
            </a>
            <a href="homepage.php" class="admin-btn admin-btn-secondary" style="justify-content:flex-start;">
                <i class="bi bi-sliders"></i> Edit Hero & Stats
            </a>
            <a href="academics.php" class="admin-btn admin-btn-secondary" style="justify-content:flex-start;">
                <i class="bi bi-plus-circle-dotted"></i> Add/Edit Program
            </a>
            <a href="notices.php" class="admin-btn admin-btn-secondary" style="justify-content:flex-start;">
                <i class="bi bi-file-earmark-plus"></i> Post Notice (PDF)
            </a>
            <a href="teachers.php" class="admin-btn admin-btn-secondary" style="justify-content:flex-start;">
                <i class="bi bi-person-plus"></i> Add Faculty Member
            </a>
            <a href="gallery.php" class="admin-btn admin-btn-secondary" style="justify-content:flex-start;">
                <i class="bi bi-upload"></i> Upload Gallery Photos
            </a>
            <a href="section_manager.php" class="admin-btn admin-btn-secondary" style="justify-content:flex-start;">
                <i class="bi bi-toggles"></i> Toggle Sections
            </a>
            <a href="settings.php" class="admin-btn admin-btn-secondary" style="justify-content:flex-start;">
                <i class="bi bi-palette"></i> Change Theme Colors
            </a>
        </div>
    </div>

    <!-- Recent Inquiries -->
    <div class="admin-card">
        <div class="card-header-flex">
            <h3>Recent Contact Inquiries</h3>
            <a href="contacts.php" class="admin-btn admin-btn-secondary" style="font-size:0.8rem; padding:4px 10px;">View All</a>
        </div>
        <?php if (empty($recent_messages)): ?>
        <p style="color:var(--admin-text-muted); font-size:0.9rem;">No inquiries received yet.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_messages as $msg): ?>
                    <tr>
                        <td>
                            <strong><?= e($msg['name']) ?></strong>
                            <div style="font-size:0.75rem; color:var(--admin-text-muted);"><?= e($msg['email']) ?></div>
                        </td>
                        <td><?= e(mb_strimwidth($msg['subject'], 0, 25, '...')) ?></td>
                        <td><small><?= date('M d, H:i', strtotime($msg['created_at'])) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
