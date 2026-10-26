<?php
$pageTitle = 'Edit Surat Keluar';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Edit Surat Keluar</h5>
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>/surat-keluar">Surat Keluar</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
        <a href="<?= APP_URL ?>/surat-keluar" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-header py-3">
            <i class="bi bi-pencil-square me-2 text-warning"></i>Edit — <?= sanitize($surat['nomor_surat']) ?>
        </div>
        <div class="card-body p-4">
            <form action="<?= APP_URL ?>/surat-keluar/update/<?= $surat['id'] ?>"
                  method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_surat" class="form-control"
                               value="<?= sanitize($surat['nomor_surat']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Klasifikasi</label>
                        <select name="klasifikasi_id" class="form-select">
                            <option value="">— Pilih Klasifikasi —</option>
                            <?php foreach ($klasifikasiList as $k): ?>
                            <option value="<?= $k['id'] ?>"
                                <?= $surat['klasifikasi_id']==$k['id']?'selected':'' ?>>
                                <?= sanitize($k['kode']) ?> — <?= sanitize($k['nama_klasifikasi']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_surat" class="form-control"
                               value="<?= $surat['tanggal_surat'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <?php foreach (['draft','dikirim','diarsipkan'] as $st): ?>
                            <option value="<?= $st ?>" <?= $surat['status']===$st?'selected':'' ?>>
                                <?= ucfirst($st) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                        <input type="text" name="tujuan" class="form-control"
                               value="<?= sanitize($surat['tujuan']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Perihal <span class="text-danger">*</span></label>
                        <input type="text" name="perihal" class="form-control"
                               value="<?= sanitize($surat['perihal']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Isi Singkat</label>
                        <textarea name="isi_singkat" class="form-control" rows="3"><?= sanitize($surat['isi_singkat'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ganti File Surat</label>
                        <?php if ($surat['file_surat']): ?>
                        <div class="mb-2">
                            <span class="file-badge"><i class="bi bi-paperclip me-1"></i>File tersedia</span>
                            <a href="<?= fileUrl($surat['file_surat']) ?>" target="_blank"
                               class="btn btn-sm btn-link p-0 ms-2">Lihat</a>
                        </div>
                        <?php endif; ?>
                        <input type="file" id="file_surat" name="file_surat"
                               class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <div id="file-preview" class="mt-1"></div>
                        <div class="form-text">Kosongkan jika tidak ingin mengganti file</div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                    <a href="<?= APP_URL ?>/surat-keluar" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>

</main>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>