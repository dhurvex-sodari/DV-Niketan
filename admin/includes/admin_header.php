<?php
// admin/includes/admin_header.php - Admin Topbar & Common HTML Header
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/../../config/auth.php';

check_auth();

$admin_user = get_logged_admin();
$school_name = get_setting('school_name', 'DV Niketan Boarding School');
$primary_color = get_setting('primary_color', '#0d47a1');
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Dashboard' ?> - <?= e($school_name) ?> CMS</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        :root {
            --admin-primary: <?= e($primary_color) ?>;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/admin_sidebar.php'; ?>

<div class="admin-main">
    <!-- Topbar -->
    <header class="admin-topbar">
        <div class="topbar-title">
            <h1><?= $page_title ?? 'Dashboard' ?></h1>
        </div>
        <div class="topbar-actions">
            <a href="../index.php" target="_blank" class="btn-view-site" title="Open Public Website">
                <i class="bi bi-box-arrow-up-right"></i> View Live Site
            </a>
            
            <div class="admin-user-pill">
                <div class="user-avatar"><?= strtoupper(substr($admin_user['fullname'], 0, 1)) ?></div>
                <div>
                    <div class="user-name"><?= e($admin_user['fullname']) ?></div>
                    <span class="user-role"><?= $admin_user['role'] === 'super_admin' ? 'Super Admin' : 'Editor' ?></span>
                </div>
            </div>

            <a href="logout.php" class="btn-icon delete" title="Logout" style="border-radius:50%;">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </header>

    <main class="admin-content">
