<?php
// config/helpers.php - Core Data Querying & CMS Helpers
require_once __DIR__ . '/database.php';

// Cache array for settings to avoid multiple queries
$GLOBAL_SETTINGS_CACHE = null;

function get_all_settings(): array {
    global $GLOBAL_SETTINGS_CACHE;
    if ($GLOBAL_SETTINGS_CACHE !== null) {
        return $GLOBAL_SETTINGS_CACHE;
    }
    $db = get_db();
    try {
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $GLOBAL_SETTINGS_CACHE = $results ?: [];
        return $GLOBAL_SETTINGS_CACHE;
    } catch (Exception $e) {
        return [];
    }
}

function get_setting(string $key, string $default = ''): string {
    $settings = get_all_settings();
    return $settings[$key] ?? $default;
}

function update_setting(string $key, string $value, string $group = 'general'): bool {
    global $GLOBAL_SETTINGS_CACHE;
    $GLOBAL_SETTINGS_CACHE = null; // Invalidate cache
    $db = get_db();
    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, setting_group) 
                          VALUES (?, ?, ?) 
                          ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_group = VALUES(setting_group)");
    return $stmt->execute([$key, $value, $group]);
}

function is_section_visible(string $key): bool {
    $db = get_db();
    try {
        $stmt = $db->prepare("SELECT is_visible FROM section_visibility WHERE section_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $res = $stmt->fetchColumn();
        return $res !== false ? (bool)$res : true;
    } catch (Exception $e) {
        return true;
    }
}

function get_sections_list(): array {
    $db = get_db();
    try {
        $stmt = $db->query("SELECT * FROM section_visibility ORDER BY display_order ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_navigation_menu(): array {
    $db = get_db();
    try {
        $stmt = $db->query("SELECT * FROM menus WHERE is_active = 1 ORDER BY display_order ASC");
        $items = $stmt->fetchAll();
        
        // Build hierarchy (Parent items and child dropdown items)
        $tree = [];
        $children = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == 0) {
                $tree[$item['id']] = $item;
                $tree[$item['id']]['children'] = [];
            } else {
                $children[] = $item;
            }
        }
        foreach ($children as $child) {
            if (isset($tree[$child['parent_id']])) {
                $tree[$child['parent_id']]['children'][] = $child;
            } else {
                // If parent not found, add as top level
                $tree[$child['id']] = $child;
                $tree[$child['id']]['children'] = [];
            }
        }
        return array_values($tree);
    } catch (Exception $e) {
        return [];
    }
}

function get_seo_data(string $slug): array {
    $db = get_db();
    $default = [
        'meta_title' => get_setting('school_name', 'DV Niketan Boarding School') . ' | Surkhet, Nepal',
        'meta_description' => get_setting('school_name', 'DV Niketan') . ' - Quality Education in Birendranagar-7, ITRAM, Surkhet.',
        'meta_keywords' => 'DV Niketan, School Surkhet, +2 Science, +2 Management',
        'og_title' => get_setting('school_name', 'DV Niketan Boarding School'),
        'og_description' => get_setting('tagline', 'Empowering Minds, Shaping Future Leaders'),
        'og_image' => get_setting('logo_url', 'assets/images/logo.png'),
        'canonical_url' => '',
    ];
    try {
        $stmt = $db->prepare("SELECT * FROM seo_pages WHERE page_slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        if ($row) {
            return [
                'meta_title' => !empty($row['meta_title']) ? $row['meta_title'] : $default['meta_title'],
                'meta_description' => !empty($row['meta_description']) ? $row['meta_description'] : $default['meta_description'],
                'meta_keywords' => !empty($row['meta_keywords']) ? $row['meta_keywords'] : $default['meta_keywords'],
                'og_title' => !empty($row['og_title']) ? $row['og_title'] : $default['og_title'],
                'og_description' => !empty($row['og_description']) ? $row['og_description'] : $default['og_description'],
                'og_image' => !empty($row['og_image']) ? $row['og_image'] : $default['og_image'],
                'canonical_url' => $row['canonical_url'] ?? '',
            ];
        }
    } catch (Exception $e) {}
    return $default;
}

function get_hero_slides(): array {
    $db = get_db();
    try {
        $stmt = $db->query("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY display_order ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_homepage_stats(): array {
    $db = get_db();
    try {
        $stmt = $db->query("SELECT * FROM homepage_stats WHERE is_active = 1 ORDER BY display_order ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_why_choose_us(): array {
    $db = get_db();
    try {
        $stmt = $db->query("SELECT * FROM why_choose_us WHERE is_active = 1 ORDER BY display_order ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_about_content(): array {
    $db = get_db();
    try {
        $stmt = $db->query("SELECT * FROM about_content");
        $items = $stmt->fetchAll();
        $mapped = [];
        foreach ($items as $item) {
            $mapped[$item['section_key']] = $item;
        }
        return $mapped;
    } catch (Exception $e) {
        return [];
    }
}

function get_principal_info(): ?array {
    $db = get_db();
    try {
        $stmt = $db->query("SELECT * FROM principal_info LIMIT 1");
        return $stmt->fetch() ?: null;
    } catch (Exception $e) {
        return null;
    }
}

function get_committee_members(bool $active_only = true): array {
    $db = get_db();
    try {
        $sql = "SELECT * FROM committee_members" . ($active_only ? " WHERE is_active = 1" : "") . " ORDER BY display_order ASC";
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_teachers(bool $active_only = true, ?string $dept = null, ?int $limit = null): array {
    $db = get_db();
    try {
        $conditions = [];
        $params = [];
        if ($active_only) {
            $conditions[] = "is_active = 1";
        }
        if ($dept) {
            $conditions[] = "department = ?";
            $params[] = $dept;
        }
        $where = $conditions ? " WHERE " . implode(" AND ", $conditions) : "";
        $sql = "SELECT * FROM teachers $where ORDER BY display_order ASC, id ASC" . ($limit ? " LIMIT $limit" : "");
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_academic_programs(bool $active_only = true, ?int $limit = null): array {
    $db = get_db();
    try {
        $sql = "SELECT * FROM academic_programs" . ($active_only ? " WHERE is_active = 1" : "") . " ORDER BY display_order ASC, id ASC" . ($limit ? " LIMIT $limit" : "");
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_facilities(bool $active_only = true, ?int $limit = null): array {
    $db = get_db();
    try {
        $sql = "SELECT * FROM facilities" . ($active_only ? " WHERE is_active = 1" : "") . " ORDER BY display_order ASC, id ASC" . ($limit ? " LIMIT $limit" : "");
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_notices(bool $active_only = true, ?int $limit = null, ?string $category = null): array {
    $db = get_db();
    try {
        $conditions = [];
        $params = [];
        if ($active_only) {
            $conditions[] = "is_active = 1";
        }
        if ($category) {
            $conditions[] = "category = ?";
            $params[] = $category;
        }
        $where = $conditions ? " WHERE " . implode(" AND ", $conditions) : "";
        $sql = "SELECT * FROM notices $where ORDER BY is_featured DESC, publish_date DESC, id DESC" . ($limit ? " LIMIT $limit" : "");
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_news(bool $active_only = true, ?int $limit = null): array {
    $db = get_db();
    try {
        $sql = "SELECT * FROM news" . ($active_only ? " WHERE is_active = 1" : "") . " ORDER BY is_featured DESC, publish_date DESC, id DESC" . ($limit ? " LIMIT $limit" : "");
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_events(bool $active_only = true, ?int $limit = null): array {
    $db = get_db();
    try {
        $sql = "SELECT * FROM events" . ($active_only ? " WHERE is_active = 1" : "") . " ORDER BY event_date ASC, id DESC" . ($limit ? " LIMIT $limit" : "");
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_gallery_albums(bool $active_only = true): array {
    $db = get_db();
    try {
        $sql = "SELECT a.*, COUNT(p.id) as photo_count FROM gallery_albums a 
                LEFT JOIN gallery_photos p ON a.id = p.album_id " .
                ($active_only ? " WHERE a.is_active = 1" : "") .
                " GROUP BY a.id ORDER BY a.display_order ASC, a.id DESC";
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_gallery_photos(?int $album_id = null): array {
    $db = get_db();
    try {
        $sql = "SELECT p.*, a.title as album_title FROM gallery_photos p 
                JOIN gallery_albums a ON p.album_id = a.id 
                WHERE a.is_active = 1 " . ($album_id ? " AND p.album_id = " . (int)$album_id : "") .
                " ORDER BY p.display_order ASC, p.id DESC";
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_achievements(bool $active_only = true, ?int $limit = null): array {
    $db = get_db();
    try {
        $sql = "SELECT * FROM achievements" . ($active_only ? " WHERE is_active = 1" : "") . " ORDER BY is_featured DESC, date DESC, id DESC" . ($limit ? " LIMIT $limit" : "");
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function get_downloads(bool $active_only = true, ?string $category = null): array {
    $db = get_db();
    try {
        $conditions = [];
        $params = [];
        if ($active_only) {
            $conditions[] = "is_active = 1";
        }
        if ($category) {
            $conditions[] = "category = ?";
            $params[] = $category;
        }
        $where = $conditions ? " WHERE " . implode(" AND ", $conditions) : "";
        $sql = "SELECT * FROM downloads $where ORDER BY display_order ASC, id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function upload_file(array $file, string $subfolder = 'media', array $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx']): ?string {
    if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        return null;
    }

    $target_dir = UPLOADS_DIR . DIRECTORY_SEPARATOR . $subfolder;
    if (!file_exists($target_dir)) {
        @mkdir($target_dir, 0777, true);
    }

    $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target_path = $target_dir . DIRECTORY_SEPARATOR . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $relative_url = 'assets/uploads/' . $subfolder . '/' . $filename;
        
        // Also register in media_library
        try {
            $db = get_db();
            $filesize = format_bytes($file['size']);
            $uploader = $_SESSION['admin_fullname'] ?? 'Admin';
            $stmt = $db->prepare("INSERT INTO media_library (file_name, file_path, file_type, file_size, uploaded_by) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$file['name'], $relative_url, $ext, $filesize, $uploader]);
        } catch (Exception $e) {}

        return $relative_url;
    }
    return null;
}

function format_bytes(int $bytes, int $precision = 2): string {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function e(?string $str): string {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
