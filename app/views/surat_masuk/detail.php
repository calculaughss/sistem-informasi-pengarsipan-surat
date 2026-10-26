<?php
$pageTitle = 'Detail Surat Masuk';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
$user = currentUser();
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Detail Surat Masuk</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/surat-masuk">Surat Masuk</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (in_array($user['role'], ['admin','operator'])): ?>
            <a href="<?= APP_URL ?>/surat-masuk/edit/<?= $surat['id'] ?>"
               class="btn btn-sm btn-warning">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/surat-masuk" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <?php if ($ok = getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= sanitize($ok) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <!-- Info surat -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-text me-2 text-primary"></i>Informasi Surat</span>
                    <?= statusBadge($surat['status']) ?>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted fw-normal">No. Agenda</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-light text-dark border fw-semibold">
                                <?= sanitize($surat['nomor_agenda']) ?>
                            </span>
                        </dd>

                        <dt class="col-sm-4 text-muted fw-normal">No. Surat</dt>
                        <dd class="col-sm-8 fw-semibold"><?= sanitize($surat['nomor_surat']) ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Tanggal Surat</dt>
                        <dd class="col-sm-8"><?= formatTanggal($surat['tanggal_surat']) ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Tanggal Terima</dt>
                        <dd class="col-sm-8"><?= formatTanggal($surat['tanggal_terima']) ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Asal Surat</dt>
                        <dd class="col-sm-8"><?= sanitize($surat['asal_surat']) ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Ditujukan Kepada</dt>
                        <dd class="col-sm-8"><?= sanitize($surat['tujuan']) ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Perihal</dt>
                        <dd class="col-sm-8 fw-semibold"><?= sanitize($surat['perihal']) ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Klasifikasi</dt>
                        <dd class="col-sm-8">
                            <?= $surat['nama_klasifikasi']
                                ? '<span class="badge bg-info bg-opacity-10 text-info border border-info">' . sanitize($surat['nama_klasifikasi']) . '</span>'
                                : '<span class="text-muted">—</span>' ?>
                        </dd>

                        <?php if ($surat['isi_singkat']): ?>
                        <dt class="col-sm-4 text-muted fw-normal">Isi Singkat</dt>
                        <dd class="col-sm-8"><?= nl2br(sanitize($surat['isi_singkat'])) ?></dd>
                        <?php endif; ?>

                        <dt class="col-sm-4 text-muted fw-normal">Diinput Oleh</dt>
                        <dd class="col-sm-8"><?= sanitize($surat['nama_user']) ?></dd>

                        <?php if ($surat['file_surat']): ?>
                        <dt class="col-sm-4 text-muted fw-normal">File Surat</dt>
                        <dd class="col-sm-8">
                            <a href="<?= fileUrl($surat['file_surat']) ?>" target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Lihat / Unduh
                            </a>
                        </dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar: Disposisi -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header py-3">
                    <i class="bi bi-arrow-right-square me-2 text-info"></i>Disposisi
                </div>
                <div class="card-body">
                    <?php if (empty($disposisi)): ?>
                        <p class="text-muted small text-center py-2">Belum ada disposisi</p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-3">
                        <?php foreach ($disposisi as $d): ?>
                            <li class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold small"><?= sanitize($d['kepada_nama']) ?></span>
                                    <?= statusBadge($d['status']) ?>
                                </div>
                                <div class="text-muted small"><?= formatTanggal($d['tanggal']) ?></div>
                                <?php if ($d['catatan']): ?>
                                <div class="small mt-1 fst-italic">"<?= sanitize($d['catatan']) ?>"</div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Form disposisi baru -->
                    <?php if (in_array($user['role'], ['admin','operator'])): ?>
                    <hr class="my-2">
                    <p class="small fw-semibold mb-2">Tambah Disposisi</p>
                    <form action="<?= APP_URL ?>/disposisi/simpan" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= csrf() ?>">
                        <input type="hidden" name="surat_masuk_id" value="<?= $surat['id'] ?>">
                        <div class="mb-2">
                            <select name="kepada_user_id" class="form-select form-select-sm" required>
                                <option value="">— Pilih Penerima —</option>
                                <?php foreach ($users as $u): ?>
                                    <?php if ($u['id'] != $user['id']): ?>
                                    <option value="<?= $u['id'] ?>"><?= sanitize($u['nama']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <textarea name="catatan" class="form-control form-control-sm"
                                      rows="2" placeholder="Catatan disposisi..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-send me-1"></i> Kirim Disposisi
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</main>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>
