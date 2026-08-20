<?php
// includes/footer.php - Dynamic Public Footer
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$school_name = get_setting('school_name', 'DV Niketan Boarding School');
$footer_about = get_setting('footer_about', 'Dedicated to academic excellence, moral integrity, and holistic student growth in Surkhet, Nepal.');
$footer_copyright = get_setting('footer_copyright', '© 2026 DV Niketan Boarding School. All Rights Reserved.');
$developer_credit = get_setting('developer_credit', 'Designed & Developed with Dynamic CMS Architecture');
$full_address = get_setting('full_address', 'Birendranagar Municipality-7, ITRAM, Surkhet, Nepal');
$phone_numbers = get_setting('phone_numbers', 'XXX');
$email_addresses = get_setting('email_addresses', 'XXX');
$office_hours = get_setting('office_hours', 'Sun - Fri: 9:00 AM - 4:30 PM');

$facebook_url = get_setting('facebook_url', '');
$instagram_url = get_setting('instagram_url', '');
$youtube_url = get_setting('youtube_url', '');
$twitter_url = get_setting('twitter_url', '');
$linkedin_url = get_setting('linkedin_url', '');

$programs = get_academic_programs(true, 5);
$quick_menus = get_navigation_menu();
?>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Col 1: About -->
            <div class="footer-col footer-about">
                <h4><?= e($school_name) ?></h4>
                <p><?= e($footer_about) ?></p>
                <div class="top-bar-social" style="justify-content: flex-start; margin-top: 15px;">
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
                    <?php if (!empty($linkedin_url)): ?>
                    <a href="<?= e($linkedin_url) ?>" target="_blank" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <?php foreach (array_slice($quick_menus, 0, 6) as $menu): ?>
                    <li><a href="<?= e($menu['url']) ?>"><i class="bi bi-chevron-right"></i> <?= e($menu['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Col 3: Academic Programs -->
            <div class="footer-col">
                <h4>Programs</h4>
                <ul class="footer-links">
                    <?php foreach ($programs as $prog): ?>
                    <li><a href="academics.php#<?= e($prog['slug']) ?>"><i class="bi bi-chevron-right"></i> <?= e($prog['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Col 4: Contact Details -->
            <div class="footer-col">
                <h4>Contact Us</h4>
                <ul class="footer-contact-list">
                    <li><i class="bi bi-geo-alt-fill"></i> <span><?= e($full_address) ?></span></li>
                    <li><i class="bi bi-telephone-fill"></i> <span><?= e($phone_numbers) ?></span></li>
                    <li><i class="bi bi-envelope-fill"></i> <span><?= e($email_addresses) ?></span></li>
                    <li><i class="bi bi-clock-fill"></i> <span><?= e($office_hours) ?></span></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div><?= e($footer_copyright) ?></div>
            <div><small><?= e($developer_credit) ?></small></div>
        </div>
    </div>
</footer>

<!-- Lightbox Modal for Photo Gallery -->
<div class="lightbox-modal" id="lightboxModal">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-img" id="lightboxImg" src="" alt="Gallery Preview">
    <div id="lightboxCaption" style="position:absolute;bottom:30px;color:#fff;font-weight:600;font-size:1.1rem;text-shadow:0 2px 4px rgba(0,0,0,0.8);"></div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
