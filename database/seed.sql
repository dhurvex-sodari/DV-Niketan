-- DV Niketan Boarding School CMS Seed Data
USE `dv_niketan_db`;

-- Clear existing data if re-seeding
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `settings`;
TRUNCATE TABLE `section_visibility`;
TRUNCATE TABLE `menus`;
TRUNCATE TABLE `hero_slides`;
TRUNCATE TABLE `homepage_stats`;
TRUNCATE TABLE `why_choose_us`;
TRUNCATE TABLE `about_content`;
TRUNCATE TABLE `principal_info`;
TRUNCATE TABLE `committee_members`;
TRUNCATE TABLE `teachers`;
TRUNCATE TABLE `academic_programs`;
TRUNCATE TABLE `facilities`;
TRUNCATE TABLE `notices`;
TRUNCATE TABLE `news`;
TRUNCATE TABLE `events`;
TRUNCATE TABLE `gallery_photos`;
TRUNCATE TABLE `gallery_albums`;
TRUNCATE TABLE `achievements`;
TRUNCATE TABLE `downloads`;
TRUNCATE TABLE `contact_messages`;
TRUNCATE TABLE `seo_pages`;
TRUNCATE TABLE `admins`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Initial Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('school_name', 'DV Niketan Boarding School', 'school_info'),
('school_type', 'Secondary & Higher Secondary Boarding School', 'school_info'),
('tagline', 'Empowering Minds, Shaping Future Leaders', 'school_info'),
('full_address', 'Birendranagar Municipality-7, ITRAM, Surkhet, Nepal', 'school_info'),
('municipality', 'Birendranagar Municipality', 'school_info'),
('ward', '7', 'school_info'),
('district', 'Surkhet', 'school_info'),
('province', 'Karnali Province', 'school_info'),
('country', 'Nepal', 'school_info'),
('phone_numbers', 'XXX', 'school_info'),
('email_addresses', 'XXX', 'school_info'),
('office_hours', 'Sun - Fri: 9:00 AM - 4:30 PM (Saturday Closed)', 'school_info'),
('google_maps_url', 'https://maps.google.com/maps?q=Surkhet+Nepal&t=&z=13&ie=UTF8&iwloc=&output=embed', 'school_info'),
('facebook_url', 'https://facebook.com/XXX', 'social'),
('instagram_url', 'https://instagram.com/XXX', 'social'),
('youtube_url', 'https://youtube.com/XXX', 'social'),
('twitter_url', 'https://twitter.com/XXX', 'social'),
('linkedin_url', 'https://linkedin.com/XXX', 'social'),
('logo_url', 'assets/images/logo.png', 'appearance'),
('favicon_url', 'assets/images/favicon.png', 'appearance'),
('primary_color', '#0d47a1', 'appearance'),
('secondary_color', '#e65100', 'appearance'),
('accent_color', '#1976d2', 'appearance'),
('footer_about', 'DV Niketan Boarding School is dedicated to academic excellence, moral integrity, and holistic personal growth in Surkhet, Nepal.', 'footer'),
('footer_copyright', '© 2026 DV Niketan Boarding School. All Rights Reserved.', 'footer'),
('developer_credit', 'Designed & Developed with Dynamic CMS Architecture', 'footer'),
('maintenance_mode', '0', 'system'),
('enable_loader', '1', 'appearance'),
('about_hero_image', 'assets/images/campus.jpg', 'about');

-- 2. Section Visibility (All toggleable)
INSERT INTO `section_visibility` (`section_key`, `section_title`, `is_visible`, `display_order`) VALUES
('hero', 'Hero Banner Slider', 1, 1),
('stats', 'Quick Statistics Counter', 1, 2),
('about', 'About School Overview', 1, 3),
('principal', 'Principal Message Card', 1, 4),
('why_us', 'Why Choose DV Niketan', 1, 5),
('programs', 'Featured Academic Programs', 1, 6),
('teachers', 'Featured Faculty & Teachers', 1, 7),
('facilities', 'Campus Facilities', 1, 8),
('notices', 'Latest Notices & Announcements', 1, 9),
('news', 'Latest News & Articles', 1, 10),
('events', 'Upcoming Events & Calendar', 1, 11),
('achievements', 'Student & School Achievements', 1, 12),
('gallery', 'Photo Gallery Highlights', 1, 13),
('downloads', 'Quick Downloads & Forms', 1, 14),
('contact', 'Contact & Location Section', 1, 15);

