<?php
// admin/login.php - Secure Admin Login
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/auth.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $db = get_db();
        $stmt = $db->prepare("SELECT * FROM admins WHERE (username = ? OR email = ?) AND is_active = 1 LIMIT 1");
        $stmt->execute([$username, $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_fullname'] = $admin['fullname'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_role'] = $admin['role'];

            // Update last login
            $upd = $db->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
            $upd->execute([$admin['id']]);

            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password. Please try again.";
        }
    }
}

$school_name = get_setting('school_name', 'DV Niketan Boarding School');
$primary_color = get_setting('primary_color', '#0d47a1');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= e($school_name) ?> CMS</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div style="text-align:center; margin-bottom:28px;">
        <div style="width:55px; height:55px; background:<?= e($primary_color) ?>; color:#fff; border-radius:14px; display:inline-flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:800; margin-bottom:12px;">
            DV
        </div>
        <h2 style="font-size:1.4rem; font-weight:800; color:#0f172a; font-family:'Outfit',sans-serif;"><?= e($school_name) ?></h2>
        <p style="font-size:0.85rem; color:#64748b;">Website Content Manager Portal</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger" style="padding:10px 14px; font-size:0.85rem;">
        <i class="bi bi-exclamation-circle-fill"></i> <?= e($error) ?>
    </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label class="form-label">Username or Email</label>
            <input type="text" name="username" class="form-control" required placeholder="admin" autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>
        <div style="margin-top:24px;">
            <button type="submit" class="admin-btn admin-btn-primary" style="width:100%; justify-content:center; padding:12px; font-size:0.95rem;">
                <i class="bi bi-box-arrow-in-right"></i> Sign In to Dashboard
            </button>
        </div>
    </form>

    <div style="margin-top:25px; text-align:center; font-size:0.82rem; color:#94a3b8; border-top:1px solid #e2e8f0; padding-top:18px;">
        <a href="../index.php" style="color:#2563eb; text-decoration:none; font-weight:600;"><i class="bi bi-arrow-left"></i> Return to Public Website</a>
    </div>
</div>

</body>
</html>
