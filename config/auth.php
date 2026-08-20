<?php
// config/auth.php - Authentication and Authorization System
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

function check_auth(): void {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}

function get_logged_admin(): ?array {
    if (!isset($_SESSION['admin_id'])) {
        return null;
    }
    return [
        'id' => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'] ?? 'admin',
        'fullname' => $_SESSION['admin_fullname'] ?? 'Admin',
        'email' => $_SESSION['admin_email'] ?? '',
        'role' => $_SESSION['admin_role'] ?? 'editor',
    ];
}

function is_super_admin(): bool {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'super_admin';
}

function require_super_admin(): void {
    check_auth();
    if (!is_super_admin()) {
        die("<div style='font-family:sans-serif;padding:30px;text-align:center;'>
            <h2>Access Denied</h2>
            <p>You do not have permission to access this area. Super Administrator rights required.</p>
            <a href='index.php'>Return to Dashboard</a>
        </div>");
    }
}

function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}
