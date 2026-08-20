<?php
// contact.php - Dynamic Contact Us Page & Inquiries Form
$page_slug = 'contact';
require_once __DIR__ . '/includes/header.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_msg = "Please fill in all required fields (Name, Email, Subject, Message).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please provide a valid email address.";
    } else {
        try {
            $db = get_db();
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            $success_msg = "Thank you! Your message has been sent successfully. Our administration office will contact you shortly.";
        } catch (Exception $e) {
            $error_msg = "Failed to send message: " . $e->getMessage();
        }
    }
}

$full_address = get_setting('full_address', 'Birendranagar Municipality-7, ITRAM, Surkhet, Nepal');
$phone_numbers = get_setting('phone_numbers', 'XXX');
$email_addresses = get_setting('email_addresses', 'XXX');
$office_hours = get_setting('office_hours', 'Sun - Fri: 9:00 AM - 4:30 PM');
$google_maps_url = get_setting('google_maps_url', '');
?>

<section style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color:#fff; padding: 60px 0; text-align:center;">
    <div class="container">
        <h1 style="color:#fff; font-size:2.6rem; margin-bottom:10px;">Contact Admissions & Info</h1>
        <p style="color:#cbd5e1; font-size:1.1rem; max-width:600px; margin:0 auto;">Reach out to our administration desk for admissions, inquiries, or campus tours.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display:grid; grid-template-columns: 1fr 1.3fr; gap:45px; align-items:start;">
            <!-- Left: Contact Details & Info -->
            <div>
                <span class="section-badge">Get in Touch</span>
                <h2 class="section-title" style="margin-bottom:20px;">We're Here to Help</h2>
                <p style="color:var(--text-muted); font-size:1.05rem; line-height:1.8; margin-bottom:30px;">
                    Have questions about +2 Science, +2 Management, school admissions, fee structures, or campus facilities? Feel free to contact us or visit in person.
                </p>

                <div class="facilities-grid" style="grid-template-columns: 1fr; gap:16px;">
                    <div class="stat-card" style="padding:18px;">
                        <div class="stat-icon"><i class="bi bi-geo-alt-fill text-warning"></i></div>
                        <div>
                            <div style="font-weight:700; color:var(--text-main);">Campus Address</div>
                            <div style="font-size:0.9rem; color:var(--text-muted);"><?= e($full_address) ?></div>
                        </div>
                    </div>

                    <div class="stat-card" style="padding:18px;">
                        <div class="stat-icon"><i class="bi bi-telephone-fill text-success"></i></div>
                        <div>
                            <div style="font-weight:700; color:var(--text-main);">Phone Number</div>
                            <div style="font-size:0.9rem; color:var(--text-muted);"><?= e($phone_numbers) ?></div>
                        </div>
                    </div>

                    <div class="stat-card" style="padding:18px;">
                        <div class="stat-icon"><i class="bi bi-envelope-fill text-primary"></i></div>
                        <div>
                            <div style="font-weight:700; color:var(--text-main);">Email Address</div>
                            <div style="font-size:0.9rem; color:var(--text-muted);"><?= e($email_addresses) ?></div>
                        </div>
                    </div>

                    <div class="stat-card" style="padding:18px;">
                        <div class="stat-icon"><i class="bi bi-clock-fill text-secondary"></i></div>
                        <div>
                            <div style="font-weight:700; color:var(--text-main);">Administration Hours</div>
                            <div style="font-size:0.9rem; color:var(--text-muted);"><?= e($office_hours) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Contact Form -->
            <div style="background:#fff; padding:35px; border-radius:var(--radius-xl); border:1px solid var(--border-color); box-shadow:var(--shadow-lg);">
                <h3 style="font-size:1.4rem; margin-bottom:15px; color:var(--text-main);">Send Us an Inquiry</h3>
                
                <?php if (!empty($success_msg)): ?>
                <div style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; padding:15px; border-radius:var(--radius-md); margin-bottom:20px; font-weight:600;">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= e($success_msg) ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                <div style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; padding:15px; border-radius:var(--radius-md); margin-bottom:20px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= e($error_msg) ?>
                </div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:6px;">Your Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="Full Name" style="width:100%; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:6px;">Email Address *</label>
                            <input type="email" name="email" class="form-control" required placeholder="you@example.com" style="width:100%; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px;">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:6px;">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="98XXXXXXXX" style="width:100%; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:6px;">Subject *</label>
                            <input type="text" name="subject" class="form-control" required placeholder="e.g. +2 Science Admission Inquiry" style="width:100%; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px;">
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:6px;">Your Message / Query *</label>
                        <textarea name="message" rows="5" class="form-control" required placeholder="Write your message here..." style="width:100%; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px;"></textarea>
                    </div>

                    <button type="submit" name="submit_contact" class="btn btn-primary" style="width:100%; padding:12px; font-size:1rem;">
                        <i class="bi bi-send-fill"></i> Submit Inquiry
                    </button>
                </form>
            </div>
        </div>

        <!-- Google Map Section -->
        <?php if (!empty($google_maps_url)): ?>
        <div style="margin-top:60px; border-radius:var(--radius-xl); overflow:hidden; box-shadow:var(--shadow-md); border:1px solid var(--border-color);">
            <iframe src="<?= e($google_maps_url) ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
