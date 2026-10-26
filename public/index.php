<?php
/**
 * public/index.php
 * Folder public hanya untuk aset statis (CSS, JS, uploads).
 * Semua request aplikasi dihandle oleh /index.php di root.
 */
$root = dirname(__DIR__) . '/index.php';

if (file_exists($root)) {
    require_once $root;
} else {
    http_response_code(500);
    exit('Konfigurasi aplikasi tidak ditemukan.');
}