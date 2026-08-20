<?php
// admin/includes/admin_sidebar.php - Dynamic Admin Navigation Sidebar
$current_page = basename($_SERVER['PHP_SELF']);
$admin_user = get_logged_admin();
$school_name = get_setting('school_name', 'DV Niketan');
?>

<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="logo-badge">DV</div>
        <div>
            <h2><?= e(mb_strimwidth($school_name, 0, 18, '...')) ?></h2>
            <span>Content Manager</span>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-category">Main</li>
        <li class="menu-item <?= $current_page === 'index.php' ? 'active' : '' ?>">
            <a href="index.php" class="menu-link"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span></a>
        </li>

        <li class="menu-category">Website Content</li>
        <li class="menu-item <?= $current_page === 'school_info.php' ? 'active' : '' ?>">
            <a href="school_info.php" class="menu-link"><i class="bi bi-info-circle-fill"></i> <span>School Profile</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'homepage.php' ? 'active' : '' ?>">
            <a href="homepage.php" class="menu-link"><i class="bi bi-house-door-fill"></i> <span>Homepage Hero & Stats</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'section_manager.php' ? 'active' : '' ?>">
            <a href="section_manager.php" class="menu-link"><i class="bi bi-toggles"></i> <span>Section Visibility</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'menu_manager.php' ? 'active' : '' ?>">
            <a href="menu_manager.php" class="menu-link"><i class="bi bi-list-nested"></i> <span>Menu & Navigation</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'about_manager.php' ? 'active' : '' ?>">
            <a href="about_manager.php" class="menu-link"><i class="bi bi-file-earmark-person-fill"></i> <span>About Us & Vision</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'principal_manager.php' ? 'active' : '' ?>">
            <a href="principal_manager.php" class="menu-link"><i class="bi bi-quote"></i> <span>Principal Message</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'committee.php' ? 'active' : '' ?>">
            <a href="committee.php" class="menu-link"><i class="bi bi-people-fill"></i> <span>Management Committee</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'teachers.php' ? 'active' : '' ?>">
            <a href="teachers.php" class="menu-link"><i class="bi bi-mortarboard-fill"></i> <span>Teachers & Staff</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'academics.php' ? 'active' : '' ?>">
            <a href="academics.php" class="menu-link"><i class="bi bi-book-half"></i> <span>Academic Programs</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'facilities.php' ? 'active' : '' ?>">
            <a href="facilities.php" class="menu-link"><i class="bi bi-building-fill"></i> <span>Campus Facilities</span></a>
        </li>

        <li class="menu-category">Communications</li>
        <li class="menu-item <?= $current_page === 'notices.php' ? 'active' : '' ?>">
            <a href="notices.php" class="menu-link"><i class="bi bi-megaphone-fill"></i> <span>Notices & Circulars</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'news.php' ? 'active' : '' ?>">
            <a href="news.php" class="menu-link"><i class="bi bi-newspaper"></i> <span>News & Articles</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'events.php' ? 'active' : '' ?>">
            <a href="events.php" class="menu-link"><i class="bi bi-calendar-event-fill"></i> <span>Events & Calendar</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'gallery.php' ? 'active' : '' ?>">
            <a href="gallery.php" class="menu-link"><i class="bi bi-images"></i> <span>Photo Gallery</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'achievements.php' ? 'active' : '' ?>">
            <a href="achievements.php" class="menu-link"><i class="bi bi-trophy-fill"></i> <span>Achievements</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'downloads.php' ? 'active' : '' ?>">
            <a href="downloads.php" class="menu-link"><i class="bi bi-file-earmark-arrow-down-fill"></i> <span>Downloads & Forms</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'contacts.php' ? 'active' : '' ?>">
            <a href="contacts.php" class="menu-link"><i class="bi bi-envelope-open-fill"></i> <span>Contact Inquiries</span></a>
        </li>

        <li class="menu-category">System & Settings</li>
        <li class="menu-item <?= $current_page === 'seo_manager.php' ? 'active' : '' ?>">
            <a href="seo_manager.php" class="menu-link"><i class="bi bi-search"></i> <span>SEO Management</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'media.php' ? 'active' : '' ?>">
            <a href="media.php" class="menu-link"><i class="bi bi-folder-fill"></i> <span>Media Library</span></a>
        </li>
        <li class="menu-item <?= $current_page === 'settings.php' ? 'active' : '' ?>">
            <a href="settings.php" class="menu-link"><i class="bi bi-sliders"></i> <span>Theme & Settings</span></a>
        </li>
        <?php if (is_super_admin()): ?>
        <li class="menu-item <?= $current_page === 'users.php' ? 'active' : '' ?>">
            <a href="users.php" class="menu-link"><i class="bi bi-person-gear"></i> <span>Admin Users</span></a>
        </li>
        <?php endif; ?>
    </ul>
</aside>
