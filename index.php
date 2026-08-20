<?php
// index.php - Dynamic Public Homepage
$page_slug = 'home';
require_once __DIR__ . '/includes/header.php';

$hero_slides = get_hero_slides();
$stats = get_homepage_stats();
$why_us = get_why_choose_us();
$about_content = get_about_content();
$principal = get_principal_info();
$programs = get_academic_programs(true, 3);
$teachers = get_teachers(true, null, 4);
$facilities = get_facilities(true, 4);
$notices = get_notices(true, 4);
$news_list = get_news(true, 3);
$events_list = get_events(true, 3);
$achievements = get_achievements(true, 3);
$gallery_photos = get_gallery_photos();
$downloads = get_downloads(true, null);
?>

<!-- 1. Hero Carousel Section -->
<?php if (is_section_visible('hero') && !empty($hero_slides)): ?>
<section class="hero-section">
    <?php foreach ($hero_slides as $index => $slide): 
        $bg_style = !empty($slide['image_path']) ? "background-image: url('" . e($slide['image_path']) . "');" : "background: linear-gradient(135deg, #0d47a1 0%, #0a2540 100%);";
    ?>
    <div class="hero-slide <?= $index === 0 ? 'active' : '' ?>" style="<?= $bg_style ?>">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-tag"><i class="bi bi-mortarboard-fill me-1"></i> <?= e($school_name) ?></div>
                <h1 class="hero-title"><?= e($slide['title']) ?></h1>
                <?php if (!empty($slide['subtitle'])): ?>
                <p class="hero-subtitle"><?= e($slide['subtitle']) ?></p>
                <?php endif; ?>
                <div class="hero-buttons">
                    <?php if (!empty($slide['btn1_text'])): ?>
                    <a href="<?= e($slide['btn1_link'] ?: 'academics.php') ?>" class="btn btn-primary"><?= e($slide['btn1_text']) ?> <i class="bi bi-arrow-right"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($slide['btn2_text'])): ?>
                    <a href="<?= e($slide['btn2_link'] ?: 'contact.php') ?>" class="btn btn-outline-white"><?= e($slide['btn2_text']) ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (count($hero_slides) > 1): ?>
    <div class="hero-nav">
        <button class="hero-arrow hero-prev" aria-label="Previous Slide"><i class="bi bi-chevron-left"></i></button>
        <button class="hero-arrow hero-next" aria-label="Next Slide"><i class="bi bi-chevron-right"></i></button>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- 2. Statistics Section -->
