<?php
$pageTitle = 'Klasifikasi Surat';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Klasifikasi Surat</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Klasifikasi</li>
                </ol>
            </nav>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Klasifikasi
        </button>
    </div>

    <?php if ($ok = getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= sanitize($ok) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($err = getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($err) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3" width="50">No</th>
                            <th width="120">Kode</th>
                            <th>Nama Klasifikasi</th>
                            <th>Keterangan</th>
                            <th class="text-center" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($list)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-tags fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada klasifikasi
                        </td></tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($list as $k): ?>
                        <tr>
                            <td class="ps-3 text-muted"><?= $no++ ?></td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary fw-semibold">
                                    <?= sanitize($k['kode']) ?>
                                </span>
                            </td>
                            <td class="fw-semibold"><?= sanitize($k['nama_klasifikasi']) ?></td>
                            <td class="text-muted small"><?= sanitize($k['keterangan'] ?? '—') ?></td>
                            <td class="text-center text-nowrap">
                                <button class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit"
                                        data-id="<?= $k['id'] ?>"
                                        data-kode="<?= sanitize($k['kode']) ?>"
                                        data-nama="<?= sanitize($k['nama_klasifikasi']) ?>"
                                        data-ket="<?= sanitize($k['keterangan'] ?? '') ?>"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="<?= APP_URL ?>/klasifikasi/hapus/<?= $k['id'] ?>"
                                   class="btn btn-sm btn-outline-danger btn-hapus" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Tambah Klasifikasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= APP_URL ?>/klasifikasi/simpan" method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control"
                               placeholder="Contoh: UND" maxlength="20"
                               style="text-transform:uppercase" required>
                        <div class="form-text">Kode singkat (maks. 20 karakter), otomatis huruf kapital</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Klasifikasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_klasifikasi" class="form-control"
                               placeholder="Contoh: Undangan" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"
                                  placeholder="Deskripsi singkat (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Edit Klasifikasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode <span class="text-danger">*</span></label>
                        <input type="text" id="editKode" name="kode" class="form-control"
                               maxlength="20" style="text-transform:uppercase" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Klasifikasi <span class="text-danger">*</span></label>
                        <input type="text" id="editNama" name="nama_klasifikasi" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Keterangan</label>
                        <textarea id="editKet" name="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Isi form edit dari data attribute
document.getElementById('modalEdit').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    document.getElementById('formEdit').action = '<?= APP_URL ?>/klasifikasi/update/' + btn.dataset.id;
    document.getElementById('editKode').value = btn.dataset.kode;
    document.getElementById('editNama').value = btn.dataset.nama;
    document.getElementById('editKet').value  = btn.dataset.ket;
});
</script>

<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>
