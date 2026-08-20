<?php
// config/config.php - Global Application Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Credentials
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dv_niketan_db');

// Root Paths
define('BASE_DIR', dirname(__DIR__));
define('UPLOADS_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads');

// Create required upload subdirectories if they don't exist
$upload_folders = [
    UPLOADS_DIR,
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'gallery',
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'documents',
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'teachers',
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'facilities',
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'news',
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'events',
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'achievements',
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'programs',
    UPLOADS_DIR . DIRECTORY_SEPARATOR . 'media',
];

foreach ($upload_folders as $folder) {
    if (!file_exists($folder)) {
        @mkdir($folder, 0777, true);
    }
}
