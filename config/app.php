<?php
define('APP_NAME',    'Sistem Informasi Pengarsipan Surat');
define('APP_VERSION', '1.0.0');

// Path filesystem
define('ROOT_PATH',    dirname(__DIR__));
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('PUBLIC_PATH',  ROOT_PATH . '/public');

(function () {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $parts  = explode('/', ltrim($script, '/'));
    $folder = ($parts[0] !== 'index.php') ? '/' . $parts[0] : '';

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

    define('APP_URL', $scheme . '://' . $host . $folder);
})();

// Upload
define('MAX_FILE_SIZE',  5 * 1024 * 1024); // 5 MB
define('ALLOWED_TYPES',  ['application/pdf', 'image/jpeg', 'image/png']);
define('ALLOWED_EXT',    ['pdf', 'jpg', 'jpeg', 'png']);

// Pagination
define('ROWS_PER_PAGE', 10);

// Session lifetime (detik)
define('SESSION_LIFETIME', 3600);