-- 3. Navigation Menus
INSERT INTO `menus` (`id`, `title`, `url`, `parent_id`, `display_order`, `is_active`, `open_new_tab`) VALUES
(1, 'Home', 'index.php', 0, 1, 1, 0),
(2, 'About Us', 'about.php', 0, 2, 1, 0),
(3, 'Academics', 'academics.php', 0, 3, 1, 0),
(4, 'Faculty & Staff', 'teachers.php', 0, 4, 1, 0),
(5, 'Facilities', 'facilities.php', 0, 5, 1, 0),
(6, 'Notices', 'notices.php', 0, 6, 1, 0),
(7, 'News & Events', 'news.php', 0, 7, 1, 0),
(8, 'Gallery', 'gallery.php', 0, 8, 1, 0),
(9, 'Achievements', 'achievements.php', 0, 9, 1, 0),
(10, 'Downloads', 'downloads.php', 0, 10, 1, 0),
(11, 'Contact', 'contact.php', 0, 11, 1, 0);

-- 4. Homepage Hero Slides
INSERT INTO `hero_slides` (`title`, `subtitle`, `image_path`, `btn1_text`, `btn1_link`, `btn2_text`, `btn2_link`, `display_order`, `is_active`) VALUES
('Welcome to DV Niketan Boarding School', 'Inspiring intellectual curiosity, character building, and academic brilliance in Surkhet, Nepal.', 'assets/images/hero1.jpg', 'Explore Programs', 'academics.php', 'Admissions', 'contact.php', 1, 1),
('Empowering Future Leaders in Science & Management', 'State-of-the-art learning facilities, experienced mentors, and modern digital education.', 'assets/images/hero2.jpg', 'Our Facilities', 'facilities.php', 'Contact Us', 'contact.php', 2, 1);

-- 5. Homepage Statistics
INSERT INTO `homepage_stats` (`number_value`, `label`, `icon`, `display_order`, `is_active`) VALUES
('1500+', 'Enrolled Students', 'bi-mortarboard', 1, 1),
('60+', 'Qualified Educators', 'bi-person-badge', 2, 1),
('100%', 'Board Exam Pass Rate', 'bi-award', 3, 1),
('25+', 'Years of Excellence', 'bi-stars', 4, 1);

-- 6. Why Choose Us
INSERT INTO `why_choose_us` (`title`, `description`, `icon`, `display_order`, `is_active`) VALUES
('Dedicated & Experienced Faculty', 'Our educators bring passion, experience, and personalized guidance to every student.', 'bi-person-check', 1, 1),
('Modern Science & Computer Labs', 'Well-equipped laboratories providing hands-on practical learning for +2 programs.', 'bi-cpu', 2, 1),
('Holistic Character Development', 'Emphasizing moral values, sports, public speaking, and leadership alongside academics.', 'bi-heart-pulse', 3, 1),
('Safe, Inspiring Campus Environment', 'A supportive, disciplined, and technologically enhanced environment for optimal growth.', 'bi-shield-check', 4, 1);

-- 7. About Page Content
INSERT INTO `about_content` (`section_key`, `title`, `content`, `image_path`) VALUES
('intro', 'About DV Niketan Boarding School', 'DV Niketan Boarding School, located in Birendranagar-7, ITRAM, Surkhet, is a premier institution dedicated to providing quality education from foundational grades through higher secondary (+2) levels. We are committed to fostering academic curiosity, creative innovation, and strong moral character.', 'assets/images/about_intro.jpg'),
('history', 'Our History & Legacy', 'Established with the vision of elevating education standards in Karnali Province, DV Niketan has consistently produced top achievers and responsible citizens who excel in diverse academic and professional fields.', 'assets/images/history.jpg'),
('vision', 'Our Vision', 'To be a center of educational excellence that nurtures enlightened, capable, and compassionate leaders for Nepal and the world.', NULL),
('mission', 'Our Mission', 'To deliver holistic, student-centric education combining modern pedagogy, technological fluency, and ethical grounding.', NULL),
('objectives', 'Core Objectives', '1. Deliver rigorous academic instruction across Science, Management, and School levels.\n2. Cultivate critical thinking and problem-solving abilities.\n3. Instill civic responsibility, integrity, and cultural pride.\n4. Provide modern infrastructure for practical and experiential learning.', NULL),
('core_values', 'Our Core Values', '• Excellence in all endeavors\n• Integrity & Moral Responsibility\n• Respect & Inclusivity\n• Lifelong Learning & Curiosity', NULL);

