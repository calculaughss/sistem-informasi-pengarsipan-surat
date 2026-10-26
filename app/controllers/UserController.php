<?php
if (defined('_USER_CTRL_LOADED_')) return;
define('_USER_CTRL_LOADED_', true);

class UserController {
    private User         $model;
    private LogAktivitas $log;

    public function __construct() {
        $this->model = new User();
        $this->log   = new LogAktivitas();
    }

    public function index(): void {
        requireRole(['admin']);
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $paging = paginate($this->model->count(), $page);
        $users  = $this->model->getAll($paging['offset']);
        require ROOT_PATH . '/app/views/users/index.php';
    }

    public function edit(int $id): void {
        requireRole(['admin']);
        $user = $this->model->findById($id);
        if (!$user) { setFlash('error', 'User tidak ditemukan.'); redirect(APP_URL . '/users'); }
        require ROOT_PATH . '/app/views/users/edit.php';
    }

    public function update(int $id): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/users');
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect(APP_URL . "/users/edit/$id");
        }
        unset($_SESSION['csrf_token']);

        $data = [
            'nama'      => sanitize($_POST['nama']     ?? ''),
            'email'     => sanitize($_POST['email']    ?? ''),
            'role'      => $_POST['role']               ?? 'anggota',
            'is_active' => (int)($_POST['is_active']   ?? 1),
        ];
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }
        $this->model->update($id, $data);
        $this->log->catat(currentUser()['id'], 'edit_user', "ID: $id");
        setFlash('success', 'Data pengguna berhasil diperbarui.');
        redirect(APP_URL . '/users');
    }

    // Toggle aktif <-> nonaktif
    public function toggle(int $id): void {
        requireRole(['admin']);

        if ($id == currentUser()['id']) {
            setFlash('error', 'Tidak dapat mengubah status akun sendiri.');
            redirect(APP_URL . '/users');
        }

        $user = $this->model->findById($id);
        if (!$user) {
            setFlash('error', 'Pengguna tidak ditemukan.');
            redirect(APP_URL . '/users');
        }

        // Balik status: aktif -> nonaktif, nonaktif -> aktif
        $statusBaru = $user['is_active'] ? 0 : 1;
        $labelBaru  = $statusBaru ? 'Aktif' : 'Nonaktif';

        $this->model->update($id, ['is_active' => $statusBaru]);
        $this->log->catat(
            currentUser()['id'],
            'toggle_status_user',
            "ID: $id, {$user['nama']} -> $labelBaru"
        );
        setFlash('success', "Status pengguna <strong>{$user['nama']}</strong> diubah menjadi <strong>$labelBaru</strong>.");
        redirect(APP_URL . '/users');
    }

    // Tetap ada untuk kompatibilitas (nonaktifkan saja, tidak hapus)
    public function hapus(int $id): void {
        $this->toggle($id);
    }
}
