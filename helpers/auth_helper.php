<?php
if (defined('_AUTH_HELPER_LOADED_')) return;
define('_AUTH_HELPER_LOADED_', true);

function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn(): bool {
    startSession();
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect(APP_URL . '/auth/login');
    }
}

function requireRole(array $roles): void {
    requireLogin();
    if (!in_array($_SESSION['user_role'] ?? '', $roles)) {
        $_SESSION['flash_error'] = 'Akses ditolak. Anda tidak memiliki izin.';
        redirect(APP_URL . '/dashboard');
    }
}

function currentUser(): array {
    startSession();
    return [
        'id'    => $_SESSION['user_id']   ?? null,
        'nama'  => $_SESSION['user_nama'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role'  => $_SESSION['user_role'] ?? '',
    ];
}

function setFlash(string $type, string $message): void {
    startSession();
    $_SESSION['flash_' . $type] = $message;
}

function getFlash(string $type): ?string {
    startSession();
    $msg = $_SESSION['flash_' . $type] ?? null;
    unset($_SESSION['flash_' . $type]);
    return $msg;
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function csrf(): string {
    startSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(string $token): bool {
    startSession();
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}