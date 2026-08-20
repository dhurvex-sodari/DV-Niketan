<?php
// includes/header.php - Dynamic Public Header & Navigation
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

// Check Maintenance Mode
$is_maintenance = get_setting('maintenance_mode', '0');
$current_script = basename($_SERVER['PHP_SELF']);
if ($is_maintenance === '1' && !isset($_SESSION['admin_logged_in']) && strpos($_SERVER['REQUEST_URI'], '/admin/') === false) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"><title>Maintenance Mode - <?= e(get_setting('school_name', 'DV Niketan')) ?></title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body style="display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;background:#091e42;color:#fff;">
        <div style="max-width:500px;padding:30px;">
            <h2>Website Under Scheduled Maintenance</h2>
            <p style="margin-top:15px;color:#cbd5e1;"><?= e(get_setting('school_name', 'DV Niketan Boarding School')) ?> is currently undergoing scheduled updates. Please check back shortly.</p>
            <p style="margin-top:20px;font-size:0.85rem;"><a href="admin/login.php" style="color:#60a5fa;">Admin Login</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Determine Current Page Slug for SEO & Active Menu
$page_slug = $page_slug ?? 'home';
$seo = get_seo_data($page_slug);
$primary_color = get_setting('primary_color', '#0d47a1');
$secondary_color = get_setting('secondary_color', '#e65100');
$accent_color = get_setting('accent_color', '#1976d2');
$school_name = get_setting('school_name', 'DV Niketan Boarding School');
$tagline = get_setting('tagline', 'Empowering Minds, Shaping Future Leaders');
$phone_numbers = get_setting('phone_numbers', 'XXX');
$email_addresses = get_setting('email_addresses', 'XXX');
$full_address = get_setting('full_address', 'Birendranagar Municipality-7, ITRAM, Surkhet, Nepal');
$logo_url = get_setting('logo_url', 'assets/images/logo.png');
$favicon_url = get_setting('favicon_url', 'assets/images/favicon.png');

$facebook_url = get_setting('facebook_url', '');
$instagram_url = get_setting('instagram_url', '');
$youtube_url = get_setting('youtube_url', '');
$twitter_url = get_setting('twitter_url', '');

$menu_items = get_navigation_menu();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($seo['meta_title']) ?></title>
    <meta name="description" content="<?= e($seo['meta_description']) ?>">
    <meta name="keywords" content="<?= e($seo['meta_keywords']) ?>">
    <?php if (!empty($seo['canonical_url'])): ?>
    <link rel="canonical" href="<?= e($seo['canonical_url']) ?>">
    <?php endif; ?>

    <!-- Open Graph Metadata -->
    <meta property="og:title" content="<?= e($seo['og_title']) ?>">
    <meta property="og:description" content="<?= e($seo['og_description']) ?>">
    <meta property="og:image" content="<?= e($seo['og_image']) ?>">
    <meta property="og:type" content="website">

    <?php if (!empty($favicon_url) && file_exists(BASE_DIR . '/' . $favicon_url)): ?>
    <link rel="icon" type="image/png" href="<?= e($favicon_url) ?>">
    <?php else: ?>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
    <?php endif; ?>

    <!-- Dynamic Theme Styling based on Database Settings -->
    <style>
        :root {
            --primary-color: <?= e($primary_color) ?>;
            --primary-light: <?= e($accent_color) ?>;
            --primary-dark: <?= e($primary_color) ?>;
            --primary-glow: <?= e($primary_color) ?>26;
            --secondary-color: <?= e($secondary_color) ?>;
        }
    </style>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Top Notification Bar -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-info">
            <?php if (!empty($full_address)): ?>
            <span><i class="bi bi-geo-alt-fill text-warning"></i> <?= e($full_address) ?></span>
            <?php endif; ?>
            <?php if (!empty($phone_numbers)): ?>
            <span><i class="bi bi-telephone-fill"></i> <?= e($phone_numbers) ?></span>
            <?php endif; ?>
            <?php if (!empty($email_addresses)): ?>
            <span><i class="bi bi-envelope-fill"></i> <?= e($email_addresses) ?></span>
            <?php endif; ?>
        </div>
        <div class="top-bar-social">
            <?php if (!empty($facebook_url)): ?>
            <a href="<?= e($facebook_url) ?>" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
            <?php endif; ?>
            <?php if (!empty($instagram_url)): ?>
            <a href="<?= e($instagram_url) ?>" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
            <?php endif; ?>
            <?php if (!empty($youtube_url)): ?>
            <a href="<?= e($youtube_url) ?>" target="_blank" title="YouTube"><i class="bi bi-youtube"></i></a>
            <?php endif; ?>
            <?php if (!empty($twitter_url)): ?>
            <a href="<?= e($twitter_url) ?>" target="_blank" title="Twitter"><i class="bi bi-twitter-x"></i></a>
            <?php endif; ?>
            <a href="admin/login.php" class="top-bar-admin" title="Admin Portal"><i class="bi bi-shield-lock"></i> Admin Portal</a>
        </div>
    </div>
</div>

<!-- Main Sticky Header -->
<header class="main-header">
    <div class="container">
        <nav class="navbar">
            <a href="index.php" class="brand-logo">
                <?php if (!empty($logo_url) && file_exists(BASE_DIR . '/' . $logo_url)): ?>
                <img src="<?= e($logo_url) ?>" alt="<?= e($school_name) ?> Logo">
                <?php else: ?>
                <div style="width:48px;height:48px;background:var(--primary-color);color:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;">DV</div>
                <?php endif; ?>
                <div class="brand-text">
                    <h1><?= e($school_name) ?></h1>
                    <span><?= e($tagline) ?></span>
                </div>
            </a>

            <button class="mobile-toggle" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>

            <ul class="nav-menu">
                <?php foreach ($menu_items as $item): 
                    $has_children = !empty($item['children']);
                    $is_active = ($current_script === $item['url']);
                ?>
                <li class="nav-item <?= $has_children ? 'has-dropdown' : '' ?> <?= $is_active ? 'active' : '' ?>">
                    <a href="<?= e($item['url']) ?>" class="nav-link" <?= $item['open_new_tab'] ? 'target="_blank"' : '' ?>>
                        <?= e($item['title']) ?>
                        <?php if ($has_children): ?><i class="bi bi-chevron-down ms-1" style="font-size:0.75rem;"></i><?php endif; ?>
                    </a>
                    <?php if ($has_children): ?>
                    <ul class="dropdown-menu">
                        <?php foreach ($item['children'] as $child): ?>
                        <li>
                            <a href="<?= e($child['url']) ?>" <?= $child['open_new_tab'] ? 'target="_blank"' : '' ?>>
                                <?= e($child['title']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>

                <li>
                    <a href="contact.php" class="nav-cta">Enroll Now</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