-- 8. Principal Profile
INSERT INTO `principal_info` (`name`, `designation`, `photo`, `qualification`, `experience`, `message`, `signature_image`) VALUES
('XXX', 'Principal', 'assets/images/principal.jpg', 'M.Sc., M.Ed.', '20+ Years in Educational Leadership', 'Welcome to DV Niketan Boarding School! We believe that education is the most powerful catalyst for positive transformation. Our dedicated faculty, modern facilities, and vibrant student community work hand-in-hand to cultivate academic brilliance and strong moral leadership. We look forward to partnering with parents and students on this inspiring educational journey.', 'assets/images/signature.png');

-- 9. Management Committee Initial Members
INSERT INTO `committee_members` (`name`, `position`, `photo`, `qualification`, `description`, `display_order`, `is_active`) VALUES
('XXX', 'Chairman, School Management Committee', 'assets/images/cm1.jpg', 'XXX', 'Guiding the strategic vision, governance, and institutional advancement of DV Niketan.', 1, 1),
('XXX', 'Vice Chairman', 'assets/images/cm2.jpg', 'XXX', 'Dedicated to infrastructure growth and student welfare.', 2, 1);

-- 10. Initial Teachers & Staff
INSERT INTO `teachers` (`name`, `photo`, `designation`, `department`, `subject`, `qualification`, `experience`, `bio`, `phone`, `email`, `display_order`, `is_featured`, `is_active`) VALUES
('XXX', 'assets/images/teacher1.jpg', 'Senior Lecturer', 'Science Department', 'Physics', 'M.Sc. Physics', '10+ Years', 'Dedicated to interactive physics education and practical laboratory training.', 'XXX', 'XXX', 1, 1, 1),
('XXX', 'assets/images/teacher2.jpg', 'Head of Department', 'Management Department', 'Accountancy', 'M.B.S., M.Phil.', '12+ Years', 'Specializes in financial accounting and practical business coaching.', 'XXX', 'XXX', 2, 1, 1),
('XXX', 'assets/images/teacher3.jpg', 'Lecturer', 'Science Department', 'Biology & Chemistry', 'M.Sc.', '8+ Years', 'Passionate about life sciences and research-oriented learning.', 'XXX', 'XXX', 3, 1, 1);

-- 11. Initial Academic Programs (+2 Science, +2 Management)
INSERT INTO `academic_programs` (`name`, `slug`, `level`, `duration`, `requirements`, `description`, `syllabus`, `image_path`, `display_order`, `is_featured`, `is_active`) VALUES
('+2 Science', 'plus-two-science', 'Higher Secondary (+2 NEB)', '2 Years', 'SEE with minimum GPA 2.8 and Grade B+ in Science & Mathematics.', 'The +2 Science program at DV Niketan is designed for students aspiring to pursue careers in Medicine, Engineering, Information Technology, and Scientific Research. With advanced physics, chemistry, biology, and computer labs, students receive thorough theoretical and practical grounding.', 'Compulsory: English, Nepali\nSpecialized: Physics, Chemistry, Mathematics, Biology / Computer Science', 'assets/images/science.jpg', 1, 1, 1),
('+2 Management', 'plus-two-management', 'Higher Secondary (+2 NEB)', '2 Years', 'SEE with minimum GPA 2.0 or equivalent.', 'The +2 Management program equips students with modern business fundamentals, accounting expertise, economics, marketing, and computer applications to excel in higher management studies and entrepreneurship.', 'Compulsory: English, Nepali\nSpecialized: Accountancy, Economics, Business Studies, Computer Science / Hotel Management / Mathematics', 'assets/images/management.jpg', 2, 1, 1);

-- 12. Facilities Initial Seed
INSERT INTO `facilities` (`title`, `description`, `image_path`, `icon`, `display_order`, `is_featured`, `is_active`) VALUES
('Science Laboratories', 'Fully equipped physics, chemistry, and biology labs for hands-on experimentation.', 'assets/images/facility_science.jpg', 'bi-radioactive', 1, 1, 1),
('Computer & IT Lab', 'High-speed internet, modern workstations, and software tools for digital literacy.', 'assets/images/facility_computer.jpg', 'bi-pc-display', 2, 1, 1),
('Library & Resource Center', 'Extensive collection of reference books, textbooks, journals, and quiet reading spaces.', 'assets/images/facility_library.jpg', 'bi-book-half', 3, 1, 1),
('Sports & Playground', 'Courts and grounds for volleyball, basketball, football, badminton, and athletics.', 'assets/images/facility_sports.jpg', 'bi-dribbble', 4, 1, 1);

