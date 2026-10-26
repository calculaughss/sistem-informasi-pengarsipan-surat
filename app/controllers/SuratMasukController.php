<?php
if (defined('_SM_CTRL_LOADED_')) return;
define('_SM_CTRL_LOADED_', true);

class SuratMasukController {
    private SuratMasuk   $model;
    private Klasifikasi  $klasModel;
    private LogAktivitas $log;

    public function __construct() {
        $this->model     = new SuratMasuk();
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

        require ROOT_PATH . '/app/views/surat_masuk/index.php';
    }

    // Form tambah — admin & operator
    public function tambah(): void {
        requireRole(['admin', 'operator']);
        $klasifikasiList = $this->klasModel->getAll();
        require ROOT_PATH . '/app/views/surat_masuk/tambah.php';
    }

    // Simpan — admin & operator
    public function simpan(): void {
        requireRole(['admin', 'operator']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/surat-masuk');
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect(APP_URL . '/surat-masuk/tambah');
        }
        unset($_SESSION['csrf_token']);

        $required = ['nomor_surat', 'tanggal_surat', 'tanggal_terima', 'asal_surat', 'tujuan', 'perihal'];
        foreach ($required as $f) {
            if (empty($_POST[$f])) {
                setFlash('error', "Field '$f' wajib diisi.");
                redirect(APP_URL . '/surat-masuk/tambah');
            }
        }

        $data = [
            'nomor_surat'    => sanitize($_POST['nomor_surat']),
            'tanggal_surat'  => $_POST['tanggal_surat'],
            'tanggal_terima' => $_POST['tanggal_terima'],
            'asal_surat'     => sanitize($_POST['asal_surat']),
            'tujuan'         => sanitize($_POST['tujuan']),
            'perihal'        => sanitize($_POST['perihal']),
            'isi_singkat'    => sanitize($_POST['isi_singkat'] ?? ''),
            'klasifikasi_id' => $_POST['klasifikasi_id'] ?: null,
            'status'         => 'baru',
            'user_id'        => currentUser()['id'],
        ];

        if (!empty($_FILES['file_surat']['name'])) {
            $upload = uploadFile($_FILES['file_surat'], 'surat_masuk');
            if (!$upload['success']) {
                setFlash('error', $upload['message']);
                redirect(APP_URL . '/surat-masuk/tambah');
            }
            $data['file_surat'] = $upload['filename'];
        }

        $id = $this->model->create($data);
        $this->log->catat(currentUser()['id'], 'tambah_surat_masuk', "ID: $id, Perihal: {$data['perihal']}");
        setFlash('success', 'Surat masuk berhasil ditambahkan.');
        redirect(APP_URL . '/surat-masuk');
    }

    // Form edit — admin & operator
    public function edit(int $id): void {
        requireRole(['admin', 'operator']);
        $surat = $this->model->findById($id);
        if (!$surat) { setFlash('error', 'Surat tidak ditemukan.'); redirect(APP_URL . '/surat-masuk'); }
        $klasifikasiList = $this->klasModel->getAll();
        require ROOT_PATH . '/app/views/surat_masuk/edit.php';
    }

    // Update — admin & operator
    public function update(int $id): void {
        requireRole(['admin', 'operator']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/surat-masuk');
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect(APP_URL . "/surat-masuk/edit/$id");
        }
        unset($_SESSION['csrf_token']);

        $surat = $this->model->findById($id);
        if (!$surat) { setFlash('error', 'Surat tidak ditemukan.'); redirect(APP_URL . '/surat-masuk'); }

        $data = [
            'nomor_surat'    => sanitize($_POST['nomor_surat']),
            'tanggal_surat'  => $_POST['tanggal_surat'],
            'tanggal_terima' => $_POST['tanggal_terima'],
            'asal_surat'     => sanitize($_POST['asal_surat']),
            'tujuan'         => sanitize($_POST['tujuan']),
            'perihal'        => sanitize($_POST['perihal']),
            'isi_singkat'    => sanitize($_POST['isi_singkat'] ?? ''),
            'klasifikasi_id' => $_POST['klasifikasi_id'] ?: null,
            'status'         => $_POST['status'],
        ];

        if (!empty($_FILES['file_surat']['name'])) {
            $upload = uploadFile($_FILES['file_surat'], 'surat_masuk');
            if (!$upload['success']) {
                setFlash('error', $upload['message']);
                redirect(APP_URL . "/surat-masuk/edit/$id");
            }
            if ($surat['file_surat']) deleteFile($surat['file_surat']);
            $data['file_surat'] = $upload['filename'];
        }

        $this->model->update($id, $data);
        $this->log->catat(currentUser()['id'], 'edit_surat_masuk', "ID: $id");
        setFlash('success', 'Surat masuk berhasil diperbarui.');
        redirect(APP_URL . '/surat-masuk');
    }

    // Detail — admin & operator
    public function detail(int $id): void {
        requireRole(['admin', 'operator']);
        $surat = $this->model->findById($id);
        if (!$surat) { setFlash('error', 'Surat tidak ditemukan.'); redirect(APP_URL . '/surat-masuk'); }
        $disp      = new Disposisi();
        $disposisi = $disp->getBySuratMasuk($id);
        $users     = (new User())->getAll(0, 100);
        require ROOT_PATH . '/app/views/surat_masuk/detail.php';
    }

    // Hapus — admin saja
    public function hapus(int $id): void {
        requireRole(['admin','operator']);
        $surat = $this->model->findById($id);
        if ($surat) {
            if ($surat['file_surat']) deleteFile($surat['file_surat']);
            $this->model->delete($id);
            $this->log->catat(currentUser()['id'], 'hapus_surat_masuk', "ID: $id");
            setFlash('success', 'Surat masuk berhasil dihapus.');
        }
        redirect(APP_URL . '/surat-masuk');
    }
}