<?php if (is_section_visible('stats') && !empty($stats)): ?>
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <?php foreach ($stats as $stat): ?>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi <?= e($stat['icon'] ?: 'bi-award') ?>"></i></div>
                <div>
                    <div class="stat-number"><?= e($stat['number_value']) ?></div>
                    <div class="stat-label"><?= e($stat['label']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 3. About Overview Section -->
<?php if (is_section_visible('about') && !empty($about_content['intro'])): 
    $intro = $about_content['intro'];
?>
<section class="section">
    <div class="container">
        <div class="about-grid">
            <div class="about-img-box">
                <?php if (!empty($intro['image_path']) && file_exists(BASE_DIR . '/' . $intro['image_path'])): ?>
                <img src="<?= e($intro['image_path']) ?>" alt="About <?= e($school_name) ?>">
                <?php else: ?>
                <div style="height:380px;background:linear-gradient(135deg,#0d47a1,#1976d2);border-radius:var(--radius-xl);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem;box-shadow:var(--shadow-xl);">
                    <i class="bi bi-building"></i>
                </div>
                <?php endif; ?>
                <div class="about-card-badge">
                    <strong>25+</strong>
                    <span>Years of Excellence</span>
                </div>
            </div>
            <div class="about-text">
                <span class="section-badge">Welcome To DV Niketan</span>
                <h2 class="section-title"><?= e($intro['title']) ?></h2>
                <p><?= nl2br(e($intro['content'])) ?></p>
                <ul class="about-features-list">
                    <li><i class="bi bi-check-circle-fill"></i> Experienced Educators</li>
                    <li><i class="bi bi-check-circle-fill"></i> Advanced Practical Labs</li>
                    <li><i class="bi bi-check-circle-fill"></i> Student-Centric Pedagogy</li>
                    <li><i class="bi bi-check-circle-fill"></i> Moral & Leadership Focus</li>
                </ul>
                <a href="about.php" class="btn btn-primary">Discover More About Us <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 4. Principal Message Section -->
<?php if (is_section_visible('principal') && !empty($principal)): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Leadership</span>
            <h2 class="section-title">Message from the Principal</h2>
        </div>
        <div class="principal-box">
            <div class="principal-photo-wrap">
                <?php if (!empty($principal['photo']) && file_exists(BASE_DIR . '/' . $principal['photo'])): ?>
                <img src="<?= e($principal['photo']) ?>" alt="<?= e($principal['name']) ?>">
                <?php else: ?>
                <div style="width:200px;height:220px;background:#cbd5e1;border-radius:var(--radius-lg);margin:0 auto 15px;display:flex;align-items:center;justify-content:center;font-size:3.5rem;color:#64748b;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <?php endif; ?>
                <h3 class="principal-name"><?= e($principal['name']) ?></h3>
                <div class="principal-designation"><?= e($principal['designation']) ?></div>
                <?php if (!empty($principal['qualification'])): ?>
                <small class="text-muted d-block"><?= e($principal['qualification']) ?></small>
                <?php endif; ?>
            </div>
            <div class="principal-message-content">
                <div class="quote-icon"><i class="bi bi-quote"></i></div>
                <p><?= nl2br(e($principal['message'])) ?></p>
                <?php if (!empty($principal['signature_image']) && file_exists(BASE_DIR . '/' . $principal['signature_image'])): ?>
                <div class="principal-sig">
                    <img src="<?= e($principal['signature_image']) ?>" alt="Principal Signature">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 5. Why Choose Us Section -->
<?php if (is_section_visible('why_us') && !empty($why_us)): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Why Choose Us</span>
            <h2 class="section-title">Nurturing Excellence & Integrity</h2>
            <p class="section-subtitle">Discover the reasons parents and students choose <?= e($school_name) ?> in Surkhet.</p>
        </div>
        <div class="facilities-grid">
            <?php foreach ($why_us as $item): ?>
            <div class="facility-card">
                <div class="facility-icon-wrap"><i class="bi <?= e($item['icon'] ?: 'bi-check-circle') ?>"></i></div>
                <h3 class="facility-title"><?= e($item['title']) ?></h3>
                <p class="facility-desc"><?= e($item['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 6. Featured Academic Programs -->
<?php if (is_section_visible('programs') && !empty($programs)): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Academics</span>
            <h2 class="section-title">Featured Academic Programs</h2>
            <p class="section-subtitle">Rigorous curricula designed to prepare students for top universities and careers.</p>
        </div>
        <div class="programs-grid">
            <?php foreach ($programs as $prog): ?>
            <div class="program-card">
                <div class="program-image">
                    <?php if (!empty($prog['image_path']) && file_exists(BASE_DIR . '/' . $prog['image_path'])): ?>
                    <img src="<?= e($prog['image_path']) ?>" alt="<?= e($prog['name']) ?>">
                    <?php else: ?>
                    <div style="height:100%;background:linear-gradient(135deg,#1e3a8a,#3b82f6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.5rem;">
                        <i class="bi bi-book"></i>
                    </div>
                    <?php endif; ?>
                    <span class="program-level-tag"><?= e($prog['level']) ?></span>
                </div>
                <div class="program-body">
                    <h3 class="program-title"><?= e($prog['name']) ?></h3>
                    <p class="program-desc"><?= e(mb_strimwidth($prog['description'], 0, 140, '...')) ?></p>
                    <div class="program-meta">
                        <?php if (!empty($prog['duration'])): ?>
                        <span><i class="bi bi-clock"></i> <?= e($prog['duration']) ?></span>
                        <?php endif; ?>
                        <span><i class="bi bi-mortarboard"></i> NEB Board</span>
                    </div>
                    <a href="academics.php#<?= e($prog['slug']) ?>" class="btn btn-primary" style="margin-top:auto;">View Program Details <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 7. Facilities Showcase -->
<?php if (is_section_visible('facilities') && !empty($facilities)): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Campus & Infrastructure</span>
            <h2 class="section-title">World-Class Facilities</h2>
            <p class="section-subtitle">Modern spaces engineered to inspire exploration, innovation, and wellness.</p>
        </div>
        <div class="facilities-grid">
            <?php foreach ($facilities as $fac): ?>
            <div class="facility-card">
                <div class="facility-icon-wrap"><i class="bi <?= e($fac['icon'] ?: 'bi-building') ?>"></i></div>
                <h3 class="facility-title"><?= e($fac['title']) ?></h3>
                <p class="facility-desc"><?= e($fac['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:40px;">
            <a href="facilities.php" class="btn btn-outline-white" style="border-color:var(--primary-color);color:var(--primary-color);">Explore All Facilities <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 8. Latest Notices & News Tabs / Grid -->
<?php if (is_section_visible('notices') && !empty($notices)): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Announcements</span>
            <h2 class="section-title">Latest School Notices</h2>
            <p class="section-subtitle">Stay informed with official circulars, exam schedules, and academic dates.</p>
        </div>
        <div class="notices-list">
            <?php foreach ($notices as $notice): 
                $date_obj = date_create($notice['publish_date']);
            ?>
            <div class="notice-item">
                <div class="notice-left">
                    <div class="notice-date-box">
                        <span class="day"><?= date_format($date_obj, 'd') ?></span>
                        <span class="month"><?= date_format($date_obj, 'M') ?></span>
                    </div>
                    <div>
                        <span class="notice-category-badge"><?= e($notice['category']) ?></span>
                        <h4 class="notice-title"><?= e($notice['title']) ?></h4>
                        <?php if (!empty($notice['description'])): ?>
                        <p style="font-size:0.85rem;color:var(--text-muted);margin-top:4px;"><?= e(mb_strimwidth($notice['description'], 0, 100, '...')) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <?php if (!empty($notice['file_path'])): ?>
                    <a href="<?= e($notice['file_path']) ?>" target="_blank" class="btn btn-primary" style="padding:6px 16px;font-size:0.82rem;"><i class="bi bi-download"></i> PDF Download</a>
                    <?php else: ?>
                    <a href="notices.php" class="btn btn-secondary" style="padding:6px 16px;font-size:0.82rem;">Read More</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:30px;">
            <a href="notices.php" class="btn btn-primary">View Notice Board <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 9. Photo Gallery Highlights -->
<?php if (is_section_visible('gallery') && !empty($gallery_photos)): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Life at DV Niketan</span>
            <h2 class="section-title">Photo Gallery Highlights</h2>
            <p class="section-subtitle">Vibrant moments captured across our academic and co-curricular life.</p>
        </div>
        <div class="gallery-grid">
            <?php foreach (array_slice($gallery_photos, 0, 8) as $photo): ?>
            <div class="gallery-item" data-caption="<?= e($photo['caption'] ?: $photo['album_title']) ?>">
                <img src="<?= e($photo['image_path']) ?>" alt="<?= e($photo['caption'] ?: 'DV Niketan Gallery') ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22220%22><rect width=%22100%%22 height=%22100%%22 fill=%22%23cbd5e1%22/><text x=%2250%%22 y=%2250%%22 fill=%22%23475569%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22>Gallery Image</text></svg>'">
                <div class="gallery-overlay">
                    <i class="bi bi-zoom-in"></i>
                    <span><?= e($photo['caption'] ?: $photo['album_title']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:35px;">
            <a href="gallery.php" class="btn btn-primary">View All Albums <i class="bi bi-images"></i></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 10. Enroll & Contact CTA -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color:#fff; text-align:center;">
    <div class="container" style="max-width:750px;">
        <h2 style="font-size:2.4rem;color:#fff;margin-bottom:16px;">Begin Your Journey at <?= e($school_name) ?></h2>
        <p style="font-size:1.1rem;color:#e2e8f0;margin-bottom:30px;">Admissions are open for +2 Science, +2 Management, and school levels. Visit our campus in Birendranagar-7, ITRAM, Surkhet or get in touch today.</p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="contact.php" class="btn btn-secondary" style="font-size:1rem;padding:14px 32px;"><i class="bi bi-send-fill"></i> Contact Admissions</a>
            <a href="downloads.php" class="btn btn-outline-white" style="font-size:1rem;padding:14px 32px;"><i class="bi bi-file-earmark-pdf"></i> Download Prospectus</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
