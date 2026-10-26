<?php
if (defined('_SK_CTRL_LOADED_')) return;
define('_SK_CTRL_LOADED_', true);

class SuratKeluarController {
    private SuratKeluar  $model;
    private Klasifikasi  $klasModel;
    private LogAktivitas $log;

    public function __construct() {
        $this->model     = new SuratKeluar();
        $this->klasModel = new Klasifikasi();
        $this->log       = new LogAktivitas();
    }

    // Lihat daftar — admin & operator
    public function index(): void {
        requireRole(['admin', 'operator']);

        $filter = [
            'search'         => trim($_GET['search']         ?? ''),
            'status'         => $_GET['status']              ?? '',
            'klasifikasi_id' => $_GET['klasifikasi_id']      ?? '',
            'dari'           => $_GET['dari']                ?? '',
            'sampai'         => $_GET['sampai']              ?? '',
        ];
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $paging = paginate($this->model->count($filter), $page);

        $suratList       = $this->model->getAll($filter, $paging['offset']);
        $klasifikasiList = $this->klasModel->getAll();

        require ROOT_PATH . '/app/views/surat_keluar/index.php';
    }

    // Form tambah — admin & operator
    public function tambah(): void {
        requireRole(['admin', 'operator']);
        $klasifikasiList = $this->klasModel->getAll();
        require ROOT_PATH . '/app/views/surat_keluar/tambah.php';
    }

    // Simpan — admin & operator
    public function simpan(): void {
        requireRole(['admin', 'operator']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/surat-keluar');
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect(APP_URL . '/surat-keluar/tambah');
        }
        unset($_SESSION['csrf_token']);

        $required = ['nomor_surat', 'tanggal_surat', 'tujuan', 'perihal'];
        foreach ($required as $f) {
            if (empty($_POST[$f])) {
                setFlash('error', "Field '$f' wajib diisi.");
                redirect(APP_URL . '/surat-keluar/tambah');
            }
        }

        $data = [
            'nomor_surat'    => sanitize($_POST['nomor_surat']),
            'tanggal_surat'  => $_POST['tanggal_surat'],
            'tujuan'         => sanitize($_POST['tujuan']),
            'perihal'        => sanitize($_POST['perihal']),
            'isi_singkat'    => sanitize($_POST['isi_singkat'] ?? ''),
            'klasifikasi_id' => $_POST['klasifikasi_id'] ?: null,
            'status'         => $_POST['status'] ?? 'draft',
            'user_id'        => currentUser()['id'],
        ];

        if (!empty($_FILES['file_surat']['name'])) {
            $upload = uploadFile($_FILES['file_surat'], 'surat_keluar');
            if (!$upload['success']) {
                setFlash('error', $upload['message']);
                redirect(APP_URL . '/surat-keluar/tambah');
            }
            $data['file_surat'] = $upload['filename'];
        }

        $id = $this->model->create($data);
        $this->log->catat(currentUser()['id'], 'tambah_surat_keluar', "ID: $id, Perihal: {$data['perihal']}");
        setFlash('success', 'Surat keluar berhasil ditambahkan.');
        redirect(APP_URL . '/surat-keluar');
    }

    // Form edit — admin & operator
    public function edit(int $id): void {
        requireRole(['admin', 'operator']);
        $surat = $this->model->findById($id);
        if (!$surat) { setFlash('error', 'Surat tidak ditemukan.'); redirect(APP_URL . '/surat-keluar'); }
        $klasifikasiList = $this->klasModel->getAll();
        require ROOT_PATH . '/app/views/surat_keluar/edit.php';
    }

    // Update — admin & operator
    public function update(int $id): void {
        requireRole(['admin', 'operator']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/surat-keluar');
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect(APP_URL . "/surat-keluar/edit/$id");
        }
        unset($_SESSION['csrf_token']);

        $surat = $this->model->findById($id);
        if (!$surat) { setFlash('error', 'Surat tidak ditemukan.'); redirect(APP_URL . '/surat-keluar'); }

        $data = [
            'nomor_surat'    => sanitize($_POST['nomor_surat']),
            'tanggal_surat'  => $_POST['tanggal_surat'],
            'tujuan'         => sanitize($_POST['tujuan']),
            'perihal'        => sanitize($_POST['perihal']),
            'isi_singkat'    => sanitize($_POST['isi_singkat'] ?? ''),
            'klasifikasi_id' => $_POST['klasifikasi_id'] ?: null,
            'status'         => $_POST['status'],
        ];

        if (!empty($_FILES['file_surat']['name'])) {
            $upload = uploadFile($_FILES['file_surat'], 'surat_keluar');
            if (!$upload['success']) {
                setFlash('error', $upload['message']);
                redirect(APP_URL . "/surat-keluar/edit/$id");
            }
            if ($surat['file_surat']) deleteFile($surat['file_surat']);
            $data['file_surat'] = $upload['filename'];
        }

        $this->model->update($id, $data);
        $this->log->catat(currentUser()['id'], 'edit_surat_keluar', "ID: $id");
        setFlash('success', 'Surat keluar berhasil diperbarui.');
        redirect(APP_URL . '/surat-keluar');
    }

    // Detail — admin & operator
    public function detail(int $id): void {
        requireRole(['admin', 'operator']);
        $surat = $this->model->findById($id);
        if (!$surat) { setFlash('error', 'Surat tidak ditemukan.'); redirect(APP_URL . '/surat-keluar'); }
        require ROOT_PATH . '/app/views/surat_keluar/detail.php';
    }

    // Hapus — admin saja
    public function hapus(int $id): void {
        requireRole(['admin','operator']);
        $surat = $this->model->findById($id);
        if ($surat) {
            if ($surat['file_surat']) deleteFile($surat['file_surat']);
            $this->model->delete($id);
            $this->log->catat(currentUser()['id'], 'hapus_surat_keluar', "ID: $id");
            setFlash('success', 'Surat keluar berhasil dihapus.');
        }
        redirect(APP_URL . '/surat-keluar');
    }
}