-- 13. Notices Initial Seed
INSERT INTO `notices` (`title`, `category`, `description`, `file_path`, `featured_image`, `publish_date`, `is_featured`, `is_active`) VALUES
('Admissions Open for +2 Science & +2 Management', 'Admissions', 'Admissions for the upcoming academic session are now open. Interested students can collect forms from the school administration desk.', 'assets/uploads/documents/admission_notice.pdf', 'assets/images/notice1.jpg', CURDATE(), 1, 1),
('First Term Examination Schedule & Guidelines', 'Examinations', 'All students and parents are requested to review the terminal examination routine and instructions available at the office desk.', NULL, NULL, CURDATE(), 0, 1);

-- 14. News Initial Seed
INSERT INTO `news` (`title`, `slug`, `category`, `description`, `image_path`, `publish_date`, `is_featured`, `is_active`) VALUES
('Annual Science & Tech Exhibition Celebrated with Enthusiasm', 'annual-science-tech-exhibition', 'Campus Event', 'Students from +2 Science and secondary levels presented over 40 innovative working models, software applications, and environmental projects.', 'assets/images/news1.jpg', CURDATE(), 1, 1),
('DV Niketan Students Shine in District Level Debate Competition', 'district-debate-competition-success', 'Achievements', 'Our students secured top positions in the Inter-School Surkhet Debate Competition, showcasing exceptional articulation.', 'assets/images/news2.jpg', CURDATE(), 0, 1);

-- 15. Events Initial Seed
INSERT INTO `events` (`title`, `event_date`, `event_time`, `location`, `description`, `image_path`, `status`, `is_featured`, `is_active`) VALUES
('Annual Parents-Teachers Meeting (PTM)', DATE_ADD(CURDATE(), INTERVAL 14 DAY), '10:00 AM - 2:00 PM', 'School Main Auditorium', 'A comprehensive discussion on student academic progress, attendance, and personalized growth plans.', 'assets/images/event1.jpg', 'Upcoming', 1, 1),
('Inter-House Sports Meet & Athletics', DATE_ADD(CURDATE(), INTERVAL 28 DAY), '8:30 AM - 4:00 PM', 'DV Niketan Sports Complex', 'Annual sports festival featuring track events, football, basketball, and team competitions.', 'assets/images/event2.jpg', 'Upcoming', 1, 1);

-- 16. Gallery Albums Initial Seed
INSERT INTO `gallery_albums` (`id`, `title`, `slug`, `description`, `cover_image`, `display_order`, `is_active`) VALUES
(1, 'Campus Life & Activities', 'campus-life', 'Memorable moments from daily classroom activities, labs, and student gatherings.', 'assets/images/gallery1.jpg', 1, 1),
(2, 'Sports & Cultural Programs', 'sports-and-culture', 'Highlights from our annual sports meet, cultural performances, and awards.', 'assets/images/gallery2.jpg', 2, 1);

-- 17. Gallery Photos Initial Seed
INSERT INTO `gallery_photos` (`album_id`, `image_path`, `caption`, `display_order`) VALUES
(1, 'assets/images/g1.jpg', 'Students engaged in physics practical lab', 1),
(1, 'assets/images/g2.jpg', 'Computer laboratory practical session', 2),
(2, 'assets/images/g3.jpg', 'Annual football championship match', 1),
(2, 'assets/images/g4.jpg', 'Cultural dance performance during anniversary celebration', 2);

-- 18. Achievements Initial Seed
INSERT INTO `achievements` (`title`, `recipient_name`, `category`, `description`, `date`, `image_path`, `is_featured`, `is_active`) VALUES
('Top Board Result in Surkhet District (+2 Science)', 'XXX', 'Academic Excellence', 'Secured outstanding GPA 3.92 in National Examination Board (+2 Science).', CURDATE(), 'assets/images/achieve1.jpg', 1, 1),
('Champions Trophy - Inter-School Basketball Tournament', 'DV Niketan Senior Basketball Team', 'Sports', 'Emerged champions in the Karnali Provincial Inter-School Basketball Championship.', CURDATE(), 'assets/images/achieve2.jpg', 1, 1);

-- 19. Downloads Initial Seed
INSERT INTO `downloads` (`title`, `category`, `file_path`, `file_size`, `file_type`, `display_order`, `is_active`) VALUES
('+2 Science & Management Prospectus 2026', 'Prospectus', 'assets/uploads/documents/prospectus.pdf', '2.4 MB', 'PDF', 1, 1),
('Student Admission & Registration Form', 'Admission', 'assets/uploads/documents/admission_form.pdf', '850 KB', 'PDF', 2, 1),
('Academic Calendar & Holiday List', 'Academic', 'assets/uploads/documents/academic_calendar.pdf', '1.1 MB', 'PDF', 3, 1);

