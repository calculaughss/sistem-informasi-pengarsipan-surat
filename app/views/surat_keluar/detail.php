<?php
$pageTitle = 'Detail Surat Keluar';
require ROOT_PATH . '/app/views/layouts/header.php';
require ROOT_PATH . '/app/views/layouts/sidebar.php';
$user = currentUser();
?>
<div class="main-content">
<?php require ROOT_PATH . '/app/views/layouts/navbar.php'; ?>
<main class="page-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Detail Surat Keluar</h5>
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>/surat-keluar">Surat Keluar</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
        <div class="d-flex gap-2">
            <?php if (in_array($user['role'], ['admin','operator'])): ?>
            <a href="<?= APP_URL ?>/surat-keluar/edit/<?= $surat['id'] ?>"
               class="btn btn-sm btn-warning">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/surat-keluar" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card" style="max-width:760px">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <span><i class="bi bi-send me-2 text-success"></i>Informasi Surat Keluar</span>
            <?= statusBadge($surat['status']) ?>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4 text-muted fw-normal">Nomor Surat</dt>
                <dd class="col-sm-8 fw-semibold"><?= sanitize($surat['nomor_surat']) ?></dd>

                <dt class="col-sm-4 text-muted fw-normal">Tanggal Surat</dt>
                <dd class="col-sm-8"><?= formatTanggal($surat['tanggal_surat']) ?></dd>

                <dt class="col-sm-4 text-muted fw-normal">Tujuan</dt>
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

                <dt class="col-sm-4 text-muted fw-normal">Dibuat Oleh</dt>
                <dd class="col-sm-8"><?= sanitize($surat['nama_user']) ?></dd>

                <?php if ($surat['file_surat']): ?>
                <dt class="col-sm-4 text-muted fw-normal">File Surat</dt>
                <dd class="col-sm-8">
                    <a href="<?= fileUrl($surat['file_surat']) ?>" target="_blank"
                       class="btn btn-sm btn-outline-success">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i> Lihat / Unduh
                    </a>
                </dd>
                <?php endif; ?>
            </dl>
        </div>
    </div>

</main>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>