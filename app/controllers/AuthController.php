<?php
if (defined('_AUTH_CTRL_LOADED_')) return;
define('_AUTH_CTRL_LOADED_', true);

class AuthController {
    private User $userModel;
    private LogAktivitas $log;

    public function __construct() {
        $this->userModel = new User();
        $this->log       = new LogAktivitas();
    }

    public function loginForm(): void {
        if (isLoggedIn()) redirect(APP_URL . '/dashboard');
        require ROOT_PATH . '/app/views/auth/login.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(APP_URL . '/auth/login');
        }

        // Verifikasi CSRF
        $tokenPost    = $_POST['csrf_token'] ?? '';
        $tokenSession = $_SESSION['csrf_token'] ?? '';

        if (empty($tokenSession) || !hash_equals($tokenSession, $tokenPost)) {
            setFlash('error', 'Sesi keamanan tidak valid. Silakan muat ulang halaman dan coba lagi.');
            redirect(APP_URL . '/auth/login');
        }

        // Hapus token lama setelah dipakai (one-time use)
        unset($_SESSION['csrf_token']);

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Email dan password wajib diisi.');
            redirect(APP_URL . '/auth/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlash('error', 'Format email tidak valid.');
            redirect(APP_URL . '/auth/login');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            setFlash('error', 'Email tidak terdaftar.');
            $this->log->catat(null, 'login_gagal', "Email tidak ditemukan: $email");
            redirect(APP_URL . '/auth/login');
        }

        if (!password_verify($password, $user['password'])) {
            setFlash('error', 'Password salah.');
            $this->log->catat(null, 'login_gagal', "Password salah untuk: $email");
            redirect(APP_URL . '/auth/login');
        }

        // Login sukses - regenerasi session ID untuk keamanan
        session_regenerate_id(true);

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_nama']  = $user['nama'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];

        $this->log->catat($user['id'], 'login', 'Login berhasil dari IP: ' . ($_SERVER['REMOTE_ADDR'] ?? '-'));
        redirect(APP_URL . '/dashboard');
    }

    public function logout(): void {
        if (isLoggedIn()) {
            $this->log->catat($_SESSION['user_id'], 'logout', 'Logout');
        }
        // Hapus semua data session
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        redirect(APP_URL . '/auth/login');
    }

    public function registerForm(): void {
        requireRole(['admin']);
        require ROOT_PATH . '/app/views/auth/register.php';
    }

    public function register(): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(APP_URL . '/auth/register');
        }
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect(APP_URL . '/auth/register');
        }
        unset($_SESSION['csrf_token']);

        $data = [
            'nama'     => trim($_POST['nama']     ?? ''),
            'email'    => trim($_POST['email']    ?? ''),
            'password' => $_POST['password']      ?? '',
            'role'     => $_POST['role']           ?? 'anggota',
        ];

        foreach (['nama', 'email', 'password'] as $f) {
            if (empty($data[$f])) {
                setFlash('error', 'Semua field wajib diisi.');
                redirect(APP_URL . '/auth/register');
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            setFlash('error', 'Format email tidak valid.');
            redirect(APP_URL . '/auth/register');
        }

        if (strlen($data['password']) < 6) {
            setFlash('error', 'Password minimal 6 karakter.');
            redirect(APP_URL . '/auth/register');
        }

        $this->userModel->create($data);
        $this->log->catat(currentUser()['id'], 'tambah_user', "User baru: {$data['email']}");
        setFlash('success', 'Pengguna berhasil ditambahkan.');
        redirect(APP_URL . '/users');
    }
}
