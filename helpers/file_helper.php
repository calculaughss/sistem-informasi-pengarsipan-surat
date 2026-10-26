<?php
if (defined('_FILE_HELPER_LOADED_')) return;
define('_FILE_HELPER_LOADED_', true);

function uploadFile(array $file, string $folder): array {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload gagal, kode error: ' . $file['error']];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Ukuran file melebihi batas 5 MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ALLOWED_TYPES)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan. Gunakan PDF, JPG, atau PNG.'];
    }

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destDir  = STORAGE_PATH . '/' . trim($folder, '/');

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $destPath = $destDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return ['success' => false, 'message' => 'Gagal menyimpan file.'];
    }

    return ['success' => true, 'filename' => $folder . '/' . $filename];
}

function deleteFile(string $relativePath): void {
    $full = STORAGE_PATH . '/' . ltrim($relativePath, '/');
    if (file_exists($full)) {
        unlink($full);
    }
}

function fileUrl(string $relativePath): string {
    return APP_URL . '/file/' . ltrim($relativePath, '/');
}