<?php
if (defined('_DISP_CTRL_LOADED_')) return;
define('_DISP_CTRL_LOADED_', true);

class DisposisiController {
    private Disposisi    $model;
    private LogAktivitas $log;

    public function __construct() {
        $this->model = new Disposisi();
        $this->log   = new LogAktivitas();
    }

    // Lihat kotak disposisi — semua role (admin, operator, anggota)
    public function index(): void {
        requireLogin();
        $user   = currentUser();
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $paging = paginate($this->model->countByUser($user['id']), $page);
        $list   = $this->model->getByUser($user['id'], $paging['offset']);
        require ROOT_PATH . '/app/views/disposisi/index.php';
    }

    // Kirim disposisi — admin & operator saja
    public function simpan(): void {
        requireRole(['admin', 'operator']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/surat-masuk');
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect(APP_URL . '/surat-masuk');
        }
        unset($_SESSION['csrf_token']);

        $data = [
            'surat_masuk_id' => (int)($_POST['surat_masuk_id']  ?? 0),
            'dari_user_id'   => currentUser()['id'],
            'kepada_user_id' => (int)($_POST['kepada_user_id']  ?? 0),
            'catatan'        => sanitize($_POST['catatan']        ?? ''),
            'tanggal'        => date('Y-m-d'),
        ];

        if (!$data['surat_masuk_id'] || !$data['kepada_user_id']) {
            setFlash('error', 'Data disposisi tidak lengkap.');
            redirect(APP_URL . '/surat-masuk/' . $data['surat_masuk_id']);
        }

        $id = $this->model->create($data);
        $this->log->catat(currentUser()['id'], 'tambah_disposisi', "ID: $id");
        setFlash('success', 'Disposisi berhasil dikirim.');
        redirect(APP_URL . '/surat-masuk/' . $data['surat_masuk_id']);
    }

    // Tandai dibaca — semua role (penerima disposisi)
    public function baca(int $id): void {
        requireLogin();
        $disp = $this->model->findById($id);
        if ($disp && $disp['kepada_user_id'] == currentUser()['id']) {
            $this->model->updateStatus($id, 'sudah_dibaca');
        }
        redirect(APP_URL . '/disposisi');
    }

    // Tandai selesai — semua role (penerima disposisi)
    public function selesai(int $id): void {
        requireLogin();
        $disp = $this->model->findById($id);
        if ($disp && $disp['kepada_user_id'] == currentUser()['id']) {
            $this->model->updateStatus($id, 'selesai');
            $this->log->catat(currentUser()['id'], 'selesai_disposisi', "ID: $id");
        }
        redirect(APP_URL . '/disposisi');
    }

    // Hapus disposisi — admin saja
    public function hapus(int $id): void {
        requireRole(['admin']);
        $this->model->delete($id);
        $this->log->catat(currentUser()['id'], 'hapus_disposisi', "ID: $id");
        setFlash('success', 'Disposisi berhasil dihapus.');
        redirect(APP_URL . '/disposisi');
    }
}
