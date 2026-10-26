<?php
$pageTitle = 'Tambah Surat Keluar';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Tambah Surat Keluar</h5>
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>/surat-keluar">Surat Keluar</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
        <a href="<?= APP_URL ?>/surat-keluar" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if ($err = getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header py-3">
            <i class="bi bi-send me-2 text-success"></i>Formulir Surat Keluar
        </div>
        <div class="card-body p-4">
            <form action="<?= APP_URL ?>/surat-keluar/simpan" method="POST"
                  enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_surat" class="form-control"
                               placeholder="Contoh: 010/BEM-OUT/III/2025" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Klasifikasi</label>
                        <select name="klasifikasi_id" class="form-select">
                            <option value="">— Pilih Klasifikasi —</option>
                            <?php foreach ($klasifikasiList as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= sanitize($k['kode']) ?> — <?= sanitize($k['nama_klasifikasi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_surat" class="form-control" data-today required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="dikirim">Dikirim</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                        <input type="text" name="tujuan" class="form-control"
                               placeholder="Instansi / pihak yang dituju" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Perihal <span class="text-danger">*</span></label>
                        <input type="text" name="perihal" class="form-control"
                               placeholder="Ringkasan perihal surat" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Isi Singkat</label>
                        <textarea name="isi_singkat" class="form-control" rows="3"
                                  placeholder="Deskripsi singkat isi surat (opsional)"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload File Surat</label>
                        <input type="file" id="file_surat" name="file_surat"
                               class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <div id="file-preview" class="mt-1"></div>
                        <div class="form-text">PDF, JPG, PNG — maks. 5 MB</div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                    <a href="<?= APP_URL ?>/surat-keluar" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>

</main>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>