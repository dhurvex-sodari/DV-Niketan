<?php
// admin/users.php - Admin User Management & Roles
$page_title = 'Admin User Management';
require_once __DIR__ . '/includes/admin_header.php';
require_super_admin(); // Only Super Admins can manage other admins

$db = get_db();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_user'])) {
    $action = $_POST['action_user'];
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = in_array($_POST['role'], ['super_admin', 'editor']) ? $_POST['role'] : 'editor';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $password = trim($_POST['password'] ?? '');

    if ($action === 'create') {
        if (empty($password)) {
            $error = "Password is required for new admin user.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            try {
                $stmt = $db->prepare("INSERT INTO admins (username, fullname, email, password_hash, role, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$username, $fullname, $email, $hash, $role, $is_active]);
                $message = "Admin user created successfully!";
            } catch (Exception $e) {
                $error = "Failed to create user: " . $e->getMessage();
            }
        }
    } elseif ($action === 'update' && $user_id > 0) {
        try {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $db->prepare("UPDATE admins SET username = ?, fullname = ?, email = ?, password_hash = ?, role = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$username, $fullname, $email, $hash, $role, $is_active, $user_id]);
            } else {
                $stmt = $db->prepare("UPDATE admins SET username = ?, fullname = ?, email = ?, role = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$username, $fullname, $email, $role, $is_active, $user_id]);
            }
            $message = "Admin user updated successfully!";
        } catch (Exception $e) {
            $error = "Failed to update user: " . $e->getMessage();
        }
    }
}

if (isset($_GET['delete_user'])) {
    $del_id = (int)$_GET['delete_user'];
    if ($del_id === (int)$_SESSION['admin_id']) {
        $error = "You cannot delete your own active administrator account.";
    } else {
        $db->prepare("DELETE FROM admins WHERE id = ?")->execute([$del_id]);
        $message = "Admin user removed!";
    }
}

$admins = $db->query("SELECT * FROM admins ORDER BY id ASC")->fetchAll();

$edit_u = null;
if (isset($_GET['edit_user'])) {
    $edit_id = (int)$_GET['edit_user'];
    $stmt = $db->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_u = $stmt->fetch();
}
?>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-auto-dismiss">
    <i class="bi bi-check-circle-fill"></i> <?= e($message) ?>
</div>
<?php endif; ?>

<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-auto-dismiss">
    <i class="bi bi-exclamation-triangle-fill"></i> <?= e($error) ?>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header-flex">
        <div>
            <h3>Administrator Accounts & Role Access</h3>
            <p style="font-size:0.85rem; color:var(--admin-text-muted);">Manage Super Administrators (full privileges) and Content Editors (content management only).</p>
        </div>
    </div>

    <!-- Form -->
    <div style="background:var(--admin-body-bg); padding:20px; border-radius:8px; margin-bottom:24px; border:1px solid var(--admin-border);">
        <h4 style="margin-bottom:15px; font-size:1.05rem;"><?= $edit_u ? 'Edit Administrator' : 'Create New Administrator' ?></h4>
        <form action="users.php" method="POST">
            <input type="hidden" name="action_user" value="<?= $edit_u ? 'update' : 'create' ?>">
            <?php if ($edit_u): ?>
            <input type="hidden" name="user_id" value="<?= $edit_u['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-control" required value="<?= e($edit_u['username'] ?? '') ?>" placeholder="username">
                </div>
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="fullname" class="form-control" required value="<?= e($edit_u['fullname'] ?? '') ?>" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" required value="<?= e($edit_u['email'] ?? '') ?>" placeholder="admin@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Role Privilege *</label>
                    <select name="role" class="form-control">
                        <option value="editor" <?= ($edit_u && $edit_u['role'] === 'editor') ? 'selected' : '' ?>>Editor (Content Management)</option>
                        <option value="super_admin" <?= ($edit_u && $edit_u['role'] === 'super_admin') ? 'selected' : '' ?>>Super Admin (Full Access)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Password <?= $edit_u ? '(Leave empty to keep unchanged)' : '*' ?></label>
                    <input type="password" name="password" class="form-control" <?= $edit_u ? '' : 'required' ?> placeholder="••••••••">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top:28px;">
                    <label class="switch">
                        <input type="checkbox" name="is_active" value="1" <?= (!$edit_u || $edit_u['is_active']) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                    <span style="font-weight:600; font-size:0.88rem;">Account Active</span>
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle"></i> <?= $edit_u ? 'Update Admin' : 'Create Admin' ?>
                </button>
                <?php if ($edit_u): ?>
                <a href="users.php" class="admin-btn admin-btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Admin Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Last Login</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $u): ?>
                <tr>
                    <td><strong><?= e($u['fullname']) ?></strong></td>
                    <td><code><?= e($u['username']) ?></code></td>
                    <td><?= e($u['email']) ?></td>
                    <td>
                        <span class="badge <?= $u['role'] === 'super_admin' ? 'badge-info' : 'badge-warning' ?>">
                            <?= $u['role'] === 'super_admin' ? 'Super Admin' : 'Editor' ?>
                        </span>
                    </td>
                    <td><small><?= $u['last_login'] ? date('M d, H:i', strtotime($u['last_login'])) : 'Never' ?></small></td>
                    <td>
                        <span class="badge <?= $u['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $u['is_active'] ? 'Active' : 'Disabled' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="users.php?edit_user=<?= $u['id'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <?php if ($u['id'] !== (int)$_SESSION['admin_id']): ?>
                            <a href="users.php?delete_user=<?= $u['id'] ?>" class="btn-icon delete confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