-- 20. SEO Pages Initial Seed
INSERT INTO `seo_pages` (`page_slug`, `page_name`, `meta_title`, `meta_description`, `meta_keywords`, `og_title`, `og_description`) VALUES
('home', 'Homepage', 'DV Niketan Boarding School | Surkhet, Nepal - Empowering Future Leaders', 'DV Niketan Boarding School in Birendranagar-7, ITRAM, Surkhet offers high quality education, +2 Science, and +2 Management programs with modern facilities.', 'DV Niketan, School in Surkhet, +2 Science Surkhet, +2 Management Nepal, ITRAM Surkhet school', 'DV Niketan Boarding School - Excellence in Education', 'Official website of DV Niketan Boarding School, Birendranagar-7, ITRAM, Surkhet.'),
('about', 'About Us', 'About DV Niketan Boarding School | History, Vision & Mission', 'Learn about DV Niketan Boarding School history, mission, visionary leadership, and management committee.', 'about DV Niketan, Surkhet school history, vision mission', 'About DV Niketan Boarding School', 'Discover our legacy of academic brilliance in Surkhet, Nepal.'),
('academics', 'Academics & Programs', 'Academic Programs | +2 Science & +2 Management | DV Niketan', 'Explore academic programs offered at DV Niketan Boarding School including +2 Science and +2 Management with advanced labs.', '+2 Science Surkhet, +2 Management Nepal, NEB programs Surkhet', 'Academic Programs at DV Niketan', 'Join +2 Science and +2 Management at DV Niketan.'),
('teachers', 'Faculty & Staff', 'Our Experienced Faculty & Staff | DV Niketan Boarding School', 'Meet our dedicated teachers and faculty members across Science, Management, and School departments.', 'DV Niketan teachers, faculty Surkhet, science lecturers', 'Faculty & Staff - DV Niketan', 'Meet our dedicated educators.'),
('facilities', 'Campus Facilities', 'Modern Campus Facilities & Labs | DV Niketan Boarding School', 'Explore modern science labs, computer labs, library, and sports amenities at DV Niketan.', 'school facilities Surkhet, science labs, computer labs', 'Campus Facilities - DV Niketan', 'World-class learning environment in Surkhet.'),
('notices', 'Notices & Announcements', 'Latest Notices & Official Announcements | DV Niketan', 'Official notices, exam schedules, holiday announcements, and academic updates from DV Niketan Boarding School.', 'DV Niketan notices, exam routine Surkhet', 'Notices - DV Niketan Boarding School', 'Stay updated with official school notices.'),
('news', 'News & Updates', 'Latest News & Events | DV Niketan Boarding School', 'Read latest campus news, exhibitions, student achievements, and academic seminars at DV Niketan.', 'school news Surkhet, DV Niketan updates', 'Latest News - DV Niketan', 'Catch up on school news and celebrations.'),
('gallery', 'Photo Gallery', 'Photo Gallery & Moments | DV Niketan Boarding School', 'Browse pictures of campus life, lab sessions, sports events, and celebrations at DV Niketan.', 'DV Niketan photo gallery, school pictures Surkhet', 'Photo Gallery - DV Niketan', 'Capturing vibrant moments of campus life.'),
('contact', 'Contact Us', 'Contact DV Niketan Boarding School | Birendranagar, Surkhet', 'Get in touch with DV Niketan Boarding School, Birendranagar-7, ITRAM, Surkhet. Send inquiries, visit campus, or reach out via phone.', 'contact DV Niketan, ITRAM Surkhet school address, phone number', 'Contact DV Niketan Boarding School', 'Reach out to our administration desk.');

-- 21. Initial Admin Users
-- Default credentials: username 'admin', password 'admin123'
-- Hash generated via password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO `admins` (`username`, `fullname`, `email`, `password_hash`, `role`, `is_active`) VALUES
('admin', 'Super Administrator', 'admin@dvniketan.edu.np', '$2y$10$RH78F/mZWNXXuX.Jt2TlW.t2IqdCFFj/nH.zU2jxKykRdWGp8kBQG', 'super_admin', 1),
('editor', 'Content Editor', 'editor@dvniketan.edu.np', '$2y$10$RH78F/mZWNXXuX.Jt2TlW.t2IqdCFFj/nH.zU2jxKykRdWGp8kBQG', 'editor', 1);
