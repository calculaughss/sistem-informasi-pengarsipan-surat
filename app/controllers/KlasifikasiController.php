<?php
if (defined('_KLAS_CTRL_LOADED_')) return;
define('_KLAS_CTRL_LOADED_', true);

class KlasifikasiController {
    private Klasifikasi  $model;
    private LogAktivitas $log;

    public function __construct() {
        $this->model = new Klasifikasi();
        $this->log   = new LogAktivitas();
    }

    // Semua aksi Klasifikasi — admin saja
    public function index(): void {
        requireRole(['admin']);
        $list = $this->model->getAll();
        require ROOT_PATH . '/app/views/klasifikasi/index.php';
    }

    public function simpan(): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/klasifikasi');
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.'); redirect(APP_URL . '/klasifikasi');
        }
        unset($_SESSION['csrf_token']);

        $data = [
            'kode'             => strtoupper(sanitize($_POST['kode'] ?? '')),
            'nama_klasifikasi' => sanitize($_POST['nama_klasifikasi'] ?? ''),
            'keterangan'       => sanitize($_POST['keterangan'] ?? ''),
        ];
        if (empty($data['kode']) || empty($data['nama_klasifikasi'])) {
            setFlash('error', 'Kode dan nama wajib diisi.'); redirect(APP_URL . '/klasifikasi');
        }
        $id = $this->model->create($data);
        $this->log->catat(currentUser()['id'], 'tambah_klasifikasi', "ID: $id");
        setFlash('success', 'Klasifikasi berhasil ditambahkan.');
        redirect(APP_URL . '/klasifikasi');
    }

    public function update(int $id): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/klasifikasi');
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.'); redirect(APP_URL . '/klasifikasi');
        }
        unset($_SESSION['csrf_token']);

        $data = [
            'kode'             => strtoupper(sanitize($_POST['kode'] ?? '')),
            'nama_klasifikasi' => sanitize($_POST['nama_klasifikasi'] ?? ''),
            'keterangan'       => sanitize($_POST['keterangan'] ?? ''),
        ];
        $this->model->update($id, $data);
        $this->log->catat(currentUser()['id'], 'edit_klasifikasi', "ID: $id");
        setFlash('success', 'Klasifikasi berhasil diperbarui.');
        redirect(APP_URL . '/klasifikasi');
    }

    public function hapus(int $id): void {
        requireRole(['admin']);
        $this->model->delete($id);
        $this->log->catat(currentUser()['id'], 'hapus_klasifikasi', "ID: $id");
        setFlash('success', 'Klasifikasi berhasil dihapus.');
        redirect(APP_URL . '/klasifikasi');
    }
}
